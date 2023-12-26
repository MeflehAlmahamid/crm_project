@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Employees of {{ $company->name }}</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>{{ $employee->first_name }}</td>
                        <td>{{ $employee->last_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
