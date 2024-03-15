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
                        <h2 class="text-black mb-0 font-w700">Upload Department</h2>
                        <p class="mb-0">Upload School Department Info</p>
                        <p class="my-4">Session: <span class="bg-success text-white p-1 rounded">{{ $session }}</span></p>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 mx-auto col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Department Info Upload(Excel)</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form custom_file_input">
                            <form class="dropzone" action="{{ route('upload.dropzone') }}" method="POST" enctype="multipart/form-data">
                                <div class="input-group mb-3">
                                    <div class="fallback">
                                        <input name="file" class="form-control-file" type="file" multiple="multiple">
                                    </div>
                                    <div class="dz-message mx-auto needsclick">
                                        <div class="mb-3">
                                            <i class="my-i-font text-muted bi bi-cloud-upload"></i>
                                        </div>

                                        <h4>Drop files here to upload</h4>
                                    </div>
                                </div>
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @csrf
                            </form>
                            <p class="mt-2">Only <code class="text-danger">.xlsx, .csx, .csv</code> extensions are allowed. Max Size: 4MB</p>
                        </div>
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
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- dropzone js -->
    <script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>

    <script>
        //var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone(".dropzone",{
             maxFilesize: 4, // 2 mb
             acceptedFiles: ".xlsx,.csx,.csv",
        });
        myDropzone.on("sending", function(file, xhr, formData) {
             //formData.append("_token", CSRF_TOKEN);
        });
        /* myDropzone.options.uiDZResume = {
            success: function(file, response){
                alert(response);
            }
        }; */
        myDropzone.on("success", function(file, response) {
            if(response.success == 0){ // Error
                Swal.fire({
                    title: "An error occurred",
                    text: response.message,
                    icon: 'warning'
                });
            }else{
                Swal.fire({
                    title: "Upload Success",
                    text: response.message,
                    icon: 'success'
                });
            }

        });
    </script>
@endsection
