<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('invoice_number');
            $table->date('transaction_date')->default(DB::raw('CURRENT_DATE'))->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'transaction_date']);
        });
    }
};