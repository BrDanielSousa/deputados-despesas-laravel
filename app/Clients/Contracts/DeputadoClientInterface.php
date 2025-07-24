<?php

namespace App\Clients\Contracts;

interface DeputadoClientInterface
{
    public function buscarDeputados(): array;

    public function buscarDespesas(int $deputadoId): array;
}
