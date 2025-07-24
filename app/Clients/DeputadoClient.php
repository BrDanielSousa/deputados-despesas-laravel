<?php

namespace App\Clients;

use App\Clients\Contracts\DeputadoClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class DeputadoClient implements DeputadoClientInterface
{
    protected string $baseUrl = 'https://dadosabertos.camara.leg.br/api/v2';

    /**
     * Busca todos os deputados usando paginação automática da API da Câmara.
     */
    public function buscarDeputados(): array
    {

        $url = "{$this->baseUrl}/deputados";

        try {
            $response = Http::get($url)->json();

            if (!isset($response['dados'])) {
                throw new \Exception("Resposta inesperada da API da Câmara.");
            }

            return $response['dados'];
        } catch (RequestException $e) {

            $status = $e->response?->status() ?? 0;
            $mensagem = $e->response?->body() ?? 'Erro desconhecido';

            Log::error("Erro ao buscar deputados. Status: {$status} - {$mensagem}");

            // Lança exceção genérica com base no status
            if ($status === 404) {


                throw new \Exception("Recurso não encontrado na API da Câmara.");
            } elseif ($status === 400) {

                throw new \Exception("Requisição inválida enviada para a API da Câmara.");
            }

            throw new \Exception("Erro inesperado ao acessar a API da Câmara. Status: {$status}");
        }
    }

    /**
     * Busca as despesas de um deputado específico por ID.
     */
    public function buscarDespesas(int $deputadoId): array
    {
        $despesas = [];
        $url = "{$this->baseUrl}/deputados/{$deputadoId}/despesas";

        try {
            do {
                $response = Http::get($url)->throw();
                $json = $response->json();

                if (!isset($json['dados']) || !is_array($json['dados'])) {
                    break;
                }

                $despesas = array_merge($despesas, $json['dados']);

                $proximo = collect($json['links'])->firstWhere('rel', 'next');
                $url = $proximo['href'] ?? null;
            } while ($url);

            return $despesas;
        } catch (RequestException $e) {
            $status = $e->response?->status() ?? 0;
            $mensagem = $e->response?->body() ?? 'Erro desconhecido';

            Log::error("Erro ao buscar despesas do deputado {$deputadoId}. Status: {$status} - {$mensagem}");

            if ($status === 404) {
                throw new \Exception("Despesas do deputado não encontradas na API.");
            } elseif ($status === 400) {
                throw new \Exception("Requisição inválida para despesas do deputado.");
            }

            throw new \Exception("Erro inesperado ao acessar despesas do deputado. Status: {$status}");
        }
    }
}
