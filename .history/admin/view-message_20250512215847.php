<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            transition: all 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-heading {
            padding: 20px 15px;
            font-size: 1.2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .message-content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            white-space: pre-line;
        }

        #loadingSpinner {
            display: flex;
            justify-content: center;
            padding: 50px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-heading d-flex align-items-center">
                    <i class="fas fa-user-shield me-2"></i>
                    <span>Admin Panel</span>
                </div>
                <div class="mt-4">
                    <a href="index.html">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="works.html">
                        <i class="fas fa-briefcase me-2"></i> Works
                    </a>
                    <a href="testimonials.html">
                        <i class="fas fa-quote-left me-2"></i> Testimonials
                    </a>
                    <a href="messages.html" class="active">
                        <i class="fas fa-envelope me-2"></i> Messages
                    </a>
                    <a href="javascript:void(0)" class="mt-5 text-danger" onclick="logout()">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">View Message</h1>
                    <div>
                        <a href="messages.html" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i> Back to Messages
                        </a>
                        <a href="#" id="replyButton" class="btn btn-primary">
                            <i class="fas fa-reply me-2"></i> Reply
                        </a>
                    </div>
                </div>

                <!-- Loading spinner -->
                <div id="loadingSpinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Error alert -->
                <div id="errorAlert" class="alert alert-danger" style="display: none;"></div>

                <!-- Message details -->
                <div id="messageCard" class="card" style="display: none;">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>From:</h5>
                                <p class="mb-0"><span id="senderName"></span> (<span id="senderEmail"></span>)</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h5>Date:</h5>
                                <p class="mb-0" id="messageDate"></p>
                            </div>
                        </div>

                        <hr>

                        <h5>Message:</h5>
                        <div class="message-content" id="messageContent"></div>

                        <div class="mt-4 text-end">
                            <button id="deleteButton" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Check authentication
        function checkAuth() {
            const token = localStorage.getItem('admin_token');
            if (!token) {
                window.location.href = 'login.html';
                return false;
            }
            return token;
        }

        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return date.toLocaleDateString('en-US', options);
        }

        // Get message ID from URL
        function getMessageId() {
            const urlParams = new URLSearchParams(window.location.search);
            const id = urlParams.get('id');

            if (!id || isNaN(id)) {
                window.location.href = 'messages.html';
                return null;
            }

            return id;
        }

        // Load message details
        function loadMessage(id) {
            const token = checkAuth();
            if (!token) return;

            $('#loadingSpinner').show();
            $('#messageCard').hide();
            $('#errorAlert').hide();

            $.ajax({
                url: 'https://farid-firdaus.lovestoblog.com/php/admin_api/get_message.php',
                type: 'GET',
                data: {
                    id: id
                },
                headers: {
                    'Authorization': token
                },
                success: function(response) {
                    $('#loadingSpinner').hide();

                    try {
                        const message = JSON.parse(response);

                        if (message && message.id) {
                            // Display message details
                            $('#senderName').text(message.name);
                            $('#senderEmail').text(message.email);
                            $('#messageDate').text(formatDate(message.created_at));
                            $('#messageContent').text(message.message);

                            // Set up reply button
                            $('#replyButton').attr('href', 'mailto:' + message.email);

                            // Show message card
                            $('#messageCard').show();

                            // Set up delete button
                            $('#deleteButton').off('click').on('click', function() {
                                if (confirm('Are you sure you want to delete this message?')) {
                                    deleteMessage(message.id);
                                }
                            });
                        } else {
                            $('#errorAlert').text('Message not found').show();
                            setTimeout(() => {
                                window.location.href = 'messages.html';
                            }, 2000);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        $('#errorAlert').text('Error loading message').show();
                    }
                },
                error: function() {
                    $('#loadingSpinner').hide();
                    $('#errorAlert').text('Failed to load message. Please try again.').show();
                }
            });
        }

        // Delete message
        function deleteMessage(id) {
            const token = checkAuth();
            if (!token) return;

            $.ajax({
                url: 'https://farid-firdaus.lovestoblog.com/php/admin_api/delete_message.php',
                type: 'POST',
                data: {
                    id: id
                },
                headers: {
                    'Authorization': token
                },
                success: function(response) {
                    try {
                        const result = JSON.parse(response);

                        if (result.success) {
                            alert('Message deleted successfully');
                            window.location.href = 'messages.html';
                        } else {
                            alert(result.message || 'Failed to delete message');
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        alert('Error processing server response');
                    }
                },
                error: function() {
                    alert('Server error. Please try again.');
                }
            });
        }

        // Logout function
        function logout() {
            localStorage.removeItem('admin_token');
            window.location.href = 'login.html';
        }

        // Initialize page
        $(document).ready(function() {
            const token = checkAuth();
            if (!token) return;

            const messageId = getMessageId();
            if (messageId) {
                loadMessage(messageId);
            }
        });
    </script>
</body>

</html>