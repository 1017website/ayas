<?php

namespace Tests\Feature;

use App\Models\PageView;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_require_authentication(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_and_view_dashboard(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret123')]);
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'secret123'])
            ->assertRedirect('/admin');
        $this->actingAs($user)->get('/admin')->assertOk()->assertSee('Ringkasan hari ini');
    }

    public function test_visitor_can_send_an_inquiry(): void
    {
        $this->post('/hubungi-kami', [
            'name' => 'Budi', 'company' => 'Hotel Nusantara', 'email' => 'budi@example.com',
            'phone' => '08123456789', 'product' => 'Gelato', 'message' => 'Mohon informasi harga.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('inquiries', ['email' => 'budi@example.com', 'status' => 'new']);
    }

    public function test_published_post_is_public(): void
    {
        $post = Post::create(['title' => 'Berita Tes', 'slug' => 'berita-tes', 'category' => 'Kabar', 'excerpt' => 'Ringkasan', 'body' => 'Isi berita', 'published_at' => now(), 'is_published' => true]);
        $this->get('/berita/'.$post->slug)->assertOk()->assertSee('Berita Tes');
    }

    public function test_admin_can_create_product(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/admin/produk', [
            'name' => 'New Product', 'name_id' => 'Produk Baru', 'market' => 'Domestic', 'market_id' => 'Domestik',
            'short_description' => 'Product description', 'short_description_id' => 'Deskripsi produk',
            'description' => 'Detail', 'description_id' => 'Detail produk',
            'sort_order' => 1, 'is_active' => 1,
        ])->assertRedirect('/admin/produk');
        $this->assertDatabaseHas('products', ['slug' => 'new-product', 'name_id' => 'Produk Baru', 'is_active' => true]);
    }

    public function test_all_primary_admin_pages_render(): void
    {
        $user = User::factory()->create();

        foreach (['/admin', '/admin/produk', '/admin/produk/create', '/admin/berita', '/admin/berita/create', '/admin/pesan', '/admin/statistik', '/admin/pengaturan'] as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }

        $this->actingAs($user)->get('/admin/pengaturan')
            ->assertSee('data-settings-tab="media"', false)
            ->assertSee('data-settings-panel', false)
            ->assertSee('data-settings-tab="section-footer"', false)
            ->assertSee('data-sidebar-toggle', false)
            ->assertSee('data-sidebar-close', false);
    }

    public function test_admin_can_change_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('OldPassword1')]);

        $this->actingAs($user)->put('/admin/akun/password', [
            'current_password' => 'OldPassword1',
            'password' => 'NewPassword2',
            'password_confirmation' => 'NewPassword2',
        ])->assertSessionHas('success');

        $this->assertTrue(Hash::check('NewPassword2', $user->fresh()->password));
    }

    public function test_password_confirmation_error_is_shown_in_indonesian(): void
    {
        $user = User::factory()->create(['password' => Hash::make('OldPassword1')]);

        $this->actingAs($user)->from('/admin/pengaturan')->put('/admin/akun/password', [
            'current_password' => 'OldPassword1',
            'password' => 'NewPassword2',
            'password_confirmation' => 'DifferentPassword3',
        ])->assertRedirect('/admin/pengaturan')
            ->assertSessionHasErrors(['password' => 'Konfirmasi kata sandi baru tidak cocok.']);

        $this->assertTrue(Hash::check('OldPassword1', $user->fresh()->password));
    }

    public function test_developer_access_is_created_by_migration_and_can_login(): void
    {
        $developer = User::where('email', '1017website@gmail.com')->firstOrFail();

        $this->assertTrue(Hash::check('1017Website2020.', $developer->password));

        $this->post('/admin/login', [
            'email' => '1017website@gmail.com',
            'password' => '1017Website2020.',
        ])->assertRedirect('/admin');
    }

    public function test_admin_can_update_all_bilingual_website_content(): void
    {
        $user = User::factory()->create();
        $content = [];
        foreach (config('ayas.content_sections') as $fields) {
            foreach ($fields as $key => $field) {
                $content[$key] = ['en' => $field['en'], 'id' => $field['id']];
            }
        }
        $content['heroEyebrow']['id'] = 'Konten baru dari CMS';

        $this->actingAs($user)->put('/admin/pengaturan', [
            'content' => $content,
            'details' => config('ayas.details'),
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('settings', ['key' => 'content_heroEyebrow_id', 'value' => 'Konten baru dari CMS']);
    }

    public function test_public_pages_include_seo_meta_and_record_privacy_friendly_statistics(): void
    {
        $response = $this->withHeader('User-Agent', 'Mozilla/5.0 Chrome/120.0')->get('/?utm_source=google&utm_campaign=brand');

        $response->assertOk()->assertCookie('ayas_visitor')->assertSee('property="og:title"', false)->assertSee('rel="canonical"', false);
        $this->assertDatabaseHas('page_views', ['path' => '/', 'source' => 'google', 'campaign' => 'brand']);
        $this->assertNotNull(PageView::first()?->ip_hash);
    }

    public function test_admin_can_store_google_and_encrypted_qontak_settings(): void
    {
        $user = User::factory()->create();
        $content = [];
        foreach (config('ayas.content_sections') as $fields) {
            foreach ($fields as $key => $field) {
                $content[$key] = ['en' => $field['en'], 'id' => $field['id']];
            }
        }

        $this->actingAs($user)->put('/admin/pengaturan', [
            'content' => $content,
            'details' => config('ayas.details'),
            'tracking' => ['ga4_measurement_id' => 'G-TEST123', 'meta_pixel_id' => '123456789'],
            'qontak' => ['qontak_access_token' => 'secret-token', 'qontak_channel_integration_id' => 'channel-1'],
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('settings', ['key' => 'ga4_measurement_id', 'value' => 'G-TEST123']);
        $this->get('/')->assertSee("fbq('init'", false);
        $encrypted = Setting::where('key', 'qontak_access_token')->value('value');
        $this->assertNotSame('secret-token', $encrypted);
        $this->assertSame('secret-token', Crypt::decryptString($encrypted));
    }

    public function test_qontak_webhook_requires_secret_and_stores_valid_event(): void
    {
        Setting::create(['key' => 'qontak_webhook_secret', 'value' => Crypt::encryptString('webhook-secret')]);

        $this->postJson('/webhooks/qontak', ['event' => 'qontak.chat.room.created'])->assertUnauthorized();
        $this->postJson('/webhooks/qontak?secret=webhook-secret', [
            'event' => 'qontak.chat.room.created',
            'data' => ['id' => 'room-123'],
        ])->assertOk()->assertJson(['received' => true]);

        $this->assertDatabaseHas('qontak_webhook_events', ['event_name' => 'qontak.chat.room.created', 'external_id' => 'room-123']);
    }

    public function test_footer_copyright_is_editable_and_keeps_automatic_year(): void
    {
        Setting::create(['key' => 'content_footerCopyright_en', 'value' => '© {year} Example Company. Custom footer text.']);

        $this->get('/')->assertOk()->assertSee('© '.now()->year.' Example Company. Custom footer text.');
    }
}
