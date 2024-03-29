<?php

namespace App\Http\Controllers;

use App\Models\Ramen;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth()->user();

        $ramens = Ramen::with('shop.prefecture')
            ->where('user_id', $user->id)
            ->get();

        $totalRamenCount = $ramens->count();

        $uniqueShopCount = $ramens->pluck('shop_id')
            ->unique()
            ->count();

        $uniquePrefectureCount = $ramens->pluck('shop.prefecture.id')
            ->unique()
            ->count();

        $topShopVisits = $ramens->groupBy('shop_id')
            ->mapWithKeys(function ($group, $shopId) {
                $shopName = $group->first()->shop->name;
                return [$shopName => count($group)];
            })
            ->sortDesc()
            ->take(5);

        $prefectureShopCounts = $ramens->groupBy('shop.prefecture_id')
            ->mapWithKeys(function ($group, $shopID) {
                $prefectureName = $group->first()->shop->prefecture->prefecture_name;
                return [ $prefectureName => count($group)];
            })
            ->sortDesc()
            ->take(5);

        return view('home', compact('user', 'ramens', 'totalRamenCount', 'uniqueShopCount', 'uniquePrefectureCount', 'topShopVisits', 'prefectureShopCounts'));
    }
}
