<!DOCTYPE html>
<html>
<head>
    <title>New Mentee Assigned</title>
</head>
<body>
    <p>Hello {{ $mapping->mentor->name }},</p>
    <p>You have been assigned a new mentee: {{ $mapping->mentee->name }}.</p>
    <p>Thank you for your mentorship!</p>
</body>
</html>
