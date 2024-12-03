@extends('layouts.new_mentee')

@section('content')

<style>
    /* Custom CSS styles for a professional look */
    .container {
        margin-top: 30px;
    }
    body {
    overflow-x: hidden; /* Ensure the entire page cannot scroll horizontally */
}

    img{
        width: 80%;
        height: 50%;
        align-items: center;
    }
    .list-group-item {
        background-color: #f8f9fa !important;
        border: none;
        border-left: 4px solid transparent;
        color: #007bff;
        font-weight: bold;
        transition: all 0.3s ease;
        position: relative;
    }

    /* Add a separator line after each list item, except the last one */
    .list-group-item:not(:last-child)::after {
        content: "";
        display: block;
        width: 100%;
        height: 1px;
        background-color: #dcdcdc; /* Light gray for the line */
        position: absolute;
        bottom: 0;
        left: 0;
    }

    /* Optional: Adjust the line between items */
    .list-group-item:not(:last-child) {
        border-bottom: 1.5px solid #e0e0e0;
    }

    .scrollspy-example {
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-height: 80vh;
        overflow-y: auto;
    }
    .scrollspy-example h4 {
        margin-top: 30px;
        font-size: 1.5rem;
        color: #343a40;
    }
    .scrollspy-example p {
        font-size: 1rem;
        color: #555;
        line-height: 1.6;
    }
</style>

@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div id="list-example" class="list-group">
                @foreach($subchapters as $index => $subchapter)
                    <a class="list-group-item list-group-item-action" href="javascript:void(0);" onclick="showSubchapter({{ $subchapter->id }})">
                        {{ $index + 1 }}. {{ $subchapter->title }}
                    </a>
                @endforeach
                <a class="list-group-item list-group-item-action" href="javascript:void(0);" onclick="showSubchapter('resources')">Resource List</a>
            </div>
        </div>
        <div class="col-md-8">
            <div class="scrollspy-example" id="subchapter-content">
                @foreach($subchapters as $subchapter)
                    <div id="subchapter-{{ $subchapter->id }}" class="subchapter-content" style="display: none;">
                        <center><h4>{{ $subchapter->title }}</h4></center>
                        <hr>
                        <center><img src="https://forstubucket1.s3.ap-south-1.amazonaws.com/elephant.jpg" alt="Elephant" /></center>
                        <p>{!! $subchapter->content !!}</p>
                            


                    </div>
                @endforeach
                
                <div id="subchapter-resources" class="subchapter-content" style="display: none;">
                    <h4>Resource List</h4>
                    <ul>
                        @foreach($moduleresources as $resource)
                            <li>
                                <strong>Title:</strong> {{ $resource->title }}<br>
                                <strong>Link:</strong> <a href="{{ $resource->link }}">{{ $resource->link }}</a><br>
                                <strong>Descriptive Material:</strong> {{ $resource->descriptivematerial }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    function showSubchapter(id) {
        // Hide all subchapters
        document.querySelectorAll('.subchapter-content').forEach(function(content) {
            content.style.display = 'none';
        });
        
        // Show the selected subchapter
        document.getElementById('subchapter-' + id).style.display = 'block';
    }

    // Show the first subchapter by default
    document.addEventListener('DOMContentLoaded', function() {
        const firstSubchapter = document.querySelector('.subchapter-content');
        if (firstSubchapter) {
            firstSubchapter.style.display = 'block';
        }
    });
</script>
@endsection
