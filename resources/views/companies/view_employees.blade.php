@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Employees of {{ $company->name }}</h3>

        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                Add Employee
            </button>
            <a href="{{ route('employee.exportExcel', ['company' => $company->id]) }}" class="btn btn-success">Export to
                Excel</a>
            <a href="{{ route('export.all.employee.pdf', ['company' => $company->id]) }}" class="btn btn-danger">Export to
                PDF</a>

        </div>

        <div class="modal fade" id="addCompanyModal" tabindex="-1" aria-labelledby="addCompanyModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCompanyModalLabel">Add Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="cEmployeeForm" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-6">
                                <label for="companyName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="companyName" name="first_name" required>
                                <div class="invalid-feedback">
                                    Please provide a valid first name.
                                </div>
                            </div>

                            <input type="hidden" class="form-control" id="companyName" name="company_id"
                                value="{{ $company->id }}">


                            <div class="mb-6">
                                <label for="Last" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="Last" name="last_name" required>
                                <div class="invalid-feedback">
                                    Please provide a valid last name.
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="companyEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="companyEmail" name="email">
                            </div>

                            <div class="mb-6">
                                <label for="Phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="Phone" name="phone">
                            </div>

                            <br>
                            <button type="button" class="btn btn-primary" id="saveEmployeeBtn">Save Employee</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editCompanyModal" tabindex="-1" aria-labelledby="editCompanyModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCompanyModalLabel">Edit Employy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editCompanyForm">


                            <input type="hidden" id="editemployeeId" name="id">

                            <div class="mb-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                                <div class="invalid-feedback">
                                    Please provide a valid first name.
                                </div>
                            </div>

                            <input type="hidden" class="form-control" id="company_id" name="company_id"
                                value="{{ $company->id }}">


                            <div class="mb-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                                <div class="invalid-feedback">
                                    Please provide a valid last name.
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>

                            <div class="mb-6">
                                <label for="Phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="PhoneE" name="phone">
                            </div>

                            <br>

                            <button type="button" class="btn btn-primary" id="saveEditemployeeBtn">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>{{ $employee->first_name }}</td>
                        <td>{{ $employee->last_name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>
                            <a class="btn btn-success btn-sm editemployeeBtn"
                                data-employee-id="{{ $employee->id }}">Edit</a>
                            <a class="btn btn-danger btn-sm deleteEmployeeBtn"
                                data-employee-id="{{ $employee->id }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex">
            {{ $employees->links() }}
        </div>

    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            $('#saveEmployeeBtn').on('click', function() {
                var form = $('#cEmployeeForm')[0];
                if (form.checkValidity()) {
                    var formData = new FormData(form);

                    $.ajax({
                        url: '/employee',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#addCompanyModal').modal('hide');
                        },
                        error: function(error) {
                            console.error('Error:', error);
                        }
                    });
                } else {
                    form.classList.add('was-validated');
                }
            });

            $('.deleteEmployeeBtn').on('click', function() {
                var deleteButton = $(this);
                var employeeId = deleteButton.data('employee-id');

                $.ajax({
                    url: '/employee/' + employeeId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        deleteButton.closest('tr').hide();
                        alert('Company deleted successfully');
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });


            $('.editemployeeBtn').on('click', function() {
                var employeeId = $(this).data('employee-id');
                $.ajax({
                    url: '/employee/' + employeeId + '/edit',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {

                        $('#editemployeeId').val(response.id);
                        $('#first_name').val(response.first_name);
                        $('#last_name').val(response.last_name);
                        $('#email').val(response.email);
                        $('#PhoneE').val(response.phone);
                        $('#editCompanyModal').modal('show');
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });

            $('#saveEditemployeeBtn').on('click', function() {
                var employeeId = $('#editemployeeId').val();

                var editedData = {
                    first_name: $('#first_name').val(),
                    last_name: $('#last_name').val(),
                    email: $('#email').val(),
                    phone: $('#PhoneE').val()
                };
                $.ajax({
                    url: '/employee/' + employeeId,
                    type: 'PUT',
                    data: editedData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#editCompanyModal').modal('hide');
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });


        });
    </script>
@endsection
