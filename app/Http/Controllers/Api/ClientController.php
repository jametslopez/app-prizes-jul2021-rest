<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Soap\Soap;
use Illuminate\Http\Request;

use App\Models\Client;

use App\Helpers\Helper;

class ClientController extends Controller
{
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

                $toSoap = [
                    'email' => $client->email,
                    'name' => $client->firstname,
                ];

                $this->getSoapData('sendWelcomeMail', 'sendWelcomeMailResult', $toSoap);
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

    public function update(Request $request, Client $client)
    {
        $response = ['success' => true];
        $client->firstname = $request->firstname;
        $client->lastname = $request->lastname;
        $client->email = $request->email;
        $client->mobile = $request->mobile;
        $client->sendemail = $request->sendemail;
        $client->save();
        return $response;
    }

    protected function getSoapData(string $method, string $result, array $data)
    {
        $soap = new Soap(config('soap.wsdl_service_mail'));
        $soap->execute($method, $result, $data);
    }
}
