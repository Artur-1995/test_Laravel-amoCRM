<?php

namespace App\Services;

use App\Models\Transactions;

class TransactionService
{
    public function create($leadsCollection): void
    {
        foreach ($leadsCollection as $lead) {
            Transactions::updateOrCreate([
                'amoCRM_id' => $lead->id,
            ],
            [
                'amoCRM_id' => $lead->id,
                'name' => $lead->name,
                'amoCRM_created_at' => $lead->getCreatedAt(),
                'contacts' => $lead->contacts ? true : false,
            ]);
        }
    }
}
