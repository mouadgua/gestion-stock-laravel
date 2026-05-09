<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        $recentProducts = Product::where('est_actif', true)
            ->with('categorie')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $categories = Category::withCount('products')->get();

        return view('home', compact('recentProducts', 'categories'));
    }
}