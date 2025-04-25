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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('botanical_name');
            $table->string('scientific_name');
            $table->string('also_known_as');
            $table->longText('description');
            $table->string('toughness');
            $table->string('maintenance');
            $table->string('sunlight');
            $table->string('hardness_zone');
            $table->decimal('min_ph', 8, 2);
            $table->decimal('max_ph', 8, 2);
            $table->decimal('min_tp', 8, 2);
            $table->decimal('ideal_min_tp', 8, 2);
            $table->decimal('ideal_max_tp' , 8, 2);
            $table->decimal('max_tp' , 8, 2);
            $table->string('water');
            $table->string('repotting');
            $table->string('fertilizer');
            $table->string('misting');
            $table->string('pruning');
            $table->longText('uses');
            $table->longText('cluture');
            $table->string('pests');
            $table->string('diseases');

            $table->foreignId('genus_id')->constrained('genera')->onDelete('cascade');
            $table->foreignId('family_id')->constrained('families') ->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('phylum_id')->constrained('phylums')->onDelete('cascade');;
            $table->foreignId('type_id')->constrained('types')->onDelete('cascade');
            $table->foreignId('drainage_id')->constrained('drainages')->onDelete('cascade');;
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
