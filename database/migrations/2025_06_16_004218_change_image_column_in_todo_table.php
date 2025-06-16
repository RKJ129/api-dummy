<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeImageColumnInTodoTable extends Migration
{
    public function up()
    {
        Schema::table('todo', function (Blueprint $table) {
            $table->text('image')->change(); // ubah dari VARCHAR ke TEXT
        });
    }

    public function down()
    {
        Schema::table('todo', function (Blueprint $table) {
            $table->string('image')->change(); // kembalikan ke string jika rollback
        });
    }
}
