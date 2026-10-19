<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('employers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('fine_reason_id')->nullable()->constrained('fine_reasons')->nullOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->text('comment')->nullable();
            $table->date('fined_at');
            $table->string('status', 20)->default('active')->index(); // active | paid | cancelled
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['employer_id', 'status']);
            $table->index('fined_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
