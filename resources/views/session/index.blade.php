@extends('layouts.app')

@section('content')
    <div class="container">
        <table id="sessions-table" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Active</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#sessions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("sessions.index") }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'active', name: 'active' },
                ]
            });
        });
    </script>
@endpush
