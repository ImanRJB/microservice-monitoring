<?php

namespace Milyoona\MicroserviceMonitor\Http\Controllers;

use GuzzleHttp\Client;
use Milyoona\MicroserviceMonitor\Model\Microservice;

class MicroserviceController
{
    public function index()
    {
        return Microservice::all();
    }

    public function microserviceStatus($microservice)
    {
        try {
            $microservice = Microservice::whereName($microservice)->first();

            $client = new Client();
            $url = $microservice->hostname . ':' . $microservice->port . '/microservice/' . $microservice->name . '/available';
            $response = $client->get($url);


            return response($response->getBody(), 200);
        } catch (\Exception $exception) {
            return response('', 500);
        }
    }
}
