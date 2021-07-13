<?php

namespace App\Helpers;

use App\Models\Campaign;

class Helper
{
    private $now;

    public function __construct($global)
    {
    }

    public static function handlerError($code)
    {
        $response = [
            '100' => [
                "status" => false,
                "title" => "Oops",
                "message" => "Unauthorized",
            ],
            '101' => [
                "status" => false,
                "title" => "Oops",
                "message" => "Invalid Campaign Id",
            ],
            '102' => [
                "status" => false,
                "title" => "Oops",
                "message" => "Unauthorized",
            ],
            '103' => [
                "status" => false,
                "title" => "Oops",
                "message" => "Unauthorized",
            ],
        ];
        return $response[$code];
    }

    public static function validateAuthorization($campaignId, $request)
    {
        $authorization = $request->header('X-Authorization-APP');

        if (!isset($authorization) || !$authorization) {
            return Helper::handlerError('100');
        } elseif (!$campaignId || !is_numeric($campaignId)) {
            return Helper::handlerError('101');
        } else {
            $campaign = Campaign::where("api_key", $authorization)->first();
            if (!$campaign) {
                return Helper::handlerError('100');
            }
        }
        return true;
    }
}
