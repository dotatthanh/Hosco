<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HttpHelper;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    private $headers = [];

    /**
     * @var OrderRepository
     */
    private $orderRepo;

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
        $dates = $request->get('dates', null);
        
        if(in_array($type, ['month', 'monthyear', 'year', 'allyear', 'customer-group', 'location'])) {
            $defaultDate = date('d-m-Y');
            if($type == 'month') {
                $defaultDate = date('m-Y');
            }
            
            if($type == 'monthyear') {
                $defaultDate = date('Y');
            }
            
            $date = $request->get('date', $defaultDate);
            
            if($type == 'year' || $type == 'allyear') {
                $data = getRevenueAllYear($type, true);
            } elseif($type == 'customer-group') {
                $data = getRevenueByCustomerGroup($type, true);
            } elseif($type == 'location') {
                $startDate = Carbon::now()->toDateString();
                $endDate = Carbon::now()->toDateString();
                if($dates != null) {
                    $dateTmp = explode('-', $dates);
                    $startDate = Carbon::parse(str_replace('/', '-', trim($dateTmp[0])))->format('Y-m-d');
                    $endDate = Carbon::parse(str_replace('/', '-', trim($dateTmp[1])))->format('Y-m-d');
                }
                
                $result = getRevenueByLocation($startDate, $endDate, $type, true);
                $data = [];
                foreach($result as $row) {
                    $data[$row['Location_ID']][] = $row;
                }
            }else {
                $data = getRevenue($date, $type, true);
            }
            return view('admin.report.' . $type, compact('dates', 'date', 'data'));    
        }
        
        $data = [];
        $location = [];
        $page = $request->get('page', (Paginator::resolveCurrentPage() ?: 1));

        $searchPattern = $request->get('searchPattern', '');
        $customerInfoSearch = $request->get('customerInfoSearch', '');
        $descriptionSearch = $request->get('descriptionSearch', '');
        $orderSearch = $request->get('orderSearch');
        $locationId = $request->get('locationId', '');
        $TotalMoney = 0;

        $startDate = Carbon::now()->toDateString();
        $endDate = Carbon::now()->toDateString();
        if($dates != null) {
            $dateTmp = explode('-', $dates);
            $startDate = Carbon::parse(str_replace('/', '-', trim($dateTmp[0])))->format('Y-m-d');
            $endDate = Carbon::parse(str_replace('/', '-', trim($dateTmp[1])))->format('Y-m-d');
        }

        $view = 'admin.report.index';
        $urlAffix = 'GetIncomeTotal';
        if($type == 'outcome') {
            $urlAffix = 'GetOutcomeTotal';
            $view = 'admin.report.outcome.index';
        }

        try {
            $result = HttpHelper::getInstance()->post("Report/$urlAffix", [
                "FilterType" => (int)$request->get('FilterType', 5),
                "FromDate" => $startDate,
                "ToDate" => $endDate,
                "SearchPattern" => $searchPattern?:'',
                "OrderSearch" => $orderSearch?:0,
                "CustomerInfoSearch" => $customerInfoSearch?:'',
                "DesctiptionSearch" => $descriptionSearch?:'',
                "LocationId" => $locationId?:'',
                "PageSize" => env('PER_PAGE', 20),
                "PageIndex" => $page > 0 ? $page - 1 : $page
            ]);
// dd($result->data);
            $location = getLocationList();
            $TotalMoney = $result->Extra->TotalMoney;
            $data = new LengthAwarePaginator($result->data, $result->paging->TotalCount, $result->paging->PageSize, $page,
                ['path' => Paginator::resolveCurrentPath(), 'lastPage' => $result->paging->TotalPage]
            );
            
            //if($data->lastPage() == $data->currentPage())
            {
                view()->share('totalMoney', $data->sum('TotalMoney'));
            }
        } catch (ClientException $exception) {
            logger()->critical($exception->getMessage(), $this->headers);
        }

        return view($view, compact('data', 'TotalMoney', 'dates', 'locationId',
            'searchPattern', 'orderSearch', 'customerInfoSearch', 'descriptionSearch', 'location'
        ));
    }
}
