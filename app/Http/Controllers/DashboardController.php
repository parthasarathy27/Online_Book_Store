<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class DashboardController extends Controller
{
    /**
     * Display the standard user purchases.
     */
    public function index()
    {
        $orders = Order::with('book')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('dashboard.index', compact('orders'));
    }
}
