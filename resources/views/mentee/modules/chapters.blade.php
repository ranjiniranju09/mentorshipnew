@extends('layouts.new_mentee')
@section('content')

<style>
    /* Existing and new styles */

    /* Adjusted Styles */

/* Container adjustments */
.container {
    margin: 10px auto;
    /* max-width:; Ensure it doesn't overflow the viewport width */
    padding: 0 15px; /* Add padding to prevent content from touching edges */
}
body {
    overflow-x: hidden; /* Ensure the entire page cannot scroll horizontally */
}

/* List group items */
.list-group-item {
    background-color: #f8f9fa !important;
    border: none;
    border-left: 4px solid transparent;
    color: #007bff;
    font-weight: bold;
    transition: all 0.3s ease;
    position: relative;
}

/* Separator line between items */
.list-group-item:not(:last-child)::after {
    content: "";
    display: block;
    width: 100%;
    height: 1px;
    background-color: #dcdcdc;
    position: absolute;
    bottom: 0;
    left: 0;
}

/* Navigation buttons */
.nav-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.nav-buttons .btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 16px;
    font-weight: bold;
}

.nav-buttons .btn:hover {
    background-color: #0056b3;
}

/* Chapter content section */
#chapter-details {
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    height: 100%; /* Ensure the content adjusts to screen height */
}

.chapter-content h4 {
    margin-top: 20px;
    font-size: 1.5rem;
    color: #343a40;
}

.chapter-content p {
    font-size: 1rem;
    color: #555;
    line-height: 1.6;
}

/* Status icons */
.status-icons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.status {
    display: inline-flex;
    align-items: center;
    font-size: 0.9rem;
    padding: 4px 8px;
    border-radius: 4px;
    background-color: #f1f1f1;
    color: #555;
    transition: background-color 0.3s;
}

.status i {
    margin-right: 4px;
}

.status.mastered { background-color: #5a4b81; color: white; }
.status.proficient { background-color: #6c757d; color: white; }
.status.familiar { background-color: #ffbf69; color: white; }
.status.attempted { background-color: #ff7f50; color: white; }
.status.not-started, .status.quiz { background-color: #d3d3d3; color: black; }
.status.unit-test { background-color: #333333; color: white; }


</style>

@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<div class="container ">
    <h2 class="text-center mb-3">{{ $module->name }}</h2>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div id="list-example" class="list-group">
                @foreach($chapters as $chapter)
                    <a class="list-group-item list-group-item-action" href="javascript:void(0);" onclick="showChapter({{ $chapter->id }})">
                        {{ $loop->iteration }}. {{ $chapter->chaptername }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="col-md-8">
            <div id="chapter-details">
                @foreach($chapters as $chapter)
                    <div id="chapter-content-{{ $chapter->id }}" class="chapter-content" style="display: none;">
                        <h4>{{ $chapter->chaptername }}</h4>
                        <div class="status-icons mt-2">
                        @if($maxResult && $maxResult->score === 4)
                            <span class="status mastered" title="Mastered"><i class="fas fa-crown"></i> Mastered</span>
                        @elseif($maxResult && $maxResult->score === 2)
                            <span class="status proficient" title="Proficient"><i class="fas fa-square"></i> Proficient</span>
                        @elseif($maxResult && $maxResult->score === 0)
                            <span class="status attempted" title="Attempted"><i class="fas fa-square"></i> Poor</span>
                        @else
                            <span class="status not-started" title="Not Started"><i class="fas fa-square"></i> Not started</span>
                        @endif

                           
                            <!-- <span class="status familiar" title="Familiar"><i class="fas fa-square"></i> Familiar</span> -->
                            <!-- <span class="status quiz" title="Quiz"><i class="fas fa-bolt"></i> Quiz</span> -->
                            <!-- <span class="status unit-test" title="Unit Test"><i class="fas fa-star"></i> Unit test</span> -->
                        </div>
                        <hr>

                        <p>{{ $chapter->description }}</p>
                        <p><small class="text-body-secondary">Last updated 3 mins ago</small></p>

                        <div class="nav-buttons">
                            <a href="{{ route('subchaptercontent', ['chapter_id' => $chapter->id]) }}" class="btn btn-primary">Get Started</a>

                            @if($chapter->has_mcq)
                                <a href="{{ route('viewquiz', ['chapter_id' => $chapter->id]) }}" class="btn">Start Quiz</a>
                            @else
                                <a href="{{ route('getDiscussionQuestions', ['chapter_id' => $chapter->id]) }}" class="btn">
                                    Discussion Points
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    function showChapter(chapterId) {
    console.log("Showing chapter ID:", chapterId); // Debugging line
    document.querySelectorAll('.chapter-content').forEach(content => content.style.display = 'none');
    document.getElementById('chapter-content-' + chapterId).style.display = 'block';
}

    document.addEventListener('DOMContentLoaded', () => {
        const firstChapter = document.querySelector('.chapter-content');
        if (firstChapter) {
            firstChapter.style.display = 'block';
        }
    });
    

</script>
@endsection
