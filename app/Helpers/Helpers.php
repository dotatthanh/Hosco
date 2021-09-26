<?php

//namespace App\Helpers;
//function to make a thumbinal image


use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\File;
use App\Helpers\HttpHelper;

function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];
    //create  THUMBNAIL dir if not exist
    $path = public_path(env('THUMBNAIL_PATH'));
    if(!File::isDirectory($path)){
        File::makeDirectory($path, 0777, true, true);
    }
    switch($mime){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;

        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;

        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;

        default:
            return false;
            break;
    }

    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);

    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if($width_new > $width){
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        //cut point by width
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }

    $image($dst_img, $dst_dir, $quality);

    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
}
//usage example
//resize_crop_image(100, 100, "test.jpg", "test.jpg");
function isFullRoleForTenant() {
    $tenantCode = Session::get('tenant_code');
    if($tenantCode == 'imegane') {
        return false;
    }
    
    return true;

}

function isCRUD($mod, $act) {
    foreach (auth()->user()->permissions as $per) {
        if($per->Code === $mod) {
            return $per->$act;
        }
    }

    return false;
}

function isRead($mod) {
    return isCRUD($mod, 'R');
}

function isCreate($mod) {
    return isCRUD($mod, 'A');
}

function isUpdate($mod) {
    return isCRUD($mod, 'U');
}

function isDelete($mod) {
    return isCRUD($mod, 'D');
}

function generateStr($length) {
    $result = HttpHelper::getInstance()->get('Helper/RandomStringGenerator/'.$length);
    return $result->data;
}

function getBankList() {
    try {
        $result = HttpHelper::getInstance()->get("Category/BankList");
        return $result->data;
    } catch (ClientException $exception) {

    }

    return [];
}

function getPriceBookList() {
    try {
        $result = HttpHelper::getInstance()->get("Category/PriceBookList");
        return $result->data;
    } catch (ClientException $exception) {

    }

    return [];
}

function getLocationList() {
    try {
        $result = HttpHelper::getInstance()->get("Category/LocationList");
        return $result->data;
    } catch (ClientException $exception) {

    }

    return [];
}

function getEmployeeList() {
    try {
        $result = HttpHelper::getInstance()->get("Category/EmployeeList");
        return $result->data;
    } catch (ClientException $exception) {

    }

    return [];
}

function getProductGroupList() {
    try {
        $result = HttpHelper::getInstance()->get("Category/ProductGroupList");
        return $result->data;
    } catch (ClientException $exception) {

    }
    return [];
}

function getNewProductCode() {
    try {
        $ret = HttpHelper::getInstance()->get("Category/GetNewProductCode");
        return $ret->data->NewCode;
    } catch (ClientException $exception) {

    }

    return null;
}

function getNewCustomerCode() {
    try {
        $ret = HttpHelper::getInstance()->get("Category/GetNewCustomerCode");
        return $ret->data->NewCode;
    } catch (ClientException $exception) {

    }

    return null;
}

function getCustomerGroupList() {
    try {
        $result = HttpHelper::getInstance()->get("Category/CustomerGroupList");
        return $result->data;
    } catch (ClientException $exception) {

    }
    return [];
}

function getCommonConfig() {
    try {
        $result = HttpHelper::getInstance()->get("Common/GetConfig");
        return $result->data;
    } catch (ClientException $exception) {

    }
    return [];
}

function getOrderConfig($type) {
    try {
        $cmd = 'SelOrder/GetSelOrderConfig';
        if($type == 'purchase') {
            $cmd = 'PurchaseOrder/GetSelOrderConfig';
        }
        $result = HttpHelper::getInstance()->get($cmd);
        return $result->data;
    } catch (ClientException $exception) {

    }
    return null;
}

function getAllTenants() {
    try {
        $result = HttpHelper::getInstance()->get("Tenant/getall");
        return $result->data;
    } catch (ClientException $exception) {
        alert()->error($exception->getMessage());
    }
    return [];
}

function getStoreList($search, $locationId) {
    try {
        $data = HttpHelper::getInstance()->post("Category/StoreList", [
            "SearchText" => $search?:"",
            "LocationId" => $locationId?:""
        ]);
        return $data->data;
    } catch (ClientException $exception) {
        alert()->error($exception->getMessage());
    }
    return [];
}

