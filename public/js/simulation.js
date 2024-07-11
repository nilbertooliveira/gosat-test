const URI_SIMULATION_CALC = '/api/v1/simulation/calculate';

function writeToken(token) {
    localStorage.setItem('api_token', token);
}

function validate(cpf, valorSolicitado, qntParcelas) {
    if (!cpf) {
        $('#cpf').focus();
        alert("Cpf obrigatório!");
        return false;
    } else if (!valorSolicitado) {
        $('#valorSolicitado').focus();
        alert("Valor solicitado obrigatório!");
        return false;
    } else if (!qntParcelas) {
        $('#qntParcelas').focus();
        alert("Quantidade de parcelas obrigatório!");
        return false;
    }
    return true;
}

function calculate(cpf, valorSolicitado, qntParcelas) {
    const token = localStorage.getItem('api_token');

    $('#tblSimulation tbody').empty();

    $.ajax({
        url: URI_SIMULATION_CALC,
        method: 'POST',
        dataType: 'Json',
        data: {
            cpf: cpf,
            valorSolicitado: valorSolicitado,
            qntParcelas: qntParcelas
        },
        headers: {
            'Authorization': 'Bearer ' + token
        },
        success: function (data) {
            $.each(data.data, function (index, simulation) {
                    const row = $('<tr></tr>');

                    row.append('<td>' + simulation.instituicaoFinanceira + '</td>');
                    row.append('<td>' + simulation.modalidadeCredito + '</td>');
                    row.append('<td>' + simulation.valorAPagar + '</td>');
                    row.append('<td>' + simulation.valorSolicitado + '</td>');
                    row.append('<td>' + simulation.taxaJuros + '</td>');
                    row.append('<td>' + simulation.qntParcelas + '</td>');

                    $('#tblSimulation tbody').append(row);
            });

            $('#tblSimulation').show();
        },
        error: function (xhr) {

            if (xhr.responseJSON.message) {
                alert(xhr.responseJSON.message);
            } else if (xhr.responseJSON.errors) {
                alert(xhr.responseJSON.errors);
            }
        },
        complete: function () {
            $("#loading").addClass("d-none");
            $('#button-cpf').attr("disabled", false);
        }
    });
}

$(document).ready(function () {
    $('#button-cpf').on('click', function () {

        const cpf = $('#cpf').val();
        const valorSolicitado = $('#valorSolicitado').val();
        const qntParcelas = $('#qntParcelas').val();

        if (!validate(cpf, valorSolicitado, qntParcelas)
        ) {
            return;
        }

        $("#loading").removeClass("d-none");

        $(this).attr("disabled", true);

        calculate(cpf, valorSolicitado, qntParcelas);
    })
});
