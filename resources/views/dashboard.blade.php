@extends('layouts.app')

@push('styles')
<style>
  body {
    background-color: #f4f6f9;
    font-family: 'Segoe UI', sans-serif;
  }

  .content-wrapper {
    max-width: 1100px;
    margin: auto;
    padding: 40px 20px;
  }

  .card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
  }

  .card-header {
    background-color: #fff;
    border-bottom: none;
  }

  .btn-sync {
    font-weight: 500;
  }

  .table> :not(caption)>*>* {
    vertical-align: middle;
  }

  .table th {
    background-color: #f0f2f5;
    text-transform: uppercase;
    font-size: 12px;
    color: #495057;
  }

  .deputado-foto {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid #dee2e6;
  }
</style>
@endpush

@section('content')
<div class="content-wrapper">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Lista de Deputados</h4>
      <form action="{{ route('deputados.sincronizar') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm btn-sync">🔄 Sincronizar Dados</button>
      </form>
    </div>

    <div class="card-body">
      {{-- Mensagens --}}
      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
      </div>
      @endif

      @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
      </div>
      @endif

      @if ($errors->any())
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
      </div>
      @endif

      <form method="GET" action="{{ route('dashboard') }}">
        <div class="input-group mb-4">
          <input type="text" name="filtro" class="form-control" placeholder="Filtrar por nome..." value="{{ $filtro ?? '' }}">
          <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
        </div>
      </form>

      {{-- Tabela --}}
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
          <thead>
            <tr>
              <th>Foto</th>
              <th>Nome</th>
              <th>Partido</th>
              <th>UF</th>
              <th>Email</th>
              <th>Ação</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($deputados as $dep)
            <tr>
              <td>
                <img src="{{ $dep->url_foto ?? asset('images/fallback.jpg') }}"
                  alt="{{ $dep->nome }}"
                  class="deputado-foto"
                  onerror="this.onerror=null;this.src='{{ asset('images/fallback.jpg') }}';">
              </td>
              <td>{{ $dep->nome }}</td>
              <td>{{ $dep->sigla_partido }}</td>
              <td>{{ $dep->sigla_uf }}</td>
              <td>{{ $dep->email }}</td>
              <td>
                <a href="{{ route('getDeputadoDetalhado', $dep->id) }}" class="btn btn-primary">Ver</a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center text-muted">Nenhum deputado encontrado.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Paginação --}}
      <div class="mt-4 d-flex justify-content-center">
        {{ $deputados->links() }}
      </div>
    </div>
  </div>
</div>
@endsection