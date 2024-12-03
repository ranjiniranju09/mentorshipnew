<!DOCTYPE html>
<html>
<head>
    <title>Quiz Submitted Notification</title>
</head>
<body>
    <p>Dear {{ $mentor->name }},</p>
    
    <p>Your mentee, {{ $mentee->mentee_name }}, has submitted a quiz for the module.</p>
    @if(isset($quizResult->score) && isset($quizResult->total_points))
        <p>Score: {{ $quizResult->score }}/{{ $quizResult->total_points }}</p>
    @else
        <p>Score information not available.</p>
    @endif

    <p>Keep guiding and motivating your mentee!</p>

    <p>Best Regards,<br>FORSTU Team</p>
</body>
</html>
