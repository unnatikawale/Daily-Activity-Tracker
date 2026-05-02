<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .field {
            margin-bottom: 20px;
        }
        .field label {
            font-weight: bold;
            color: #667eea;
            display: block;
            margin-bottom: 5px;
        }
        .field-value {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        .message-field .field-value {
            min-height: 100px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 New Contact Form Submission</h1>
            <p>Daily Activity Tracker</p>
        </div>

        <div class="field">
            <label>👤 Name:</label>
            <div class="field-value">{{ $contact->name }}</div>
        </div>

        <div class="field">
            <label>📧 Email Address:</label>
            <div class="field-value">{{ $contact->email }}</div>
        </div>

        <div class="field">
            <label>📋 Subject:</label>
            <div class="field-value">{{ $contact->subject }}</div>
        </div>

        <div class="field message-field">
            <label>💬 Message:</label>
            <div class="field-value">{{ nl2br(e($contact->message)) }}</div>
        </div>

        <div class="footer">
            <p>This message was sent from the Daily Activity Tracker contact form.</p>
            <p>Received on: {{ $contact->created_at->format('l, F j, Y \a\t g:i A') }}</p>
        </div>
    </div>
</body>
</html>
