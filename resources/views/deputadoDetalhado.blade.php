@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-header">
            <h3>{{ $deputado['nome'] }}</h3>
        </div>
        <div class="card-body d-flex">
            <img src="{{ $deputado['url_foto'] }}" alt="Foto de {{ $deputado['nome'] }}" class="img-thumbnail me-4" width="150">
            <div>
                <p><strong>Partido:</strong> {{ $deputado['sigla_partido'] }}</p>
                <p><strong>UF:</strong> {{ $deputado['sigla_uf'] }}</p>
                <p><strong>Legislatura:</strong> {{ $deputado['id_legislatura'] }}</p>
                <p><strong>Email:</strong> <a href="mailto:{{ $deputado['email'] }}">{{ $deputado['email'] }}</a></p>
            </div>
        </div>
    </div>

    <h4>Despesas</h4>
    @if($despesas->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Ano</th>
                    <th>Mês</th>
                    <th>Tipo</th>
                    <th>Data</th>
                    <th>Fornecedor</th>
                    <th>Valor</th>
                    <th>Documento</th>
                </tr>
            </thead>
            <tbody>
                @foreach($despesas as $despesa)
                <tr>
                    <td>{{ $despesa['ano'] }}</td>
                    <td>{{ $despesa['mes'] }}</td>
                    <td>{{ $despesa['tipo_despesa'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($despesa['data_documento'])->format('d/m/Y') }}</td>
                    <td>{{ $despesa['nome_fornecedor'] }}</td>
                    <td>R$ {{ number_format($despesa['valor_liquido'], 2, ',', '.') }}</td>
                    <td>
                        <a href="{{ $despesa['url_documento'] }}" target="_blank" class="btn btn-sm btn-outline-primary">Abrir</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $despesas->links() }}
    </div>
    @else
    <p class="text-muted">Nenhuma despesa registrada para este deputado.</p>
    @endif
</div>
@endsection