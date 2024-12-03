<!DOCTYPE html>
<html>
<head>
    <title>Task Submitted by Mentee</title>
</head>
<body>
    <h1>Task Submitted by Mentee</h1>
    <p>Hello {{ $mentee->mentor_name }},</p>
    <p>Your mentee {{ $mentee->name }} has successfully submitted a task.</p>
    <p><strong>Task Response:</strong> {{ $taskResponse }}</p>
    @if($submittedFileUrl)
        <p><strong>Submitted File:</strong> <a href="{{ $submittedFileUrl }}">Download File</a></p>
    @endif
    <p>Thank you!</p>
</body>
</html>
