<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $customer, array $order)
    {
        $this->customer = $customer;
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $template = EmailTemplate::where('title', 'Status Update')->first();
        // file_put_contents('../resources/views/order-success.blade.php', $template->html_template);

        //         $content = "<p>Xin chào ". $this->customer['Name'] ."!</p>
        // <p>Đơn hàng #". $this->order['OrderCode']  ." của bạn đã được đặt thành công ngày ". date('d-m-Y', strtotime($this->order['OrderDate'])) ."</p>
        // <p>Chi tiết đơn hàng:</p>
        // <p>- Mã đơn hàng: ". $this->order['OrderCode']  ."</p>
        // <p>- Ngày đặt: ". date('d-m-Y', strtotime($this->order['OrderDate'])) ."</p>
        // <p>- Email: ". $this->customer['Email'] ."</p>
        // <p>- Số điện thoại: ". $this->customer['Tel'] ."</p>
        // <p>- Địa chỉ nhận hàng: ". $this->customer['Address'] ."</p>
        // <p>- Thành tiền: ". number_format($this->order['OrderTotalDiscount'], 0, ',', '.') ." VNĐ</p>
        // <p>- Phương thức thanh toán: ". $this->order['PaymentMethod'] ."</p>";
        //         file_put_contents('../resources/views/emails/order-success1.blade.php', $content);

        //         return $this->subject('Bạn đã đặt hàng thành công!')->view('emails.order-success1');

        
        return $this->subject('Bạn đã đặt hàng thành công!')->view('emails.order-success');
    }
}
