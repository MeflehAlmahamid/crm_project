
<h1>All Employee PDF</h1>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full name</th>
            <th>Company name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>created at</th>
            <th>updated at</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->id }}</td>
                <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td>{{ $employee->company->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->phone }}</td>
                <td>{{ $employee->created_at }}</td>
                <td>{{ $employee->updated_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
