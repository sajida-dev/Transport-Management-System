<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('transporter_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->enum('license_type', ['light_vehicle', 'heavy_vehicle', 'commercial', 'specialized']);
            $table->date('license_expiry_date');
            $table->date('date_of_birth');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('emergency_contact_relationship');
            $table->string('medical_certificate_number')->nullable();
            $table->date('medical_certificate_expiry')->nullable();
            $table->integer('experience_years')->default(0);
            $table->json('vehicle_types'); // Array of vehicle types driver can operate
            $table->enum('status', ['active', 'inactive', 'suspended', 'expired', 'pending_verification'])->default('pending_verification');
            $table->text('notes')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('license_photo')->nullable();
            $table->string('medical_certificate_photo')->nullable();
            $table->json('kyc_documents')->nullable(); // Additional KYC documents
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
