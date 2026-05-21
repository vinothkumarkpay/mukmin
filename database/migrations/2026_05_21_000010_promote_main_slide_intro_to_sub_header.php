<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PromoteMainSlideIntroToSubHeader extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $row = DB::table('widgets')->where('slug', 'home-hero')->first();
        if (! $row) {
            return;
        }

        $newHeroContent = <<<'HTML'
<h1 id="mukmin-hero-heading" class="mukmin-hero__headline">
    <span class="mukmin-hero__line">One Identity.</span>
    <span class="mukmin-hero__line">One Vision.</span>
    <span class="mukmin-hero__line">One Community<span class="mukmin-hero__side-panel-origin" aria-hidden="true"></span>.</span>
</h1>
<p class="mukmin-hero__sub-headline">MUKMIN is a national platform advancing inclusive community development, empowering people, shaping future-ready talent and strengthening collaboration through a unified, values-driven ecosystem.</p>
<div class="mukmin-hero__sub">
    <p class="mukmin-hero__subline">We connect communities, align stakeholders and turn ideas into action&mdash;creating real opportunities across Malaysia.</p>
</div>
HTML;

        DB::table('widgets')->where('id', $row->id)->update([
            'content' => $newHeroContent,
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        //
    }
}
