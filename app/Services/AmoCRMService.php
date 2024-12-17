<?php

/**
 * Сервис для взаимодейвствия с AmoCRM
 * 
 * PHP version 7.4.33
 * 
 * @author Avetisyan Artur <89254423508@mail.ru>
 * 
 * @version GIT: 
 */

namespace App\Services;

use ArrayAccess;
use App\Traits\AmoCRMTrait;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\BaseApiModel;
use Illuminate\Support\Facades\Log;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;

class AmoCRMService
{
    use AmoCRMTrait;

    /**
     * Загруска списка контактов из сервиса AmoCRM
     *
     * Метод получает коллекцию сделок и список контактов из AmoCRM
     * 
     * @throws AmoCRMApiNoContentException Данные отсутствуют
     * 
     * @return ?BaseApiModel
     */
    public function getLead($leadId): ?BaseApiModel
    {
        try {
            return $this->apiClient->leads()->getOne($leadId, [LeadModel::CONTACTS]);
        } catch (AmoCRMApiNoContentException $e) {
            Log::info('get_contacts_collection_error', [$e->getCode() => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Загруска списка контактов из сервиса AmoCRM
     *
     * Метод получает коллекцию сделок и список контактов из AmoCRM
     * 
     * @throws AmoCRMApiNoContentException Данные отсутствуют
     * 
     * @return 
     */
    public function getContact($contactId): ?BaseApiModel
    {
        try {
            return $this->apiClient->contacts()->getOne($contactId);
        } catch (AmoCRMApiNoContentException $e) {
            Log::info('get_contacts_collection_error', [$e->getCode() => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Получение списка контактов из сервиса AmoCRM
     *
     * Метод получает коллекцию сделок и список контактов из AmoCRM
     * 
     * @throws AmoCRMApiNoContentException Данные отсутствуют
     * 
     * @return ArrayAccess|array $contactsCollection коллекция контактов или
     * пустой массив
     */
    public function getContactsCollection()
    {
        try {
            $contacts = $this->apiClient->contacts();
            if ($contacts->get() instanceof ArrayAccess) {
                $contactsCollection = $contacts->get();
            }

            return $contactsCollection;
        } catch (AmoCRMApiNoContentException $e) {
            Log::info('get_contacts_collection_error', [$e->getCode() => $e->getMessage()]);
        }

        return [];
    }

    /**
     * Получение списка сделок из сервиса AmoCRM
     *
     * Метод получает коллекцию всех сделок из AmoCRM
     * 
     * @param Request $request результат выполнения привязки контактов
     * 
     * @throws AmoCRMApiNoContentException Данные отсутствуют
     * 
     * @return ArrayAccess|array $leadsCollection коллекция сделок 
     * или пустой массив
     */
    public function getLeadsCollection()
    {
        try {
            $leadsCollection = $this->apiClient->leads()->get(null, ['contacts']);

            return $leadsCollection;
        } catch (AmoCRMApiNoContentException $e) {
            Log::info('get_leads_collection_error', [$e->getCode() => $e->getMessage()]);
        }

        return [];
    }
}
