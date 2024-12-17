<?php

/**
 * Сервис для линковки сущностей
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 * 
 * @version GIT: 
 */
namespace App\Services;

use Exception;
use ArrayAccess;
use App\Http\Requests\LinkRequest;
use Illuminate\Support\Facades\Log;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;

class LinkService extends AmoCRMService implements AmoServiseInterface
{
    /**
     * Подключение контакта к сделке
     *
     * Метод добавляет связь между сущностями сервиса AmoCRM
     * 
     * @param LinkRequest $request id контакта и id сделки
     * 
     * @throws Exception Данные отсутствуют
     * 
     * @return ArrayAccess Коллекция модели подключения контакта
     */
    public function action($contact): ArrayAccess
    {
        try {
            $links = new LinksCollection();
            $links->add($contact);
        } catch (Exception $e) {
            Log::info('error_add_action', [$e->getCode() => $e->getMessage()]);
        }

        return $links;
    }

    /**
     * Загруска списка контактов из сервиса AmoCRM
     *
     * Метод получает коллекцию сделок и список контактов из AmoCRM
     * 
     * @throws AmoCRMApiNoContentException Данные отсутствуют
     * 
     * @return string $status Результат дейвствия 'Готово' или 'Ошибка'
     */
    public function getStatus($request, $links): string
    {
        try {
            $contactId = $request->id;
            $leadId = $request->leadId;
            $lead = $this->getLead($leadId);
            $contacts = $lead->contacts ? $lead->contacts->pluck('id') : [];
            $contactIds = $contacts;
            $status = (!in_array($contactId, $contactIds) && $this->apiClient->leads()->link($lead, $links)) ? 'Готово' : 'Ошибка';

            return $status;
        } catch (AmoCRMApiNoContentException $e) {
            Log::info('get_contacts_collection_error', [$e->getCode() => $e->getMessage()]);
        }

        return $status ?? 'Ошибка';
    }
}
