<?php

namespace Xoxoday\Plumapi;

use App\Jobs\PlumOrder;
use  Xoxoday\Plumapi\Model\PlumApiCredential;
use Illuminate\Support\Facades\Http;
use Config;
use Xoxoday\Plumapi\Model\xoplum_order;

class Xoxo
{
    private $client_id = '';
    private $secret_key = '';
    private $refresh_token = '';
    private $url = '';
    private $env = '';


    public function createOrder($data)
    {
        if ($data) {
            $check_refrence_id  = xoplum_order::where(['reference_id' => $data['reference_id']])->first();
            if (!$check_refrence_id) {
                $xoplum_order = new xoplum_order();
                $xoplum_order->name = $data['name'];
                $xoplum_order->prefix = $data['prefix'];
                $xoplum_order->mobile = $data['mobile'];
                $xoplum_order->amount = $data['amount'];
                $xoplum_order->product_id = $data['product_id'];
                $xoplum_order->quantity = $data['quantity'];
                $xoplum_order->reference_id = $data['reference_id'];
                $xoplum_order->status = 0;
                if ($xoplum_order->save()) {
                    dispatch(new PlumOrder($xoplum_order->id));
                }
            }
        }
        return false;
    }
}
