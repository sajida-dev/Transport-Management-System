<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('load_owner_id')->constrained()->onDelete('cascade');
            $table->foreignId('transporter_id')->constrained()->onDelete('cascade');

            // Invoice details
            $table->date('invoice_date');
            $table->date('due_date');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 3)->default('USD');

            // Payment details
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->date('paid_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();

            // Additional charges
            $table->decimal('fuel_surcharge', 12, 2)->default(0);
            $table->decimal('waiting_charges', 12, 2)->default(0);
            $table->decimal('detention_charges', 12, 2)->default(0);
            $table->decimal('other_charges', 12, 2)->default(0);

            // Notes and terms
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('payment_instructions')->nullable();

            // Document tracking
            $table->string('pdf_path')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
