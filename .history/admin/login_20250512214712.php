<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-form {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="login-form">
        <div class="login-logo">
            <h2>Admin Login</h2>
        </div>

        <div id="error-message" class="alert alert-danger" style="display: none;"></div>

        <form id="loginForm">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="mt-3 text-center">
            <a href="../index.html">Back to Website</a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Check if already logged in
        $(document).ready(function() {
            const token = localStorage.getItem('admin_token');
            if (token) {
                window.location.href = 'index.html';
            }

            // Handle login form submission
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                // Hide previous error messages
                $('#error-message').hide();

                // Get form values
                const username = $('#username').val().trim();
                const password = $('#password').val().trim();

                // Basic validation
                if (!username || !password) {
                    $('#error-message').text('Please fill in both fields.').show();
                    return;
                }

                // Send login request
                $.ajax({
                    type: 'POST',
                    url: 'https://farid-firdaus.lovestoblog.com/php/admin_api/login.php',
                    data: {
                        username: username,
                        password: password
                    },
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);

                            if (data.success) {
                                // Store token and redirect
                                localStorage.setItem('admin_token', data.token);
                                window.location.href = 'index.html';
                            } else {
                                // Show error message
                                $('#error-message').text(data.message || 'Login failed. Please check your credentials.').show();
                            }
                        } catch (e) {
                            $('#error-message').text('Invalid response from server.').show();
                            console.error('Error parsing JSON response:', e);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#error-message').text('Server error. Please try again later.').show();
                        console.error('AJAX Error:', status, error);
                    }
                });
            });
        });
    </script>
</body>

</html>