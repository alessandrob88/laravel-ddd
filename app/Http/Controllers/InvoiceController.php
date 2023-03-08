<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller {

    public function handle(InvoiceRequest $request) {       
        return response()->json([''], 200);
    }
}
