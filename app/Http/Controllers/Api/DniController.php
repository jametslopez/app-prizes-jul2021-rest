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
        $status = true;
        $message = 'ok';
        $data = [];

        $validator = $this->dniValidator();
        if ($validator->fails()) {
            $status = false;
            $message = $validator->errors()->first();
        } else {
            $dni = $request->request->get('dni');
            $data = $this->getSoapData($dni);

            if (count($data) < 1) {
                $status = false;
                $message = 'Se produjo un error.';
            }
        }

        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * @param int $dni
     * @return array
     */
    protected function getSoapData(int $dni): array
    {
        $soap = new Soap('https://soap.dsd-is213-2101-e42b.ml/dni?wsdl');
        $result = $soap->execute('search', 'searchResult', ['dni' => $dni]);
        return $this->processData($result);
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
