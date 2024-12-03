<!DOCTYPE html>
<html>
<head>
    <title>Module Completed Notification</title>
</head>
<body>
    <p>Dear {{ $mentee->name }},</p>

    <p>Your mentor, {{ $mentor->name }}, has marked the module "{{ $module->name }}" as completed.</p>

    <p>Keep up the great work!</p>

    <p>Best Regards,<br>FORSTU Team</p>
</body>
</html>
