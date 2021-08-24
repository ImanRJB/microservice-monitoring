<?php

namespace Milyoona\MicroserviceMonitor\Http\Controllers;

use GuzzleHttp\Client;
use Milyoona\MicroserviceMonitor\Model\Microservice;
use phpDocumentor\Reflection\Types\Self_;

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

            $microservice['supervisor'] = self::getSupervisorInfo($microservice['name']);
        }

        return response(['microservices' => $microservices, 'models' => $models], 200);
    }


    public function supervisorStart($microservice)
    {
        try {
            $supervisor = self::getSupervisor();
            $supervisor->startProcess($microservice, false);
            return response(['message' => 'سرویس با موفقیت فعال شد', 'status' => 200]);
        } catch (\Exception $exception) {
            if ($exception->getMessage() == 'ALREADY_STARTED: ' . $microservice) {
                return response(['message' => 'سرویس در حال حاضر فعال می باشد', 'status' => 200]);
            }
            return response(['message' => 'خطای ناشناس', 'status' => 500]);
        }
    }


    public function supervisorRestart($microservice)
    {
        try {
            $supervisor = self::getSupervisor();
            $supervisor->stopProcess($microservice, false);
            sleep(2);
            $supervisor->startProcess($microservice, false);
            return response(['message' => 'سرویس با موفقیت ریست شد', 'status' => 200]);
        } catch (\Exception $exception) {
            if ($exception->getMessage() == 'NOT_RUNNING: ' . $microservice) {
                return response(['message' => 'سرویس در حال حاضر فعال نمی باشد', 'status' => 500]);
            }
            return response(['message' => 'خطای ناشناس', 'status' => 500]);
        }
    }


    public function supervisorStop($microservice)
    {
        try {
            $supervisor = self::getSupervisor();
            $supervisor->stopProcess($microservice, false);
            return response(['message' => 'سرویس با موفقیت غیرفعال شد', 'status' => 200]);
        } catch (\Exception $exception) {
            if ($exception->getMessage() == 'NOT_RUNNING: ' . $microservice) {
                return response(['message' => 'سرویس در حال حاضر فعال نمی باشد', 'status' => 500]);
            }
            return response(['message' => 'خطای ناشناس', 'status' => 500]);
        }
    }


    private function getSupervisor() {
        $guzzleClient = new \GuzzleHttp\Client([
            'auth' => [
                config('microservice-monitor.supervisor_username'),
                config('microservice-monitor.supervisor_password')
            ],
        ]);

        $client = new \fXmlRpc\Client(
            config('microservice-monitor.supervisor_url'),
            new \fXmlRpc\Transport\HttpAdapterTransport(
                new \Http\Message\MessageFactory\GuzzleMessageFactory(),
                new \Http\Adapter\Guzzle7\Client($guzzleClient)
            )
        );

        return new \Supervisor\Supervisor($client);
    }


    private function getSupervisorInfo($microservice) {
        try {
            $supervisor = self::getSupervisor();
            $process = $supervisor->getProcess($microservice);
            return $process->getPayload();
        } catch (\Exception $exception) {
            return ['statename' => 'NOT FOUND'];
        }
    }
}
