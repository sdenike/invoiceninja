<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInclusiveTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_rates', function($table)
        {
            $table->boolean('is_inclusive')->default(false);
        });

        Schema::table('companies', function ($table)
        {
			$table->enum('bluevine_status', ['ignored', 'signed_up'])->nullable();
		});

        DB::statement('UPDATE companies
            LEFT JOIN accounts ON accounts.company_id = companies.id AND accounts.bluevine_status IS NOT NULL
            SET companies.bluevine_status = accounts.bluevine_status');

        Schema::table('accounts', function($table)
        {
            $table->dropColumn('bluevine_status');
            $table->text('bcc_email')->nullable();
            $table->text('client_number_prefix')->nullable();
            $table->integer('client_number_counter')->default(0)->nullable();
            $table->text('client_number_pattern')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_rates', function($table)
        {
            $table->dropColumn('is_inclusive');
        });

        Schema::table('companies', function($table)
        {
            $table->dropColumn('bluevine_status');
        });

        Schema::table('accounts', function ($table)
        {
			$table->enum('bluevine_status', ['ignored', 'signed_up'])->nullable();
            $table->dropColumn('bcc_email');
            $table->dropColumn('client_number_prefix');
            $table->dropColumn('client_number_counter');
            $table->dropColumn('client_number_pattern');
		});
    }
}
