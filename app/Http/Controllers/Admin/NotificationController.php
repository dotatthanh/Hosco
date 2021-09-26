<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\HttpHelper;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
class NotificationController extends Controller
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
        $dateFrom = $request->get('dateFrom', '');
        $dateFrom = $dateFrom!=''?date_format(date_create($dateFrom),'Y-m-d'):$dateFrom;
        $dateFrom1 = $dateFrom!=''?date_create($dateFrom):$dateFrom;
        $dateTo = $request->get('dateTo', '');
        $dateTo = $dateTo!=''?date_format(date_create($dateTo),'Y-m-d'):$dateTo;
        $dateTo1 = $dateTo!=''?date_create($dateTo):$dateTo;

        
        try {
            $result = null;
            
            $result = HttpHelper::getInstance()->get("Crm/GetAllNotification");
            $data = $result->data;
            if($search != ''){
                foreach ($data as $key => $value) {
                    if(strpos($value->title,$search)===false){
                        unset($data[$key]);
                    }
                }
            }
            if($dateTo != '' && $dateFrom != '' ){
                if($dateFrom1<$dateTo1){
                    foreach ($data as $key => $value) {
                        $dateNotifi = date_create(date_format(date_create($value->created_at),'Y-m-d')) ;
                        if($dateFrom1>$dateNotifi || $dateTo1<$dateNotifi){
                            unset($data[$key]);
                        }
                    }
                }else if($dateFrom1>$dateTo1){
                    foreach ($data as $key => $value) {
                        $dateNotifi = date_create(date_format(date_create($value->created_at),'Y-m-d')) ;
                        if($dateFrom1<$dateNotifi || $dateTo1>$dateNotifi){
                            unset($data[$key]);
                        }
                    }
                }else if($dateFrom1==$dateTo1){
                    foreach ($data as $key => $value) {
                        $dateNotifi = date_format(date_create($value->created_at),'Y-m-d') ;
                        if( $dateFrom!=$dateNotifi){
                            unset($data[$key]);
                        }
                    }
                }
            }else if($dateTo == '' && $dateFrom != ''){
                foreach ($data as $key => $value) {
                    $dateNotifi = date_format(date_create($value->created_at),'Y-m-d') ;
                    if( $dateFrom!=$dateNotifi){
                        unset($data[$key]);
                    }
                }

            }else if($dateFrom == '' && $dateTo != ''){
                foreach ($data as $key => $value) {
                    $dateNotifi = date_format(date_create($value->created_at),'Y-m-d') ;
                    if( $dateTo!=$dateNotifi){
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
        return view('admin.notification.index',compact('data','dateFrom','search','dateTo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $customerGroups = getCustomerGroupList();

        return view('admin.notification.create',compact('customerGroups'));

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
        $type = $request->post("typePushNotification");
        $obj1 = new \stdClass;
        $obj2 = new \stdClass;
        $title=$request->post("title");
        $message=$request->post("message");

        switch ($type) {
            case '0':
                $topic="app-notifi-crm-".auth()->user()->getAttributes()['tenantCode'];
                
                  if($this->pushNotificationToTopic($title,$message,$topic)){
                    return json_encode(['status'=>true,'data'=>[],'messenge'=>'Gửi thông báo thành công']);

                  }else{
                    return json_encode(['status'=>false,'data'=>[],'messenge'=>'Gửi thông báo thất bại']);
                  }

                break;
            case '1':
                $customerGroups = getCustomerGroupList();

                $groups = [];
                $paramSend = $request->post();
                $obj1 = new \stdClass;
               
                $obj1->Payload =  [];
                $obj1->Item = new \stdClass;
                $obj1->Item->title = $title;
                $obj1->Item->body = $message;
                $obj1->Item->send_to = [];

                foreach ($paramSend as $key => $value) {
                    $obj = new \stdClass;

                    if(strpos($key,'groupSend')===0){
                        $obj->id = $value;
                        foreach ($customerGroups as $key1 => $value1) {
                            if($value1->Id == $value){
                                $obj->name =$value1->GroupName;
                                break;
                            }
                        }
                        array_push($groups,$obj);
                    }
                }

                
                foreach ($groups as $key => $value) {
                    $data = $this->getCustomer(0,$value->id);
                    $countPage = $data->paging->TotalPage;
                    array_push($obj1->Item->send_to,$value->name);
                    if($countPage>1){
                        for($i = 0;$i<$countPage;$i++){
                            $dataSend = $this->getCustomer($i,$value->id);
                            $dataSend = $dataSend->data;
                            foreach($dataSend as $key =>$item){
                                $topic="app-notifi-".auth()->user()->getAttributes()['tenantCode']."-".$item->CustomerCode;

                                $obj2 = new \stdClass;
                                $obj2->title=$title;
                                $obj2->message=$message;
                                $obj2->topic=$topic;
                                $obj2->account_id = $item->CustomerCode;
                                $obj2->refer_id = "";
                                $obj2->refer_type = "notifi_type_20";
                                array_push($obj1->Payload,$obj2);
                                
                                // var_dump($item->CustomerCode);
                            }
                        }

                    }else{
                        foreach($data->data as $key =>$item){
                            $topic="app-notifi-".auth()->user()->getAttributes()['tenantCode']."-".$item->CustomerCode;

                            $obj2 = new \stdClass;
                            $obj2->title=$title;
                            $obj2->message=$message;
                            $obj2->topic=$topic;
                            $obj2->account_id = $item->CustomerCode;
                            $obj2->refer_id = "";
                            $obj2->refer_type = "notifi_type_20";
                            array_push($obj1->Payload,$obj2);
                             
                            // var_dump($topic);
                        }


                        // die();

                    }
                }
                $this->pushNotificationToTopicGroup($obj1);

                // var_dump(json_encode($obj1));
                // die();
                return json_encode(['status'=>true,'data'=>[],'messenge'=>'Gửi thông báo thành công']);

    
                // die();
                # code...
                break;
            default:
            return json_encode(['status'=>false,'data'=>[],'messenge'=>'Gửi thông báo thất bại']);

                break;
        }
        

        
    }
    
    //send message
    public function pushNotificationToTopicGroup($obj)
    {
        
        
          try{
            $data = HttpHelper::getInstance()->post("Crm/SendNotification", $obj);
            return true;
          }catch (ClientException $exception) {
            return false;
          }
    }
    public function pushNotificationToTopic($title, $message,$topic)
    {
        
        $obj1 = new \stdClass;
        $obj2 = new \stdClass;
        $obj2->title=$title;
        $obj2->message=$message;
        $obj2->topic=$topic;
        $obj2->account_id="";
        $obj2->refer_id="";
        $obj2->refer_type="notifi_type_20";
        $obj1->Payload =  [
            $obj2
          ];
        $obj1->Item = new \stdClass;
        $obj1->Item->title = $title ;
        $obj1->Item->body = $message ;
        $obj1->Item->send_to = ["all"] ;

          try{
            $data = HttpHelper::getInstance()->post("Crm/SendNotification", $obj1);
            return true;
          }catch (ClientException $exception) {
            return false;
          }
    }

    

    //get customer 
    public function getCustomer($page,$id)
    {
        try{
            $params = [
                "SearchText" => "",
                "Email" => "",
                "ObjectGroupId" => $id,
                "ObjectType" => 0,
                "Status" => 0,
                "PageSize" => 40,
                "PageIndex" => $page
            ];
            $result = HttpHelper::getInstance()->post("Category/CustomerList", $params);
            return $result;
        }catch(\Exception $ex){
            var_dump($ex->getMessage());
            return [];

        }
        

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
        $id = $request->post('id','');
        if($id != '' ){
            try{
                $result = HttpHelper::getInstance()->get("Crm/GetAllNotification");
                // var_dump($)
                foreach($result->data as $key => $value){
                    if($id ==  $value->id){
                        $notifi = $value;
                        // var_dump($notifi);
                        // die();
                        return view('admin.notification.show',compact('notifi'));
                    }
                }
                
    
            }catch(\Exception $ex){
                // var_dump();
                $error = $ex->getMessage();
                $code = $ex->getCode();
                return view('admin.errors.error',compact('code','error'));
            }
        }else{
                $error = "ID IS EMPTY";
                $code = "404";
                return view('admin.errors.error',compact('code','error'));
        }
        


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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
