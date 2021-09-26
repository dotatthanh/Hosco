<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\HttpHelper;

use Carbon\Carbon;
use App\Services\FirebaseService;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];
        $page = $request->get('page', (Paginator::resolveCurrentPage() ?: 1));
        $search = $request->get('search', '');
        $typeNews = $request->get('typeNews', '');
        $categoryId = $request->get('categoryId', '');
        $categoryNews = null;
        try {
            $params = [];
            $result = null;
            $categoryNews = HttpHelper::getInstance()->get("Crm/GetNewsCategoryList");
            $categoryNews = $categoryNews->data;
            switch ($typeNews) {
                case '1':
                    $result = HttpHelper::getInstance()->get("Crm/GetHomeNews");
                    if($categoryId != ''){
                        $dataGet = $result;
                        foreach ($dataGet->data as $key => $value) {
                            # code...
                            if($value->category_id != $categoryId){
                                unset($result->data[$key]);
                            }
                        }
                    }
                     break;
                  case '2':
                     $result = HttpHelper::getInstance()->get("Crm/GetHotNews");
                     if($categoryId != ''){
                        $dataGet = $result;
                        foreach ($dataGet->data as $key => $value) {
                            # code...
                            if($value->category_id != $categoryId){
                                unset($result->data[$key]);
                            }
                        }
                    }
                      break;
                  case '3':
                      $result = HttpHelper::getInstance()->get("Crm/GetFeatureNews");
                      if($categoryId != ''){
                        $dataGet = $result;
                        foreach ($dataGet->data as $key => $value) {
                            # code...
                            if($value->category_id != $categoryId){
                                unset($result->data[$key]);
                            }
                        }
                    }
                      break;    
                  default:
                  if($categoryId != ''){
                        $result = HttpHelper::getInstance()->get("Crm/GetNewsListByCategory/".$categoryId);
                        
                    }else{
                        $result = HttpHelper::getInstance()->get("Crm/GetNewsList");
        
                    }
                      break;
            }                  
            
            
            $data = $result->data;
            if($search != ''){
                foreach ($data as $key => $value) {
                    if(strpos($value->title,$search)===false){
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
        return view('admin.news.index',compact('data','typeNews','search','categoryNews','categoryId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoryNews = HttpHelper::getInstance()->get("Crm/GetNewsCategoryList");
        $categoryNews = $categoryNews->data;
        return view('admin.news.create',compact("categoryNews"));
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
        
        // return response()->json(var_dump($request->post()));

        // die();
        $res = ['success' => true, 'message' => '', 'result' => []];

        $avatar = "";

        if($request->hasFile('urlImage'))
        {
            $file = $request->file('urlImage');
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

        $title = $request->post('title', '');
        $shortContent = $request->post('shortContent', '');
        $categoryId = $request->post('typeNews', '');
        $fullContent = $request->post('fullContent', '');
        $isPushNotification = $request->post('isPushNotification', '0');
        $is_hot = $request->post('is_hot', '0');
        $is_feature = $request->post('is_feature', '0');
        $is_home = $request->post('is_home', '0');

        $params = [
            "title" => $title,
            "short" => $shortContent,
            "full" => $fullContent,
            "picture" => $avatar!=""?$avatar:"",
            "source" => "",
            "isHome" => $is_home=='on'?"true":"false",
            "isHot" => $is_hot=='on'?"true":"false",
            "isFeature" => $is_feature=='on'?"true":"false",
            "categoryId" => $categoryId,
            "isPushNotification" => $isPushNotification=='on'?"true":"false",
            
        ];
        // return response()->json($params);\
        // var_dump($params);
        // die();

        try {
            $result = HttpHelper::getInstance()->post("Crm/PostNew", $params);
                    // return response()->json($result);

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
    public function show($id)
    {
        //
        
        try {
            $categoryNews = HttpHelper::getInstance()->get("Crm/GetNewsCategoryList");
            $categoryNews = $categoryNews->data;
            $news = HttpHelper::getInstance()->get("Crm/GetNews/".$id);       
        $news= $news->data;
        
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }
        return view('admin.news.show',compact('news','categoryNews'));

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
        $news = null;
        try {
            $categoryNews = HttpHelper::getInstance()->get("Crm/GetNewsCategoryList");
            $categoryNews = $categoryNews->data;
            $news = HttpHelper::getInstance()->get("Crm/GetNews/".$id);       
            $news= $news->data;
            
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        return view('admin.news.edit',compact('news','categoryNews'));

    

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
        // return response()->json();

        $res = ['success' => true, 'message' => '', 'result' => []];

        $avatar = $request->post('urlImageNews', '');

        if($request->hasFile('urlImage'))
        {
            $file = $request->file('urlImage');
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
        $id =  $request->post('id', '');
        if($id==''){
            $res['success'] = false;
            $res['message'] = "id is empty";
            return response()->json($res);

        }
        $title = $request->post('title', '');
        $shortContent = $request->post('shortContent', '');
        $categoryId = $request->post('typeNews', '');
        $fullContent = $request->post('fullContent', '');
        $isPushNotification = $request->post('isPushNotification', '0');
        $is_hot = $request->post('is_hot', '0');
        $is_feature = $request->post('is_feature', '0');
        $is_home = $request->post('is_home', '0');      
        $params = [
            "id"=> $id,
            "title" => $title,
            "short" => $shortContent,
            "full" => $fullContent,
            "picture" => $avatar!=""?$avatar:"",
            "source" => "",
            "isHome" => $is_home=='on'?"true":"false",
            "isHot" => $is_hot=='on'?"true":"false",
            "isFeature" => $is_feature=='on'?"true":"false",
            "categoryId" => $categoryId,
            "isPushNotification" => $isPushNotification=='on'?"true":"false",
            
        ];
       
        try {
            $result = HttpHelper::getInstance()->post("Crm/UpdateNew", $params);
                    // return response()->json($result);

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
    public function destroy($id)
    {
        $res = ['success' => false, 'message' => 'Đã có lỗi xảy ra !!!', 'result' => []];
        
        
        // return response()->json($id);

        try {
            $result = HttpHelper::getInstance()->postNew("Crm/DeleteNews", ['id' => $id]);
            if($result->meta->status_code == 0) {
                $res['success'] = true;
            }
            $res['message'] = $result->meta->message;
        } catch (\Exception $e) {
            
        }
        
        return response()->json($res);
    }
    public function sendNewsNotification(Request $request){
        $id = $request->post('id');
        $news = HttpHelper::getInstance()->get("Crm/GetNews/".$id);       
        $news= $news->data;
        $obj1 = new \stdClass;
        $obj2 = new \stdClass;
        $obj2->title=$news->title;
        $obj2->message=$news->short;
        $obj2->topic="app-notifi-crm-".auth()->user()->getAttributes()['tenantCode'];
        $obj2->account_id="";
        $obj2->refer_id=$news->id;
        $obj2->refer_type="post";
        $obj1->Payload =  [
            $obj2
          ];
        $obj1->Item = new \stdClass;
        $obj1->Item->title = $news->title;
        $obj1->Item->body = $news->short ;
        $obj1->Item->send_to = ["all"] ;
        if($news != null){
            $data = HttpHelper::getInstance()->post("Crm/SendNotification", $obj1);

            return json_encode(['status'=>true,'data'=>$data,'messenge'=>'Gửi thông báo thành công']);
        }else
            return json_encode(['status'=>false,'data'=>$news,'messenge'=>'Gửi thông báo thất bại']);

        
    }

    public function uploadImage(Request $request){
        // return response()->json($request->file('image')->getMimeType());
 
        $res = ['success' => false, 'message' => 'Đã có lỗi xảy ra !!!', 'result' => []];

        if($request->hasFile("image"))
        {
            $file = $request->file('image');
            $ret = HttpHelper::getInstance()->uploadFile("Upload/Image",
                [
                    'headers'  => ['Content-Type' => $file->getMimeType()],
                    'contents' => fopen($file->getRealPath(), 'r+'),
                    'name' => 'image_upload'
                ]
            );

            if($ret && $ret->meta->status_code == 0) {
                $res["success"] = true;
                $res["message"] = $ret->meta->message;
                $res['result'] = $ret->data->cdn_image;
                
                return response()->json($res);

            }
        }
        return response()->json($res);

    }

}
