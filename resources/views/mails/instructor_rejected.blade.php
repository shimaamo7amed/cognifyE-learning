<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Instructor Rejected</title>
  </head>
  <body
    style="
      margin: 0;
      padding: 20px;
      background-color: #f9fafb;
      font-family: 'Poppins', Arial, sans-serif;
    "
  >
    <div
      style="
        max-width: 400px;
        margin: 0 auto;
        background-color: white;
        border-radius: 24px;
        padding: 40px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      "
    >
      <div style="margin-bottom: 30px">
      <img src="{{ $message->embed(public_path('storage/logos/logo.png')) }}" alt="Cognify Logo">
      </div>

      <h1
        style="
          color: #dc2626;
          font-size: 28px;
          margin-bottom: 20px;
          font-weight: 600;
        "
      >
        Sorry, {{ $name_en }} 😔
      </h1>

      <p
        style="
          color: #6b7280;
          font-size: 16px;
          line-height: 1.6;
          margin-bottom: 30px;
        "
      >
        Unfortunately, your request to join as an instructor has been
        <strong style="color:#dc2626;">rejected</strong> at this time.
      </p>

      <div
        style="
          background-color: #f3f4f6;
          border-radius: 12px;
          padding: 16px;
          margin: 12px 0;
          text-align: left;
        "
      >
        <span style="font-weight: 600; color: #1f2937">Reason:</span>
        <p style="color: #dc2626; margin: 8px 0; font-size: 14px;">
          {{ $reason }}
        </p>
      </div>

      <p
        style="
          color: #6b7280;
          font-size: 14px;
          margin-top: 20px;
        "
      >
        You can update your profile and reapply again at any time.  
        Thank you for your interest in joining us!
      </p>

      <div style="margin-top: 30px">
        <a
          target="_blank"
          href="https://ra-qmy.vercel.app/auth/signup"
          style="
            display: inline-block;
            background-color: #dc2626;
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 500;
          "
        >
          Update Profile & Reapply
        </a>
      </div>
    </div>
  </body>
</html>
