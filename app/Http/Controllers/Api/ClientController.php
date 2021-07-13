<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Client;

use App\Helpers\Helper;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campaignId, Request $request)
    {
        $authorization = Helper::validateAuthorization($campaignId, $request);
        if ($authorization !== true) {
            return $authorization;
        }

        $response = ["status" => false, "message" => '', "data" => ''];

        $client = Client::where("campaign_id", $campaignId)
            ->where("email", $request->email)->first();

        if ($client) {
            $response['status'] = true;
            $response['data'] = $client;
        }

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($campaignId, Request $request)
    {
        $authorization = Helper::validateAuthorization($campaignId, $request);
        if ($authorization !== true) {
            return $authorization;
        }

        $response = ["status" => false, "message" => '', "data" => ''];

        $validator_fields = [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'address' => 'required'
        ];

        $valid = \Validator::make($request->all(), $validator_fields);

        if (!$valid->fails()) {

            $client = Client::where("campaign_id", $campaignId)
                        ->where("email", $request->email)->first();

            if (!$client) {
                $client = new Client();
                $client->campaign_id = $campaignId;
                $client->firstname = $request->firstname;
                $client->lastname = $request->lastname;
                $client->email = $request->email;
                $client->mobile = $request->mobile;
                $client->sendemail = $request->sendemail;
                $client->save();

                $response['status'] = true;
                $response['data'] = $client->toArray();
            } else {
                $response['status'] = true;
                $response['data'] = $client->toArray();
                $response['message'] = 'The client is already in the database.';    
            }
        } else {
            $response['status'] = false;
            $response['message'] = 'Please make sure you have entered all the information. (*)';
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //dd($client);
        $response = ['success' => true];
        $client->firstname = $request->firstname;
        $client->lastname = $request->lastname;
        $client->email = $request->email;
        $client->mobile = $request->mobile;
        $client->sendemail = $request->sendemail;
        $client->save();
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
