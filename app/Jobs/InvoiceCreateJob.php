<?php

namespace App\Jobs;

use App\Domain\Customer\Factories\CreateCustomerServiceFactory;
use App\Domain\Invoice\Factories\CreateInvoiceServiceFactory;
use App\Domain\Invoice\ValueObjects\InvoicePayload;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InvoiceCreateJob implements ShouldQueue
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
            $customerId = (CreateCustomerServiceFactory::create())->create($this->invoicePayload->getCustomer());
            (CreateInvoiceServiceFactory::create())->create($this->invoicePayload, $customerId);
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
