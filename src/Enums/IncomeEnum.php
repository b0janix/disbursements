<?php

namespace App\Enums;

enum IncomeEnum: string
{
    case TRANSACTION_TYPE = 'Order';
    case AMOUNT_DESCRIPTION = 'Principal';
}
