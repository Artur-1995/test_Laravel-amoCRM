<?php

/**
 * Интерфейс дейвствий пользователя в AmoCRM
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 * 
 * @version GIT: 
 */

namespace App\Services;

interface AmoServiseInterface
{

    /**
     * Метод выполняющий дейвствий в сервисе AmoCRM
     */
    public function action($args);

    /**
     * Метод для проверки дейвствий
     * 
     * @return string Результат дейвствия 'Готово' или 'Ошибка'
     */
    public function getStatus($args, $args1): string;
}