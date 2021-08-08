<?php

namespace Milyoona\MicroserviceMonitor\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Microservice extends Model
{
    use HasFactory;

    protected $appends = ['status', 'consumer_logs', 'models', 'models_updated', 'models_deleted'];

    public function getStatusAttribute()
    {
        return 500;
    }

    public function getConsumerLogsAttribute()
    {
        return '--';
    }

    public function getModelsAttribute()
    {
        return '--';
    }

    public function getModelsUpdatedAttribute()
    {
        return '--';
    }

    public function getModelsDeletedAttribute()
    {
        return '--';
    }
}
