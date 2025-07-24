<?php

namespace App\Repositories;

use App\Models\Deputado;
use App\Repositories\Contracts\DeputadoRepositoryInterface;

class DeputadoRepository implements DeputadoRepositoryInterface
{

    public function getDeputado($deputadoId): Deputado
    {
        return Deputado::find($deputadoId);
    }

    public function getDeputados(): array
    {
        return Deputado::all()->toArray();
    }

    public function firstOrCreate(array $dados): Deputado
    {
        return Deputado::firstOrCreate(
            ['api_id' => $dados['id']],
            [
                'nome' => $dados['nome'],
                'sigla_partido' => $dados['siglaPartido'],
                'sigla_uf' => $dados['siglaUf'],
                'email' => $dados['email'] ?? null,
                'id_legislatura' => $dados['idLegislatura'] ?? null,
                'uri' => $dados['uri'] ?? null,
                'uri_partido' => $dados['uriPartido'] ?? null,
                'url_foto' => $dados['urlFoto'] ?? null,
            ]
        );
    }
}
