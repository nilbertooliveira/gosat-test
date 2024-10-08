@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="card-body">
                                <div class="ui-widget">
                                    <textarea id="template" rows="5" cols="50"></textarea>
                                </div>
                                <br>

                                <div id="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#template').autocomplete({
            select: function (event, ui) {

                const template = $('#template').val();
                const regex = /\{\{}}$/;
                const newTemplate = template.replace(regex, ui.item.value);

                $('#template').val(newTemplate);

                const data = @json($data);

                if (!isBalanced(template)) {
                    return false;
                }

                // Envia o template completo para o servidor
                $.ajax({
                    url: '/render',
                    type: 'POST',
                    data: {template: newTemplate, data},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        // Atualiza a pré-visualização ou realiza outras ações
                        $('#preview').html(response);
                    }
                });
            },
            source: function (request, response) {
                const templateContent = $('#template').val();
                const regex = /\{\{}}/i;
                let matches = request.term.match(regex);

                if (templateContent.includes(matches)) {
                    const liquid = @json($variablesLiquid);

                    response($.map(liquid, function (item) {
                        return {
                            label: '{' + '{' + item + '}}',
                            value: '{' + '{' + item + '}}',
                        };
                    }));
                }
            }
        });

        $("#template").on('input', function () {
            const template = $(this).val();
            const data = @json($data);

            if (!isBalanced(template)) {
                return false;
            }

            $.ajax({
                url: '/render',
                method: 'POST',
                data: {template, data},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    $('#preview').html(result);
                }
            });
        });

        function isBalanced(str) {
            let balance = 0;
            for (let i = 0; i < str.length; i++) {
                if (str[i] === '{') {
                    balance++;
                } else if (str[i] === '}') {
                    balance--;
                    if (balance < 0) {
                        return false; // Chaves desbalanceadas
                    }
                }
            }
            return balance === 0; // Chaves balanceadas
        }

        //caso queira trocar por js
        function renderTemplate(template, data) {
            const engine = new Liquid();

            return engine.parseAndRender(template, data)
                .then(html => {
                    return html;
                })
                .catch(err => {

                    return null;
                });
        }

    </script>
@endsection
