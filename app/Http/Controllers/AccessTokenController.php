<?php

/**
 * Контроллер для перенаправления пользователя
 * на запрашиваемую страницу при подучении токена
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 * 
 * @version GIT: 
 */

namespace App\Http\Controllers;

use App\Traits\AmoCRMTrait;
use App\Traits\RedirectUserTrait;
use Illuminate\Http\RedirectResponse;

/**
 * Класс вызывает метод для редиректа пользователя по необходимому адресу
 */
class AccessTokenController extends BaseAmoController
{
    use AmoCRMTrait, RedirectUserTrait;

    /**
     * Редиректа на запрашиваемую страницу
     *
     * @return RedirectResponse
     */
    public function __invoke(): RedirectResponse
    {
        return $this->redirectUri();
    }
}
