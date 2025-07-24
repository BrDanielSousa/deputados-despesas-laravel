<?php

namespace App\Services;

use App\Clients\Contracts\DeputadoClientInterface;
use App\Jobs\ProcessarDeputados;
use App\Models\Deputado;
use App\Repositories\Contracts\DeputadoRepositoryInterface;
use App\Repositories\Contracts\DespesaRepositoryInterface;
use App\Services\Contracts\DeputadoServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class DeputadoService implements DeputadoServiceInterface
{
    protected $client;
    protected $deputadoRepo;
    protected $despesasRepo;

    public function __construct(DeputadoClientInterface $client, DeputadoRepositoryInterface $deputadoRepo, DespesaRepositoryInterface $despesasRepo)
    {
        $this->client = $client;
        $this->deputadoRepo = $deputadoRepo;
        $this->despesasRepo = $despesasRepo;
    }

    function getDeputado(int $id): Deputado
    {   
        return $this->deputadoRepo->getDeputado($id);
    }

    public function getDeputados(): array
    {
        return $this->deputadoRepo->getDeputados();
    }

    public function getDespesasDoDeputado(int $id): LengthAwarePaginator
    {
        return $this->despesasRepo->getDespesasDoDeputado($id);
    }

    public function sincronizarDeputados(): void
    {

        $dados = $this->client->buscarDeputados();

        if (empty($dados)) {
            throw new \Exception('Nenhum deputado retornado da API.');
        }

        foreach ($dados as $deputado) {
            dispatch(new ProcessarDeputados($deputado));
        }
    }
}
