<?php

use App\Enum\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname');
            $table->string('username');
            $table->string('password');
            $table->string("mobile", 20);
            $table->string("email")->unique();
            $table->boolean("super_admin")->default(false);
            $table->string('pic')->nullable();
            $table->foreignId("province_id")->constrained();
            $table->foreignId("city_id")->constrained();
            $table->timestamp("birth_date");
            $table->enum('gender', array_column(Gender::cases(), 'value'));
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
