<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('load_id')->constrained()->onDelete('cascade');
            $table->foreignId('transporter_id')->constrained()->onDelete('cascade');
            $table->foreignId('truck_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');

            // Booking details
            $table->decimal('quoted_amount', 12, 2);
            $table->decimal('accepted_amount', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->text('notes')->nullable();
            $table->text('special_instructions')->nullable();

            // Status tracking
            $table->enum('status', ['pending', 'accepted', 'rejected', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');

            // Timestamps for status changes
            $table->timestamp('quoted_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Cancellation details
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');

            // Performance tracking
            $table->decimal('actual_distance_km', 8, 2)->nullable();
            $table->decimal('fuel_consumption_liters', 8, 2)->nullable();
            $table->integer('delivery_time_hours')->nullable();
            $table->decimal('rating', 3, 2)->nullable(); // 1-5 rating
            $table->text('feedback')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
