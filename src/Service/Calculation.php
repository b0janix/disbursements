<?php

namespace App\Service;

use App\Enums\IncomeEnum;

class Calculation
{
    public function calculateIncome(array $csvData): float
    {
        if (IncomeEnum::tryFrom($csvData['transaction-type']) && IncomeEnum::tryFrom($csvData['amount-description'])) {
            return $csvData['amount'];
        }

        return 0;
    }

    public function calculateExpenses(): float
    {
        //TODO
    }

    public function calculateTransfers(): float
    {
        //TODO
    }
}