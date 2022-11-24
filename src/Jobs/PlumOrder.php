<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Xoxoday\Plumapi\Model\xoplum_api_credential;
use Xoxoday\Plumapi\Model\xoplum_order;

class PlumOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $request_data = array();
    private $request_id;

    //Request ID id use to fetch the data from DB table
    public function __construct($request_id)
    {
        $this->request_id = $request_id;
        $this->request_data = xoplum_order::where(['id' => $request_id])->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request_data = $this->request_data;
        
        if ($request_data) {
            $response = $this->createXoxoOrder($request_data['mobile'], $request_data['amount'], $request_data['refrence_id'], $request_data['prefix'], $request_data['product_id'],$request_data['quantity']);

            if (!isset($response['data']['placeOrder']['status']) || $response['data']['placeOrder']['status'] != 1) {
                xoplum_order::where('id', $this->request_id)->update([
                    'plum_response' => json_encode($response),
                    'status' => 2,
                ]);
            }
        }
    }
    //generateXoxoAccessToken returns access token
    public function generateXoxoAccessToken()
    {
        $credentials_id = Config('app.plum_pro_creds_id');
        try {
            $xoplum_credentials = xoplum_api_credential::where(['key' => 'refresh_token'])->first();
            $refresh_token = $xoplum_credentials['value'];
        } catch (QueryException $ex) {
            return false;
        }

        if ($xoplum_credentials['updated_at'] == '' || $xoplum_credentials['updated_at'] == null) {
            $expiry_date = date('d', strtotime('now'));
        } else {
            $expiry_date = date('d', strtotime($xoplum_credentials['updated_at'] . ' + 12 days'));
        }

        $today_date = date('d', strtotime('now'));

        //checking if refresh token is near to expiry date or not,if yes then updating the same.
        if ($expiry_date > $today_date) {
            try {
                $access_token = xoplum_api_credential::where(['key' => 'access_token'])->first();
            } catch (QueryException $ex) {
                return false;
            }
            return $access_token['value'];
        } else {
            $data = array(
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
                'client_id' => !empty(Config('xoplum.xoplum_client_id')) ? Config('xoplum.xoplum_client_id') : '',
                'client_secret' => !empty(Config('xoplum.xoplum_client_secret')) ? Config('xoplum.xoplum_client_secret') : '',
            );

            //Setting the API url
            if ((!empty(Config('xoplum.xoplum_env')) ? Config('xoplum.xoplum_env') : '') == 'sandbox') {
                $url = !empty(Config('xoplum.xoplum_sandbox_url')) ? Config('xoplum.xoplum_sandbox_url') : 'https://stagingaccount.xoxoday.com/chef/v1/';
            } else if ((!empty(Config('xoplum.xoplum_env')) ? Config('xoplum.xoplum_env') : '') == 'production') {
                $url = !empty(Config('xoplum.xoplum_production_url')) ? Config('xoplum.xoplum_production_url') : 'https://accounts.xoxoday.com/chef/v1/';
            }

            $url = $url . 'oauth/token/access_token';

            $response = Http::withHeaders([
                'Content-type' => 'application/json',
            ])->post($url, $data);

            if ($response->status() == 200) {
                $result = json_decode(json_encode($response->object()), true);
                if (isset($result) && !empty($result) && isset($result['access_token']) && $result['access_token'] != '' && isset($result['refresh_token']) && $result['refresh_token'] != '') {
                    try {
                        xoplum_api_credential::where('key', 'refresh_token')->update(['value' => $result['refresh_token']]); //updating refresh
                        $checkd = xoplum_api_credential::where('key', 'access_token')->update(['value' => $result['access_token']]); //updating access token

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
    public function createXoxoOrder($mobile, $amount, $po_number, $mobile_prefix, $product_id,$quantity)
    {

        $accesstoken = $this->generateXoxoAccessToken();

        if ($accesstoken) {
            $data = array(
                "query" => "plumProAPI.mutation.placeOrder",
                "tag" => "plumProAPI",
                "variables" => array('data' => array(
                    "productId" => $product_id,
                    "quantity" => $quantity,
                    "denomination" => $amount,
                    "contact" => $mobile_prefix . '-' . $mobile,
                    "poNumber" => $po_number,
                    "notifyAdminEmail" => 0,
                    "notifyReceiverEmail" => 0,
                )),
            );

            $credentials_id = Config('app.plum_pro_creds_id');

            //Setting the API url
            if ((!empty(Config('xoplum.xoplum_env')) ? Config('xoplum.xoplum_env') : '') == 'sandbox') {
                $url = !empty(Config('xoplum.xoplum_sandbox_url')) ? Config('xoplum.xoplum_sandbox_url') : 'https://stagingaccount.xoxoday.com/chef/v1/';
            } else if ((!empty(Config('xoplum.xoplum_env')) ? Config('xoplum.xoplum_env') : '') == 'production') {
                $url = !empty(Config('xoplum.xoplum_production_url')) ? Config('xoplum.xoplum_production_url') : 'https://accounts.xoxoday.com/chef/v1/';
            }

            $url = $url . 'oauth/api';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accesstoken,
                'Content-type' => 'application/json',
            ])->post($url, $data);

            $result = json_decode(json_encode($response->object()), true);

            if ($response->status() == 200) {
                if (isset($result['data']['placeOrder']['status']) && $result['data']['placeOrder']['status'] == 1) {
                    xoplum_order::where('id', $this->request_id)->update([
                        'plum_order_id' => $result['data']['placeOrder']['data']['orderId'],
                        'plum_response' => json_encode($result),
                        'status' => 1,
                    ]);
                    return $result;
                } else {
                    return $result;
                }
            }
            return $result;
        }
    }
}
