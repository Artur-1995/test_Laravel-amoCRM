<?php

namespace App\Http\Controllers;

use App\Traits\AmoCRM;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Token\AccessTokenInterface;

class AccessTokenController extends Controller
{
    use AmoCRM;
    
    public function __invoke()
    {
        $status = $this->getToken() ? 'Токен успешно получен!' :  'Ошибка получения токена!';
        
        return $this->uri ? $this->RedirectUri() : $status;
    }
}
