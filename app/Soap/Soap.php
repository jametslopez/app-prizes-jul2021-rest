<?php

namespace App\Soap;

use SoapClient;
use SoapFault;

class Soap
{
    /**
     * @var string
     */
    protected $wsdl;

    public function __construct(string $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function execute($method, string $result, array $parameters)
    {
        try {
            $soap = new SoapClient($this->wsdl);
            $data = $soap->$method($parameters);
            return [
                'status' => true,
                'message' => 'ok',
                'response' => $data->$result
            ];
        } catch (SoapFault $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'response' => null
            ];
        }
    }
}
