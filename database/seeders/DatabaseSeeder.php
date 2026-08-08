<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@ayasfoodlink.co.id'], ['name' => 'Administrator AYAS', 'password' => Hash::make('admin12345')]);

        foreach (config('ayas.content_sections') as $fields) {
            foreach ($fields as $key => $field) {
                foreach (['en', 'id'] as $language) {
                    Setting::firstOrCreate(['key' => "content_{$key}_{$language}"], ['value' => $field[$language]]);
                }
            }
        }
        foreach (config('ayas.details') as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        foreach (config('ayas.media') as $key => $item) {
            Setting::firstOrCreate(['key' => "media_{$key}"], ['value' => $item['default']]);
        }
        foreach (['seo', 'tracking', 'qontak'] as $group) {
            foreach (config("ayas.{$group}") as $key => $field) {
                Setting::firstOrCreate(['key' => $key], ['value' => $field['default']]);
            }
        }

        $products = [
            ['name' => 'Gelato', 'name_id' => 'Gelato', 'slug' => 'gelato', 'market' => 'Domestic Market Only (Indonesia)', 'market_id' => 'Khusus Pasar Domestik (Indonesia)', 'short_description' => 'Assorted flavours for local HORECA supply.', 'short_description_id' => 'Beragam pilihan rasa untuk pasokan HORECA lokal.', 'description' => 'Assorted flavours for local HORECA supply.', 'description_id' => 'Beragam pilihan rasa untuk pasokan HORECA lokal.', 'image' => 'assets/images/gelato-fallback.jpg', 'image_url' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?auto=format&fit=crop&w=1400&q=86', 'gallery_image' => 'assets/images/gelato-fallback.jpg', 'sort_order' => 1],
            ['name' => 'Smoked Chicken', 'name_id' => 'Smoked Chicken', 'slug' => 'smoked-chicken', 'market' => 'Domestic Market Only (Indonesia)', 'market_id' => 'Khusus Pasar Domestik (Indonesia)', 'short_description' => 'Tender, flavourful, and ready for service.', 'short_description_id' => 'Empuk, kaya rasa, dan siap disajikan.', 'description' => 'Tender, flavourful, and ready for service.', 'description_id' => 'Empuk, kaya rasa, dan siap disajikan.', 'image' => 'assets/images/chicken-fallback.jpg', 'image_url' => 'https://images.unsplash.com/photo-1532550907401-a500c9a57435?auto=format&fit=crop&w=1100&q=86', 'gallery_image' => 'assets/images/chicken-fallback.jpg', 'sort_order' => 2],
            ['name' => 'Smoked Brisket', 'name_id' => 'Smoked Brisket', 'slug' => 'smoked-brisket', 'market' => 'Domestic Market Only (Indonesia)', 'market_id' => 'Khusus Pasar Domestik (Indonesia)', 'short_description' => 'Slow-smoked perfection with rich character.', 'short_description_id' => 'Diasap perlahan dengan cita rasa yang kaya.', 'description' => 'Slow-smoked perfection with rich character.', 'description_id' => 'Diasap perlahan dengan cita rasa yang kaya.', 'image' => 'assets/images/brisket-fallback.jpg', 'image_url' => 'https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?auto=format&fit=crop&w=1100&q=86', 'gallery_image' => 'assets/images/brisket-fallback.jpg', 'sort_order' => 3],
            ['name' => 'Frozen Tortilla Wrap', 'name_id' => 'Frozen Tortilla Wrap', 'slug' => 'frozen-tortilla-wrap', 'market' => 'Export Market Only', 'market_id' => 'Khusus Pasar Ekspor', 'short_description' => 'Flexible, convenient, and export ready.', 'short_description_id' => 'Fleksibel, praktis, dan siap ekspor.', 'description' => 'Flexible, convenient, and export ready.', 'description_id' => 'Fleksibel, praktis, dan siap ekspor.', 'image' => 'assets/images/tortilla-fallback.jpg', 'image_url' => 'https://images.unsplash.com/photo-1626700051175-6818013e1d4f?auto=format&fit=crop&w=1400&q=86', 'gallery_image' => 'assets/images/tortilla-fallback.jpg', 'sort_order' => 4],
        ];
        foreach ($products as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product + ['is_active' => true]);
        }

        $posts = [
            ['title' => 'Supply Progress', 'title_id' => 'Perkembangan Pasokan', 'slug' => 'perkembangan-pasokan', 'category' => 'Supply update', 'category_id' => 'Kabar pasokan', 'excerpt' => 'Developments in product availability, fulfilment, and distribution for our customers.', 'excerpt_id' => 'Perkembangan ketersediaan produk, pemenuhan pesanan, dan distribusi untuk pelanggan kami.', 'body' => "AYAS FOODLINK continues to develop practical and dependable coordination between producers, products, and customer requirements.\n\nUpdates can include new product availability, fulfilment progress, distribution coverage, and improvements that help customers plan their operational needs more confidently.", 'body_id' => "AYAS FOODLINK terus mengembangkan koordinasi yang praktis dan dapat diandalkan antara produsen, produk, dan kebutuhan pelanggan.\n\nKabar dapat mencakup ketersediaan produk baru, perkembangan pemenuhan pesanan, jangkauan distribusi, dan peningkatan yang membantu pelanggan merencanakan kebutuhan operasional dengan lebih baik.", 'image' => 'assets/images/warehouse-fallback.jpg', 'gallery_image_2' => 'assets/images/horeca-fallback.jpg', 'gallery_image_3' => 'assets/images/brisket-fallback.jpg', 'gallery_caption_1' => 'Products prepared for customer requirements', 'gallery_caption_1_id' => 'Produk disiapkan untuk kebutuhan pelanggan', 'gallery_caption_2' => 'Support for HORECA operations', 'gallery_caption_2_id' => 'Dukungan bagi operasional HORECA', 'gallery_caption_3' => 'Coordinated supply and distribution', 'gallery_caption_3_id' => 'Koordinasi pasokan dan distribusi'],
            ['title' => 'Export Activities', 'title_id' => 'Kegiatan Ekspor', 'slug' => 'kegiatan-ekspor', 'category' => 'Export update', 'category_id' => 'Kabar ekspor', 'excerpt' => 'Stories and milestones from our growing export supply activities.', 'excerpt_id' => 'Cerita dan pencapaian dari aktivitas pasokan ekspor yang terus berkembang.', 'body' => "This page is prepared to share verified developments from AYAS FOODLINK export activities. Each update can explain the product, destination, preparation process, and partners involved.\n\nThe focus is on clear information that helps customers and partners understand product readiness and the coordination required for dependable export supply.", 'body_id' => "Halaman ini disiapkan untuk membagikan perkembangan terverifikasi dari kegiatan ekspor AYAS FOODLINK. Setiap kabar dapat menjelaskan produk, negara tujuan, proses persiapan, dan mitra yang terlibat.\n\nFokusnya adalah informasi jelas yang membantu pelanggan dan mitra memahami kesiapan produk serta koordinasi yang diperlukan untuk pasokan ekspor yang dapat diandalkan.", 'image' => 'assets/images/tortilla-fallback.jpg', 'gallery_image_2' => 'assets/images/gelato-fallback.jpg', 'gallery_image_3' => 'assets/images/warehouse-fallback.jpg', 'gallery_caption_1' => 'Export-ready product portfolio', 'gallery_caption_1_id' => 'Portofolio produk siap ekspor', 'gallery_caption_2' => 'Product variety for international markets', 'gallery_caption_2_id' => 'Ragam produk untuk pasar internasional', 'gallery_caption_3' => 'Supply preparation and coordination', 'gallery_caption_3_id' => 'Persiapan dan koordinasi pasokan'],
            ['title' => 'Partner Factory Visits', 'title_id' => 'Kunjungan Pabrik Mitra', 'slug' => 'kunjungan-pabrik-mitra', 'category' => 'Partner activity', 'category_id' => 'Kegiatan mitra', 'excerpt' => 'A closer look at the partners, facilities, and quality processes behind our supply.', 'excerpt_id' => 'Mengenal lebih dekat mitra, fasilitas, dan proses mutu di balik pasokan kami.', 'body' => "Factory visits provide an opportunity to understand production capabilities, quality practices, and the teams behind the products supplied by AYAS FOODLINK.\n\nFuture updates can present verified visit documentation, production insights, and the steps taken with partners to maintain dependable products and supply chains.", 'body_id' => "Kunjungan pabrik menjadi kesempatan untuk memahami kemampuan produksi, praktik mutu, dan tim di balik produk yang dipasok oleh AYAS FOODLINK.\n\nKabar berikutnya dapat menampilkan dokumentasi kunjungan terverifikasi, wawasan produksi, dan langkah bersama mitra untuk menjaga produk serta rantai pasok yang dapat diandalkan.", 'image' => 'assets/images/horeca-fallback.jpg', 'gallery_image_2' => 'assets/images/chicken-fallback.jpg', 'gallery_image_3' => 'assets/images/brisket-fallback.jpg', 'gallery_caption_1' => 'Facilities supporting dependable supply', 'gallery_caption_1_id' => 'Fasilitas yang mendukung pasokan terpercaya', 'gallery_caption_2' => 'Product handling and preparation', 'gallery_caption_2_id' => 'Penanganan dan persiapan produk', 'gallery_caption_3' => 'Quality products from trusted producers', 'gallery_caption_3_id' => 'Produk berkualitas dari produsen terpercaya'],
        ];
        foreach ($posts as $post) {
            Post::updateOrCreate(['slug' => $post['slug']], $post + ['published_at' => now(), 'is_published' => true]);
        }
    }
}
