<?php

namespace App\Repositories\Contracts;

use App\Models\Deputado;
use Illuminate\Pagination\LengthAwarePaginator;

interface DeputadoRepositoryInterface
{
    public function getDeputado($deputadoId): Deputado;

    public function getDeputados(?string $filtro = null): LengthAwarePaginator;

    public function firstOrCreate(array $dados): Deputado;
}
