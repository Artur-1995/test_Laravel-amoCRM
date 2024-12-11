<?php

namespace App\Http\Controllers;

use App\Traits\AmoCRM;
use Illuminate\Http\RedirectResponse;

/**
 * Контроллер для получения токена и редиректа пользователя по запрашиваемой ссылке
 *
 * @return RedirectResponse $this->RedirectUri
 */
class AccessTokenController extends Controller
{
    use AmoCRM;
    
    /**
     * Метод для редиректа на запрашиваемую пользователем ссылку
     *
     * @return RedirectResponse redirectUri() метод для редиректа
     */
    public function __invoke(): RedirectResponse
    {        
        return $this->redirectUri();
    }
}
