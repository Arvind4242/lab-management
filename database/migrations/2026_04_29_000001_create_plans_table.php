<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_yearly', 10, 2)->default(0);
            $table->integer('max_reports')->default(-1);
            $table->integer('max_users')->default(-1);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('razorpay_plan_id')->nullable();
            $table->timestamps();
        });

        // Seed default plans
        \DB::table('plans')->insert([
            ['name' => 'Free Trial', 'slug' => 'free-trial', 'description' => '30-day free trial with limited reports', 'price_monthly' => 0, 'price_yearly' => 0, 'max_reports' => 50, 'max_users' => 2, 'features' => json_encode(['50 Reports/month', '2 Users', 'Basic PDF Reports', 'Email Support']), 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Basic', 'slug' => 'basic', 'description' => 'Perfect for small diagnostic labs', 'price_monthly' => 999, 'price_yearly' => 9999, 'max_reports' => 200, 'max_users' => 5, 'features' => json_encode(['200 Reports/month', '5 Users', 'PDF & Print Reports', 'Test Panels & Packages', 'Email Support']), 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Professional', 'slug' => 'professional', 'description' => 'For growing labs with higher volume', 'price_monthly' => 2499, 'price_yearly' => 24999, 'max_reports' => 1000, 'max_users' => 15, 'features' => json_encode(['1000 Reports/month', '15 Users', 'PDF & Print Reports', 'Test Panels & Packages', 'Custom Lab Branding', 'Priority Support']), 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Enterprise', 'slug' => 'enterprise', 'description' => 'Unlimited capacity for large diagnostics chains', 'price_monthly' => 4999, 'price_yearly' => 49999, 'max_reports' => -1, 'max_users' => -1, 'features' => json_encode(['Unlimited Reports', 'Unlimited Users', 'PDF & Print Reports', 'Test Panels & Packages', 'Custom Lab Branding', 'API Access', 'Dedicated Support']), 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
