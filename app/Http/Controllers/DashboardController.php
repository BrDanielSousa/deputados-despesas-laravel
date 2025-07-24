<?php

namespace App\Http\Controllers;

use App\Services\Contracts\DeputadoServiceInterface;

class DashboardController extends Controller
{
    protected $deputadoService;

    public function __construct(DeputadoServiceInterface $deputadoService)
    {
        $this->deputadoService = $deputadoService;
    }
    
    public function dashboard()
    {
        $deputados = $this->deputadoService->getDeputados();

        return view('dashboard', compact('deputados'));
    }
}
