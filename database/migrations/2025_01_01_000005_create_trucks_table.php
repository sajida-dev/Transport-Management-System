<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transporter_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            $table->string('registration_number')->unique();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->string('color');
            $table->enum('type', ['flatbed', 'box_truck', 'refrigerated', 'tanker', 'dump_truck', 'lowboy', 'other']);
            $table->decimal('capacity_tonnes', 8, 2);
            $table->decimal('length_meters', 5, 2);
            $table->decimal('width_meters', 5, 2);
            $table->decimal('height_meters', 5, 2);
            $table->string('engine_number')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->string('fitness_certificate_number')->nullable();
            $table->date('fitness_expiry')->nullable();
            $table->string('permit_number')->nullable();
            $table->date('permit_expiry')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'out_of_service'])->default('active');
            $table->text('notes')->nullable();
            $table->string('photo')->nullable();
            $table->json('documents')->nullable(); // Registration, insurance, fitness certificates
            $table->json('tracking_data')->nullable(); // GPS coordinates, speed, etc.
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
}; 