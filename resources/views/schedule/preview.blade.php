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
                        <p class="mb-0">Time Table Schedule Preview</p>
                        <p class="my-4">Session: <span class="bg-success text-white p-1 rounded">{{ $session->name }}</span></p>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 mx-auto col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Time Table Preview</h4>
                    </div>
                    <div class="card-body">
                        @foreach($batches as $key => $batch)
                            <h3>Batch <?= $key+1 . " " .  $batch[0]['time']; ?></h3>
                            <?php $formatted = $batch['formatted']; ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Hall</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($formatted as $key => $exam): ?>
                                        <tr>
                                            <td><?= $exam['course'] ?></td>
                                            <td><?= $exam['hall'] ?></td>
                                            <td><?= $exam['department'] ?>(<?= $exam['students'] ?>)</td>
                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mx-auto col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('scheduler.print.setup') }}" class="btn btn-primary btn-block">Continue to Print</a>
                        <hr>
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
                                <a class="my-2 text-success" href="{{ route('home') }}">Back Home ></a>
                            </li>
                            @if(!request()->is('scheduler/preview'))
                                <li class="d-block my-3">
                                    <a class="my-2 text-success" href="{{ route('scheduler.preview') }}">Preview</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="sidebar row mx-auto">
                            @foreach($halls as $key => $hall)
                                <div class="item col-5 border border-secondary rounded m-1">
                                    <h4>Hall <?= $key+1; ?></h4>
                                    <p class="my-1 ml-1"><?= $hall; ?></p>
                                </div>
                            @endforeach
                            <div class="my-2"></div>
                            <div class="accordion col-12 mt-3" id="accordionExample">
                                @foreach($courses_main as $key => $value)
                                    <div class="card">
                                        <div class="card-header" id="heading{{ $key }}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapseOne">
                                                {{ $key }}
                                                <span style="text-decoration: underline; font-size: 12px" class="underline ml-3">Open</span>
                                            </button>
                                        </h2>
                                        </div>

                                        <div id="collapse{{ $key }}" class="collapse" aria-labelledby="heading{{ $key }}" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @foreach($value as $key => $course)
                                                <p class="my-3 ml-3">-<?= $key ?>(<?= $course ?>)</p>
                                            @endforeach
                                        </div>
                                        </div>
                                    </div>
                                @endforeach
                              </div>
                            {{-- @foreach($courses_main as $key => $value)
                                <div class="item col-12">
                                    <h4><?= $key ?></h4>
                                    @foreach($value as $key => $course)
                                        <p class="my-3 ml-3">-<?= $key ?>(<?= $course ?>)</p>
                                    @endforeach
                                </div>
                            @endforeach --}}
                        </div>
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
