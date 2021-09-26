<?php

namespace App\Http\Controllers\Admin;

use App\Modelss\DetailsOrder;
use App\Modelss\Order;
use App\Modelss\Payment;
use App\Modelss\Product;
use App\User;
use App\Helpers\HttpHelper;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private $order;
    private $product;
    private $payment;
    private $httpHelper;

    public function __construct()
    {
        $this->middleware('checkRole');
    }

    /**
     * admin dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $num = 10;
        $order_sent = 0;
        $order_delivered = 0;
        $order_news = 0;
        $order_not_complete = 0;
        $payment_week = 0;
        $payment_success = 0;
        $payment_failed = 0;
        $popular_products= [];
        $top_order_products=[];
        $date = Carbon::today()->subDays(7);
	/*
        try {
            $result = HttpHelper::getInstance()->post("Dashboard/IncomeOutcomeOverView", [
                'Type' => 0,
                'FromDate' => $date,
                'ToDate' => $date,
                'LocationIds' => ''
            ]);

            $result2 = HttpHelper::getInstance()->post("Dashboard/IncomeOverView", [
                'Type' => 0,
                'FromDate' => $date,
                'ToDate' => $date,
                'LocationIds' => ''
            ]);

            $ret = HttpHelper::getInstance()->get('Dashboard/TopProductByQty/'.$num);
            $popular_products = $ret->data;
            $res = HttpHelper::getInstance()->get('Dashboard/TopProductByOrder/'.$num);
            $top_order_products = $res->data;
//dd($result, $result2, $top_order_products, $popular_products);
            if($result->meta->status_code === 0 ) {
                $data = $result->data;
                $order_sent = $data->TotalMoney;
            }
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }*/

        /*---------------users------------------*/
        $employees = 0; //DB::table('user_has_roles')->count();
        $new_users = 0; //User::where('created_at', '>=', $date)->count();
        /*---------------Products------------------*/

        $discounted_products = 0;// $this->product->where('is_off', 1)->count();
        $available_products = 0;//$this->product->where('status', 1)->count();
        $product_news = 0;//$this->product->where('created_at', '>=', $date)->count();
        //calculate Product availability percentage
//        $all_pr = \cache('menu_count')['products'];
//        $percantage = ((($all_pr - $available_products) * 100 ) / $all_pr);
//        $Product_availability = number_format((float)$percantage , 2 , '.' ,'');

        /*--------------- Popular Products:------------------*/
        $popular_product = []; //DetailsOrder::select('product_id')->orderBy('product_id', 'desc')->distinct()->pluck('product_id')->take(5);
        //$popular_products = [];//$this->product->findOrFail($popular_product, ['status', 'product_id', 'product_name', 'sale_price', 'off_price', 'is_off']);

        return view('admin.dashboard.dashboard', compact(
            'discounted_products', 'available_products', 'product_news', 'new_users', 'employees',
            'order_news', 'order_sent', 'order_not_complete', 'popular_products','payment_failed','payment_success','payment_week', 'top_order_products'));
    }

    /**
     * search in admin
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'search_kind' => 'required',
            'search' => 'string|nullable'
        ]);
        if ($request->search_kind == 'orders') {
            $orders = $this->order
                ->Where('track_code', 'like', '%' . $request->search)->paginate(10);
            $view = view('admin.orders._data', compact('orders'))->render();

        } else {
            $products = $this->product
                ->Where('product_name', 'like', '%' . $request->search . '%')
                ->orWhere('sku', $request->search)
                ->paginate(10);
            $index_categories = true;
            $view = view('admin.products._data', compact('products', 'index_categories'))->render();
        }

        if ($request->ajax()) {
            return response()->json(['html' => $view]);
        }

    }
}
