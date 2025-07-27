<?php

namespace App\Http\Controllers;

use App\Services\Contracts\DeputadoServiceInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $deputadoService;

    public function __construct(DeputadoServiceInterface $deputadoService)
    {
        $this->deputadoService = $deputadoService;
    }

    public function dashboard(Request $request)
    {
        $filtro = $request->input('filtro');
        $deputados = $this->deputadoService->getDeputados($filtro);

        return view('dashboard', compact('deputados', 'filtro'));
    }
}
