<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PixelController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\AppProxyController;
use App\Http\Controllers\CheckEmbbedController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\TestController;
use Osiset\ShopifyApp\Storage\Models\Plan;
use App\Models\User;
use App\Models\Pixel;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
 Route::get('/login',function(){
    return view('login');
});
//  Route::get('/', function () {
//      $pixels = Pixel::all();
//       return view('welcome');
    

//     })->middleware('auth.shopify');

// Route::get('savepixel',function(){
    
// });
Route::get('/proxy', [AppProxyController::class , 'index'])->middleware('auth.proxy');
Route::middleware(['auth.shopify'])->group(function(){
    Route::get('/', [PixelController::class, 'index'], function () {
        return view('welcome');
    })->name('home');
    
    Route::get('pixel', [PixelController::class, 'index']);
    Route::post('savepixel', [PixelController::class, 'store'])->name('savepixel');
    Route::get('delpixel', [PixelController::class, 'destroy'])->name('delpixel');
    Route::get('editPixel', [PixelController::class, 'edit']);
    

});
Route::get('/proxy/checkembbed', [CheckEmbbedController::class , 'index'])->middleware('auth.proxy');
Route::get('/proxy/test', [TestController::class , 'index'])->middleware('auth.proxy');
Route::get('pixels', [PixelController::class,'index'])->middleware(['auth.shopify'])->name('pixels');
Route::get('catalog', [CatalogController::class,'index'])->middleware(['auth.shopify'])->name('catalog');
Route::post('/savecatalog', [CatalogController::class,'store'])->middleware(['auth.shopify'])->name('savecatalog');
Route::get('/delcatalog', [CatalogController::class,'destroy'])->middleware(['auth.shopify'])->name('delcatalog');
Route::get('generate/collection/{id}', [CatalogController::class,'generate'])->middleware(['auth.shopify'])->name('generate');
Route::get("{shop}/pages/pixels-fox-feed" , [FeedController::class,'index']);
Route::get("status" , [PixelController::class,'status']);
Route::get("contact" , [PixelController::class,'contact']);
Route::get('/purchase/{name}', [PixelController::class, 'purchase'])->name('purchase');
Route::get('getPixel', [PixelController::class, 'getPixel'])->middleware(['auth.shopify'])->name('getPixel');
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
Route::get('facebook/interest', [FacebookController::class,'interest'])->name('facebook.interest');

Route::get('pixeltrialcheck7days',[PixelController::class, 'pixeltrialcheck7days']);

