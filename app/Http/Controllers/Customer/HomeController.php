<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $popularFoods = Food::where('is_popular', true)
            ->where('is_available', true)
            ->with('category')
            ->take(8)
            ->get();

        $newFoods = Food::where('is_available', true)
            ->with('category')
            ->latest()
            ->take(8)
            ->get();

        return view('customer.home', compact('categories', 'popularFoods', 'newFoods'));
    }

    public function foods(Request $request)
    {
        $query = Food::where('is_available', true)->with('category');

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $foods = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('customer.foods.index', compact('foods', 'categories'));
    }

    public function foodDetail(Food $food)
    {
        $relatedFoods = Food::where('category_id', $food->category_id)
            ->where('id', '!=', $food->id)
            ->where('is_available', true)
            ->take(4)
            ->get();

        return view('customer.foods.detail', compact('food', 'relatedFoods'));
    }
}
