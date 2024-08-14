<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Folha de Pagamento</title>
    <style>
        @page {
            size: A4 landscape; /* Define o tamanho e a orientação da página */
            margin: 10mm 20mm 20mm 20mm; /* Ajusta as margens: topo, direita, baixo, esquerda */
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .details {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        .details th, .details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .details th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p><strong>Empresa:</strong> {{ $team->name }}</p>
            <p><strong>CNPJ:</strong> {{ $team->cnpj }}</p>
        </div>

        <h1>Demonstrativo de Pagamento</h1>

        <div class="company-info">
            <p><strong>Matrícula:</strong> {{ $employee->id }}</p>
            <p><strong>Nome:</strong> {{ $employee->first_name }} {{ $employee->last_name }}</p>
            <p><strong>Folha de pagamento:</strong> {{ $paySlip->reference }}</p>
            <p><strong>Admissão:</strong> {{ $employee->date_hired }}</p>
        </div>

        <table class="details">
            <thead>
                <tr>
                    <th>Banco</th>
                    <th>Agência</th>
                    <th>Conta pagamento</th>
                    <th>Salário Base</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $employee->bank }}</td>
                    <td>{{ $employee->agency }}</td>
                    <td>{{ $employee->account }}</td>
                    <td>R$ {{ number_format($employee->salary_base, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="details">
            <thead>
                <tr>
                    <th colspan="2">Employee Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Reference:</strong></td>
                    <td>{{ $paySlip['reference'] }}</td>
                </tr>
                <tr>
                    <td><strong>Process:</strong></td>
                    <td>{{ $paySlip['process'] }}</td>
                </tr>
            </tbody>
        </table>

        <table class="details">
            <thead>
                <tr>
                    <th colspan="2">Earnings</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Earnings:</strong></td>
                    <td>R$ {{ number_format($paySlip['earnings'], 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="details">
            <thead>
                <tr>
                    <th colspan="2">Deductions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Deductions:</strong></td>
                    <td>R$ {{ number_format($paySlip['deductions'], 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <table class="details">
            <thead>
                <tr>
                    <th colspan="2">Totals</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Net Pay:</strong></td>
                    <td>R$ {{ number_format($paySlip['net'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>INSS Base:</strong></td>
                    <td>R$ {{ number_format($paySlip['inss_base'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>IRRF Base:</strong></td>
                    <td>R$ {{ number_format($paySlip['irrf_base'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>FGTS Base:</strong></td>
                    <td>R$ {{ number_format($paySlip['fgts_base'], 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>FGTS Deposited:</strong></td>
                    <td>R$ {{ number_format($paySlip['fgts_deposited'], 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Gerado em {{ date('d/m/Y') }}</p>
        </div>
    </div>
</body>
</html>
