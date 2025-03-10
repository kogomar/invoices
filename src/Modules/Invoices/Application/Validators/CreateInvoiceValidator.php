<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Validators;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

final class CreateInvoiceValidator
{
    public function validate(array $data): Validator
    {
        return ValidatorFacade::make($data, [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);
    }
}
