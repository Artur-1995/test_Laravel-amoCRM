<?php

/**
 * Контроллер для вывода таблицы со сделаком сделок из AmoCRM
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

use Exception;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Класс получает данные для отображения таблицы со сделками из сервиса AmoCRM
 */
class TransactionController extends BaseAmoController
{
    /**
     * Метод получает все данные для таблицы сделок
     *
     * Метод получает коллекцию сделок и список контактов из AmoCRM
     * 
     * @param Request $request результат выполнения привязки контактов
     * 
     * @throws Exception Данные отсутствуют
     * 
     * @return View 'transaction' страница с таблицей сделок
     */
    public function __invoke(Request $request): View
    {
        try {
            $leadsCollection = $this->service->getLeadsCollection();
            $contactsCollection = $this->service->getContactsCollection();
        } catch (Exception $e) {
            Log::info('get_trnsaction_error', [$e->getCode() => $e->getMessage()]);
        }
        $this->trnsactionService->create($leadsCollection);
        $status = $request->session()->get('status') ?? null;

        return view(
            'transaction',
            compact(
                'leadsCollection',
                'contactsCollection',
                'status'
            )
        );
    }
}
