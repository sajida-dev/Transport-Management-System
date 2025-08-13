<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_verifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('verifiable'); // Polymorphic relationship for drivers, transporters, load_owners
            $table->enum('document_type', ['id_card', 'passport', 'drivers_license', 'business_license', 'tax_certificate', 'insurance_certificate', 'vehicle_registration', 'medical_certificate', 'other']);
            $table->string('document_number')->nullable();
            $table->string('document_file_path');
            $table->string('document_file_name');
            $table->string('document_file_type');
            $table->integer('document_file_size'); // in bytes

            // Verification details
            $table->enum('status', ['pending', 'approved', 'rejected', 'under_review'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();

            // Document metadata
            $table->date('document_issue_date')->nullable();
            $table->date('document_expiry_date')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_verifications');
    }
};
