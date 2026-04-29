@extends('admin.layouts.layout')

@section('title', 'Contact List')

@section('content')

    <div class="container mt-3">
        <h4>Contact List</h4>
        <a href="{{ route('admin-contacts-create') }}" class="btn btn-primary mb-3">
            Add Contact
        </a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company Name</th>
                    <th>Type</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->id }}</td>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->company_name }}</td>
                        <td>{{ $contact->type }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection
