<?php

namespace App\Http\Controllers;

use App\Services\Contracts\DeputadoServiceInterface;

class DeputadoController extends Controller
{

    protected $deputadoService;

    public function __construct(DeputadoServiceInterface $deputadoService)
    {
        $this->deputadoService = $deputadoService;
    }

    public function getDeputadoDetalhado($id)
    {

        $deputado = $this->deputadoService->getDeputado($id);

        $despesas = $this->deputadoService->getDespesasDoDeputado($id);

        return view('deputadoDetalhado', compact('deputado', 'despesas'));
    }

    public function sincronizarDeputados()
    {
        try {

            $this->deputadoService->sincronizarDeputados();

            return redirect()->route('dashboard')->with('success', 'Sicronização realizada com sucesso');
        } catch (\Throwable $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}
