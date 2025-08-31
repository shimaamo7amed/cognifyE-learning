<!DOCTYPE html>
<html lang="en">

<head>
    <title>New Instructors Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        table {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #fb9117;
            color: #ffffff;
            font-size: 16px;
        }
        td {
            background-color: #ecf0f1;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #7f8c8d;
            background: #ecf0f1;
            margin-top: 20px;
        }
        .footer a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <th colspan="2" style="text-align:center; font-size: 20px; background-color: #fb9117; color: #fff;">
                New Instructors Request 📩
            </th>
        </tr>
        <tr>
            <td><strong>English Name:</strong></td>
            <td>{{ $emailData['name_en'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Arabic Name:</strong></td>
            <td>{{ $emailData['name_ar'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Phone:</strong></td>
            <td>{{ $emailData['phone'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td><a href="mailto:{{ $emailData['email'] }}">{{ $emailData['email'] ?? 'N/A' }}</a></td>
        </tr>
          <tr>
            <td><strong>LinkedIn:</strong></td>
            <td>{{ $emailData['linkedIn'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Message:</strong></td>
            <td>{!! nl2br(e($emailData['message'] ?? 'No message provided.')) !!}</td>
        </tr>
        <tr>
         <tr>
            <td><strong>Experince:</strong></td>
            <td>{{ $emailData['experince'] ?? 'N/A' }}</td>
        </tr>
        <td><strong>CV:</strong></td>
        <td>
        <a href="{{ $emailData['cv'] ?? '#' }}" target="_blank" style="color: #2980b9; text-decoration: underline;">
            Download CV
        </a>
        </td>
        </tr>


    </table>
</body>

</html>
