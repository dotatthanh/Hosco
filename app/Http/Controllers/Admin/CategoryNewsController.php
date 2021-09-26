<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\HttpHelper;

class CategoryNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data = [];
        $page = $request->get('page', (Paginator::resolveCurrentPage() ?: 1));
        $search = $request->get('search', '');
        $typeNews = $request->get('typeNews', '');
        $categoryNews = null;
        try {
            $params = [];
            $result = null;
            $categoryNews = HttpHelper::getInstance()->get("Crm/GetNewsCategoryList");
            $data = $categoryNews->data;
            
           
            if($search != ''){
                foreach ($data as $key => $value) {
                    if(strpos(strtolower($value->name),strtolower($search))===false){
                        unset($data[$key]);
                    }
                }
            }
                        

            
            // dd($data);
            // die();
            $countItem = count($data);

            // dd($data);
            // die();
            $data  = array_slice($data,($page-1)*10,10);
            $data = new LengthAwarePaginator($data, $countItem, 10, $page,
                ['path' => Paginator::resolveCurrentPath(), 'lastPage' => ceil($countItem/10)]
            );
            // dd($data);
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        if($request->ajax()) {
            return response()->json(['success' => true, 'result' => $data]);
        }
        // die();
        return view('admin.categoryNews.index',compact('data','search'));
        

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $res = ['success' => true, 'message' => '', 'result' => []];
        
        $name = $request->post('nameCategory', '');
        $description = $request->post('descriptionCategory', '');
        $status = $request->post('statusCategory', '');

        $params = [
            
            "name" => $name,
            "description" => $description,
            "enable" => $status=='on'?"true":"false",
        ];
        // return response()->json($params);

        try {
            $result = HttpHelper::getInstance()->postNew("Crm/PostNewsCategory", $params);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $category = HttpHelper::getInstance()->get("Crm/GetNewsCategory/".$request->post('id'));

        return json_encode($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $res = ['success' => true, 'message' => '', 'result' => []];
        
        $name = $request->post('nameCategory', '');
        $description = $request->post('descriptionCategory', '');
        $status = $request->post('statusCategory', '');
        $id =  $request->post('_idCategory', '');
        if($id==''){
            $res['success'] = false;
            $res['message'] = "id is empty";
            return response()->json($res);

        }
        $params = [
            "id"=>$id,
            "name" => $name,
            "description" => $description,
            "enable" => $status=='on'?"true":"false",
        ];
        // return response()->json($params);

        try {
            $result = HttpHelper::getInstance()->postNew("Crm/UpdateNewsCategory", $params);

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $res = ['success' => false, 'message' => 'Đã có lỗi xảy ra !!!', 'result' => []];
        
        
        // return response()->json($id);

        try {
            $result = HttpHelper::getInstance()->postNew("Crm/DeleteNewsCategory", ['id' => $id]);
            if($result->meta->status_code == 0) {
                $res['success'] = true;
            }
            $res['message'] = $result->meta->message;
        } catch (\Exception $e) {
            
        }
        
        return response()->json($res);
    }
    
}
