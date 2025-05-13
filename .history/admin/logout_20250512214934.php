<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .logout-container {
            text-align: center;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 4px solid #007bff;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="logout-container">
        <div class="spinner"></div>
        <h2>Logging Out...</h2>
        <p>Please wait while we log you out.</p>
    </div>

    <script>
        // Clear admin token from localStorage
        localStorage.removeItem('admin_token');

        // Redirect to login page after a short delay
        setTimeout(function() {
            window.location.href = 'login.html';
        }, 1000);
    </script>
</body>

</html>