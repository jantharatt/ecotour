<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // admin = ผู้ดูแลระบบ, member = สมาชิกวิสาหกิจ/ชุมชน, customer = นักท่องเที่ยว/ลูกค้า
            $table->enum('role', ['admin', 'member', 'customer'])->default('customer')->after('email');
            $table->string('phone', 20)->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address']);
        });
    }
};
