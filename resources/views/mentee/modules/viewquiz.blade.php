@extends('layouts.new_mentee')

@section('content')
<div class="container mt-5">
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2>{{ $chapter->title }} - Quiz</h2>
                </div>
                <div class="card-body">
                    <p>Answer the following questions based on the chapter content.</p>
                    <form action="{{ route('quiz.submit') }}" method="POST">
                        @csrf

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                        <input type="hidden" name="module_id" value="{{ $chapter->module_id }}">
                        @foreach($tests as $test)
                            <div class="quiz-section mb-4">
                                <h3>{{ $test->title }}</h3>
                                <p>{{ $test->description }}</p>
                                @foreach($test->questions as $question)
                                    <div class="question mb-3">
                                        <p><strong>{{ $loop->iteration }}. {{ $question->question_text }}</strong></p>
                                        <div class="options">
                                            @foreach($question->options as $option)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="question_{{ $question->id }}" id="option_{{ $option->id }}" value="{{ $option->id }}">
                                                    <label class="form-check-label" for="option_{{ $option->id }}">
                                                        {{ $option->option_text }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    
                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-4">Submit Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .container {
        margin-top: 30px;
    }
    body {
    overflow-x: hidden; /* Ensure the entire page cannot scroll horizontally */
}

    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0;
    }
    .form-check-label {
        font-size: 1rem;
        color: #555;
    }
    .form-check-input:checked + .form-check-label {
        color: #007bff;
    }
    .btn-success {
        padding: 10px 20px;
        font-size: 1.2rem;
    }
</style>
@endsection

@section('scripts')
<script>
    document.querySelector('.btn-success').addEventListener('click', function() {
        alert('Quiz submitted!');
    });
</script>
@endsection
