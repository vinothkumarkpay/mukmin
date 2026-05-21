<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Widget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WidgetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed default widgets & admin user
        $this->seed(\Database\Seeders\PortalSeeder::class);
    }

    public function test_homepage_renders_voice_of_mukmin_and_no_eyebrow()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Voices of Mukmin');
        $response->assertDontSee('VOICES OF CHANGE');
        
        // Check that Youtube thumbnail links are present
        $response->assertSee('https://img.youtube.com/vi/9No-FiE9yyo/hqdefault.jpg');
        $response->assertSee('https://img.youtube.com/vi/k1w5Z7d5a5s/hqdefault.jpg');
    }

    public function test_homepage_renders_premium_hero_slider()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee('mukmin-hero--split');
        $response->assertSee('js-hero-slide');
        $response->assertSee('mukmin-hero__media');
        $response->assertSee('mukmin-hero__card');
        $response->assertSee('js-hero-prev');
        $response->assertSee('js-hero-next');
        $response->assertSee('js-hero-indicator');

        // Verify LinkedIn social button is in the top section
        $response->assertSee('header-social-btn--linkedin');

        // Verify the old continuous card carousel container classes are removed
        $response->assertDontSee('mukmin-hero-carousel');
        $response->assertDontSee('mukmin-hero__banners');
    }

    public function test_admin_can_update_voices_youtube_urls()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $widget = Widget::where('slug', 'home-voices')->first();

        $this->actingAs($admin);

        // Edit widget settings
        $settings = $widget->settings;
        $settings['voices_title'] = 'Voices of Mukmin';
        $settings['voices_items'][0]['quote'] = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
        
        // Prepare request parameters matching syncVoicesSettings logic
        $postData = [
            'slug' => 'home-voices',
            'title' => 'Voices of Mukmin',
            'zone' => 'home',
            'is_active' => 1,
            'voices_title' => 'Voices of Mukmin',
            'sort_order' => 15,
            'voices_marquee_seconds' => 0,
            'voices_marquee_autoplay' => 1,
            'voices_items' => $settings['voices_items'],
        ];

        $response = $this->put(route('admin.widgets.update', $widget), $postData);
        $response->assertRedirect(route('admin.widgets.index'));

        // Assert DB updated
        $updatedWidget = $widget->fresh();
        $this->assertEquals('Voices of Mukmin', $updatedWidget->settings['voices_title']);
        $this->assertEquals('https://www.youtube.com/watch?v=dQw4w9WgXcQ', $updatedWidget->settings['voices_items'][0]['quote']);
        $this->assertEquals('', $updatedWidget->settings['voices_eyebrow']);
        $this->assertEquals(15, $updatedWidget->sort_order);

        // Check homepage response with updated custom video
        $response = $this->get('/');
        $response->assertSee('Voices of Mukmin');
        $response->assertSee('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');
    }
}
