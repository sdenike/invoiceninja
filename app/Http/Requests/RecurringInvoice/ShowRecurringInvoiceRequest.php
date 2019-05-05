<?php

namespace App\Http\Requests\RecurringInvoice;

use App\Http\Requests\Request;
use App\Models\RecurringInvoice;

class ShowRecurringInvoiceRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize() : bool
    {
        return auth()->user()->can('view', $this->recurring_invoice);
    }

}