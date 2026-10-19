<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Fine;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $u = $request->user();

        $stats = [
            'restaurants'  => Restaurant::visibleTo($u)->count(),
            'employers'    => Employer::visibleTo($u)->where('is_active', true)->count(),
            'active_fines' => Fine::visibleTo($u)->where('status', Fine::ACTIVE)->sum('amount'),
            'month_fines'  => Fine::visibleTo($u)->where('status', '!=', Fine::CANCELLED)
                ->whereBetween('fined_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount'),
        ];

        $recent = Fine::visibleTo($u)->with(['employer.restaurant', 'reason'])->latest()->limit(6)->get();

        $restaurants = Restaurant::visibleTo($u)
            ->withCount(['employers as active_employers_count' => fn ($q) => $q->where('is_active', true)])
            ->orderByDesc('active_employers_count')->limit(5)->get();

        return view('dashboard', compact('stats', 'recent', 'restaurants'));
    }
}
