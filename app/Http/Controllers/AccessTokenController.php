<?php

namespace App\Http\Controllers;

use App\Traits\AmoCRMTrait;
use App\Traits\RedirectUserTrait;
use Illuminate\Http\RedirectResponse;

/**
 * Контроллер для получения токена и перенаправления пользователя
 * на запрашиваемую страницу
 *
 * @category
 * @package
 * @author
 * @license
 * @link
 * @return RedirectResponse редирект на запрашиваемую страницу
 */
class AccessTokenController extends Controller
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
