<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HttpHelper;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderSuccess;
use App\Mail\SuccessfulDelivery;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    private $headers = [];

    public function __construct()
    {
        $this->middleware('checkRole');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $type
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type)
    {
        $locations = [];
        $employees = [];
        $data = [];
        $page = $request->get('page', (Paginator::resolveCurrentPage() ?: 1));
        $TotalMoney = 0;

        $searchByOrderInfo = $request->get('searchByOrderInfo', '');
        $searchByCustomerInfo = $request->get('searchByCustomerInfo', '');
        $searchByProductInfo = $request->get('searchByProductInfo', '');
        $filterType = $request->get('filterType', 5);
        $locationId = $request->get('locationId', "");
        $employeeId = $request->get('employeeId', "");
        $orderStatus = $request->get('orderStatus', -1);
        $dates = $request->get('dates');

        $startDate = Carbon::now()->toDateString();
        $endDate = Carbon::now()->toDateString();
        if($dates != null) {
            $dateTmp = explode('-', $dates);
            $startDate = Carbon::parse(str_replace('/', '-', trim($dateTmp[0])))->format('Y-m-d');
            $endDate = Carbon::parse(str_replace('/', '-', trim($dateTmp[1])))->format('Y-m-d');
        }

        $view = 'admin.orders.selOrders.index';
        $urlAffix = 'SelOrder/SelOrderList';
        if($type == 'purchase') {
            $view = 'admin.orders.purchase.index';
            $urlAffix = 'PurchaseOrder/PurchaseOrderList';
        }
        elseif ($type == 'delivery') {
            $view = 'admin.orders.delivery.index';
            $urlAffix = 'PurchaseOrder/PurchaseOrderList';
        }

        try {
            $locations = getLocationList();
            $employees = getEmployeeList();

            $params = [
                "FilterType" => $filterType?:5,
                "FromDate" => $startDate,
                "ToDate" => $endDate,
                "LocationId" => $locationId?:'',
                "OrderStatus" => (int)$orderStatus?:0,
                "SearchByOrderInfo" => $searchByOrderInfo?:'',
                "SearchByCustomerInfo" => $searchByCustomerInfo?:'',
                "SearchByProductInfo" => $searchByProductInfo?:'',
                "PageSize" => env('PER_PAGE', 20),
                "PageIndex" => $page > 0 ? $page - 1 : $page
            ];

            $result = HttpHelper::getInstance()->post($urlAffix, $params);

            // Lấy trạng thái đơn giao hàng
            if ($type == 'delivery') {
                foreach ($result->data as $order_delivery) {
                    $result_api_list_deliver_status = HttpHelper::getInstance()->get2("PurchaseOrder/GetListDeliverStatus?orderId=".$order_delivery->Id);
                    if (count($result_api_list_deliver_status->data) > 0) {
                        $order_delivery->Status = $result_api_list_deliver_status->data[0]->Status;
                    }
                    else {
                        $order_delivery->Status = '';
                    }
                }
            }

            $TotalMoney = $result->Extra->TotalMoney;
            $orders = $result->data;
            foreach ($orders as $k => $od) {
                $order = HttpHelper::getInstance()->get("SelOrder/GetSelOrderDetail/" . $od->Id);
                $orders[$k]->Detail = $order->data;
            }

            $data = new LengthAwarePaginator($orders, $result->paging->TotalCount, $result->paging->PageSize, $page,
                ['path' => Paginator::resolveCurrentPath(), 'lastPage' => $result->paging->TotalPage]
            );
            view()->share('totalMoney', $data->sum('TotalMoney'));
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        return view($view, compact('TotalMoney', 'data',
            'locations', 'employees', 'searchByOrderInfo', 'searchByCustomerInfo', 'searchByProductInfo',
            'orderStatus', 'locationId', 'employeeId', 'dates', 'type'));
    }

    /**
     * Display a new orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function notSent()
    {
        $orders = $this->order->where('order_status', 0)->with(['address', 'giftCard', 'users', 'payment'])->paginate(5);
        return view('admin.orders.index', compact('orders'));
    }

    public function create($type)
    {
        $employees = getEmployeeList();
        $locations = getLocationList();
        $orderConfig = getOrderConfig($type);

        $view = 'admin.orders.selOrders.create';
        if($type == 'purchase') {
            $view = 'admin.orders.purchase.create';
        }
        return view($view, compact( 'employees','locations', 'type', 'orderConfig'));
    }

    public function store(Request $request)
    {
        $type = $request->post('type');
        $locationId = $request->post('locationId');
        $orderCode = $request->post('orderCode');
        $orderId = $request->post('orderId', "");
        $storeId = $request->post('storeId', "");
        $products = $request->post('products');
        $quantities = $request->post('quantities');
        $prices = $request->post('prices');
        $paymentMethod = $request->post('paymentMethod', "");
        $f_discounts = $request->post('f_discount');
        $m_discounts = $request->post('m_discount');
        $descriptions = $request->post('description');
        $fVat = (int)$request->post('vat', 0);
        $fDiscount = (int)$request->post('discount', 0);
        $mDiscount = (int)$request->post('discountAmount', 0);
        $discountType = $request->post('discountType');
        $orderDetails = [];
        $res = ['success' => true, 'message' => '', 'result' => []];

        if(is_null($products)) {
            $res['success'] = false;
            $res['message'] = 'Bạn chưa chọn sản phẩm';

            return response()->json($res);
        }

        try {
            $orderTotal = 0;

            foreach ($products as $k => $id) {
                $result = HttpHelper::getInstance()->get("Category/GetProductInfo/".$id);
                $product = $result->data;

                $fDisc = (float)$f_discounts[$k];
                $mDisc = (int)$m_discounts[$k];
                $qty = (float)$quantities[$k];
                $price = (float)(str_replace(".", "", $prices[$k]));// * 1000;

                $ords = [
                    "f_Vat" => 0,
                    "Id" => $product->Id,
                    "OrderId" => $orderId,
                    "ProductId" => $product->Id,
                    "ProductName" => $product->Name,
                    "ProductCode" => $product->ProductCode,
                    "Unit" => $product->Unit,
                    "Price" => $price,
                    "Qty" => $qty,
                    "f_Discount" => $fDisc, // %chietkhau
                    "m_Discount" => $mDisc, // $ chietkhau
                    "Description" => $descriptions[$k]?:"",
                    "f_Convert" => 0,
                    "StoreId" => $storeId,
                    "LocationId" => $locationId
                ];

                if($fDisc > 0) {
                    $orderTotal += $qty * ($price - $fDisc*$price/100);
                } else if($mDisc > 0) {
                    $orderTotal += $qty * ($price - $mDisc);
                } else {
                    $orderTotal += $qty * $price;
                }

                array_push($orderDetails, $ords);
            }

            if($discountType == 'fDiscount') {
                $mDiscount = 0;
            } else if($discountType == 'mDiscount') {
                $fDiscount = 0;
            } else {
                $mDiscount = 0;
                $fDiscount = 0;
            }

            $orderTotalDiscount = $orderTotal + ($orderTotal*$fVat/100) - ($orderTotal*$fDiscount/100) - $mDiscount;

            $orderDate = $request->post('orderDate', Carbon::now()->toDateString());
            $employee = $request->post('employeeId', "");
            $customerId = $request->post('customerId', "");
            $sNote = $request->post('desc', "");
            $mTotalMoney = (float)$request->post('mTotalMoney', 0);

            if($mTotalMoney > 0) {
                if($mTotalMoney > $orderTotalDiscount) {
                    $mTotalMoney = $orderTotalDiscount;
                } else {
                    $mTotalMoney = floatval($mTotalMoney) - $orderTotalDiscount;
                }
            }

            $params = [
                "OrderCode" => $orderCode,
                "EmployeeId" => $employee,
                "CustomerId" => $customerId,
                "OrderDate" => $orderDate?: Carbon::now()->toDateString(),
                "Status" => (int)$request->post('orderStatus', 0),
                "BillingAddress" => "",
                "ShippingAddress" => "",
                "OrderTotal" => $orderTotal,
                "f_Vat" => $fVat,
                "m_Vat" => 0,
                "OrderTotalDiscount" => $orderTotalDiscount,
                "f_Discount" => $fDiscount,
                "m_Discount" => 0,
                "m_TotalMoney" => $mTotalMoney,
                "OrderDetail" => $orderDetails,
                "Description" => $sNote?:"",
                "CreatedBy" => auth()->user()->username,
                "ModifiedBy" => auth()->user()->username,
                "CreatedDate" => Carbon::now()->toDateString(),
                "ModifiedDate" => Carbon::now()->toDateString(),
                "LocationId" => $locationId,
                "CashMoney" => $paymentMethod == "cash" ? 1 : 0,
                "CardMoney" => $paymentMethod == "card" ? 1 : 0,
                "CardId" => $paymentMethod == "card"? $request->post('cardId', "") : ""
            ];

            $prefixURL = 'SelOrder/AddNewSelOrder';
            if($type == 'purchase') {
                $prefixURL = 'PurchaseOrder/AddNewPurchaseOrder';
                $params['Location_Id'] = $locationId;
            }
// dd($params, $prefixURL);
            $result = HttpHelper::getInstance()->post($prefixURL, $params);
            if($result->meta->status_code != 0) {
                $res['success'] = false;
            }
            $res['message'] = $result->meta->message;
            /*
            if($result->meta->status_code === 0) {
                alert()->success($result->meta->message);
                return redirect()->route('order.index', $type);
            } else {
                alert()->error($result->meta->message);
                return redirect()->back()->with('error', $result->meta->message);
            }*/
        } catch (ClientException $exception) {
            $res['message'] = $exception->getMessage();
            $res['success'] = false;
            logger()->critical($exception->getMessage(), $this->headers);
        }

        // dd($res);
        if ($res['success'] == true && $type == 'purchase') {
            // gửi mail
            $result = HttpHelper::getInstance()->get("Category/GetCustomerInfo/$customerId");
            // $customer = $result->data;
            // convert obj to array
            $customer = @json_decode(json_encode($result->data), true);

            if ($customer['Email'] != "") {
                $params['PaymentMethod'] = $params['CashMoney'] ? 'Tiền mặt' : 'Chuyển khoản';
                Mail::to($customer['Email'])->send(new OrderSuccess($customer, $params));
            }

            if ($customer['Tel'] != "") {
                // gửi sms
                $APIKey = env('API_KEY');
                $SecretKey = env('SECRET_KEY');
                $YourPhone = $customer['Tel'];
                $Content = "Cam on quy khach da su dung dich vu cua chung toi. Chuc quy khach mot ngay tot lanh!";

                $SendContent = urlencode($Content);
                $data = "http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=$YourPhone&ApiKey=$APIKey&SecretKey=$SecretKey&Content=$SendContent&Brandname=Baotrixemay&SmsType=2";
                //De dang ky brandname rieng vui long lien he hotline 0901.888.484 hoac nhan vien kinh Doanh cua ban
                $curl = curl_init($data); 
                curl_setopt($curl, CURLOPT_FAILONERROR, true); 
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
                $result = curl_exec($curl); 

                $obj = json_decode($result,true);
                // dd($obj);
                
                // if($obj['CodeResult']==100)
                // {
                //     print "<br>";
                //     print "CodeResult:".$obj['CodeResult'];
                //     print "<br>";
                //     print "CountRegenerate:".$obj['CountRegenerate'];
                //     print "<br>";     
                //     print "SMSID:".$obj['SMSID'];
                //     print "<br>";
                // }
                // else
                // {
                //     print "ErrorMessage:".$obj['ErrorMessage'];
                // }
            }
        }
        
        return response()->json($res);
    }

    /**
     * Display the specified resource.
     *
     * @param String $id
     * @param $type
     * @return \Illuminate\Http\Response
     */
    public function show(string $id, $type)
    {
        $order = null;
        $urlAff = 'SelOrder/GetSelOrderDetail';
        $view = 'admin.orders.selOrders.show';
        if($type == 'purchase') {
            $urlAff = 'PurchaseOrder/GetPurchaseOrderDetail';
            $view = 'admin.orders.purchase.show';
        }
        try {
            $result = HttpHelper::getInstance()->get("$urlAff/$id");
            $order = $result->data;
            // dd($result);

            if ($type != 'purchase') {
                foreach ($order->OrderDetail as $k => $ord) {
                    $params = [
                        "SearchText" => "",//$ord->StoreId,
                        "LocationId" => $ord->LocationId
                    ];

                    $storeResult = HttpHelper::getInstance()->post("Category/StoreList", $params);
                    foreach ($storeResult->data as $s) {
                        if ($s->Id == $ord->StoreId) {
                            $ord->StoreName = $s->StoreName;
                            break;
                        }
                    }

                    $order->OrderDetail[$k] = $ord;
                }
            }
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }
//dd($order);
        return view($view, compact('order'));
        //return view('admin.orders.show', compact('order'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $type, $id)
    {
        $order = null;
        $urlAff = 'SelOrder/GetSelOrderDetail';
        $editCheckURL = 'SelOrder/CheckEditOrder';
        if($type == 'purchase') {
            $urlAff = 'PurchaseOrder/GetPurchaseOrderDetail';
            $editCheckURL = 'PurchaseOrder/CheckEditOrder';
        }

        $employees = getEmployeeList();
        $locations = getLocationList();
        $orderConfig = getOrderConfig($type);

        $storeId = null;
        try {
            $res = HttpHelper::getInstance()->get("$editCheckURL/$id");
            if($res->meta->status_code === 1) {
                alert()->error($res->meta->message);
                return redirect()->back()->with('error', $res->meta->message);
            }

            $result = HttpHelper::getInstance()->get("$urlAff/$id");
            $order = $result->data;
            $storeId = $order->OrderDetail[0]->StoreId;

            if($type == 'sel') {
                foreach ($order->OrderDetail as $k => $ord) {
                    $params = [
                        "SearchText" => "",//$ord->StoreId,
                        "LocationId" => $ord->LocationId
                    ];

                    $storeResult = HttpHelper::getInstance()->post("Category/StoreList", $params);
                    foreach ($storeResult->data as $s) {
                        if ($s->Id == $ord->StoreId) {
                            $ord->StoreName = $s->StoreName;
                            break;
                        }
                    }

                    $order->OrderDetail[$k] = $ord;
                }
            }
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        $view = 'admin.orders.selOrders.edit';
        if($type == 'purchase') {
            $view = 'admin.orders.purchase.edit';
        }
//dd($order);
        return view($view, compact('order', 'employees', 'locations', 'type', 'storeId', 'orderConfig'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $type
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $type, $id)
    {
        $locationId = $request->post('locationId');
        $orderCode = $request->post('orderCode');
        $orderId = $request->post('orderId', "");
        $storeId = $request->post('storeId', "");
        $products = $request->post('products');
        $quantities = $request->post('quantities');
        $prices = $request->post('prices');
        $paymentMethod = $request->post('paymentMethod', "");
        $f_discounts = $request->post('f_discount');
        $m_discounts = $request->post('m_discount');
        $descriptions = $request->post('description');
        $fVat = (int)$request->post('vat', 0);
        $fDiscount = (int)$request->post('discount', 0);
        $mDiscount = (int)$request->post('discountAmount', 0);
        $discountType = $request->post('discountType');
        $orderDetails = [];
        $res = ['success' => true, 'message' => '', 'result' => []];

        if(is_null($products)) {
            $res['success'] = false;
            $res['message'] = 'Bạn chưa chọn sản phẩm';

            return response()->json($res);
        }

        try {
            $orderTotal = 0;

            foreach ($products as $k => $pid) {
                $result = HttpHelper::getInstance()->get("Category/GetProductInfo/".$pid);
                $product = $result->data;

                $fDisc = (float)$f_discounts[$k];
                $mDisc = (int)$m_discounts[$k];
                $qty = (float)$quantities[$k];
                //$price = (float)$prices[$k] * 1000;
                $price = (float)(str_replace(".", "", $prices[$k]));

                $ords = [
                    "f_Vat" => 0,
                    "Id" => $product->Id,
                    "OrderId" => $orderId,
                    "ProductId" => $product->Id,
                    "ProductName" => $product->Name,
                    "ProductCode" => $product->ProductCode,
                    "Unit" => $product->Unit,
                    "Price" => $price,
                    "Qty" => $qty,
                    "f_Discount" => $fDisc, // %chietkhau
                    "m_Discount" => $mDisc, // $ chietkhau
                    "Description" => $descriptions[$k]?:"",
                    "f_Convert" => 0,
                    "StoreId" => $storeId,
                    "LocationId" => $locationId
                ];

                if($fDisc > 0) {
                    $orderTotal += $qty * ($price - $fDisc*$price/100);
                } else if($mDisc > 0) {
                    $orderTotal += $qty * ($price - $mDisc);
                } else {
                    $orderTotal += $qty * $price;
                }

                array_push($orderDetails, $ords);
            }

            if($discountType == 'fDiscount') {
                $mDiscount = 0;
            } else if($discountType == 'mDiscount') {
                $fDiscount = 0;
            } else {
                $mDiscount = 0;
                $fDiscount = 0;
            }

            $orderTotalDiscount = $orderTotal + ($orderTotal*$fVat/100) - ($orderTotal*$fDiscount/100) - $mDiscount;

            $orderDate = $request->post('orderDate', Carbon::now()->toDateString());
            $employee = $request->post('employeeId', "");
            $customerId = $request->post('customerId', "");
            $sNote = $request->post('desc', "");

            $params = [
                "Id" => $id,
                "OrderCode" => $orderCode,
                "EmployeeId" => $employee,
                "CustomerId" => $customerId,
                "OrderDate" => $orderDate?: Carbon::now()->toDateString(),
                "Status" => (int)$request->post('orderStatus', 0),
                "BillingAddress" => "",
                "ShippingAddress" => "",
                "OrderTotal" => $orderTotal,
                "f_Vat" => $fVat,
                "m_Vat" => 0,
                "OrderTotalDiscount" => $orderTotalDiscount,
                "f_Discount" => $fDiscount,
                "m_Discount" => $mDiscount,
                "m_TotalMoney" => (int)$request->post('mTotalMoney', 0),
                "OrderDetail" => $orderDetails,
                "Description" => $sNote?:"",
                "CreatedBy" => auth()->user()->username,
                "ModifiedBy" => auth()->user()->username,
                "CreatedDate" => Carbon::now()->toDateString(),
                "ModifiedDate" => Carbon::now()->toDateString(),
                "LocationId" => $locationId,
                "CashMoney" => $paymentMethod == "cash" ? 1 : 0,
                "CardMoney" => $paymentMethod == "card" ? 1 : 0,
                "CardId" => $paymentMethod == "card"? $request->post('cardId', "") : ""
            ];

            $prefixURL = 'SelOrder/EditSelOrder';
            if($type == 'purchase') {
                $prefixURL = 'PurchaseOrder/EditPurchaseOrder';
                $params['Location_Id'] = $locationId;
            }
//dd($params, $prefixURL);
            $result = HttpHelper::getInstance()->post($prefixURL, $params);
            if($result->meta->status_code != 0) {
                $res['success'] = false;
            }
            $res['message'] = $result->meta->message;
        } catch (ClientException $exception) {
            $res['message'] = $exception->getMessage();
            $res['success'] = false;
            logger()->critical($exception->getMessage(), $this->headers);
        }

        return response()->json($res);
    }

    public function getOrderCode(Request $request, $type)
    {
        $locationId = $request->get('locationId');
        $newCodeUrl = 'SelOrder';
        if($type == 'purchase') {
            $newCodeUrl = 'PurchaseOrder';
        }

        $params = [
            "SearchText" => "",
            "LocationId" => $locationId?:""
        ];

        $storeResult = HttpHelper::getInstance()->post("Category/StoreList", $params);

        $ret = HttpHelper::getInstance()->get($newCodeUrl.'/GetNewCode/'.$locationId);
        $orderCode = $ret->data->NewCode;

        return response()->json(['success' => true, 'orderCode' => $orderCode, 'storeList' => $storeResult->data]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @throws \Exception
     */
    public function destroy(Request $request, $type, $id)
    {
        $res = ['success' => false, 'message' => 'Đã có lỗi xảy ra !!!', 'result' => []];
        
        if(!isDelete(env('CUSTOMER_ROLE_CODE'))) {
            $res['message'] = 'Bạn không có quyền xóa đơn hàng';
            alert('Lỗi 403', $res['message'], 'error');
            return response()->json($res);
            //return abort('403');
        }
        
        $cmd = "SelOrder/DeleteOrder";
        if($type == "purchase") {
            $cmd = "PurchaseOrder/DeleteOrder";
        }
        
        try {
            $result = HttpHelper::getInstance()->post($cmd, ['ObjectId' => $id]);
            if($result->meta->status_code == 0) {
                $res['success'] = true;
            }
            $res['message'] = $result->meta->message;
        } catch (\Exception $e) {
            
        }
        
        return response()->json($res);
    }
    public function createIncome(Request $request)
    {
        try{
            $res = ['success' => true, 'message' => 'Thành công', 'code' => 0, 'result' => []];
            $param = [
                "OrderId" => $request->get('orderId'),
                "TotalMoney" => $request->get('total_money'),
                "OrderDate" =>$request->orderDate?$request->orderDate:date('y-m-d'),
                "Description" => $request->description ? $request->description : "",
                "PaymentMethod" =>$request->paymentMethod?$request->paymentMethod:"cash",
                "BankId" => $request->cardId ? $request->cardId : "",
            ];

            $result = HttpHelper::getInstance()->post("SelOrder/AddNewIncome", $param);
            if($result->meta->status_code != 0) {
                $res['success'] = false;
            }
            $res['result'] = $result->data;
            $res['message'] = $result->meta->message;
        } catch (ClientException $exception) {
            $res['message'] = $exception->getMessage();
            $res['success'] = false;
        } catch(ServerException $ex) {
            $res['message'] = $ex->getMessage();
            $res['success'] = false;
        }
        return response()->json($res);
    }
    public function exportFile(Request $request, $id, $type)
    {
        view()->share('download', 1);
        $order = null;
        $urlAff = 'SelOrder/GetSelOrderDetail';
        $view = 'admin.orders.selOrders.export';
        if($type == 'purchase') {
            $urlAff = 'PurchaseOrder/GetPurchaseOrderDetail';
            $view = 'admin.orders.purchase.export';
        }
        try {
            $result = HttpHelper::getInstance()->get("$urlAff/$id");
            $order = $result->data;
            
            if ($type != 'purchase') {
                foreach ($order->OrderDetail as $k => $ord) {
                    $params = [
                        "SearchText" => "",//$ord->StoreId,
                        "LocationId" => $ord->LocationId
                    ];
                    
                    $storeResult = HttpHelper::getInstance()->post("Category/StoreList", $params);
                    foreach ($storeResult->data as $s) {
                        if ($s->Id == $ord->StoreId) {
                            $ord->StoreName = $s->StoreName;
                            break;
                        }
                    }
                    
                    $order->OrderDetail[$k] = $ord;
                }
            }
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }
        
        view()->share('order', $order);
        
        $html = view($view, compact('order'))->render();
                
        $snappy = app()->make('snappy.pdf');
        return new \Illuminate\Http\Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="'.$id.'.pdf"'
            )
        );
    }

    public function editDeliveryStatus(Request $request, $id)
    {
        $order = null;
        $type = 'delivery';

        $result = HttpHelper::getInstance()->get2("PurchaseOrder/GetListDeliverStatus?orderId=".$id);
        if (count($result->data) > 0) {
            $status = $result->data[0]->Status;
        }
        else {
            $status = '';
        }

        return view('admin.orders.delivery.edit', compact('order', 'type', 'id', 'status'));
    }

    public function changeStatus(Request $request, $id) {
        try {
            $params = [
                "Id" => "",
                "OrderId" => $id,
                "Status" => $request->status,
                "Note" => "test",
                "Created_By" => auth()->user()->username,
                "Created_At" => date("Y-m-d H:i:s")
            ];

            // API cập nhật trạng thái đơn giao
            $result = HttpHelper::getInstance()->post("PurchaseOrder/UpdateDeliverStatus", $params);

            // gửi mail khi trạng thái đã giao
            if ($result->meta->status_code == 0 && $request->status == 2) {
                // API chi tiết đơn hàng
                $result = HttpHelper::getInstance()->get("PurchaseOrder/GetPurchaseOrderDetail/$id");
                // $order = $result->data;
                $order = @json_decode(json_encode($result->data), true);;
                // dd($order);
                if ($order['CustomerEmail'] != "") {
                    Mail::to($order['CustomerEmail'])->send(new SuccessfulDelivery($order));
                }

                if ($order['CustomerPhone'] != "") {
                    // gửi sms
                    $APIKey = env('API_KEY');
                    $SecretKey = env('SECRET_KEY');
                    $YourPhone = $order['CustomerPhone'];
                    $Content = "Cam on quy khach da su dung dich vu cua chung toi. Chuc quy khach mot ngay tot lanh!";

                    $SendContent = urlencode($Content);
                    $data = "http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=$YourPhone&ApiKey=$APIKey&SecretKey=$SecretKey&Content=$SendContent&Brandname=Baotrixemay&SmsType=2";

                    $curl = curl_init($data); 
                    curl_setopt($curl, CURLOPT_FAILONERROR, true); 
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
                    $result = curl_exec($curl); 

                    $obj = json_decode($result,true);
                }
            }

            return redirect()->route('order.index', 'delivery');
        } catch (ClientException $exception) {
            dd($exception->getMessage());
            logger()->critical($exception->getMessage(), $this->headers);
        }
    }
}
