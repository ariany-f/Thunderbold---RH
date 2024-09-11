<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaySlipController;
use App\Http\Controllers\Controller;
use App\Filament\Resources\EmployeeResource\Pages\ViewDependents;
use App\Filament\Resources\ChecklistResource\Pages\ViewChecklistItems;
use App\Filament\Resources\DependentResource\Pages\CreateDependent;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',  [Controller::class, 'index']);

Route::get('payslips/{id}/download', [PaySlipController::class, 'downloadPdf'])->name('payslips.downloadPdf');

Route::get('app/{tenant}/checklists/{checklist}/checklist-items', [ViewChecklistItems::class, 'index'])
        ->name('checklists.checklist-items');

Route::get('app/{tenant}/employees/{employee}/dependents', ViewDependents::class)
        ->name('employees.dependents');

Route::prefix('admin')->group(function () {
    Route::get('employees/{employee}/dependents', ViewDependents::class)
        ->name('admin.employees.dependents');
});