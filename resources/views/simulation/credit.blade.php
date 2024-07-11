@extends('layouts.app')

@section('content')
    <script src="{{ asset('js/simulation.js') }}"></script>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" placeholder="Cpf" maxlength="11" id="cpf">
                            </div>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" placeholder="Valor solicitado"
                                       id="valorSolicitado">
                            </div>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" placeholder="Quantidade parcelas"
                                       id="qntParcelas">
                            </div>
                            <div class="col">
                                <button class="btn btn-outline-secondary" type="button" id="button-cpf">
                                    Search
                                </button>
                            </div>
                            <div class="col-sm-2 d-none" id="loading">
                                Carregando...
                            </div>
                        </div>
                    </div>
                    <table class="table" id="tblSimulation">
                        <thead>
                        <tr>
                            <th scope="col">Instituicao Financeira</th>
                            <th scope="col">Modalidade Credito</th>
                            <th scope="col">Valor Pagar</th>
                            <th scope="col">Valor Solicitado</th>
                            <th scope="col">Taxa Juros</th>
                            <th scope="col">Quantidade Parcelas</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="card-body">
                        @if (session('token'))
                            <script>
                                writeToken("{{ session('token') }}");
                            </script>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
