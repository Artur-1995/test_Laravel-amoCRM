<?php

namespace App\Http\Controllers;

use AmoCRM\Collections\LinksCollection;
use AmoCRM\Models\LeadModel;
use App\Models\Action;
use App\Traits\AmoCRM;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LinkContactToTransactionController extends Controller
{
    use AmoCRM;

    public function __invoke(Request $request)
    {
        $contactId = $request->id;
        $leadId = $request->leadId;

        try {
            $lead = $this->apiClient->leads()->getOne($leadId, [LeadModel::CONTACTS]);
            $contact = $this->apiClient->contacts()->getOne($contactId);
            $contacts = $lead->contacts ? $lead->contacts->pluck('id') : [];
            $contactIds = $contacts;

            $status = !in_array($contactId, $contactIds) ? 'Готово' : 'Ошибка';
            $data = [
                'action' => 'Добавление контакта ' . $contact->name ?? null . ' (' . $contact->id ?? null . ')',
                'result' => $status ?? 'Готово',
            ];
            Action::create($data);
            
            $links = new LinksCollection();
            $links->add($contact);
            $this->apiClient->leads()->link($lead, $links);
        } catch (Exception $e) {
            Log::info('error_add_action', [$e->getCode() => $e->getMessage()]);
            return $e->getMessage();
        }

        return back()->with('status', $status);
    }
}
