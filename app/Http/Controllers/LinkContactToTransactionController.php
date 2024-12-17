<?php

/**
 * Контроллер для подключения контакта к сделке
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 * 
 * @version GIT: 
 * 
 * @link [https://github.com/amocrm/amocrm-api-php/blob/master/README.md#поддерживаемые-методы-и-сервисы]
 * [Описание работы с методами библиотеки]
 */

namespace App\Http\Controllers;

use App\Http\Requests\LinkRequest;
use Exception;
use Illuminate\Http\RedirectResponse;

/**
 * Класс для подключения контакта к сделке
 *
 * @throws Exception Ошибка при подключении контакта к сделке
 */
class LinkContactToTransactionController extends BaseAmoController
{
    /**
     * Метод подключения контакта к сделке
     * 
     * Получение сущности контакта и сделки с последующей линковкой
     *
     * @param LinkRequest $request запрос содержащий id сделки и id контакта
     * 
     * @throws Exception Ошибка при подключении контакта к сделке
     * 
     * @return RedirectResponse возврат на страницу со статусом выполнения
     */
    public function __invoke(LinkRequest $request): RedirectResponse
    {
        $request->validated();

        $contactId = $request->id;
        $contact = $this->service->getContact($contactId);

        $links = $this->linkService->action($contact);
        $status = $this->linkService->getStatus($request, $links);

        if ($status == 'Готово') {
            $this->actionService->create($contact, $status);
        }
            
        return back()->with('status', $status);
    }
}
