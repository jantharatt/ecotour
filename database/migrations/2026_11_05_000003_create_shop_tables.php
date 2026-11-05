<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // ของฝาก, ผ้าทอ, อาหารแปรรูป, หัตถกรรม ...
            $table->string('slug')->unique();
            $table->string('icon', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_category_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('producer')->nullable();     // ผู้ผลิต/กลุ่มแม่บ้าน
            $table->text('story')->nullable();          // เรื่องเล่าผลิตภัณฑ์
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->string('unit', 30)->default('ชิ้น');
            $table->unsignedInteger('weight_gram')->default(0); // ใช้คำนวณค่าส่ง
            $table->string('image')->nullable();
            $table->boolean('is_otop')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('payment_method', ['promptpay', 'transfer', 'cod'])->default('promptpay');
            $table->string('payment_slip')->nullable();
            $table->enum('status', ['pending', 'paid', 'packing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->string('ship_name');
            $table->string('ship_phone', 20);
            $table->text('ship_address');
            $table->string('tracking_no', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');   // snapshot ชื่อ ณ วันที่สั่ง
            $table->decimal('price', 10, 2);  // snapshot ราคา ณ วันที่สั่ง
            $table->unsignedInteger('qty');
            $table->decimal('line_total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
