<?php

namespace Milyoona\MicroserviceMonitor\Http\Controllers;

use GuzzleHttp\Client;
use Milyoona\MicroserviceMonitor\Model\Microservice;

class MicroserviceController
{
    public function index()
    {
        $models = getAppModels();
        $models = array_diff($models, ["Admin", "Role", "Microservice"]);
        $microservices = Microservice::all();

        foreach ($microservices as $microservice) {
            try {
                $client = new Client();
                $url = $microservice->hostname . ':' . $microservice->port . '/microservice/' . $microservice->name . '/status';
                $response = $client->get($url);
                $response = json_decode($response->getBody());

                $microservice->update([
                    'all_models' => $response->all_models,
                    'deleted_models' => $response->deleted_models,
                    'updated_models' => $response->updated_models,
                    'consumer_logs' => $response->consumer_logs,
                    'status' => 1
                ]);
            } catch (\Exception $exception) {
                $microservice->update([
                    'status' => 0
                ]);
            }
        }

        return response(['microservices' => $microservices, 'models' => $models], 200);
    }
}
