<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Soap\Soap;
use Illuminate\Http\Request;
use Validator;

class DniController extends Controller
{
    public function index(Request $request)
    {
        $validator = $this->dniValidator();
        if ($validator->fails()) {
            return [
                'status' => false,
                'message' => $validator->errors()->first(),
                'response' => [],
            ];
        }

        $dni = $request->request->get('dni');
        return $this->getSoapData($dni);
    }

    /**
     * @param int $dni
     * @return array
     */
    protected function getSoapData(int $dni): array
    {
        $soap = new Soap(config('soap.wsdl_service_dni'));
        $result = $soap->execute('search', 'searchResult', ['dni' => $dni]);
        if (!$result['status']) {
            $result['response'] = [];
        }
        $result['response'] = $this->processData($result['response']);
        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function processData(array $data): array
    {
        $person = [];
        foreach ($data as $item) {
            if (property_exists($item, 'key') and property_exists($item, 'value')) {
                $person[$item->key] = $item->value;
            }
        }
        return $person;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function dniValidator(): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make(request()->all(),
            [
                'dni' => ['required', 'digits:8']
            ],
            [
                'required' => 'No se ha enviado un número de dni',
                'digits' => 'El número de dni debe tener 8 caracteres',
            ],
            [
                'dni' => 'DNI'
            ]
        );
    }
}
