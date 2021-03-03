<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('acronyms')->nullable()->default(null);
            $table->string('institution')->nullable()->default(null);
            $table->string('uc_name')->nullable()->default(null);
            $table->string('responsible_uc')->nullable()->default(null);
            $table->mediumText('folder_title')->nullable()->default(null);
            $table->date('published_at')->nullable()->default(null);
            $table->date('opened_at')->nullable()->default(null);
            $table->string('contract_type')->nullable()->default(null);
            $table->string('procedure_type')->nullable()->default(null);
            $table->string('contract_code')->nullable()->default(null);
            $table->mediumText('contract_title')->nullable()->default(null);
            $table->date('contract_init_date')->nullable()->default(null);
            $table->date('contract_finish_date')->nullable()->default(null);
            $table->double('contract_amount', 16, 3)->nullable()->default(null);
            $table->string('currency', 4)->nullable()->default(null);
            $table->string('contract_status', 15)->nullable()->default(null);
            $table->string('provider')->nullable()->default(null);
            $table->string('company_size')->nullable()->default(null);
            $table->string('rfc', 15)->nullable()->default(null);
            $table->boolean('rfc_verification')->nullable()->default(null);
            $table->string('address_ad')->nullable()->default(null);
            $table->string('dataset_origin')->nullable()->default(null);
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
        Schema::dropIfExists('contracts');
    }
}
