@extends('layouts.mentor')
@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

 
<div class="content">
    <!-- Display Jobs Table -->
    <div class="display-jobs">
        <h2>Jobs Details</h2>
        <span>
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addJobModal">
                Add Job
            </button>
            <!-- Add Job Modal -->
                <div class="modal fade" id="addJobModal" tabindex="-1" role="dialog" aria-labelledby="addJobModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addJobModalLabel">Add New Job</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('opportunity.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="title">Title :</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="opportunity_type">Opportunity Type</label>
                                        <select class="form-control" id="opportunity_type" name="opportunity_type">
                                            <option>= Select Here =</option>
                                            <option value="Jobs">Job</option>
                                            <option value="Internships">Internships</option>
                                            <option value="Fellowships">Fellowships</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="link">Related Link:</label>
                                        <input type="url" class="form-control" id="link" name="link" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Job</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#editJobModal">
                Edit Job
            </button> -->
            </span>
        <table class="table table-bordered job-table">
            <thead class="table-dark">
                <tr>
                    <th>Job ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Related Link</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($opportunities as $opportunity)
                <tr>
                    <td>{{ $opportunity->id }}</td>
                    <td>{{ $opportunity->title }}</td>
                    <td>{{ $opportunity->opportunity_type }}</td>
                    <td><a href="{{ $opportunity->link }}" target="_blank">{{ $opportunity->link }}</a></td>
                    <td>
                        <!-- Edit Button -->
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editJobModal{{ $opportunity->id }}">
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <form action="{{ route('opportunity.destroy', $opportunity->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this opportunity?');">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    <!-- Edit Modal for Each Opportunity -->
                    <div class="modal fade" id="editJobModal{{ $opportunity->id }}" tabindex="-1" role="dialog" aria-labelledby="editJobModalLabel{{ $opportunity->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editJobModalLabel{{ $opportunity->id }}">Edit Job</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('opportunity.update', $opportunity->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="title{{ $opportunity->id }}">Title:</label>
                                            <input type="text" class="form-control" id="title{{ $opportunity->id }}" name="title" value="{{ $opportunity->title }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="opportunity_type{{ $opportunity->id }}">Opportunity Type</label>
                                            <select class="form-control" id="opportunity_type{{ $opportunity->id }}" name="opportunity_type">
                                                <option value="Jobs" {{ $opportunity->opportunity_type == 'Jobs' ? 'selected' : '' }}>Job</option>
                                                <option value="Internships" {{ $opportunity->opportunity_type == 'Internships' ? 'selected' : '' }}>Internships</option>
                                                <option value="Fellowships" {{ $opportunity->opportunity_type == 'Fellowships' ? 'selected' : '' }}>Fellowships</option>
                                                <option value="Others" {{ $opportunity->opportunity_type == 'Others' ? 'selected' : '' }}>Others</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="link{{ $opportunity->id }}">Related Link:</label>
                                            <input type="url" class="form-control" id="link{{ $opportunity->id }}" name="link" value="{{ $opportunity->link }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')

@endpush
