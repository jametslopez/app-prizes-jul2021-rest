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
        $name = $request->request->get('firstname', 'Raul');
        $email = $request->request->get('email', 'u201603794@gmail.com');

        $toSoap = [
            'code' => $code,
            'name' => $name,
            'email' => $email,
        ];

        $result = $this->getSoapData('use', 'useResult', ['code' => $code]);
        if ($result['response']) {
            $this->sendMail('sendCongratsEmail', 'sendCongratsEmailResult', $toSoap);
        }

        return $result;
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
        $result = $soap->execute($method, $result, $data);
        return $this->processData($result);
    }

    protected function processData(array $data): array
    {
        if (!$data['response']) {
            $data['response'] = false;
        }
        return $data;
    }

    protected function sendMail(string $method, string $result, array $data)
    {
        $soap = new Soap(config('soap.wsdl_service_mail'));
        $soap->execute($method, $result, $data);
    }
}
