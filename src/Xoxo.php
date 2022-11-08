<?php 

namespace Xoxoday\Plumapi;


use  Xoxoday\Plumapi\Model\PlumApiCredential;
use Illuminate\Support\Facades\Http;
use Config;

class Xoxo
{
    private $client_id = '';
    private $secret_key = '';
    private $refresh_token = '';
    private $url = '';
    private $env = '';

    

    public function generateXoxoAccessToken()
    {
        $credentials_id = Config('app.plum_pro_creds_id');
        try {
            $credentials = PlumApiCredential::where('id',$credentials_id )->first();
        } catch (QueryException $ex) {
            return false;
        }

       
        $data = array(
            'grant_type' => 'refresh_token',
            'refresh_token' => $credentials['refresh_token'],
            'client_id' => $credentials['client_id'],
            'client_secret' => $credentials['client_secret'],
        );

       

        if($credentials_id == 1){
            $url = 'https://stagingaccount.xoxoday.com/chef/v1/';
        }else{
            $url = 'https://accounts.xoxoday.com/chef/v1/';
        }

        $url = $url . 'oauth/token/access_token';

        

        $response = Http::withHeaders([
            'Content-type' => 'application/json',
        ])->post($url, $data);
       
        if ($response->status() == 200) {
            $result = json_decode(json_encode($response->object()), true);
            if (isset($result) && !empty($result) && isset($result['access_token']) && $result['access_token'] != '' && isset($result['refresh_token']) && $result['refresh_token'] != '') {
                try {
                    $creds_update = PlumApiCredential::where('id', '1')->update(['access_token' => $result['access_token'], 'refresh_token' => $result['refresh_token']]); //updating refresh and access token in DB
                    return $result['access_token']; 
                } catch (QueryException $ex) {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Create XOXO Order
     *
     * @param String  $mobile       Customer Mobile Number
     * @param String  $amount       Amount which needs to be Transferred
     * @param String  $po_number    Local System PO Number
     * @param String  $mobile_prefix   Mobile Country Code
     * @param String  $product_id   PayTM Product ID
     * 
     * @return Object
     */
    public function createXoxoOrder($mobile, $amount, $po_number, $mobile_prefix = "+91", $product_id)
    {   

        $accesstoken = $this->generateXoxoAccessToken();

       
        if ($accesstoken) {
            $data = array(
                "query" => "plumProAPI.mutation.placeOrder",
                "tag" => "plumProAPI",
                "variables" => array('data' => array(
                    "productId" => $product_id,
                    "quantity" => 1,
                    "denomination" => $amount,
                    "contact" => $mobile_prefix . $mobile,
                    "poNumber" => $po_number,
                    "notifyAdminEmail" => 0,
                    "notifyReceiverEmail" => 0,
                )),
            );

            $credentials_id = Config('app.plum_pro_creds_id');

            if($credentials_id == 1){
                $url = 'https://stagingaccount.xoxoday.com/chef/v1/';
            }else{
                $url = 'https://accounts.xoxoday.com/chef/v1/';
            }

            $url = 'https://stagingaccount.xoxoday.com/chef/v1/';
            $url = $url . 'oauth/api';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accesstoken,
                'Content-type' => 'application/json',
            ])->post($url, $data);

            dd($response->object());


            $result = json_decode(json_encode($response->object()), true);

            if ($response->status() == 200) {
                if (isset($result['data']['placeOrder']['status']) && $result['data']['placeOrder']['status'] == 1) {
                    return $result;
                } 
            }
            return $result;
        }
    }
}