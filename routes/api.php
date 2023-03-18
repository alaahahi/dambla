<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Tags\TagsController;
use App\Http\Controllers\Brands\BrandsController;
use App\Http\Controllers\Events\EventsController;
use App\Http\Controllers\City\CityController;
use App\Http\Controllers\Slides\SlidesController;
use App\Http\Controllers\Wallet\WalletController;
use App\Http\Controllers\Card\CardController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\MainController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => 'api'
], function ($router) {

    Route::group(['prefix' => 'v1'], function() {
        Route::post('register', [AuthController::class, 'registerMobile']);
        Route::post('login', [AuthController::class, 'loginMobile']);
        Route::post('login_check', [AuthController::class, 'loginCheck']);
        Route::post('login_update_location', [AuthController::class, 'loginUpdateLocation']);
        Route::get('topHunters', [AuthController::class, 'topHunters']);
        Route::get('popularHunters', [AuthController::class, 'popularHunters']);
        Route::post('updateUserInfo', [AuthController::class, 'updateUserInfo']);
        Route::get('infoHunters', [AuthController::class, 'me']);
        Route::get('infoHunters/{id}', [AuthController::class, 'infoHunters']);
        Route::post('editHunters', [AuthController::class, 'update']);

        Route::get('city/view/all', [CityController::class, 'indexAll']);
     
        Route::get('tag/view/all', [TagsController::class, 'indexAll']);
        Route::get('category/view/all', [CategoryController::class, 'indexAll']);
        Route::get('slide/view/all', [SlidesController::class, 'indexAll']);


        Route::resource('brand', BrandsController::class);
        Route::get('topBrand', [BrandsController::class, 'topBrand']);
        Route::get('brandSponsors',     [BrandsController::class, 'brandSponsors']);
        Route::post('addBrand', [BrandsController::class, 'addBrand']);
        Route::get('myBrand', [BrandsController::class, 'myBrand']);
        Route::get('brand/view/search', [BrandsController::class, 'search']);
        Route::post('editBrand/{id}',  [BrandsController::class, 'editBrand']);


        Route::resource('event', EventsController::class);
        Route::get('event/view/all', [EventsController::class, 'indexAll']);
        Route::get('eventAllOneRequest', [EventsController::class, 'EventAllOneRequest']);
        Route::post('addEvent', [EventsController::class, 'addEvent']);
        Route::get('myEvent', [EventsController::class, 'myEvent']);
        Route::get('myTicket', [EventsController::class, 'myTicket']);
        Route::get('eventTicket/{id}', [EventsController::class, 'eventTicket']);
        Route::get('event/view/search', [EventsController::class, 'search']);
        Route::post('editEvent/{id}',[ EventsController::class,'editEvent']);


         Route::get('follow', [AuthController::class, 'follow']);
         Route::get('unfollow', [AuthController::class, 'unfollow']);

         Route::get('addWishlistsEvent',     [AuthController::class, 'addWishlistsEvent']);
         Route::get('removeWishlistsEvent',  [AuthController::class, 'removeWishlistsEvent']);

         Route::get('addWishlistsBrand',     [AuthController::class, 'addWishlistsBrand']);
         Route::get('removeWishlistBrand',  [AuthController::class, 'removeWishlistBrand']);


         Route::get('infoWallet',  [WalletController::class, 'infoWallet']);
         

         Route::post('chargeCard',  [CardController::class, 'chargeCard']);

         Route::post('bookingEvent',  [EventsController::class, 'bookingEvent']);

         
         
    });
    
    /**
     * Authentication Module
     */
    Route::group(['prefix' => 'auth'], function() {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });


    /**
     * Products Module
     */


    Route::resource('products', ProductsController::class);
    Route::get('products/view/all', [ProductsController::class, 'indexAll']);
    Route::get('products/view/search', [ProductsController::class, 'search']);

    Route::resource('category', CategoryController::class);
    Route::get('category/view/all', [CategoryController::class, 'indexAll']);
    Route::get('category/view/search', [CategoryController::class, 'search']);

    Route::resource('tag', TagsController::class);
    Route::get('tag/view/all', [TagsController::class, 'indexAll']);
    Route::get('tag/view/search', [TagsController::class, 'search']);

    Route::post('brand/{id}',  [BrandsController::class, 'editBrand']);

    Route::resource('brand', BrandsController::class);

    Route::post('editBrand/{id}',  [BrandsController::class, 'editBrand']);
    Route::get('brand/view/all', [BrandsController::class, 'indexAll']);
    Route::get('brand/view/search', [BrandsController::class, 'search']);


    Route::post('event/{id}',[ EventsController::class,'editEvent']);
    Route::resource('event', EventsController::class);
    Route::post('editEvent/{id}',[ EventsController::class,'editEvent']);
    Route::get('event/view/all', [EventsController::class, 'indexAll']);
    Route::get('event/view/search', [EventsController::class, 'search']);

    Route::resource('city', CityController::class);
    Route::get('city/view/all', [CityController::class, 'indexAll']);
    Route::get('city/view/search', [CityController::class, 'search']);

    Route::resource('slide', SlidesController::class);
    Route::get('slide/view/all', [SlidesController::class, 'indexAll']);
    Route::get('slide/view/search', [SlidesController::class, 'search']);

    Route::resource('card', CardController::class);
    Route::get('card/view/all', [CardController::class, 'indexAll']);
    Route::get('card/view/search', [CardController::class, 'search']);


    Route::resource('wallet', WalletController::class);
    Route::get('wallet/view/all', [WalletController::class, 'indexAll']);
    Route::get('wallet/view/search', [WalletController::class, 'search']);



    Route::get('hunters', [AuthController::class, 'hunters']);

    Route::get('dashboardAllInOneRequest', [DashboardController::class, 'dashboardAllInOneRequest']);

    Route::post('createBoard',  [MainController::class, 'createBoard']);

    
    

});

