<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id');
            $table->string('chart_number')->unique();
            $table->string('legal_first_name');
            $table->string('legal_middle_name')->nullable();
            $table->string('legal_last_name');
            $table->string('legal_suffix')->nullable();
            $table->string('legal_prefix')->nullable();
            $table->string('nickname')->nullable();
            $table->string('birth_first_name')->nullable();
            $table->string('birth_middle_name')->nullable();
            $table->string('birth_last_name')->nullable();
            $table->string('birth_suffix')->nullable();
            $table->string('birth_prefix')->nullable();
            $table->string('gender')->nullable();
            $table->string('ssn')->nullable();
            $table->longText('comments')->nullable();
            $table->timestamp('date_of_birth')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('client_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('author_id');
            $table->string('type')->nullable();
            $table->boolean('primary')->default(0);
            $table->boolean('verified')->default(0);
            $table->string('email');
            $table->longText('comments')->nullable();
            $table->timestamp('active_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('client_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('author_id');
            $table->string('type')->nullable();
            $table->boolean('primary')->default(0);
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->longText('comments')->nullable();
            $table->timestamp('active_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('client_phones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('author_id');
            $table->string('type')->nullable();
            $table->string('contact_time')->nullable();
            $table->boolean('primary')->default(0);
            $table->string('phone');
            $table->longText('comments')->nullable();
            $table->timestamp('active_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('client_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('author_id');
            $table->longText('comments')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('denied_at')->nullable();
            $table->timestamp('active_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('client_rendered_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('service_id');
            $table->longText('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lists', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('system')->default(0);
            $table->string('list_name');
            $table->string('item_title');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('case_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('client_id');
            $table->longText('comments');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('client_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('user_id');
            $table->string('type');
            $table->longText('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('client_record_views', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('client_id');
            $table->longText('comments');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('versions', function(Blueprint $table)
        {
            $table->increments('version_id');
            $table->string('versionable_id');
            $table->string('versionable_type');
            $table->string('user_id')->nullable();
            $table->binary('model_data');
            $table->string('reason', 100)->nullable();
            $table->index('versionable_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
