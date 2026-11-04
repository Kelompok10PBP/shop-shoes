<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings
        $settings = [
            ['key' => 'store_name', 'value' => 'Stride Footwear', 'group' => 'general'],
            ['key' => 'announcement_shipping', 'value' => 'Free Shipping on Orders Over $50', 'group' => 'announcement'],
            ['key' => 'announcement_guarantee', 'value' => '30-Day Money Back Guarantee', 'group' => 'announcement'],
            ['key' => 'announcement_rating', 'value' => 'Excellent 4.9 out of 5 Rating', 'group' => 'announcement'],
            ['key' => 'free_shipping_threshold', 'value' => '50.00', 'group' => 'checkout'],
            ['key' => 'standard_shipping_fee', 'value' => '0.00', 'group' => 'checkout'],
            ['key' => 'express_shipping_fee', 'value' => '15.00', 'group' => 'checkout'],
            ['key' => 'tax_rate', 'value' => '0.00', 'group' => 'checkout'],
            ['key' => 'currency_symbol', 'value' => '$', 'group' => 'general'],
            ['key' => 'currency_code', 'value' => 'USD', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'support@stridefootwear.test', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+1 (800) 555-7874', 'group' => 'contact'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 2. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@stride.test'],
            [
                'name' => 'Stride Administrator',
                'password' => Hash::make('Admin#12345'),
                'role' => 'admin',
                'phone' => '+1 (555) 019-2831',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@stride.test'],
            [
                'name' => 'Alex Runner',
                'password' => Hash::make('Customer#12345'),
                'role' => 'customer',
                'phone' => '+1 (555) 839-4412',
            ]
        );

        Address::create([
            'user_id' => $customer->id,
            'label' => 'Home',
            'recipient_name' => 'Alex Runner',
            'phone' => '+1 (555) 839-4412',
            'address_line1' => '742 Evergreen Terrace',
            'address_line2' => 'Apt 4B',
            'city' => 'Portland',
            'state' => 'OR',
            'postal_code' => '97201',
            'country' => 'United States',
            'is_default' => true,
        ]);

        // Reviewer pool
        $reviewers = [];
        $reviewerNames = [
            'Elena Rostova', 'Marcus Vance', 'David Chen', 'Sarah Jenkins', 'Lucas Miller',
            'Aria Montgomery', 'Liam Gallagher', 'Sophia Kim', 'Noah Brooks', 'Maya Patel',
            'Ethan Wright', 'Chloe Bennett', 'Oliver Scott', 'Isabella Morales', 'James Wilson'
        ];
        foreach ($reviewerNames as $idx => $rName) {
            $reviewers[] = User::firstOrCreate(
                ['email' => 'runner' . ($idx + 1) . '@stride.test'],
                [
                    'name' => $rName,
                    'password' => Hash::make('Secret#123'),
                    'role' => 'customer',
                ]
            );
        }

        // 3. Brands
        $brandMap = [];
        $brandsData = [
            ['name' => 'Stride Athletics', 'slug' => 'stride-athletics', 'description' => 'Flagship biomechanically engineered footwear.'],
            ['name' => 'Stride BioLab', 'slug' => 'stride-biolab', 'description' => 'Experimental natural bio-foam composites.'],
            ['name' => 'Terraform', 'slug' => 'terraform', 'description' => 'Rugged all-weather alpine trail equipment.'],
            ['name' => 'Monolith', 'slug' => 'monolith', 'description' => 'Minimalist urban leather silhouettes.'],
        ];
        foreach ($brandsData as $b) {
            $brandMap[$b['slug']] = Brand::create($b);
        }

        // 4. Categories (with target models count in mind)
        $categoriesData = [
            ['name' => 'Running', 'slug' => 'running', 'image' => '/storage/categories/running.jpg', 'order' => 1],
            ['name' => 'Sneakers', 'slug' => 'sneakers', 'image' => '/storage/categories/sneakers.jpg', 'order' => 2],
            ['name' => 'Casual', 'slug' => 'casual', 'image' => '/storage/categories/casual.jpg', 'order' => 3],
            ['name' => 'Formal', 'slug' => 'formal', 'image' => '/storage/categories/formal.jpg', 'order' => 4],
            ['name' => 'Trail & Hiking', 'slug' => 'trail-hiking', 'image' => '/storage/categories/trail.jpg', 'order' => 5],
            ['name' => 'Sandals', 'slug' => 'sandals', 'image' => '/storage/categories/sandals.jpg', 'order' => 6],
            ['name' => 'Kids', 'slug' => 'kids', 'image' => '/storage/categories/kids.jpg', 'order' => 7],
            ['name' => 'Accessories', 'slug' => 'accessories', 'image' => '/storage/categories/accessories.jpg', 'order' => 8],
        ];
        $catMap = [];
        foreach ($categoriesData as $c) {
            $catMap[$c['slug']] = Category::create($c);
        }

        // 5. Collections
        $collectionsData = [
            ['name' => 'CloudStrider Series', 'slug' => 'cloudstrider-series', 'image' => '/storage/categories/running.jpg', 'description' => 'Ultra-cushioned responsive road trainers designed for half and full marathon distances.'],
            ['name' => 'Terraform Trail', 'slug' => 'terraform-trail', 'image' => '/storage/categories/trail.jpg', 'description' => 'Rugged Vibram lugged outsoles paired with waterproof breathable membranes.'],
            ['name' => 'Monolith Minimalist', 'slug' => 'monolith-minimalist', 'image' => '/storage/categories/sneakers.jpg', 'description' => 'Low-profile silhouettes in vegetable-tanned leather for purposeful movement.'],
            ['name' => 'Zero-Drop Editions', 'slug' => 'zero-drop-editions', 'image' => '/storage/categories/casual.jpg', 'description' => 'Anatomical toe boxes and neutral heel drops promoting natural biomechanics.'],
            ['name' => 'Archive Sale', 'slug' => 'archive-sale', 'image' => '/storage/categories/formal.jpg', 'description' => 'Special vault editions and seasonal transitions at exceptional archive values.'],
        ];
        $colMap = [];
        foreach ($collectionsData as $col) {
            $colMap[$col['slug']] = Collection::create($col);
        }

        // 6. Hero Banner
        Banner::create([
            'type' => 'hero',
            'pill_label' => 'New Collection 2025',
            'title' => 'Step Into Your',
            'title_highlight' => 'Best Pace',
            'subtitle' => 'Precision-engineered running and everyday performance footwear built with responsive bio-foam cushioning for 10K+ runs and daily stride.',
            'cta_text' => 'Shop Now',
            'cta_url' => '/shop',
            'secondary_cta_text' => 'Explore Collections',
            'secondary_cta_url' => '/collections',
            'image' => '/storage/banners/hero-aeroglide.png',
            'tech_tag_text' => 'BioFoam Propulsion v2',
            'discount_badge_percent' => 30,
            'is_active' => true,
        ]);

        // 7. Promotion (Countdown Banner)
        Promotion::create([
            'title' => 'Spring Performance Sale — Up to 40% Off Select Running Models',
            'subtitle' => 'Spring Sale Is Live!',
            'description' => 'Limited inventory on our carbon-plated racers and daily trainers. Engineered for long-distance durability. Ends midnight Sunday.',
            'discount_text' => 'Up to 40% Off',
            'image_1' => '/storage/banners/promo-runner.jpg',
            'image_2' => '/storage/banners/promo-plate.jpg',
            'cta_text' => 'Shop the Sale →',
            'cta_url' => '/shop?sale=1',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(5)->setTime(23, 59, 59),
            'is_active' => true,
        ]);

        // 8. Coupons
        Coupon::create([
            'code' => 'WELCOME15',
            'type' => 'percent',
            'value' => 15,
            'min_spend' => 50,
            'max_discount' => 100,
            'usage_limit' => 1000,
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'SPRING10',
            'type' => 'percent',
            'value' => 10,
            'min_spend' => 40,
            'max_discount' => 50,
            'usage_limit' => 500,
            'expires_at' => now()->addMonths(1),
            'is_active' => true,
        ]);

        // 9. Core 8 Products from Reference + Additional Catalog Items
        $productsToSeed = [
            // PRODUCT 1 (Trending #1)
            [
                'name' => 'Stride AeroGlide Carbon',
                'slug' => 'stride-aeroglide-carbon',
                'sku' => 'STR-AGC-001',
                'category' => 'running',
                'brand' => 'stride-athletics',
                'subtitle' => 'Marathon Racing Shoe',
                'description' => 'Engineered for sub-3-hour marathoners and elite road racers. Features our proprietary full-length curved carbon fiber propulsion plate suspended in dual-density nitrogen-infused BioFoam.',
                'details' => ['Drop' => '8mm', 'Weight' => '198g (US 9)', 'Stack Height' => '39mm heel / 31mm forefoot', 'Upper' => 'Single-layer engineered monomesh', 'Midsole' => 'Supercritical BioFoam with carbon plate'],
                'base_price' => 210.00,
                'compare_at_price' => 210.00,
                'discount_type' => 'fixed',
                'discount_value' => 32.00, // 210 - 32 = 178
                'is_discount_active' => true,
                'badge' => 'SALE',
                'is_trending' => true,
                'is_featured' => true,
                'is_new' => false,
                'image' => '/storage/products/aeroglide-carbon.png',
                'colors' => [
                    ['name' => 'Forest Green', 'hex' => '#1F5F3A'],
                    ['name' => 'Stone Gray', 'hex' => '#8E908D'],
                    ['name' => 'Midnight Black', 'hex' => '#141B2B'],
                ],
                'collections' => ['cloudstrider-series'],
            ],

            // PRODUCT 2 (Trending #2)
            [
                'name' => 'Stride PaceMaker 3',
                'slug' => 'stride-pacemaker-3',
                'sku' => 'STR-PM3-002',
                'category' => 'running',
                'brand' => 'stride-athletics',
                'subtitle' => 'High-Mileage Daily Trainer',
                'description' => 'The workhorse of your training block. Tested over 800+ kilometers of continuous abuse without foam pack-out. Cushioned, forgiving, yet delightfully energetic on toe-off.',
                'details' => ['Drop' => '10mm', 'Weight' => '245g (US 9)', 'Stack Height' => '34mm / 24mm', 'Outsole' => 'Full-contact blown rubber compound'],
                'base_price' => 145.00,
                'compare_at_price' => null,
                'discount_type' => 'none',
                'discount_value' => 0,
                'is_discount_active' => false,
                'badge' => 'NEW',
                'is_trending' => true,
                'is_featured' => true,
                'is_new' => true,
                'image' => '/storage/products/pacemaker-3.png',
                'colors' => [
                    ['name' => 'Alabaster Sand', 'hex' => '#E5E2DC'],
                    ['name' => 'Clay Beige', 'hex' => '#C2B7A3'],
                ],
                'collections' => ['cloudstrider-series', 'zero-drop-editions'],
            ],

            // PRODUCT 3 (Trending #3)
            [
                'name' => 'TrailVenture Pro GTX',
                'slug' => 'trailventure-pro-gtx',
                'sku' => 'STR-TVP-003',
                'category' => 'trail-hiking',
                'brand' => 'terraform',
                'subtitle' => 'Technical Alpine Trail Runner',
                'description' => 'Conquer wet scrambles, scree, and technical mountain singletracks. Outfitted with Gore-Tex Invisible Fit waterproof membrane and a directional 5mm Vibram Megagrip lug configuration.',
                'details' => ['Drop' => '6mm', 'Weight' => '285g (US 9)', 'Lug Depth' => '5.0mm multidirectional', 'Waterproofing' => 'Gore-Tex Invisible Fit'],
                'base_price' => 189.00,
                'compare_at_price' => null,
                'discount_type' => 'none',
                'discount_value' => 0,
                'is_discount_active' => false,
                'badge' => 'WATERPROOF',
                'is_trending' => true,
                'is_featured' => true,
                'is_new' => false,
                'image' => '/storage/products/trailventure-gtx.png',
                'colors' => [
                    ['name' => 'Deep Olive', 'hex' => '#3F4E38'],
                    ['name' => 'Bark Brown', 'hex' => '#5C4D3C'],
                ],
                'collections' => ['terraform-trail'],
            ],

            // PRODUCT 4 (Trending #4)
            [
                'name' => 'CloudShift Recovery Slip',
                'slug' => 'cloudshift-recovery-slip',
                'sku' => 'STR-CSR-004',
                'category' => 'casual',
                'brand' => 'stride-biolab',
                'subtitle' => 'Post-Workout Active Recovery',
                'description' => 'Slip on blissful relief post-long run. Sculpted ergonomic footbed with anatomical arch support aligns your lower kinetic chain while the rocker sole encourages natural calf recovery.',
                'details' => ['Arch Support' => 'Deep contour bio-foam', 'Upper' => 'Breathable open-cell waffle mesh', 'Care' => 'Machine washable cold cycle'],
                'base_price' => 120.00,
                'compare_at_price' => 120.00,
                'discount_type' => 'fixed',
                'discount_value' => 25.00, // 120 - 25 = 95
                'is_discount_active' => true,
                'badge' => 'SALE',
                'is_trending' => true,
                'is_featured' => false,
                'is_new' => false,
                'image' => '/storage/products/cloudshift-slip.png',
                'colors' => [
                    ['name' => 'Charcoal', 'hex' => '#33383B'],
                    ['name' => 'Oatmeal', 'hex' => '#D6D0C4'],
                ],
                'collections' => ['archive-sale'],
            ],

            // PRODUCT 5 (Trending #5)
            [
                'name' => 'UrbanStance Classic Low',
                'slug' => 'urbanstance-classic-low',
                'sku' => 'STR-USC-005',
                'category' => 'sneakers',
                'brand' => 'monolith',
                'subtitle' => 'Heritage Everyday Sneaker',
                'description' => 'Timeless minimalist court silhouette handcrafted with supple Italian full-grain nappa leather and durable vulcanized natural gum soles. Built for all-day urban comfort.',
                'details' => ['Leather' => 'Full-grain Italian calfskin', 'Lining' => 'Smooth calf leather', 'Sole' => 'Natural vulcanized gum rubber'],
                'base_price' => 135.00,
                'compare_at_price' => null,
                'discount_type' => 'none',
                'discount_value' => 0,
                'is_discount_active' => false,
                'badge' => 'BEST SELLER',
                'is_trending' => true,
                'is_featured' => true,
                'is_new' => false,
                'image' => '/storage/products/urbanstance-low.png',
                'colors' => [
                    ['name' => 'Chalk White', 'hex' => '#EDECE6'],
                    ['name' => 'Sage Leather', 'hex' => '#7D8C7C'],
                    ['name' => 'Cognac', 'hex' => '#8E5B3D'],
                ],
                'collections' => ['monolith-minimalist'],
            ],

            // PRODUCT 6 (In Mini-Cart)
            [
                'name' => 'CloudStrider Low',
                'slug' => 'cloudstrider-low',
                'sku' => 'STR-CSL-006',
                'category' => 'running',
                'brand' => 'stride-athletics',
                'subtitle' => 'Featherweight Tempo Trainer',
                'description' => 'Precision balance of featherweight upper mesh and dynamic propulsion foam. Ideal for 5K workouts, tempo runs, and daily sprints.',
                'details' => ['Drop' => '6mm', 'Weight' => '210g', 'Midsole' => 'BioFoam ActiveLite'],
                'base_price' => 140.00,
                'compare_at_price' => null,
                'discount_type' => 'none',
                'discount_value' => 0,
                'is_discount_active' => false,
                'badge' => null,
                'is_trending' => false,
                'is_featured' => true,
                'is_new' => true,
                'image' => '/storage/categories/running.jpg',
                'colors' => [
                    ['name' => 'Alabaster Sand', 'hex' => '#E5E2DC'],
                    ['name' => 'Midnight', 'hex' => '#141B2B'],
                ],
                'collections' => ['cloudstrider-series'],
            ],

            // PRODUCT 7 (In Mini-Cart)
            [
                'name' => 'Terraform Runner',
                'slug' => 'terraform-runner',
                'sku' => 'STR-TFR-007',
                'category' => 'trail-hiking',
                'brand' => 'terraform',
                'subtitle' => 'All-Terrain Adventure Runner',
                'description' => 'Engineered for smooth transitions between pavement and steep backcountry trails. Rock plate protection and ripstop upper.',
                'details' => ['Drop' => '5mm', 'Weight' => '260g', 'Protection' => 'Ballistic woven rock shield'],
                'base_price' => 185.00,
                'compare_at_price' => null,
                'discount_type' => 'none',
                'discount_value' => 0,
                'is_discount_active' => false,
                'badge' => null,
                'is_trending' => false,
                'is_featured' => true,
                'is_new' => false,
                'image' => '/storage/categories/trail.jpg',
                'colors' => [
                    ['name' => 'Forest Moss', 'hex' => '#1F5F3A'],
                    ['name' => 'Earth Slate', 'hex' => '#707971'],
                ],
                'collections' => ['terraform-trail'],
            ],

            // PRODUCT 8 (In Mini-Cart)
            [
                'name' => 'Merino Wool Sock 3-Pack',
                'slug' => 'merino-wool-sock-3-pack',
                'sku' => 'STR-MWS-008',
                'category' => 'accessories',
                'brand' => 'stride-athletics',
                'subtitle' => 'Anti-Blister Running Socks',
                'description' => 'Seamless toe construction crafted with 200-needle superfine Australian merino wool. Naturally moisture-wicking and odor-resistant.',
                'details' => ['Material' => '65% Merino Wool, 30% Recycled Nylon, 5% Lycra', 'Cushion' => 'Targeted heel and forefoot micro-terry'],
                'base_price' => 38.00,
                'compare_at_price' => null,
                'discount_type' => 'none',
                'discount_value' => 0,
                'is_discount_active' => false,
                'badge' => 'BEST SELLER',
                'is_trending' => false,
                'is_featured' => true,
                'is_new' => false,
                'image' => '/storage/categories/accessories.jpg',
                'colors' => [
                    ['name' => 'Natural White', 'hex' => '#F4F3EF'],
                    ['name' => 'Oatmeal Heather', 'hex' => '#D6D0C4'],
                ],
                'collections' => [],
            ],
        ];

        // Additional catalog products to reach 42+ products distributed across all categories
        $additionalCatalog = [
            ['name' => 'Velocity Glide Elite', 'cat' => 'running', 'price' => 195, 'badge' => 'NEW'],
            ['name' => 'Strata Road Master', 'cat' => 'running', 'price' => 155, 'badge' => null],
            ['name' => 'AeroKnit Sprint 2', 'cat' => 'running', 'price' => 130, 'badge' => 'SALE', 'sale' => 110],
            ['name' => 'HyperBreeze 10K', 'cat' => 'running', 'price' => 160, 'badge' => null],
            ['name' => 'Endurance Marathon Pro', 'cat' => 'running', 'price' => 225, 'badge' => 'BEST SELLER'],
            ['name' => 'Pulse Pace Trainer', 'cat' => 'running', 'price' => 140, 'badge' => null],
            ['name' => 'Apex Carbon Flash', 'cat' => 'running', 'price' => 240, 'badge' => 'NEW'],

            ['name' => 'Monolith Court High', 'cat' => 'sneakers', 'price' => 165, 'badge' => null],
            ['name' => 'Atelier Minimalist Low', 'cat' => 'sneakers', 'price' => 145, 'badge' => 'NEW'],
            ['name' => 'Solace Canvas Deck', 'cat' => 'sneakers', 'price' => 95, 'badge' => null],
            ['name' => 'Metropolis Knit Low', 'cat' => 'sneakers', 'price' => 125, 'badge' => 'SALE', 'sale' => 99],
            ['name' => 'Studio Suede Runner', 'cat' => 'sneakers', 'price' => 150, 'badge' => null],
            ['name' => 'Origin Zero Drop Sneaker', 'cat' => 'sneakers', 'price' => 135, 'badge' => null],

            ['name' => 'Coastline Slip-On Moccasin', 'cat' => 'casual', 'price' => 110, 'badge' => null],
            ['name' => 'Nomad Wool Mule', 'cat' => 'casual', 'price' => 98, 'badge' => 'NEW'],
            ['name' => 'Terrace Walk Oxford', 'cat' => 'casual', 'price' => 130, 'badge' => null],
            ['name' => 'Drift Organic Canvas Slip', 'cat' => 'casual', 'price' => 85, 'badge' => null],

            ['name' => 'Diplomat Cushion Derby', 'cat' => 'formal', 'price' => 195, 'badge' => 'BEST SELLER'],
            ['name' => 'Executive Brogue Comfort', 'cat' => 'formal', 'price' => 210, 'badge' => null],
            ['name' => 'Monk Strap Ergonomic', 'cat' => 'formal', 'price' => 220, 'badge' => null],
            ['name' => 'Milano Chelsea Boot Hybrid', 'cat' => 'formal', 'price' => 245, 'badge' => 'NEW'],

            ['name' => 'RidgePeak Waterproof Boot', 'cat' => 'trail-hiking', 'price' => 215, 'badge' => 'WATERPROOF'],
            ['name' => 'Alpine Boulder Scrambler', 'cat' => 'trail-hiking', 'price' => 175, 'badge' => null],
            ['name' => 'Ascent Vibram Hiker', 'cat' => 'trail-hiking', 'price' => 190, 'badge' => null],
            ['name' => 'Summit Traverse GTX', 'cat' => 'trail-hiking', 'price' => 230, 'badge' => 'NEW'],

            ['name' => 'BioSlide Recovery Sandal', 'cat' => 'sandals', 'price' => 65, 'badge' => 'BEST SELLER'],
            ['name' => 'RiverBed Trekking Sandal', 'cat' => 'sandals', 'price' => 85, 'badge' => null],
            ['name' => 'Coastline Foam Thong', 'cat' => 'sandals', 'price' => 45, 'badge' => null],
            ['name' => 'Alpine Strap Ergonomic Slide', 'cat' => 'sandals', 'price' => 75, 'badge' => 'NEW'],

            ['name' => 'Stride Youth Sprinter', 'cat' => 'kids', 'price' => 75, 'badge' => 'NEW'],
            ['name' => 'Kids TrailScout Magnetic', 'cat' => 'kids', 'price' => 80, 'badge' => null],
            ['name' => 'Junior Court Classic', 'cat' => 'kids', 'price' => 65, 'badge' => null],
            ['name' => 'PlayPace Flexible Runner', 'cat' => 'kids', 'price' => 70, 'badge' => 'BEST SELLER'],

            ['name' => 'Biomechanical Cork Insoles', 'cat' => 'accessories', 'price' => 32, 'badge' => 'BEST SELLER'],
            ['name' => 'Natural Sneaker Care Kit', 'cat' => 'accessories', 'price' => 28, 'badge' => null],
            ['name' => 'Reflective Night Running Laces', 'cat' => 'accessories', 'price' => 14, 'badge' => null],
            ['name' => 'Technical Running Cap', 'cat' => 'accessories', 'price' => 35, 'badge' => 'NEW'],
        ];

        foreach ($additionalCatalog as $idx => $item) {
            $catSlug = $item['cat'];
            $img = $catMap[$catSlug]->image ?? '/storage/categories/running.jpg';
            $sale = $item['sale'] ?? null;
            $productsToSeed[] = [
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'sku' => 'STR-CAT-' . str_pad($idx + 10, 3, '0', STR_PAD_LEFT),
                'category' => $catSlug,
                'brand' => 'stride-athletics',
                'subtitle' => 'Engineered for Performance & Movement',
                'description' => 'Designed with thoughtful biomechanical parameters to support natural articulation and maximum energy return.',
                'details' => ['Construction' => 'Stitchless heat-welded overlay', 'Material' => '100% Vegan & Recycled Components'],
                'base_price' => $item['price'],
                'compare_at_price' => $sale ? $item['price'] : null,
                'discount_type' => $sale ? 'fixed' : 'none',
                'discount_value' => $sale ? ($item['price'] - $sale) : 0,
                'is_discount_active' => (bool) $sale,
                'badge' => $item['badge'],
                'is_trending' => in_array($idx, [0, 1, 7, 8, 14, 21]),
                'is_featured' => ($idx % 3 === 0),
                'is_new' => $item['badge'] === 'NEW',
                'image' => $img,
                'colors' => [
                    ['name' => 'Alabaster', 'hex' => '#EDECE6'],
                    ['name' => 'Forest Green', 'hex' => '#1F5F3A'],
                ],
                'collections' => [$idx % 2 === 0 ? 'cloudstrider-series' : 'terraform-trail'],
            ];
        }

        // US Sizes list
        $sizes = ['6.0', '6.5', '7.0', '7.5', '8.0', '8.5', '9.0', '9.5', '10.0', '10.5', '11.0', '11.5', '12.0', '12.5', '13.0'];

        // Sample human review pool
        $sampleReviews = [
            ['title' => 'Game changer for my marathon block', 'comment' => 'The foam energy return is unbelievable. Ran 32km on Saturday and woke up with zero knee ache. Stride has truly nailed the propulsion geometry.', 'rating' => 5],
            ['title' => 'Best road shoe I have tested this year', 'comment' => 'Light, breathable, and locks down the midfoot securely. Fits true to size with an anatomical toe box.', 'rating' => 5],
            ['title' => 'Exceptional build quality', 'comment' => 'Zero break-in required right out of the box. The craftsmanship and attention to detail justify every single dollar.', 'rating' => 5],
            ['title' => 'Super comfortable daily trainer', 'comment' => 'Logged 250 miles so far and the outsole shows virtually no wear. Very impressed with the bio-foam durability.', 'rating' => 4],
            ['title' => 'Clean aesthetic and top-tier comfort', 'comment' => 'Transitioned from running straight to the cafe. Gorgeous styling and the cushioning is remarkably plush.', 'rating' => 5],
            ['title' => 'Snug heel lockdown', 'comment' => 'No heel slippage whatsoever. Breathability in warm weather is unmatched. Highly recommended.', 'rating' => 5],
            ['title' => 'Solid performance and great aesthetics', 'comment' => 'Slightly snug on the arch initially, but molded to my foot after two 5-mile runs. Fantastic traction in the wet.', 'rating' => 4],
        ];

        foreach ($productsToSeed as $pData) {
            $cat = $catMap[$pData['category']] ?? $catMap['running'];
            $brand = $brandMap[$pData['brand']] ?? $brandMap['stride-athletics'];

            $product = Product::create([
                'category_id' => $cat->id,
                'brand_id' => $brand->id,
                'name' => $pData['name'],
                'slug' => $pData['slug'],
                'sku' => $pData['sku'],
                'subtitle' => $pData['subtitle'],
                'description' => $pData['description'],
                'details' => $pData['details'],
                'base_price' => $pData['base_price'],
                'compare_at_price' => $pData['compare_at_price'],
                'discount_type' => $pData['discount_type'],
                'discount_value' => $pData['discount_value'],
                'is_discount_active' => $pData['is_discount_active'],
                'badge' => $pData['badge'],
                'is_trending' => $pData['is_trending'],
                'is_featured' => $pData['is_featured'],
                'is_new' => $pData['is_new'],
                'status' => 'active',
            ]);

            // Assign collections
            if (!empty($pData['collections'])) {
                foreach ($pData['collections'] as $cSlug) {
                    if (isset($colMap[$cSlug])) {
                        $product->collections()->attach($colMap[$cSlug]->id);
                    }
                }
            }

            // Create primary image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $pData['image'],
                'alt_text' => $product->name,
                'sort_order' => 0,
                'is_primary' => true,
            ]);

            // Create Variants (Colors x Sizes)
            $colors = $pData['colors'];
            foreach ($colors as $cIndex => $color) {
                // If accessories, don't generate 15 shoe sizes, just S, M, L or One Size
                $variantSizes = ($pData['category'] === 'accessories') ? ['One Size', 'S', 'M', 'L'] : $sizes;

                foreach ($variantSizes as $sIndex => $sz) {
                    // Out-of-stock for some edge sizes (e.g. 12.0 or 13.0)
                    $stock = ($sz === '12.0' || $sz === '13.0' || ($sIndex > 10 && $cIndex > 0)) ? 0 : rand(8, 35);

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_name' => $color['name'],
                        'color_hex' => $color['hex'],
                        'size' => $sz,
                        'sku' => $product->sku . '-' . Str::slug($color['name']) . '-' . str_replace('.', '', $sz),
                        'stock' => $stock,
                        'price_override' => null,
                    ]);
                }
            }

            // Create Reviews (15 to 45 reviews for realistic rating 4.8 - 5.0)
            $reviewCount = rand(15, 40);
            for ($r = 0; $r < $reviewCount; $r++) {
                $reviewer = $reviewers[$r % count($reviewers)];
                $sample = $sampleReviews[$r % count($sampleReviews)];
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $reviewer->id,
                    'rating' => $sample['rating'],
                    'title' => $sample['title'],
                    'comment' => $sample['comment'],
                    'is_verified_purchase' => true,
                    'is_approved' => true,
                    'created_at' => now()->subDays(rand(1, 120)),
                ]);
            }
        }

        // 10. Seed a sample completed order for the test customer
        $sampleProduct = Product::where('slug', 'stride-aeroglide-carbon')->first();
        $sampleVariant = $sampleProduct->variants()->first();

        $order = Order::create([
            'order_number' => 'STR-000101',
            'user_id' => $customer->id,
            'guest_name' => $customer->name,
            'guest_email' => $customer->email,
            'shipping_address' => [
                'recipient_name' => 'Alex Runner',
                'phone' => '+1 (555) 839-4412',
                'address_line1' => '742 Evergreen Terrace',
                'address_line2' => 'Apt 4B',
                'city' => 'Portland',
                'state' => 'OR',
                'postal_code' => '97201',
                'country' => 'United States',
            ],
            'shipping_method' => 'standard',
            'shipping_fee' => 0.00,
            'tax_amount' => 0.00,
            'subtotal' => 178.00,
            'discount_amount' => 0.00,
            'total_amount' => 178.00,
            'status' => 'delivered',
            'notes' => 'Please leave package by the front door.',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $sampleProduct->id,
            'product_variant_id' => $sampleVariant->id,
            'product_name' => $sampleProduct->name,
            'variant_details' => ['color' => $sampleVariant->color_name, 'size' => $sampleVariant->size],
            'unit_price' => 178.00,
            'quantity' => 1,
            'subtotal' => 178.00,
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'card',
            'status' => 'paid',
            'amount' => 178.00,
            'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
        ]);
    }
}
