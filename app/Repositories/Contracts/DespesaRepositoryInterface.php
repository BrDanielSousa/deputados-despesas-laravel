<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface DespesaRepositoryInterface
{
    public function getDespesasDoDeputado(int $deputadoId): LengthAwarePaginator;

    public function firstOrCreate(int $deputadoId, array $dados): void;
}