function getRevenue($date, $type, $assoc = false) {
    if($type == 'monthyear' && strlen($date) == 4) {
        $date = "01-01-" .$date;
    }
    
    $url = 'iM_DoanhThu_NgayThang';
    if($type == 'monthyear') {
        $url = 'iM_DoanhThu_ThangNam';
    }
    try {
        $page = 1;
        $data = HttpHelper::getInstance()->get("Report/$url/$date", $page, $assoc);
        if($assoc) {
            return $data['data'];
        }
        return $data->data;
    } catch (ClientException $exception) {
        alert()->error($exception->getMessage());
    }
    return [];
}

function getRevenueAllYear($type, $assoc = false) {
    $url = 'iM_DoanhThu_Nam';
    if($type == 'allyear') {
        $url = 'iM_DoanhThu_NamAll';
    }
    try {
        $page = 1;
        $data = HttpHelper::getInstance()->get("Report/$url", $page, $assoc);
        if($assoc) {
            return $data['data'];
        }
        return $data->data;
    } catch (ClientException $exception) {
        alert()->error($exception->getMessage());
    }
    return [];
}

function getRevenueByCustomerGroup($type, $assoc = false) {
    $url = 'iM_DoanhThu_CustomerGroup';
    
    try {
        $page = 1;
        $data = HttpHelper::getInstance()->get("Report/$url", $page, $assoc);
        if($assoc) {
            return $data['data'];
        }
        return $data->data;
    } catch (ClientException $exception) {
        alert()->error($exception->getMessage());
    }
    return [];
}

function getRevenueByLocation($fromDate, $toDate, $type, $assoc = false) {
    $url = 'iM_DoanhThu_ChiNhanh';
    
    try {
        $page = 1;
        $data = HttpHelper::getInstance()->get("Report/$url/$fromDate/$toDate", $page, $assoc);
        if($assoc) {
            return $data['data'];
        }
        return $data->data;
    } catch (ClientException $exception) {
        alert()->error($exception->getMessage());
    }
    return [];
}

function currency_convert($money, $type) {
    $orderConfig = getOrderConfig($type);
    $fmt = numfmt_create( 'vi_VN', \NumberFormatter::CURRENCY );
    $fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, isset($orderConfig->format_number_money)? $orderConfig->format_number_money:2);
    return numfmt_format_currency($fmt, $money, 'VND');
}

function qty_convert($qty, $type) {
    $orderConfig = getOrderConfig($type);
    return number_format($qty,isset($orderConfig->format_number_qty)? $orderConfig->format_number_qty:2);
}

function convertDataToChartForm($data, $type = 'Month')
{
    $newData = [];
    $firstLine = true;
    $firstLineStr = null;
    
    if($type == 'allyear' || $type == 'circle') {
        $newData = _convertDataToChartForm($data);
    } else {
        foreach ($data as $key => $dataRow)
        {
            $firstLineStr = array_shift($dataRow);
            foreach($dataRow as $k => $v) {
                if(count($newData) > 0 && $key > 0) {
                    foreach($newData as $i => $val) {
                        if($val[$type] == $k) {
                            $val[$firstLineStr] = $v;
                            $newData[$i] = $val;
                        }
                    }
                } else {
                    $newData[] = [
                        $type => $k,
                        $firstLineStr => $v
                    ];
                }
            }
        }
        
        $newData = _convertDataToChartForm($newData);
    }
        
    if(strtolower($type) == 'year') {
        foreach($newData[0] as $i => $val) {
            if($i == 0) continue;
            $newData[0][$i] = 'Năm/Year ' . $val;
        }
    }
    
    if(strtolower($type) == 'allyear') {
        foreach($newData as $i => $val) {
            if($i == 0) continue;
            $val[0] = 'Năm/Year ' . $val[0];
            $newData[$i] = $val;
        }
    }
    
//     dd($newData);
    return $newData;
}

function _convertDataToChartForm($data)
{
    $newData = array();
    $firstLine = true;
    
    foreach ($data as $dataRow)
    {
        if ($firstLine)
        {
            $newData[] = array_keys($dataRow);
            $firstLine = false;
        }
        
        $tmp = array_values($dataRow);
        $tmp[0] = str_replace('T', 'Tháng/Month ', $tmp[0]);
        $newData[] = $tmp;
    }
    
    return $newData;
}


