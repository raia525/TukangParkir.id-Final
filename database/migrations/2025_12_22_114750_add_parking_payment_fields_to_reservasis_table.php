<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {


            // durasi (menit)
            $table->integer('duration_minutes')->nullable()->after('end_time');

            // biaya parkir
            $table->integer('total_price')->nullable()->after('duration_minutes');

            // pembayaran
            $table->enum('payment_status', ['unpaid', 'paid'])
                  ->default('unpaid')
                  ->after('status');

            $table->string('payment_method', 30)
                  ->nullable()
                  ->after('payment_status');

            $table->dateTime('paid_at')
                  ->nullable()
                  ->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropColumn([
                'duration_minutes',
                'total_price',
                'payment_status',
                'payment_method',
                'paid_at',
            ]);
        });
    }
};