<?php

namespace Tests\Feature;

use App\Models\Community;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\TourPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_tour_details_show_community_contact_without_booking_controls(): void
    {
        $community = Community::create([
            'name' => 'ชุมชนริมน้ำ',
            'slug' => 'riverside-community',
            'province' => 'สุพรรณบุรี',
            'contact_phone' => '0812345678',
            'is_active' => true,
        ]);
        $tour = TourPackage::create([
            'community_id' => $community->id,
            'name' => 'เส้นทางป่าชุมชน',
            'slug' => 'community-forest',
            'duration_days' => 1,
            'price_adult' => 500,
            'price_child' => 250,
            'max_people_per_day' => 20,
            'is_active' => true,
        ]);

        $response = $this->get(route('tours.show', $tour));

        $response->assertOk()
            ->assertSee('เส้นทางป่าชุมชน')
            ->assertSee('tel:0812345678')
            ->assertSee('สอบถามราคาและรายละเอียดการเดินทางกับชุมชนโดยตรง')
            ->assertDontSee('จองทริปนี้')
            ->assertDontSee('วันเดินทาง');
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_booking_pages_are_not_available(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/bookings')->assertNotFound();
        $this->actingAs($user)->get('/account/bookings/old-booking')->assertNotFound();
    }

    public function test_account_page_only_displays_product_orders(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('account'));

        $response->assertOk()
            ->assertSee('ติดตามคำสั่งซื้อสินค้า')
            ->assertDontSee('การจองทริป');
    }

    public function test_active_community_page_is_publicly_accessible(): void
    {
        $community = Community::create([
            'name' => 'ชุมชนริมน้ำ',
            'slug' => 'riverside-community',
            'is_active' => true,
        ]);

        $this->get(route('communities.show', $community))
            ->assertOk()
            ->assertSee('ชุมชนริมน้ำ');
    }

    public function test_shop_displays_products_from_active_communities(): void
    {
        $community = Community::create([
            'name' => 'ชุมชนสินค้า',
            'slug' => 'product-community',
            'is_active' => true,
        ]);
        $category = ProductCategory::create(['name' => 'ของฝาก', 'slug' => 'souvenirs']);
        Product::create([
            'community_id' => $community->id,
            'product_category_id' => $category->id,
            'name' => 'กระเป๋าสานชุมชน',
            'slug' => 'community-bag',
            'price' => 450,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->get(route('shop.index'))
            ->assertOk()
            ->assertSee('กระเป๋าสานชุมชน')
            ->assertSee('จากชุมชน ชุมชนสินค้า')
            ->assertSee(route('communities.show', $community), false);
    }

    public function test_admin_dashboard_shows_store_and_content_summaries(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('ชุมชนที่เผยแพร่')
            ->assertSee('ยอดขายสินค้า 6 เดือนล่าสุด')
            ->assertDontSee('การจอง');
    }

    public function test_inactive_community_is_not_publicly_visible(): void
    {
        $community = Community::create([
            'name' => 'ชุมชนที่ซ่อน',
            'slug' => 'hidden-community',
            'is_active' => false,
        ]);

        $this->get(route('communities.show', $community))->assertNotFound();
    }
}
