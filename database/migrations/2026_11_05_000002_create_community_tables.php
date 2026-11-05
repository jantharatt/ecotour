<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ชุมชน / วิสาหกิจชุมชน
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline', 200)->nullable();     // คำขวัญ/จุดเด่นสั้น ๆ
            $table->longText('description')->nullable();    // เรื่องราว ประวัติชุมชน
            $table->text('conservation')->nullable();       // งานอนุรักษ์ที่ชุมชนทำ
            $table->string('address')->nullable();
            $table->string('subdistrict', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('line_id', 50)->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // แหล่งท่องเที่ยว / จุดเรียนรู้ / ร้านอาหาร / ที่พัก ในชุมชน (นำเสนอข้อมูล ไม่มีการจอง)
        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['nature', 'culture', 'learning', 'food', 'homestay', 'viewpoint'])->default('nature');
            $table->enum('faith', ['buddhism', 'christianity', 'islam'])->nullable(); // สำหรับเส้นทาง 3 ศาสนา
            $table->string('summary', 300)->nullable();
            $table->longText('description')->nullable();
            $table->string('address')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('opening_hours', 150)->nullable();  // เช่น ทุกวัน 08:00–17:00
            $table->string('entry_fee', 100)->nullable();      // เช่น ฟรี / 20 บาท
            $table->string('best_time', 150)->nullable();      // ช่วงเวลา/ฤดูที่แนะนำ
            $table->string('contact_phone', 20)->nullable();
            $table->text('eco_tips')->nullable();              // ข้อปฏิบัติเพื่อการอนุรักษ์
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index(['lat', 'lng']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attractions');
        Schema::dropIfExists('communities');
    }
};
