<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Company;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\PDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'company_id' => 'required',
            'email' => 'nullable|email|unique:companies,email',
            'phone' => 'nullable',
        ]);

        $Employee = new Employee([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'company_id' => $request->input('company_id'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

        $Employee->save();

        return response()->json(['message' => 'Employee added successfully']);
    }

    public function edit(Employee $employee)
    {
        return response()->json($employee);
    }

    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->all());

        return response()->json(['message' => 'employee updated successfully']);
    }
    

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->delete();

        return response()->json(['message' => 'employee deleted successfully']);
    }


    public function exportExcel(Company $company)
    {
        return Excel::download(new EmployeeExport($company->id), 'Employees.xlsx');
    }

    public function exportAllemployeePDF(Company $company)
    {
        $employees = Employee::with('company')->where('company_id', $company->id)->get();
    
        $pdf = SnappyPdf::loadView('pdf.export_all_employee', compact('employees'));
    
        return $pdf->download('all_employee_document.pdf');
    }

}
