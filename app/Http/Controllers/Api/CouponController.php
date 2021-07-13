<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Soap\Soap;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function search(Request $request)
    {
        $code = $request->request->get('code');
        return $this->getSoapData('search', 'searchResult', ['code' => $code]);
    }

    public function use(Request $request)
    {
        $code = $request->request->get('code');
        return $this->getSoapData('use', 'useResult', ['code' => $code]);
    }

    /**
     * @param string $method
     * @param string $result
     * @param array $data
     * @return array
     */
    protected function getSoapData(string $method, string $result, array $data): array
    {
        $soap = new Soap(config('soap.wsdl_service_coupons'));
        return $soap->execute($method, $result, $data);
    }
}
