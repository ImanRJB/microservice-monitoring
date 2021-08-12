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
                    'base_models' => $response->base_models,
                    'all_models' => $response->all_models,
                    'deleted_models' => $response->deleted_models,
                    'updated_models' => $response->updated_models,
                    'consumer_logs' => $response->consumer_logs,
                    'consumer_error_logs' => $response->consumer_error_logs,
                    'status' => 1
                ]);
            } catch (\Exception $exception) {
                $microservice->update([
                    'status' => 0
                ]);
            }
        }

        foreach ($microservices as $microservice) {
            $errors = [];
            foreach ($microservice->all_models as $name => $value) {
                $owner = Microservice::whereJsonContains('base_models', $name)->first();
                if ($owner) {
                    $errors['all_models'][$name] = $owner->all_models[$name] - $value;
                } else {
                    $errors['all_models'][$name] = 'دیتابیس اصلی یافت نشد';
                }
            }

            foreach ($microservice->updated_models as $name => $value) {
                $owner = Microservice::whereJsonContains('base_models', $name)->first();
                if ($owner) {
                    $errors['updated_models'][$name] = $owner->updated_models[$name] - $value;
                } else {
                    $errors['updated_models'][$name] = 'دیتابیس اصلی یافت نشد';
                }
            }

            foreach ($microservice->deleted_models as $name => $value) {
                $owner = Microservice::whereJsonContains('base_models', $name)->first();
                if ($owner) {
                    $errors['deleted_models'][$name] = $owner->deleted_models[$name] - $value;
                } else {
                    $errors['deleted_models'][$name] = 'دیتابیس اصلی یافت نشد';
                }
            }

            $microservice->update(['model_errors' => $errors]);
        }

        return response(['microservices' => $microservices, 'models' => $models], 200);
    }
}
