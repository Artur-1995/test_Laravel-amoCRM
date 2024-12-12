<?php

namespace App\Http\Controllers;

use App\Traits\AmoCRMTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    use AmoCRMTrait;
    
    public function getTransactions(Request $request)
    {
        try {
            $leadsCollection = $this->apiClient->leads()->get(null, ['contacts']);
            $contacts = $this->apiClient->contacts();
            $contactsCollection = $contacts->get();
        } catch (Exception $e) {
            Log::info('get_trnsaction_error', [$e->getCode() => $e->getMessage()]);
        }
        $status = $request->session()->get('status') ?? null;

        return view('transaction', compact('leadsCollection', 'contactsCollection', 'status'));
    }
}
