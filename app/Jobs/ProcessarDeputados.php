<?php

namespace App\Jobs;

use App\Clients\Contracts\DeputadoClientInterface;
use App\Repositories\Contracts\DeputadoRepositoryInterface;
use App\Repositories\Contracts\DespesaRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class ProcessarDeputados implements ShouldQueue
{
    use Queueable;

    protected array $deputado;

    public function __construct(array $deputado)
    {
        $this->deputado = $deputado;
    }

    public function handle(
        DeputadoRepositoryInterface $deputadoRepo,
        DespesaRepositoryInterface $despesaRepo,
        DeputadoClientInterface $client
    ): void {

        DB::transaction(function () use ($deputadoRepo, $despesaRepo, $client) {

            $deputadoModel = $deputadoRepo->firstOrCreate($this->deputado);

            $despesas = $client->buscarDespesas($deputadoModel->api_id);

            foreach ($despesas as $despesa) {
                $despesaRepo->firstOrCreate($deputadoModel->id, $despesa);
            }
        });
    }
}
