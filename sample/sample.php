<?php

namespace App\Http\Controllers;

use Xoxoday\Plumapi\Xoxo;

class TestController extends Controller
{
    public function createOrder()
    {
        //Pass the data accordingly in the $data array
        $data = array('name' => '', 'prefix' => '', 'mobile' => '', 'reference_id' => '', 'amount' => '');

        //Creates an object of Xoxo class
        $create_order = new Xoxo();

        $order_id = $create_order->createOrder($data);
    }
}
