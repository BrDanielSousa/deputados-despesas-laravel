<?php

namespace App\Services\Contracts;

use App\Models\Deputado;
use Illuminate\Pagination\LengthAwarePaginator;

interface DeputadoServiceInterface
{
    public function getDeputado(int $id): Deputado;

    public function getDeputados(): array;

    public function getDespesasDoDeputado(int $id): LengthAwarePaginator;

    public function sincronizarDeputados(): void;
}
