<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Milyoona\MicroserviceMonitor\Model\Microservice;

class MicroserviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $microservices = [
            ['name' => 'ml_auth', 'hostname' => 'http://ml_auth', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_user', 'hostname' => 'http://ml_user', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_dashboard', 'hostname' => 'http://ml_dashboard', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_excel', 'hostname' => 'http://ml_excel', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_discount', 'hostname' => 'http://ml_discount', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_product', 'hostname' => 'http://ml_product', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_backup', 'hostname' => 'http://ml_backup', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_withdraw', 'hostname' => 'http://ml_backup', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_transaction', 'hostname' => 'http://ml_withdraw', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_notification', 'hostname' => 'http://ml_notification', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_terminal', 'hostname' => 'http://ml_terminal', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_iban', 'hostname' => 'http://ml_iban', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_ticket', 'hostname' => 'http://ml_ticket', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_balance', 'hostname' => 'http://ml_balance', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_home', 'hostname' => 'http://ml_home', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_ipg', 'hostname' => 'http://ml_ipg', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_fastpay', 'hostname' => 'http://ml_fastpay', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_withdrawfile', 'hostname' => 'http://ml_withdrawfile', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_tax', 'hostname' => 'http://ml_tax', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_shaparak', 'hostname' => 'http://ml_shaparak', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_irankish', 'hostname' => 'http://ml_irankish', 'port' => 80, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
        ];


        foreach ($microservices as $microservice) {
            $model = Microservice::whereName($microservice['name'])->first();
            if (!$model) {
                Microservice::create($microservice);
            } else {
                $model->update([
                    'hostname' => $microservice['hostname'],
                    'port' => $microservice['port']
                ]);
            }
        }

        foreach (Microservice::all() as $microservice) {
            $microservice->update([
                'base_models' => [],
                'all_models' => [],
                'updated_models' => [],
                'deleted_models' => [],
                'model_errors' => [],
            ]);
        }
    }
}