<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index($date = null) {
        if(Gate::denies(['logged-in'])) {
            abort(403);
        }

        $orders = (!empty($date)) 
                ? Dashboard::whereDate('created_at', '=', $date)->latest()->paginate(50) 
                : Dashboard::latest()->paginate(50);
       
        return view('dashboard.index', [
            'orders' => $orders,
            'date'   => $date,
        ]);
    }
}
