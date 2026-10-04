@extends('layouts.app', ['title' => 'Add Venue'])

@section('content')
<section>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="form-head text-center d-flex flex-wrap mt-5 mb-sm-4 mb-3 align-items-center">
                    <div class="mx-auto d-lg-block mb-3">
                        <h2 class="text-black mb-0 font-w700">Add Venue</h2>
                        <p class="mb-0">Register a new exam hall</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-7 mx-auto col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Venue Info</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('venues.store') }}" method="POST">
                            @csrf
                            <div class="row my-2">
                                <div class="col-md-8">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="e.g. Hall 1" value="{{ old('name') }}">
                                    @error('name')
                                        <small class="invalid-feedback d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="capacity">Capacity <span class="text-danger">*</span></label>
                                    <input type="number" min="1" class="form-control" name="capacity" placeholder="e.g. 120" value="{{ old('capacity') }}">
                                    @error('capacity')
                                        <small class="invalid-feedback d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-12">
                                    <label for="is_available">Available for Scheduling? <span class="text-danger">*</span></label>
                                    <select class="form-control" name="is_available" id="is_available">
                                        <option @if(old('is_available', '1') == '1') selected @endif value="1">Yes</option>
                                        <option @if(old('is_available') == '0') selected @endif value="0">No</option>
                                    </select>
                                    @error('is_available')
                                        <small class="invalid-feedback d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-12">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" rows="3" placeholder="Optional notes about this venue">{{ old('description') }}</textarea>
                                    @error('description')
                                        <small class="invalid-feedback d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-6 mx-auto">
                                    <button class="btn btn-success btn-block" type="submit">Save Venue</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="my-3 text-center">
                    <a class="text-success" href="{{ route('venues.index') }}">&larr; Back to Venues</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
