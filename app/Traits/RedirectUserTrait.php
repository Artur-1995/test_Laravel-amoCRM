<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

/**
 * Трейд для получения имения апрашиваемого маршрута
 */
trait RedirectUserTrait
{
    /**
     * Метод возвращающий название запрашиваемого роута
     * 
     * @return RedirectResponse запрашиваемый маршрут
     */
    public function redirectUri(): RedirectResponse
    {
        Cache::forget('uri');
        return redirect()->route($this->uri);
    }
}