<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class JobProdutoEditado implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private $user, private $request)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        //Enviar o e-mail de atualização de produto
        Mail::to($this->user->email)->later(now()->addMinute(), new JobProdutoEditado($this->user, $this->request));
    }
}
