@extends('admin.layouts.layout')

@section('content')

<div class="container">
        <h4>Sequences List</h4>

        {{-- <a href="{{ route('admin-sequences-create') }}" class="btn btn-primary mb-3">Add New</a> --}}

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Step</th>
                    <th>Subject</th>
                    <th>Gap</th>
                    <th>Type</th>
                    <th>Telegram</th>
                    <th>WhatsApp</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sequences as $seq)
                <tr>
                    <td>{{ $seq->step }}</td>
                    <td>{{ $seq->subject }}</td>
                    <td>{{ $seq->gap_days }}</td>
                    <td>{{ $seq->type }}</td>
                    <td>{{ $seq->telegram_link }}</td>
                    <td>{{ $seq->whatsapp_link }}</td>
                        {{-- <a href="{{ $seq->telegram_link }}" target="_blank">Telegram</a> |
                        <a href="{{ $seq->whatsapp_link }}" target="_blank">WhatsApp</a> --}}

                </tr>
                @endforeach
            </tbody>
        </table>
</div>

@endsection
