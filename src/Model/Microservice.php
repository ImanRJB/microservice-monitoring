<?php

namespace Milyoona\MicroserviceMonitor\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Microservice extends Model
{
    use HasFactory;

    protected $fillable = ['all_models', 'deleted_models', 'updated_models', 'consumer_logs', 'status'];

    protected $casts = [
        'all_models' => 'json',
        'deleted_models' => 'json',
        'updated_models' => 'json',
    ];
}
