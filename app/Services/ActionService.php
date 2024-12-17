<?php

/**
 * Сервис для даботы с таблицей action
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 * 
 * @version GIT: 
 */

namespace App\Services;

use Exception;
use App\Models\Action;
use App\Http\Requests\LinkRequest;

class ActionService
{

    /**
     * Создание записи в таблице
     *
     * Метод добавляет запись в таблицу с историей изменений
     * 
     * @param LinkRequest $request id контакта и id сделки
     * 
     * @throws Exception Данные отсутствуют
     * 
     * @return void запись добавлена
     */
    public function create($contact, $status): void
    {
        $data = [
            'action' => 'Добавление контакта ' . $contact->name ?? null . ' (' . $contact->id ?? null . ')',
            'result' => $status,
        ];
        Action::updateOrCreate($data);
    }
}
