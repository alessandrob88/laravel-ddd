<?php

namespace App\Jobs;

use App\Domain\Invoice\Factories\DeleteInvoiceServiceFactory;
use App\Domain\Invoice\ValueObjects\InvoicePayload;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InvoiceCancelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public InvoicePayload $invoicePayload,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $className = get_class();
        Log::info("$className start");
        try 
        {
            (DeleteInvoiceServiceFactory::create())->delete($this->invoicePayload);
            Log::info("$className completed");
        } 
        catch (Exception $ex) 
        {
            Log::info("$className failed with message {$ex->getMessage()}");
            dispatch(new SendErrorEmailJob(
                $className,
                $ex->getMessage(),
                $this->invoicePayload
            ));
        }
    }
}
