@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Company List</h1>


        <div class="modal fade" id="addCompanyModal" tabindex="-1" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCompanyModalLabel">Add Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="companyForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="companyName" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="companyName" name="name" required>
                                <div class="invalid-feedback">
                                    Please provide a valid company name.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="companyEmail" class="form-label">Company Email</label>
                                <input type="email" class="form-control" id="companyEmail" name="email">
                            </div>

                            <div class="mb-3">
                                <label for="companyLogo" class="form-label">Company Logo</label>
                                <input type="file" class="form-control" id="companyLogo" name="logo" accept="image/*">
                                <div class="invalid-feedback">
                                    Please choose a valid image file (JPEG, PNG, JPG, GIF, or SVG) with a maximum size of
                                    100KB.
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary" id="saveCompanyBtn">Save Company</button>
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
                        <h5 class="modal-title" id="editCompanyModalLabel">Edit Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editCompanyForm">
                            <input type="hidden" id="editCompanyId" name="id">
                            <div class="mb-3">
                                <label for="editCompanyName" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="editCompanyName" name="name">
                            </div>
                            <div class="mb-3">
                                <label for="editCompanyEmail" class="form-label">Company Email</label>
                                <input type="email" class="form-control" id="editCompanyEmail" name="email">
                            </div>
                            <button type="button" class="btn btn-primary" id="saveEditCompanyBtn">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                Add Company
            </button>
            <a href="{{ route('companies.exportExcel') }}" class="btn btn-success">Export to Excel</a>
            <a href="{{ route('export.all.companies.pdf') }}" class="btn btn-danger">Export to PDF</a>
    
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($companies as $company)
                    <tr data-company-id="{{ $company->id }}">
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->email }}</td>
                        <td>
                            <a href="{{ route('companies.viewEmployees', ['company' => $company->id]) }}"
                                class="btn btn-secondary btn-sm">View Employees</a>
                            <a class="btn btn-success btn-sm editCompanyBtn" data-company-id="{{ $company->id }}">Edit</a>
                            <a class="btn btn-danger btn-sm deleteCompanyBtn"
                                data-company-id="{{ $company->id }}">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex">
            {!! $companies->links() !!}
        </div>

    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#saveCompanyBtn').on('click', function() {
                var form = $('#companyForm')[0];
                if (form.checkValidity()) {
                    var formData = new FormData(form);

                    $.ajax({
                        url: '/companies',
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

            $('.deleteCompanyBtn').on('click', function() {
                var deleteButton = $(this);
                var companyId = deleteButton.data('company-id');

                $.ajax({
                    url: '/companies/' + companyId,
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


            $('.editCompanyBtn').on('click', function() {
                var companyId = $(this).data('company-id');
                $.ajax({
                    url: '/companies/' + companyId + '/edit',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);

                        console.log(response);
                        $('#editCompanyId').val(response.id);
                        $('#editCompanyName').val(response.name);
                        $('#editCompanyEmail').val(response.email);
                        $('#editCompanyModal').modal('show');
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });


            $('#saveEditCompanyBtn').on('click', function() {
                var companyId = $('#editCompanyId').val();

                var editedData = {
                    name: $('#editCompanyName').val(),
                    email: $('#editCompanyEmail').val(),
                };

                $.ajax({
                    url: '/companies/' + companyId,
                    type: 'PUT',
                    data: editedData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#editCompanyModal').modal('hide');

                        $('tr[data-company-id="' + companyId + '"]').find('.company-name').text(
                            editedData.name);
                        $('tr[data-company-id="' + companyId + '"]').find('.company-email')
                            .text(editedData.email);
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });


        });
    </script>
@endsection
