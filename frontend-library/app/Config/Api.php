<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Api extends BaseConfig
{
    public $baseURL = 'http://localhost:8000/api/';
    public $timeout = 30;
    
    // Headers untuk API request
    public $headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json'
    ];
}