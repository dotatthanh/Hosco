<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HttpHelper;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Products\productRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends AppBaseController
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
     * @return Response
     */
    public function index(Request $request)
    {
        $page = $request->get('page', (Paginator::resolveCurrentPage() ?: 1));

        $productCode = $request->get('productCode', '');
        $productName = $request->get('productName', '');
        $groupId = $request->get('groupId', "");
        $description = $request->get('description', "");
        $status = $request->get('status', -1);
        $instock = (int)$request->get('instock', -1);
        $perPage = (int)$request->get('perPage', env('PER_PAGE', 20));

        try {
            $params = [
                "ProductName" => $productName?:'',
                "ProductCode" => $productCode?:'',
                "Description" => $description?:'',
                "Visible" => (bool)$status,
                "Instock" => $instock,
                "ProductGroup" => $groupId?:"",
                "PageSize" => $perPage,
                "PageIndex" => $page > 0 ? $page - 1 : $page
            ];

            $result = HttpHelper::getInstance()->post("Category/ProductList", $params);
            $data = new LengthAwarePaginator($result->data, $result->paging->TotalCount, $perPage, $page,
                ['path' => Paginator::resolveCurrentPath(), 'lastPage' => $result->paging->TotalPage]
            );

            $productGroups = getProductGroupList();
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        if($request->ajax() || $request->wantsJson()) {
            return response()->json($result->data);
            //return response()->json(['success' => true, 'result' => $data]);
        }
        return view('admin.products.index', compact('description', 'productCode', 'instock',
            'data', 'productGroups', 'status', 'productName', 'groupId'));
    }

    /* Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $productGroups = getProductGroupList();
        $newCode = getNewProductCode();
        return view('admin.products.create', compact('productGroups', 'newCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     *
     * @return JsonResponse|RedirectResponse
     */
    public function store(ProductRequest $request)
    {
        $res = ['success' => true, 'message' => '', 'result' => []];

        $avatar = "";

        if($request->hasFile('picture'))
        {
            $file = $request->file('picture');
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

        $productName = $request->post('product_name', '');
        $productCode = $request->post('product_code', '');
        $unit = $request->post('unit', '');
        $unitPrice = $request->post('unitPrice', 0.0);
        $purchasePrice = $request->post('purchasePrice', 0.0);
        $minStock = $request->post('minInStock', 0);
        $maxStock = $request->post('maxInStock', 0);
        $groupId = $request->post('groupId', '');
        $desc = $request->post('description', '');
        $sNoteImport = $request->post('noteImport', '');
        $sNoteOrder = $request->post('noteOrder', '');

        $unitPrice = floatval(str_replace(".", "", $unitPrice));
        $purchasePrice = floatval(str_replace(".", "", $purchasePrice));
        $IsNew = $request->post('IsNew', '0');
        $IsFeature = $request->post('IsFeature', '0');
        $Discount = $request->post('Discount', 0.0);
        $Discount = floatval(str_replace(".", "", $Discount));

        /*
        $ret = HttpHelper::getInstance()->get('Category/CheckExistCustomerCode/'.$productCode);
        if($ret->data) {
            if($autoCustomerCode != 1 && $request->wantsJson()) {
                $res['success'] = false;
                $res['message'] = 'Mã khách hàng đã tồn tại';
                $res['code'] = -1;
                return response()->json($res);
            }

            $newCode = getNewProductCode();
        }*/

        $params = [
            "ProductName" => $productName?:"",
            "ProductCode" => $productCode?:"",
            "Unit" => $unit?:"",
            "UnitPrice" => floatval($unitPrice?:0.0),
            "PurchasePrice" => floatval($purchasePrice?:0.0),
            "MinInStock" => $minStock?:0,
            "MaxInStock" => $maxStock?:0,
            "GroupId" => $groupId?:"",
            "Description" => $desc?:"",
            "s_NoteImport" => $sNoteImport?:"",
            "s_NoteOrder" => $sNoteOrder?:"",
            "Picture" => $avatar,
            "IsNew"=> $IsNew=='on'?"true":"false",
            "IsFeature"=> $IsFeature=='on'?"true":"false",
            "Discount"=> floatval($Discount?:0.0),
        ];

        try {
            $result = HttpHelper::getInstance()->post("Category/AddNewProduct", $params);
            if ($result->meta->status_code != 0) {
                $res['success'] = false;
            }
            $res['message'] = $result->meta->message;
            $res['result'] = $result->data;
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
     * Display the specified resource.
     *
     * @param string $id
     * @return Response
     */
    public function show($id)
    {
        $type = 'in-stock';
        $data = [];
        try {
            $result = HttpHelper::getInstance()->get("Category/GetProductInfo/".$id);
            $product = $result->data;
            $ret = HttpHelper::getInstance()->get("Category/GetProductStock/".$id);
            $productStocks = $ret->data;
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }
// dd($productStocks);
        $comments = [];//$product->comments()->paginate(4);
        $colors = []; //$product->colors(['color_name', 'color_code'])->get();
        $averageRating = 0;//$product->averageRating;
        $categories = [];//$product->categories(['category_name'])->get();
        return view('admin.products.show', compact('data', 'type',
            'product', 'productStocks', 'comments', 'colors', 'averageRating', 'categories'
        ));
    }

    public function history(Request $request, $id, $type)
    {
        if(!isRead(env('CUSTOMER_ROLE_CODE'))) {
            alert('Lỗi 403', 'Bạn không có quyền xem', 'error');
            return abort('403');
        }

        $page = $request->get('page', (Paginator::resolveCurrentPage() ?: 1));

        $product = null;
        $data = [];
        $comments = [];//$product->comments()->paginate(4);
        $colors = []; //$product->colors(['color_name', 'color_code'])->get();
        $averageRating = 0;//$product->averageRating;
        $categories = [];//$product->categories(['category_name'])->get();

        $url = 'GetProductHistoryOrder';
        if($type == 'import') {
            $url = 'GetProductHistoryImport';
        }

        try {
            $ret = HttpHelper::getInstance()->get("Category/GetProductInfo/$id");
            $product = $ret->data;
            $page = $page - 1;
            $result = HttpHelper::getInstance()->get("Category/$url/$id/$page/" . env('PER_PAGE', 10));
            $data = new LengthAwarePaginator($result->data, $result->paging->TotalCount, env('PER_PAGE', 10), $page + 1,
                ['path' => Paginator::resolveCurrentPath(), 'lastPage' => $result->paging->TotalPage]
            );
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        return view('admin.products.show', compact('product', 'type', 'data', 'colors', 'comments', 'averageRating', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return Response
     */
    public function edit($id)
    {
        $result = HttpHelper::getInstance()->get("Category/GetProductInfo/".$id);
        $product = $result->data;
        $productGroups = getProductGroupList();
        return view('admin.products.edit', compact('product', 'productGroups'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $data = $request->all();

        $res = ['success' => true, 'message' => '', 'result' => []];

        $avatar = "";

        if($request->hasFile('picture'))
        {
            $file = $request->file('picture');
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

        $unitPrice = floatval(str_replace(".", "", $data['unitPrice']));
        $purchasePrice = floatval(str_replace(".", "", $data['purchasePrice']));
        $IsNew = $request->post('IsNew', '0');
        $IsFeature = $request->post('IsFeature', '0');
        $Discount = $request->post('Discount', 0.0);
        $Discount = floatval(str_replace(".", "", $Discount));
        $params = [
            "Id" => $id,
            "ProductName" => $data['product_name'],
            "ProductCode" => $data['product_code'],
            "Unit" => $data['unit'],
            "UnitPrice" => $unitPrice,
            "PurchasePrice" => $purchasePrice,
            "MinInStock" => (int)$data['minInStock'],
            "MaxInStock" => (int)$data['maxInStock'],
            "GroupId" => $data['groupId'],
            "Description" => $data['description'],
            "s_NoteImport" => $data['noteImport'],
            "s_NoteOrder" => $data['noteOrder'],
            "Picture" => $avatar,
            "IsNew"=> $IsNew=='on'?"true":"false",
            "IsFeature"=> $IsFeature=='on'?"true":"false",
            "Discount"=> floatval($Discount?:0.0),
        ];
//        dd($params);
        try {
            $result = HttpHelper::getInstance()->post("Category/UpdateProduct", $params);
            if ($result->meta->status_code != 0) {
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

    public function price(Request $request)
    {
        $price = 0.0;
        try {
            $params = [
                "ProductId" => $request->get('productId', ""),
                "CustomerId" => $request->get('customerId', ""),
                "PriceDefault" => 0
            ];

            $result = HttpHelper::getInstance()->post("SelOrder/GetPriceBook", $params);
            $price = $result->data;
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        return response()->json(['success' => true, 'result' => $price]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $res = ['success' => false, 'message' => 'Đã có lỗi xảy ra !!!', 'result' => []];
        
        if(!isDelete(env('CUSTOMER_ROLE_CODE'))) {
            $res['message'] = 'Bạn không có quyền xóa sản phẩm';
            alert('Lỗi 403', $res['message'], 'error');
            return response()->json($res);
            //return abort('403');
        }
        
        try {
            $result = HttpHelper::getInstance()->post("Category/DeleteProduct", ['ObjectId' => $id]);
            if($result->meta->status_code == 0) {
                $res['success'] = true;
            }
            $res['message'] = $result->meta->message;
        } catch (\Exception $e) {
            
        }
        
        return response()->json($res);
    }
}
