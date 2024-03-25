<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('timestamps', function (Blueprint $table) {
            $table->string('issue_title')->nullable();
            $table->longText('issue_body')->nullable();
            $table->string('issue_url')->nullable();
            $table->string('repo_name')->nullable()->index();
            $table->string('issue_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timestamps', function (Blueprint $table) {
            $table->dropColumn('issue_title');
            $table->dropColumn('issue_body');
            $table->dropColumn('issue_url');
            $table->dropColumn('repo_name');
            $table->dropColumn('issue_number');
        });
    }
};
