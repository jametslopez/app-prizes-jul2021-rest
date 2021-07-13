<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\Helper;
use App\Models\Campaign;

class CampaignController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setting($id, Request $request)
    {
        $authorization = Helper::validateAuthorization($id, $request);
        if ($authorization !== true) {
            return $authorization;
        }

        $response = ["status" => false, "message" => '', "data" => ''];

        $campaign = Campaign::find($id);
        
        if ($campaign) {
            $response['status'] = true;
            $response['data'] = $campaign;
        }
    
        return $response;
    }
}
