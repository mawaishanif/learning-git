<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;


class CustomBillable
{
 
     public function handle(Request $request, Closure $next)
    {
          if (Config::get('shopify-app.billing_enabled') === true) {
            $shop = auth()->user();
            if (!$shop->isFreemium() && !$shop->isGrandfathered() && $shop->plan == null) {
                return redirect()->route('plans');
            }
        }
        return $next($request);
    }
}
