<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HttpHelper;
use App\Http\Requests\Customers\UpdateCustomerRequest;
use App\Http\Requests\Customers\CustomerRequest;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerController extends Controller
{
    private $httpHelper;
    private $headers = [];

    public function __construct()
    {
        $this->middleware('checkRole');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if(!isRead(env('CUSTOMER_ROLE_CODE'))) {
            alert('Lỗi 403', 'Ban khong co quyen xem danh sach khach hang', 'error');
            return abort('403');
        }

        $data = [];
        $page = $request->get('page', (Paginator::resolveCurrentPage() ?: 1));

        $search = $request->get('search', '');
        $email = $request->get('email', '');
        $groupId = $request->get('groupId', "");
        $objectType = $request->get('objectType');
//        $employeeId = $request->get('employeeId', "");
        $status = $request->get('status', "");

        $customerGroups = getCustomerGroupList();
        //$employees = getEmployeeList();

        try {
            $params = [
                "SearchText" => $search?:"",
                "Email" => $email?:"",
                "ObjectGroupId" => $groupId?:"",
                "ObjectType" => $objectType?:0,
                "Status" => (int)$status,
                "PageSize" => env('PER_PAGE', 20),
                "PageIndex" => $page > 0 ? $page - 1 : $page
            ];
            // dd($params);

            $result = HttpHelper::getInstance()->post("Category/CustomerList", $params);

            $data = new LengthAwarePaginator($result->data, $result->paging->TotalCount, $params['PageSize'], $page,
                ['path' => Paginator::resolveCurrentPath(), 'lastPage' => $result->paging->TotalPage]
            );
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        if($request->ajax()) {
            return response()->json(['success' => true, 'result' => $data]);
        }

        return view('admin.customer.index', compact('data', 'customerGroups',
            'search', 'status', 'groupId', 'status', 'email', 'objectType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return view
     */
    public function create()
    {
        if(!isCreate(env('CUSTOMER_ROLE_CODE'))) {
            alert('Lỗi 403', 'Bạn không có quyền thêm khách hàng', 'error');
            return abort('403');
        }

        $customerGroups = getCustomerGroupList();
        $customer_code = getNewCustomerCode();

        return view('admin.customer.create', compact('customerGroups', 'customer_code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerRequest $request
     * @return JsonResponse|RedirectResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function store(CustomerRequest $request)
    {
        if(!isCreate(env('CUSTOMER_ROLE_CODE'))) {
            alert('Lỗi 403', 'Bạn không có quyền thêm khách hàng', 'error');
            return abort('403');
        }

        $res = ['success' => true, 'message' => '', 'code' => 0, 'result' => []];
        try {
            $avatar = "";

            if($request->hasFile('avatar'))
            {
                $image = base64_encode($request->file('avatar')->get());
                $ret = HttpHelper::getInstance()->uploadFile("Upload/Image", [
                    //'content-type' => 'text/plain',
                    'contents' => $image,
                    'name' => 'image_upload'
                ]);

                if($ret && $ret->meta->status_code == 0) {
                    $avatar = $ret->data;
                }
            }

            $customerName = $request->post('customer_name', '');
            $birthday = $request->post('birthday', '');
            $cusCode = $request->post('customer_code');
            $autoCustomerCode = $request->post('autoCustomerCode', 0);

            $ret = HttpHelper::getInstance()->get('Category/CheckExistCustomerCode/'.$cusCode);

            if($ret->data) {
                if($autoCustomerCode != 1 && $request->wantsJson()) {
                    $res['success'] = false;
                    $res['message'] = 'Mã khách hàng đã tồn tại';
                    $res['code'] = -1;
                    return response()->json($res);
                }

                $cusCode = getNewCustomerCode();
            }

            $param = [
                "CustomerCode" => $cusCode,
                "CustomerName" => $customerName,
                "BirthDay" => $birthday? $birthday : date('Y-m-d'),
                "GroupId" => $request->groupId,
                "Address" => $request->address ? $request->address : "",
                "Tel" => $request->tel ? $request->tel : "",
                "Email" => $request->email ? $request->email : "",
                "Description" => $request->description ? $request->description : "",
                "Avatar" => $avatar,
                "TaxCode" => $request->taxCode ? $request->taxCode : "",
            ];
            $result = HttpHelper::getInstance()->post("Category/AddNewCustomer", $param);
            if($result->meta->status_code != 0) {
                $res['success'] = false;
            }
            $res['result'] = $result->data;
            $res['message'] = $result->meta->message;
        } catch (ClientException $exception) {
            $res['message'] = $exception->getMessage();
            $res['success'] = false;
            logger()->critical($exception->getMessage(), $this->headers);
        } catch(ServerException $ex) {
            $res['message'] = $ex->getMessage();
            $res['success'] = false;
            logger()->critical($ex->getMessage(), $this->headers);
        }

        return response()->json($res);
    }

    public function show(Request $request, $id)
    {
        if(!isRead(env('CUSTOMER_ROLE_CODE'))) {
            alert('Lỗi 403', 'Bạn không có quyền xem khách hàng', 'error');
            return abort('403');
        }

        $isPopup = $request->get('isPopup');

        $type = '';
        $data = [];
        $customer = null;
        try {
            $result = HttpHelper::getInstance()->get("Category/GetCustomerInfo/$id");
            $customer = $result->data;
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        $view = 'admin.customer.show';
        if($isPopup == 1) {
            $view = 'admin.customer.showPopup';
        }

        return view($view, compact('customer', 'type', 'data'));
    }

    public function history(Request $request, $id, $type)
    {
        if(!isRead(env('CUSTOMER_ROLE_CODE'))) {
            alert('Lỗi 403', 'Bạn không có quyền xem khách hàng', 'error');
            return abort('403');
        }

        $page = $request->get('page', (Paginator::resolveCurrentPage() ?: 1));

        $customer = null;
        $data = [];

        $url = 'GetHistoryCustomerOrder';
        if($type == 'order') {
            $url = 'GetHistoryCustomerPurchaseOrder';
        }elseif($type == 'import') {
            $url = 'GetHistoryCustomerImportOrder';
        } elseif($type == 'need-pay') {
            $url = 'GetHistoryCustomerOrderNeedPay';
        } elseif($type == 'debit') {
            $url = 'GetHistoryCustomerPaymentDebit';
        }

        try {
            $result = HttpHelper::getInstance()->get("Category/GetCustomerInfo/$id");
            $customer  = $result->data;
            $result = HttpHelper::getInstance()->get("Category/$url/$id/$page/" . env('PER_PAGE', 20));
            $data = new LengthAwarePaginator($result->data, $result->paging->TotalCount, env('PER_PAGE', 20), $page,
                ['path' => Paginator::resolveCurrentPath(), 'lastPage' => $result->paging->TotalPage]
            );
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        return view('admin.customer.show', compact('customer', 'type', 'data'));
    }

    public function edit($id)
    {
        if(!isUpdate(env('CUSTOMER_ROLE_CODE'))) {
            alert('Lỗi 403', 'Bạn không có quyền sửa khách hàng', 'error');
            return abort('403');
        }

        $customer = null;
        $customerGroups = getCustomerGroupList();
        try {
            $result = HttpHelper::getInstance()->get("Category/GetCustomerInfo/$id");
            $customer = $result->data;
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        return view('admin.customer.edit', compact('customer', 'customerGroups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCustomerRequest $request
     * @param String $id
     * @return JsonResponse|RedirectResponse
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        if(!isUpdate(env('CUSTOMER_ROLE_CODE'))) {
            alert('Lỗi 403', 'Bạn không có quyền sửa khách hàng', 'error');
            return abort('403');
        }

        $res = ['success' => true, 'message' => '', 'result' => []];

        try {
            $result = HttpHelper::getInstance()->get("Category/GetCustomerInfo/$id");
            $customer = $result->data;
            if($customer == null) {
                $res['success'] = false;
                $res['message'] = "Mã khách hàng không tồn tại";
                return response()->json($res);
            }

            $avatar = "";

            if($request->hasFile('avatar'))
            {
                $file = $request->file('avatar');
                $ret = HttpHelper::getInstance()->uploadFile("Upload/Image",
                    [
                        'headers'  => ['Content-Type' => $file->getMimeType()],
                        'contents' => fopen($file->getRealPath(), 'r+'),
                        'name' => 'image_upload'
                    ]
                );

                if($ret && $ret->meta->status_code == 0) {
                    $avatar = $ret->data->cdn_image;
                }
            }

            $desc = $request->post('description', "");
            $email = $request->post('email');
            $tel = $request->post('tel');
            $address = $request->post('address');
            $birthday = $request->post('birthday');
            $customer_code = $request->post('customer_code');

            if($birthday == null) {
                $birthday = $customer->Birthday;
            }

            if($customer_code == null) {
                $customer_code = $customer->CustomerCode;
            }

            $param = [
                "Id" => $customer->Id,
                "CustomerCode" => $customer_code,
                "CustomerName" => $request->customer_name,
                "BirthDay" => $birthday,
                "GroupId" => $request->groupId,
                "Address" => $address?:"",
                "Tel" => $tel?:"",
                "Email" => $email?:"",
                "Description" => $desc?:"",
                "Avatar" => $avatar,
                "TaxCode" => $request->taxCode,
            ];

            $result = HttpHelper::getInstance()->post("Category/UpdateCustomer", $param);

            if($result->meta->status_code != 0) {
                $res['success'] = false;
            }
            $res['message'] = $result->meta->message;
        } catch (ClientException $exception) {
            $res['message'] = $exception->getMessage();
            $res['success'] = false;
            logger()->critical($exception->getMessage(), $this->headers);
        } catch(ServerException $ex) {
            $res['message'] = $ex->getMessage();
            $res['success'] = false;
            logger()->critical($ex->getMessage(), $this->headers);
        }

        return response()->json($res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @throws Exception
     */
    public function destroy(Request $request, $id)
    {
        $res = ['success' => false, 'message' => '', 'result' => []];
        
        if(!isDelete(env('CUSTOMER_ROLE_CODE'))) {
            $res['message'] = 'Bạn không có quyền xóa khách hàng';
            alert('Lỗi 403', $res['message'], 'error');
            return response()->json($res);
            //return abort('403');
        }

        try {
            $result = HttpHelper::getInstance()->post("Category/DeleteCustomer", ['ObjectId' => $id]);
            if($result->meta->status_code == 0) {
                $res['success'] = true;
                $res['message'] = $result->meta->message;
            }
        } catch (\Exception $e) {
            $res['message'] = 'Đã có lỗi xảy ra !!!';
        }
        
        return response()->json($res);
    }

    public function getBankList()
    {
        $bankList = getBankList();
        return response()->json(['success' => true, 'result' => $bankList]);
    }
}
