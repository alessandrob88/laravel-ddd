<?php

namespace App\Http\Controllers;

use App\Events\{InvoiceCancel, InvoiceCreate, InvoiceRowCreate, InvoiceRowUpdate};
use App\Http\Requests\InvoiceRequest;
use Illuminate\Http\Response;

class InvoiceController extends Controller {

    public function handle(InvoiceRequest $request) {  
        $data = $request->json('data');
        $event = (match ($data['event']) {
            'invoice-create' => InvoiceCreate::class,
            'invoice-cancel' => InvoiceCancel::class,
            'invoice-row-create' => InvoiceRowCreate::class,
            'invoice-row-update' => InvoiceRowUpdate::class,
        });
        $event::dispatch($data['payload']);
        
        return response()->json([
            'success' => true,
            'message' => 'Event Accepted',
        ], Response::HTTP_OK);
    }
}