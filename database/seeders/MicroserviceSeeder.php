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
            ['name' => 'ml_auth', 'hostname' => 'http://localhost', 'port' => 10100, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_user', 'hostname' => 'http://localhost', 'port' => 10200, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_dashboard', 'hostname' => 'http://localhost', 'port' => 10500, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_excel', 'hostname' => 'http://localhost', 'port' => 11200, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_discount', 'hostname' => 'http://localhost', 'port' => 11000, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_product', 'hostname' => 'http://localhost', 'port' => 10900, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_backup', 'hostname' => 'http://localhost', 'port' => 11300, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_withdraw', 'hostname' => 'http://localhost', 'port' => 10800, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_transaction', 'hostname' => 'http://localhost', 'port' => 10700, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_notification', 'hostname' => 'http://localhost', 'port' => 10600, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_terminal', 'hostname' => 'http://localhost', 'port' => 10300, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_iban', 'hostname' => 'http://localhost', 'port' => 10400, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_balance', 'hostname' => 'http://localhost', 'port' => 8027, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_home', 'hostname' => 'http://localhost', 'port' => 8028, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_ipg', 'hostname' => 'http://localhost', 'port' => 8029, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_fastpay', 'hostname' => 'http://localhost', 'port' => 8030, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_withdrawfile', 'hostname' => 'http://localhost', 'port' => 8031, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_tax', 'hostname' => 'http://localhost', 'port' => 8032, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_shaparak', 'hostname' => 'http://localhost', 'port' => 8033, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
            ['name' => 'ml_irankish', 'hostname' => 'http://localhost', 'port' => 8034, 'consumer_logs' => 0, 'consumer_error_logs' => 0, 'status' => 0],
        ];


        foreach ($microservices as $microservice) {
            if (!Microservice::whereName($microservice['name'])->first()) {
                Microservice::create($microservice);
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
