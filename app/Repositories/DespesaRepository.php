<?php

namespace App\Repositories;

use App\Models\Despesa;
use App\Repositories\Contracts\DespesaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class DespesaRepository implements DespesaRepositoryInterface
{
    public function getDespesasDoDeputado(int $deputadoId): LengthAwarePaginator
    {
        return Despesa::where('deputado_id', $deputadoId)
            ->orderBy('data_documento', 'desc')
            ->paginate(25);
    }

    public function firstOrCreate(int $deputadoId, array $dados): void
    {
        Despesa::firstOrCreate(
            [
                'deputado_id' => $deputadoId,
                'cod_documento' => $dados['codDocumento'],
            ],
            [
                'ano' => $dados['ano'],
                'mes' => $dados['mes'],
                'tipo_despesa' => $dados['tipoDespesa'],
                'tipo_documento' => $dados['tipoDocumento'],
                'cod_tipo_documento' => $dados['codTipoDocumento'] ?? null,
                'data_documento' => isset($dados['dataDocumento']) ? date('Y-m-d', strtotime($dados['dataDocumento'])) : null,
                'num_documento' => $dados['numDocumento'] ?? null,
                'valor_documento' => $dados['valorDocumento'] ?? 0,
                'url_documento' => $dados['urlDocumento'] ?? null,
                'nome_fornecedor' => $dados['nomeFornecedor'] ?? null,
                'cnpj_cpf_fornecedor' => $dados['cnpjCpfFornecedor'] ?? null,
                'valor_liquido' => $dados['valorLiquido'] ?? 0,
                'valor_glosa' => $dados['valorGlosa'] ?? 0,
                'cod_lote' => $dados['codLote'] ?? null,
                'parcela' => $dados['parcela'] ?? null,
                'num_ressarcimento' => $dados['numRessarcimento'] ?? null,
            ]
        );
    }
}
