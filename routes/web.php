<?php

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

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'web'], function () {

    Route::get('/', function() {
        return redirect('/admin/dashboard');
    })->name('home');

    /*---------------AUTH------------------*/
    Auth::routes();
});

/*---------------***************ADMIN ROUTES******************------------------*/
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'checkRole']], function () {

    Route::get('/', 'Admin\DashboardController@index');
    /*---------------USERS------------------*/
    Route::resource('/user', 'Admin\UserController');

    Route::get('/user/change-pass/{id}', 'Admin\UserController@changePassword')->name('user.changepass');

    /*---------------DASHBOARD------------------*/
    Route::get('dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');


    /*---------------Products Routes------------------*/
    Route::resource('product', 'Admin\ProductController');

    Route::post('product/sort', 'Admin\ProductController@sort')->name('product.index.sort');
    Route::get('product/stock/{id}', 'Admin\ProductController@productStocks')->name('products.stocks');
    Route::get('product/history/{id}/{type}', 'Admin\ProductController@history')->name('products.history');
    Route::post('product/price', 'Admin\ProductController@price')->name('products.price');
    Route::delete('product/{id}', 'Admin\ProductController@destroy')->name('product.destroy');


    /*---------------CATEGORIES ROUTE------------------*/
    Route::resource('category', 'Admin\CategoryController')->except(['show', 'edit', 'update']);

    /*---------------****ORDERS****------------------*/
    Route::get('orders/{type}', 'Admin\OrderController@index')->name('order.index');
    // Route::get('orders/delivery', 'Admin\OrderController@list_delivery_order')->name('order.delivery');
    Route::get('orders/create/{type}', 'Admin\OrderController@create')->name('order.create');
    Route::post('orders/store', 'Admin\OrderController@store')->name('admin.orders.store');
    Route::get('not-sent-orders', 'Admin\orderController@notSent')->name('order.not_sent');
    Route::get('orders/{id}/{type}', 'Admin\OrderController@show')->name('order.show');
    Route::delete('orders/{type}/{id}', 'Admin\OrderController@destroy')->name('order.destroy');
    Route::delete('orders/orders-status/{id}', 'Admin\OrderController@detailDestroy')->name('order.detail.destroy');
    Route::get('orders/status/{id}/{status}', 'Admin\OrderController@status')->name('order.status');

    Route::get('orders/{type}/{id}/edit','Admin\OrderController@edit')->name('selOrder.edit');
    Route::post('orders/{type}/{id}/update','Admin\OrderController@update')->name('admin.orders.update');

    Route::get('order/sel-order/{id}', 'Admin\OrderController@showSelOrder')->name('order.selOrder.show');

    Route::post('order/get-code/{type}', 'Admin\OrderController@getOrderCode')->name('order.code');
    
    Route::get('order/export-file/{id}/{type}', 'Admin\OrderController@exportFile')->name('order.export');

    Route::post('income/create','Admin\OrderController@createIncome')->name('order.selOrder.create_income');

    Route::get('orders/delivery/update-status/{id}', 'Admin\OrderController@editDeliveryStatus')->name('order.edit-delivery-status');
    Route::post('orders/delivery/change-status/{id}','Admin\OrderController@changeStatus')->name('order.delivery.change_status');

    /*---------------Customer Routes------------------*/
    Route::resource('customer', 'Admin\CustomerController');
    Route::get('customer/{id}/{type}', 'Admin\CustomerController@history')->name('customer.history');
    Route::post('customer/{id}', 'Admin\CustomerController@update')->name('customer.updateCus');
    Route::get('customers/bank-list', 'Admin\CustomerController@getBankList')->name('customer.bank-list');
    Route::delete('customer/{id}', 'Admin\CustomerController@destroy')->name('customer.destroy');

    /*---------------Report Routes------------------*/
    //Route::resource('report', 'Admin\ReportController');
    Route::get('reports/{type}', 'Admin\ReportController@index')->name('report.index');
    /*---------------News Routes------------------*/

    Route::resource('news', 'Admin\NewsController');
    Route::post('news/insert', 'Admin\NewsController@store')->name('news.store');
    Route::post('news/update', 'Admin\NewsController@update')->name('news.update');
    Route::post('news/delete/{id}', 'Admin\NewsController@destroy')->name('news.destroy');
    Route::post('news/push', 'Admin\NewsController@sendNewsNotification')->name('news.push');
    Route::post('news/uploadImage', 'Admin\NewsController@uploadImage')->name('news.uploadImage');
    /*---------------notification Routes------------------*/

    Route::resource('notification', 'Admin\NotificationController');
    Route::post('notification/push', 'Admin\NotificationController@store')->name('notification.store');
    Route::get('notification/show', 'Admin\NotificationController@show')->name('notification.show');

    /*---------------CategoryNew Routes------------------*/
    Route::resource('CategoryNew', 'Admin\CategoryNewsController');
    Route::post('CategoryNew/insert', 'Admin\CategoryNewsController@store')->name('CategoryNew.store');
    Route::post('CategoryNew/update', 'Admin\CategoryNewsController@update')->name('CategoryNew.update');
    Route::get('CategoryNew/show', 'Admin\CategoryNewsController@show')->name('CategoryNew.show');

    Route::post('CategoryNew/delete/{id}', 'Admin\CategoryNewsController@destroy')->name('CategoryNew.destroy');
});

