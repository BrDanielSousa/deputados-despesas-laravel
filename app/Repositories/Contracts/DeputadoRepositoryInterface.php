<?php

namespace App\Repositories\Contracts;

use App\Models\Deputado;

interface DeputadoRepositoryInterface
{
    public function getDeputado($deputadoId): Deputado;

    public function getDeputados(): array;

    public function firstOrCreate(array $dados): Deputado;
}
