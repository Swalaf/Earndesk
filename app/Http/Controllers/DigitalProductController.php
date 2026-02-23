<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use App\Models\DigitalProductOrder;
use App\Models\DigitalProductReview;
use App\Models\MarketplaceCategory;
use App\Services\DigitalProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DigitalProductController extends Controller
{
    protected $service;

    public function __construct(DigitalProductService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = DigitalProduct::active()->with(['user', 'category']);

        if ($request->category) {
            $query->byCategory($request->category);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->sort == 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_high') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort == 'popular') {
            $query->orderBy('total_sales', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);
        $categories = MarketplaceCategory::where('type', 'digital_product')->get();

        return view('digital-products.index', compact('products', 'categories'));
    }

    public function featured()
    {
        $products = DigitalProduct::active()
            ->featured()
            ->with(['user', 'category'])
            ->latest()
            ->take(8)
            ->get();

        return view('digital-products.featured', compact('products'));
    }

    public function show(DigitalProduct $product)
    {
        $product->load(['user', 'category', 'reviews.user']);
        
        $relatedProducts = DigitalProduct::active()
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(4)
            ->get();

        return view('digital-products.show', compact('product', 'relatedProducts'));
    }

    public function myProducts()
    {
        $products = DigitalProduct::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('digital-products.my-products', compact('products'));
    }

    public function myPurchases()
    {
        $orders = DigitalProductOrder::where('buyer_id', Auth::id())
            ->with('product')
            ->latest()
            ->paginate(10);

        return view('digital-products.my-purchases', compact('orders'));
    }

    public function create()
    {
        $digitalCategories = MarketplaceCategory::where('type', 'digital_product')->get();
        $physicalCategories = MarketplaceCategory::where('type', 'physical_product')->get();
        return view('digital-products.create', compact('digitalCategories', 'physicalCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:marketplace_categories,id',
            'thumbnail' => 'nullable|image|max:2048',
            'file' => 'required|file|max:51200', // 50MB max
            'tags' => 'nullable|string',
            'license_type' => 'required|in:1,2,3',
            'version' => 'nullable|string',
            'changelog' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_free' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_free'] = $request->boolean('is_free');
        
        if ($data['is_free']) {
            $data['price'] = 0;
        }

        $product = $this->service->createProduct($data, Auth::id());

        return redirect()->route('digital-products.show', $product)
            ->with('success', 'Product created successfully!');
    }

    public function edit(DigitalProduct $product)
    {
        $this->authorize('update', $product);
        
        $categories = MarketplaceCategory::where('type', 'digital_product')->get();
        return view('digital-products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, DigitalProduct $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:marketplace_categories,id',
            'thumbnail' => 'nullable|image|max:2048',
            'file' => 'nullable|file|max:51200',
            'tags' => 'nullable|string',
            'license_type' => 'required|in:1,2,3',
            'version' => 'nullable|string',
            'changelog' => 'nullable|string',
            'requirements' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active', true);

        $product = $this->service->updateProduct($product, $data);

        return redirect()->route('digital-products.show', $product)
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(DigitalProduct $product)
    {
        $this->authorize('delete', $product);

        $this->service->deleteProduct($product);

        return redirect()->route('digital-products.my-products')
            ->with('success', 'Product deleted successfully!');
    }

    public function purchase(DigitalProduct $product)
    {
        if ($product->user_id === Auth::id()) {
            return back()->with('error', 'You cannot purchase your own product.');
        }

        if (!$product->is_active) {
            return back()->with('error', 'This product is not available.');
        }

        try {
            $order = $this->service->purchaseProduct($product, Auth::id());

            if ($product->is_free) {
                return redirect()->route('digital-products.download', $order)
                    ->with('success', 'Download started!');
            }

            return redirect()->route('digital-products.my-purchases')
                ->with('success', 'Purchase completed! You can now download your product.');
        } catch (\Exception $e) {
            $message = $e->getMessage();
            
            // Provide specific error messages
            if (strpos($message, 'Insufficient balance') !== false) {
                return back()->with('error', 'Insufficient wallet balance. Please fund your wallet to purchase this product.');
            }
            
            if (strpos($message, 'activate your wallet') !== false) {
                return redirect()->route('wallet.activate')
                    ->with('error', 'Please activate your wallet first to make purchases.');
            }
            
            // Log the actual error for debugging
            \Log::error('Digital product purchase error: ' . $message, [
                'product_id' => $product->id,
                'user_id' => Auth::id(),
            ]);
            
            return back()->with('error', $message ?: 'An error occurred while processing your purchase. Please try again.');
        }
    }

    public function download(DigitalProductOrder $order)
    {
        if ($order->buyer_id !== Auth::id()) {
            abort(403);
        }

        $downloadUrl = $this->service->processDownload($order);

        if (!$downloadUrl) {
            return back()->with('error', 'Download limit reached or expired.');
        }

        return redirect($downloadUrl);
    }

    public function review(Request $request, DigitalProduct $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $this->service->addReview($product, Auth::id(), $request->all());

        return back()->with('success', 'Review submitted successfully!');
    }

    public function feature(DigitalProduct $product)
    {
        $this->authorize('feature', $product);
        
        $product = $this->service->featureProduct($product);
        
        return back()->with('success', 'Product is now featured!');
    }

    public function unfeature(DigitalProduct $product)
    {
        $this->authorize('feature', $product);
        
        $product = $this->service->unfeatureProduct($product);
        
        return back()->with('success', 'Product is no longer featured.');
    }
}
