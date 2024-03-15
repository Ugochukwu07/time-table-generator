@extends('layouts.app', ['title' => 'All Departments'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <div class="form-head text-center d-flex flex-wrap mt-5 mb-sm-4 mb-3 align-items-center">
                <div class="mx-auto d-lg-block mb-3">
                    <h2 class="text-black mb-0 font-w700">Departments Info</h2>
                    <p class="mb-0">List Departments</p>
                    <p class="my-4">Session: <span class="bg-success text-white p-1 rounded">{{ $session->name }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-xl-11 mx-auto col-lg-12">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Session</th>
                        <th>Faculty</th>
                        <th>Courses</th>
                        <th>Added On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $key => $department)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $department->name }}</td>
                            <td>{{ $session->name }}</td>
                            <td>{{ $department->faculty ?? "NULL" }}</td>
                            <td> <a href="{{ route('upload.stats.course', ['id' => $department->id]) }}">
                                View Courses({{ count($department->session_courses) }})</a>
                            </td>
                            <td>{{ $department->created_at->format('F, j Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Session</th>
                        <th>Faculty</th>
                        <th>Courses</th>
                        <th>Added On</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-xl-12 text-center">
            <a href="{{ route('upload.first') }}" class="btn btn-success btn-lg">Bact to Upload</a>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="//cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <script>
        new DataTable('#example');
    </script>
@endsection
