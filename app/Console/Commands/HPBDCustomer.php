<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\HappyBirthdayCustomerMail;
use App\Helpers\HttpHelper;
use Illuminate\Support\Facades\Auth;

class HPBDCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hpbd:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command HPBD customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $credentials = ['username' => 'admin', 'password' => "1", 'tenantCode' => "hosco"];
        $login = Auth::attempt($credentials);

        // $today = "20-09";
        $today = date("d-m");
        $params = [
            "SearchText" => "",
            "Email" => "",
            "ObjectGroupId" => "",
            "ObjectType" => 0,
            "Status" => 0,
            "PageSize" => 20,
            "PageIndex" => 0
        ];

        $result = HttpHelper::getInstance()->post("Category/CustomerList", $params);
        $params['PageSize'] = $result->paging->TotalCount;
        $result = HttpHelper::getInstance()->post("Category/CustomerList", $params);
        foreach ($result->data as $customer) {
            $birthday = date("d-m", strtotime($customer->BirthDay));
            if ($customer->BirthDay != "" && $birthday == $today) {
                if ($customer->Email != "") {
                    Mail::to($customer->Email)->send(new HappyBirthdayCustomerMail($customer));
                }
            }
        }
        \Log::info("Cron HPBD is working fine!");
        $this->info('hpbd:cron Run successfully!');
    }
}
