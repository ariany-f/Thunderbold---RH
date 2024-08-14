<?php

namespace App\Http\Controllers;

use App\Services\PaySlipService;
use Illuminate\Http\Request;

class PaySlipController extends Controller
{
    public function downloadPdf($id)
    {
        $paySlipService = new PaySlipService();
        return $paySlipService->generatePaySlipPdf($id);
    }
}