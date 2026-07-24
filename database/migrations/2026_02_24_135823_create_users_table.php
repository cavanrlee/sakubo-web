<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); 
            $table->string('firstname')->nullable();
            $table->string('middlename')->nullable();
            $table->string('lastname')->nullable();
            $table->string('suffix')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('api_token')->nullable();
            $table->string('nickname')->nullable();
            $table->string('number')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('account_type')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_category')->nullable();
            $table->string('business_type')->nullable();
            $table->string('business_address')->nullable();
            $table->string('business_barangay')->nullable();
            $table->string('business_city')->nullable();
            $table->string('business_province')->nullable();
            $table->string('business_number')->nullable();
            $table->timestamps(); // Handles created_at and updated_at
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};