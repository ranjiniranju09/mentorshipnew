<!DOCTYPE html>
<html>
<head>
    <title>Task Submission Confirmation</title>
</head>
<body>
    <h1>Task Submission Confirmation</h1>
    <p>Hello {{ $mentee->name }},</p>
    <p>You have successfully submitted your task.</p>
    <p><strong>Task Response:</strong> {{ $taskResponse }}</p>
    @if($submittedFileUrl)
        <p><strong>Submitted File:</strong> <a href="{{ $submittedFileUrl }}">Download File</a></p>
    @endif
    <p>Thank you for your submission!</p>
</body>
</html>
