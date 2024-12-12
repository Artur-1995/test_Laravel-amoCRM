<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Action;
use App\Traits\AmoCRMTrait;
use AmoCRM\Models\LeadModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use AmoCRM\Collections\LinksCollection;
use Illuminate\Http\RedirectResponse;

/**
 * Класс для подключения контакта к сделке
 *
 * @throws Exception Ошибка при подключении контакта к сделке
 */
class LinkContactToTransactionController extends Controller
{
    use AmoCRMTrait;

    /**
     * Класс для подключения контакта к сделке
     *
     * @throws Exception Ошибка при подключении контакта к сделке
     * @param Request $request запрос содержащий id сделки и id контакта
     * @return RedirectResponse возврат на страницу со статусом выполнения
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $contactId = $request->id;
        $leadId = $request->leadId;

        try {
            
            $lead = $this->apiClient->leads()->getOne($leadId, [LeadModel::CONTACTS]);
            $contact = $this->apiClient->contacts()->getOne($contactId);
            $contacts = $lead->contacts ? $lead->contacts->pluck('id') : [];
            $contactIds = $contacts;
            
            $links = new LinksCollection();
            $links->add($contact);
            $status = (!in_array($contactId, $contactIds) && $this->apiClient->leads()->link($lead, $links)) ? 'Готово' : 'Ошибка';
            $data = [
                'action' => 'Добавление контакта ' . $contact->name ?? null . ' (' . $contact->id ?? null . ')',
                'result' => $status ?? 'Готово',
            ];
            Action::create($data);
        } catch (Exception $e) {
            Log::info('error_add_action', [$e->getCode() => $e->getMessage()]);
        }

        return back()->with('status', $status ?? 'Ошибка');
    }
}
