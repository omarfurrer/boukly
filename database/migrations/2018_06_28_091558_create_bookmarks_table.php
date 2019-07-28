<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookmarksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'bookmarks',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->mediumText('url'); //TODO: make unique
                $table->mediumText('title')->nullable();
                $table->mediumText('description')->nullable();
                $table->mediumText('image')->nullable();
                $table->string('domain');
                $table->boolean('is_dead')->default(0);
                $table->integer('http_code')->nullable();
                $table->string('http_message')->nullable();
                $table->timestamp('last_availability_check_at')->nullable();
                $table->boolean('is_adult')->default(0);
                $table->json('metatags')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmarks');
    }
}
