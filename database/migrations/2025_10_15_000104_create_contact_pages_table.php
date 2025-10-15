<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('contact_pages', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->string('phone_no')->nullable();
			$table->string('email')->nullable();
			$table->text('address')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('contact_pages');
	}
};


