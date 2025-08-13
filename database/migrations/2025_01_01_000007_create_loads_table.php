<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('load_owner_id')->constrained()->onDelete('cascade');
            $table->foreignId('truck_id')->nullable()->constrained()->onDelete('set null');
            $table->string('load_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('load_type', ['general', 'refrigerated', 'hazardous', 'oversized', 'fragile', 'liquid', 'other']);
            $table->decimal('weight_tonnes', 8, 2);
            $table->decimal('length_meters', 5, 2)->nullable();
            $table->decimal('width_meters', 5, 2)->nullable();
            $table->decimal('height_meters', 5, 2)->nullable();

            // Pickup details
            $table->string('pickup_location');
            $table->text('pickup_address');
            $table->string('pickup_city');
            $table->string('pickup_state');
            $table->string('pickup_postal_code');
            $table->string('pickup_country');
            $table->decimal('pickup_latitude', 10, 8)->nullable();
            $table->decimal('pickup_longitude', 11, 8)->nullable();
            $table->dateTime('pickup_date');
            $table->time('pickup_time');
            $table->string('pickup_contact_name')->nullable();
            $table->string('pickup_contact_phone')->nullable();
            $table->text('pickup_instructions')->nullable();

            // Delivery details
            $table->string('delivery_location');
            $table->text('delivery_address');
            $table->string('delivery_city');
            $table->string('delivery_state');
            $table->string('delivery_postal_code');
            $table->string('delivery_country');
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->dateTime('delivery_date');
            $table->time('delivery_time');
            $table->string('delivery_contact_name')->nullable();
            $table->string('delivery_contact_phone')->nullable();
            $table->text('delivery_instructions')->nullable();

            // Pricing
            $table->decimal('rate_per_km', 10, 2)->nullable();
            $table->decimal('total_distance_km', 8, 2)->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 3)->default('USD');

            // Requirements
            $table->json('required_vehicle_types')->nullable();
            $table->json('special_requirements')->nullable();
            $table->boolean('requires_refrigeration')->default(false);
            $table->boolean('requires_special_equipment')->default(false);
            $table->boolean('is_hazardous')->default(false);

            // Status and tracking
            $table->enum('status', ['pending', 'assigned', 'in_transit', 'delivered', 'cancelled', 'completed'])->default('pending');
            $table->text('cancel_reason')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->text('notes')->nullable();
            $table->json('documents')->nullable(); // Load documents, photos, etc.
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loads');
    }
};
