<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'brand', 'variants', 'images']);

        if ($request->filled('search')) {
            $s = $request->query('search');
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::all();
        $brands = Brand::all();
        $collections = Collection::all();

        return view('admin.products.create', compact('categories', 'brands', 'collections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'required|string|max:100|unique:products,sku',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:none,percent,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start_at' => 'nullable|date',
            'discount_end_at' => 'nullable|date',
            'is_discount_active' => 'nullable|boolean',
            'badge' => 'nullable|string|max:50',
            'is_trending' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'status' => 'required|in:active,draft',
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(5);
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => $validated['sku'],
            'subtitle' => $validated['subtitle'] ?? null,
            'description' => $validated['description'] ?? null,
            'base_price' => $validated['base_price'],
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'] ?? 0,
            'discount_start_at' => $validated['discount_start_at'] ?? null,
            'discount_end_at' => $validated['discount_end_at'] ?? null,
            'is_discount_active' => $request->boolean('is_discount_active'),
            'badge' => $validated['badge'] ?? null,
            'is_trending' => $request->boolean('is_trending'),
            'is_featured' => $request->boolean('is_featured'),
            'is_new' => $request->boolean('is_new'),
            'status' => $validated['status'],
        ]);

        if (!empty($validated['collections'])) {
            $product->collections()->sync($validated['collections']);
        }

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $img) {
                $path = $img->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => '/storage/' . $path,
                    'is_primary' => $idx === 0,
                    'sort_order' => $idx,
                ]);
            }
        }

        // Variants creation
        if ($request->has('variants') && is_array($request->input('variants'))) {
            foreach ($request->input('variants') as $v) {
                if (!empty($v['size'])) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_name' => $v['color_name'] ?? null,
                        'color_hex' => $v['color_hex'] ?? '#004725',
                        'size' => $v['size'],
                        'sku' => $product->sku . '-' . Str::slug($v['color_name'] ?? 'std') . '-' . str_replace('.', '', $v['size']),
                        'stock' => (int) ($v['stock'] ?? 0),
                        'price_override' => !empty($v['price_override']) ? $v['price_override'] : null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(int $id): View
    {
        $product = Product::with(['category', 'brand', 'collections', 'images', 'variants'])->findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();
        $collections = Collection::all();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'collections'));
    }

    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'discount_type' => 'required|in:none,percent,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start_at' => 'nullable|date',
            'discount_end_at' => 'nullable|date',
            'is_discount_active' => 'nullable|boolean',
            'badge' => 'nullable|string|max:50',
            'is_trending' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'status' => 'required|in:active,draft',
            'collections' => 'nullable|array',
            'collections.*' => 'exists:collections,id',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'sku' => $validated['sku'],
            'subtitle' => $validated['subtitle'] ?? null,
            'description' => $validated['description'] ?? null,
            'base_price' => $validated['base_price'],
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'] ?? 0,
            'discount_start_at' => $validated['discount_start_at'] ?? null,
            'discount_end_at' => $validated['discount_end_at'] ?? null,
            'is_discount_active' => $request->boolean('is_discount_active'),
            'badge' => $validated['badge'] ?? null,
            'is_trending' => $request->boolean('is_trending'),
            'is_featured' => $request->boolean('is_featured'),
            'is_new' => $request->boolean('is_new'),
            'status' => $validated['status'],
        ]);

        $product->collections()->sync($validated['collections'] ?? []);

        // Upload new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => '/storage/' . $path,
                    'is_primary' => false,
                    'sort_order' => $product->images()->count(),
                ]);
            }
        }

        // Update/create variants
        if ($request->has('variants') && is_array($request->input('variants'))) {
            foreach ($request->input('variants') as $v) {
                if (!empty($v['id'])) {
                    $variant = ProductVariant::where('product_id', $product->id)->find($v['id']);
                    if ($variant) {
                        $variant->update([
                            'color_name' => $v['color_name'] ?? null,
                            'color_hex' => $v['color_hex'] ?? '#004725',
                            'size' => $v['size'],
                            'stock' => (int) $v['stock'],
                            'price_override' => !empty($v['price_override']) ? $v['price_override'] : null,
                        ]);
                    }
                } elseif (!empty($v['size'])) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_name' => $v['color_name'] ?? null,
                        'color_hex' => $v['color_hex'] ?? '#004725',
                        'size' => $v['size'],
                        'sku' => $product->sku . '-' . Str::slug($v['color_name'] ?? 'std') . '-' . str_replace('.', '', $v['size']) . '-' . rand(10, 99),
                        'stock' => (int) ($v['stock'] ?? 0),
                        'price_override' => !empty($v['price_override']) ? $v['price_override'] : null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function toggleStatus(int $id)
    {
        $product = Product::findOrFail($id);
        $product->status = ($product->status === 'active') ? 'draft' : 'active';
        $product->save();

        return back()->with('success', 'Product status toggled to ' . $product->status . '.');
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product archived.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Product::whereIn('id', $ids)->delete();
        }

        return back()->with('success', count($ids) . ' products deleted.');
    }
}
