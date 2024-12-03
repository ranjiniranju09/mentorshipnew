<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Session Created</title>
</head>
<body>
    <h2>New Session Created</h2>
    <p>Hello ,</p>
    <p>A new session has been created:</p>
    <ul>
        <li>Title: {{ $session->session_title }}</li>
        <li>Date and Time: {{ $session->sessiondatetime }}</li>
        <!-- Add more session details here -->
    </ul>
    <p>Thank you!</p>
</body>
</html>
