<!DOCTYPE html>
<html>
<head>
    <title>Your Quiz Submission Details</title>
</head>
<body>
    <h1>Quiz Submission Confirmation</h1>

    <p>Dear {{ $mentee->mentee_name }},</p>

    <p>Thank you for submitting your quiz. Here are your results:</p>
    <p><strong>Score:</strong> {{ $score }}</p>

    <p>Keep up the great work!</p>

    <p>Best Regards,<br>FORSTU Team</p>
</body>
</html>
