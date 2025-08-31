<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Instructor Accepted</title>
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
          color: #fb9117;
          font-size: 28px;
          margin-bottom: 20px;
          font-weight: 600;
        "
      >
        Welcome, {{ $name_en }} 👋
      </h1>

      <p
        style="
          color: #6b7280;
          font-size: 16px;
          line-height: 1.6;
          margin-bottom: 30px;
        "
      >
        Your account has been successfully created and activated. You can now
        access all features.
      </p>

      <div
        style="
          background-color: #f3f4f6;
          border-radius: 12px;
          padding: 16px;
          margin: 12px 0;
          text-align: left;
          display: flex;
          justify-content: space-between;
          align-items: center;
        "
      >
        <span style="font-weight: 600; color: #1f2937">Email:</span>
        <div style="display: flex; align-items: center; gap: 8px">
          <span style="color: #fb9117">{{ $email }}</span>
          <button
            onclick="copyToClipboard('{{ $email }}', this)"
            style="
              background: none;
              border: none;
              cursor: pointer;
              padding: 4px;
            "
          >
            <svg
              class="copy-icon"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="#fb9117"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
              <path
                d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"
              ></path>
            </svg>
            <svg
              class="check-icon"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="#fb9117"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              style="display: none"
            >
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </button>
        </div>
      </div>

      <div
        style="
          background-color: #f3f4f6;
          border-radius: 12px;
          padding: 16px;
          margin: 12px 0;
          text-align: left;
          display: flex;
          justify-content: space-between;
          align-items: center;
        "
      >
        <span style="font-weight: 600; color: #1f2937">Password:</span>
        <div style="display: flex; align-items: center; gap: 8px">
          <span style="color: #fb9117">{{ $password }}</span>
          <button
            onclick="copyToClipboard('{{ $password }}', this)"
            style="
              background: none;
              border: none;
              cursor: pointer;
              padding: 4px;
            "
          >
            <svg
              class="copy-icon"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="#fb9117"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
              <path
                d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"
              ></path>
            </svg>
            <svg
              class="check-icon"
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="#fb9117"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              style="display: none"
            >
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </button>
        </div>
      </div>

      <p
        style="
          color: #6b7280;
          font-size: 14px;
          font-style: italic;
          margin-top: 10px;
          margin-bottom: 20px;
        "
      >
        Tip: For security reasons, we recommend changing your password after
        your first login.
      </p>

      <div style="margin-top: 30px">
        <a
          target="_blank"
          href="https://ra-qmy.vercel.app/auth/signin"
          style="
            display: inline-block;
            background-color: #fb9117;
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 500;
          "
        >
          Get Started Now
        </a>
      </div>
    </div>

    <script>
      function copyToClipboard(text, button) {
        navigator.clipboard
          .writeText(text)
          .then(() => {
            const copyIcon = button.querySelector(".copy-icon");
            const checkIcon = button.querySelector(".check-icon");

            // Hide copy icon and show check icon
            copyIcon.style.display = "none";
            checkIcon.style.display = "block";

            // After 2 seconds, revert back to copy icon
            setTimeout(() => {
              copyIcon.style.display = "block";
              checkIcon.style.display = "none";
            }, 200);
          })
          .catch((err) => {
            console.error("Failed to copy text: ", err);
          });
      }
    </script>
  </body>
</html>