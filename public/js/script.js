document.addEventListener("DOMContentLoaded", function () {
    const qrMasuk = document.getElementById("qrcode-masuk");
    if (qrMasuk) {
        new QRCode(qrMasuk, {
            text: "MASUK-{{ $userReservation->id ?? '' }}",
            width: 150,
            height: 150,
        });
    }

    const qrKeluar = document.getElementById("qrcode-keluar");
    if (qrKeluar) {
        new QRCode(qrKeluar, {
            text: "KELUAR-{{ $userReservation->id ?? '' }}",
            width: 150,
            height: 150,
        });
    }

    lokasiWaktu();
    startClock();
});

function startClock() {
    const dateElem = document.getElementById("date");
    const timeElem = document.getElementById("time");

    let sourceElem = null;

    if (dateElem && dateElem.dataset.now) {
        sourceElem = dateElem;
    } else if (timeElem && timeElem.dataset.now) {
        sourceElem = timeElem;
    }

    if (!sourceElem) {
        return;
    }

    const rawTime = sourceElem.dataset.now;
    let demoTime = new Date(rawTime);

    function updateClock() {
        demoTime.setSeconds(demoTime.getSeconds() + 1);

        const dateOptions = { year: "numeric", month: "long", day: "numeric" };
        const dateStr = demoTime.toLocaleDateString("id-ID", dateOptions);
        const timeStr = demoTime.toLocaleTimeString("id-ID", { hour12: false });

        if (dateElem) dateElem.textContent = dateStr;
        if (timeElem) timeElem.textContent = timeStr;
    }

    updateClock();
    setInterval(updateClock, 1000);
}

function refreshKondisi() {
    window.location.reload();
}

async function lokasiWaktu() {
    const info_lokasiElem = document.getElementById("info_lokasi");
    if (!info_lokasiElem) return;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                console.log("Latitude:", lat);
                console.log("Longitude:", lon);

                getAddress(lat, lon);
            },
            (error) => {
                console.log("Error:", error.message);
                if (error.code === error.PERMISSION_DENIED) {
                    alert("Anda menolak permintaan lokasi.");
                }
            }
        );
    } else {
        console.log("Geolocation tidak didukung browser.");
    }
}

async function getAddress(lat, lon) {
    const apiKey = "b8d5165951a84fa2835872efea679abf";

    const url = `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lon}&key=${apiKey}`;

    const res = await fetch(url);
    const data = await res.json();

    const components = data.results[0].components;

    const city =
        components.city ||
        components.town ||
        components.village ||
        components.county;
    const country = components.country;

    const address = `${city}, ${country}`;
    getWeather(lat, lon, address);
}

async function getWeather(lat, lon, address) {
    const weatherBox = document.getElementById("weather");

    try {
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,wind_speed_10m&timezone=Asia%2FJakarta`;

        const response = await fetch(url);
        const data = await response.json();

        const t = data.current.temperature_2m;
        const h = data.current.relative_humidity_2m;
        const w = data.current.wind_speed_10m;

        info_lokasi.innerText = `${address}`;
        temperature.innerText = `${t} °C`;
        wind_speed.innerText = `${w} m/s`;
        humidity.innerText = `${h} %`;
    } catch (error) {
        console.error(error);
        weatherBox.innerHTML = "❌ Gagal mengambil data cuaca.";
    }
}
