<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('account_number_from');
            $table->string('account_number_type_from');
            $table->string('account_number_to');
            $table->string('account_number_type_to');
            $table->string('trace_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['credit', 'debit']);
            $table->string('description')->nullable();
            $table->string('reference')->nullable();
            $table->timestamp('creation_date');
            $table->timestamps();
            
            $table->index(['trace_number', 'type', 'creation_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};