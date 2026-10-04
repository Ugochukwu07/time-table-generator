@extends('layouts.app', ['title' => 'Venues'])

@section('content')
<section>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="form-head text-center d-flex flex-wrap mt-5 mb-sm-4 mb-3 align-items-center">
                    <div class="mx-auto d-lg-block mb-3">
                        <h2 class="text-black mb-0 font-w700">Venues</h2>
                        <p class="mb-0">Manage exam halls used by the time table generator</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 mx-auto col-lg-12">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">All Venues</h4>
                        <a href="{{ route('venues.create') }}" class="btn btn-success">+ Add Venue</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Capacity</th>
                                    <th>Available</th>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($venues as $venue)
                                    <tr>
                                        <td>{{ $venue->name }}</td>
                                        <td>{{ $venue->capacity }}</td>
                                        <td>
                                            @if($venue->is_available)
                                                <span class="badge badge-success">Available</span>
                                            @else
                                                <span class="badge badge-secondary">Unavailable</span>
                                            @endif
                                        </td>
                                        <td>{{ $venue->description }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('venues.edit', $venue) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('venues.destroy', $venue) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this venue?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No venues have been added yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="my-3">
                    <a class="text-success" href="{{ route('home') }}">Back Home</a>
                    &middot;
                    <a class="text-success" href="{{ route('scheduler.preview') }}">Preview Time Table</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
