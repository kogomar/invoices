<?php

declare(strict_types=1);

namespace Modules\Invoices\Application\Validators;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

class CreateInvoiceProductLineValidator
{
    public function validate(array $data): Validator
    {
        return ValidatorFacade::make($data, [
            'invoice_id' => 'required|uuid|exists:invoices,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);
    }
}
