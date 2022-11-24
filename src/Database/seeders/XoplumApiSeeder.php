<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Xoxoday\Plumapi\Model\xoplum_api_credential;

class XoplumApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $key_array = array('refresh_token','access_token');
        foreach($key_array as $key){
            $xoplum_api_credential = new xoplum_api_credential();
            $xoplum_api_credential->key = $key;
            $xoplum_api_credential->save();
        }
    }
}
