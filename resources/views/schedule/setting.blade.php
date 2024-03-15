@extends('layouts.app', ['title' => 'Upload Data'])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css" rel="stylesheet">
@endsection

@section('content')
<section>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="form-head text-center d-flex flex-wrap mt-5 mb-sm-4 mb-3 align-items-center">
                    <div class="mx-auto d-lg-block mb-3">
                        <h2 class="text-black mb-0 font-w700">Time Table Schedule</h2>
                        <p class="mb-0">Time Table Schedule Preview Settings</p>
                        <p class="my-4">Session: <span class="bg-success text-white p-1 rounded">{{ $session->name }}</span></p>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 mx-auto col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Setup Exam Info</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('scheduler.setup.save') }}" method="POST">
                            @csrf
                            <div class="row my-2">
                                <div class="col-md-6">
                                    <label for="start_date">Exam Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="start_date" placeholder="Start Date" value="{{ old('start_date') }}">
                                    @error('start_date')
                                    <small class="invalid-feedback d-block">Start Date is required</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="end_date">Expected Exam End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="end_date" placeholder="End Date" value="{{ old('end_date') }}">
                                    <small class="invalid-feedback d-block">Start Date is required</small>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-6">
                                    <label for="start_time">Exam Start Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="start_time" placeholder="Start Time" value="{{ old('start_time') }}">
                                    <small class="invalid-feedback d-block">Start time is required</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="end_time">Expected Exam End Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="end_time" placeholder="End Time" value="{{ old('end_time') }}">
                                    <small class="invalid-feedback d-block">End time is required</small>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-6 mx-auto">
                                    <div class="btn btn-success btn-block nav-pills">Generate</div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mx-auto col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Upload Statistics</h4>
                        <small>Refresh Page to update</small>
                        <button class="btn btn-sm btn-primary p-1" onclick="location.reload()">Refresh Page</button>
                    </div>
                    <div class="card-body">
                        <ul class="nav d-flex flex-column">
                            <li class="d-block my-2">
                                <a class="my-2 text-success" href="{{ route('upload.stats.departments') }}">Departments({{ $stats['department'] }})</a>
                            </li>
                            <li class="d-block my-3">
                                <a class="my-2 text-success" href="{{ route('upload.stats.courses') }}">Courses({{ $stats['course'] }})</a>
                            </li>
                            <li class="d-block my-3">
                                <a class="my-2 text-success" href="{{ route('home') }}">Back Home</a>
                            </li>
                            <li class="d-block my-3">
                                <a class="my-2 text-success" href="{{ route('scheduler.preview') }}">Preview</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Other Sessions</h4>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

@endsection
