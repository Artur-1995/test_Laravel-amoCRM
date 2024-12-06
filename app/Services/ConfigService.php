<?php

namespace App\Services;

use Symfony\Component\Dotenv\Dotenv;

class ConfigService
{
    public $clientId;
    public $clientSecret;
    public $redirectUri;
    
    public function __construct()
    {
        $dotenv = new Dotenv();
        // server
        // $dotenv->load('/var/www/u2588309/data/www/amo/.env');
        // local
        $dotenv->load(base_path() . '/.env');

        $this->clientId = $_ENV['CLIENT_ID'];
        $this->clientSecret = $_ENV['CLIENT_SECRET'];
        $this->redirectUri = $_ENV['CLIENT_REDIRECT_URI'];
    }
}