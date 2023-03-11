<?php

namespace App\Http\Controllers;

use App\Events\{InvoiceCancel, InvoiceCreate, InvoiceRowCreate, InvoiceRowUpdate};
use App\Http\Requests\InvoiceRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
class InvoiceController extends Controller {

    public function handle(InvoiceRequest $request) {  
        $event = (match ($request->json('data')['event']) {
            'invoice-create' => InvoiceCreate::class,
            'invoice-cancel' => InvoiceCancel::class,
            'invoice-row-create' => InvoiceRowCreate::class,
            'invoice-row-update' => InvoiceRowUpdate::class,
        });
        $event::dispatch($request->json()->all());
        
        Log::info("$event dispatched!");

        return response()->json([
            'success' => true,
            'message' => 'Event Accepted',
        ], Response::HTTP_OK);
    }
}