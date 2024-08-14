<?php

namespace App\Services;

use App\Models\PaySlip;
use Barryvdh\DomPDF\Facade\Pdf;

class PaySlipService
{
    public function generatePaySlipPdf($paySlipId)
    {
        // Encontre o holerite pelo ID
        $paySlip = PaySlip::with('employee.team')->findOrFail($paySlipId);

        // Prepare os dados para a visualização
        $data = [
            'paySlip' => $paySlip,
            'employee' => $paySlip->employee,
            'team' => $paySlip->employee->team,
        ];

        // Gere o PDF com os dados
        $pdf = Pdf::loadView('payslips.pdf', $data);

        return $pdf->download('payslip_' . $paySlipId . '.pdf');
    }
}