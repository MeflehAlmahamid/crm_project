<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Exports\CompaniesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\PDF;
use Barryvdh\Snappy\Facades\SnappyPdf;


class CompaniesController extends Controller
{
    public function index()
    {
        $companies = Company::paginate(10);

        return view('companies.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'nullable|email|unique:companies,email',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:102400',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        } else {
            $logoPath = null;
        }

        $company = new Company([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'logo' => $logoPath,
        ]);

        $company->save();

        return response()->json(['message' => 'Company added successfully']);
    }


    public function viewEmployees(Company $company)
    {
        $employees = $company->employees;

        return view('companies.view_employees', compact('company', 'employees'));
    }

    public function edit(Company $company)
    {
        return response()->json($company);
    }


    public function update(Request $request, Company $company)
    {
        $company->update($request->all());

        return response()->json(['message' => 'Company updated successfully']);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);

        $company->employees()->delete();

        $company->delete();

        return response()->json(['message' => 'Company deleted successfully']);
    }

    public function exportExcel()
    {
        return Excel::download(new CompaniesExport, 'companies.xlsx');
    }

//     public function generatePDF()
// {
//     $pdf = app('dompdf.wrapper');
//     $pdf->loadView('pdf.view', ['data' => $data]);

//     return $pdf->download('document.pdf');
// }

//     public function exportPDF()
//     {
//         $companies = Company::all();

//         $pdf = PDF::loadView('companies.pdf', compact('companies'));
//         return $pdf->download('companies.pdf');
//     }

        public function exportAllCompaniesPDF()
        {
            $companies = Company::all();

            $pdf = SnappyPdf::loadView('pdf.export_all_companies', compact('companies'));

            return $pdf->download('all_companies_document.pdf');
        }

}
