<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages</title>
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
            white-space: pre-wrap;
            word-break: break-word;
        }

        .modal-message {
            max-height: 300px;
            overflow-y: auto;
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
                    <h1 class="h2">Messages</h1>
                </div>

                <!-- Loading indicator -->
                <div id="loadingIndicator" class="text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading messages...</p>
                </div>

                <!-- Error message -->
                <div id="errorAlert" class="alert alert-danger" style="display: none;">
                    Failed to load messages. Please try refreshing the page.
                </div>

                <!-- Messages table -->
                <div id="messagesContainer" style="display: none;">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="messagesTableBody">
                                        <!-- Messages will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- View Message Modal -->
    <div class="modal fade" id="viewMessageModal" tabindex="-1" aria-labelledby="viewMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMessageModalLabel">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold">From:</label>
                        <div id="messageFrom"></div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Email:</label>
                        <div id="messageEmail"></div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Date:</label>
                        <div id="messageDate"></div>
                    </div>
                    <div>
                        <label class="fw-bold">Message:</label>
                        <div id="messageContent" class="p-3 bg-light rounded modal-message message-content"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteMessageBtn">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Check if user is logged in
        function checkAuth() {
            const token = localStorage.getItem('admin_token');
            if (!token) {
                window.location.href = 'login.html';
            }
            return token;
        }

        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return date.toLocaleDateString('en-US', options);
        }

        // Truncate text
        function truncateText(text, length) {
            if (text.length <= length) return text;
            return text.substring(0, length) + '...';
        }

        // Load messages from API
        function loadMessages() {
            const token = checkAuth();

            $.ajax({
                url: 'https://farid-firdaus.lovestoblog.com/php/admin_api/get_messages.php',
                type: 'GET',
                headers: {
                    'Authorization': token
                },
                success: function(response) {
                    try {
                        const messages = JSON.parse(response);
                        displayMessages(messages);
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        showError();
                    }
                },
                error: function() {
                    showError();
                }
            });
        }

        // Display messages in table
        function displayMessages(messages) {
            let html = '';

            if (messages.length > 0) {
                messages.forEach(message => {
                    html += `
                        <tr data-id="${message.id}" data-message='${JSON.stringify(message).replace(/'/g, "\\'")}'>
                            <td>${message.id}</td>
                            <td>${message.name}</td>
                            <td><a href="mailto:${message.email}">${message.email}</a></td>
                            <td>${truncateText(message.message, 50)}</td>
                            <td>${formatDate(message.created_at)}</td>
                            <td>
                                <button class="btn btn-sm btn-info me-2 view-message" title="View Message">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-message" title="Delete Message">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                html = '<tr><td colspan="6" class="text-center">No messages found</td></tr>';
            }

            $('#messagesTableBody').html(html);
            $('#loadingIndicator').hide();
            $('#messagesContainer').show();

            // Attach event listeners
            $('.view-message').on('click', function() {
                const messageData = JSON.parse($(this).closest('tr').attr('data-message'));
                viewMessage(messageData);
            });

            $('.delete-message').on('click', function() {
                const id = $(this).closest('tr').data('id');
                if (confirm('Are you sure you want to delete this message?')) {
                    deleteMessage(id);
                }
            });
        }

        // Show error message
        function showError() {
            $('#loadingIndicator').hide();
            $('#errorAlert').show();
        }

        // View message details
        function viewMessage(message) {
            $('#messageFrom').text(message.name);
            $('#messageEmail').html(`<a href="mailto:${message.email}">${message.email}</a>`);
            $('#messageDate').text(formatDate(message.created_at));
            $('#messageContent').text(message.message);

            // Set up delete button
            $('#deleteMessageBtn').off('click').on('click', function() {
                if (confirm('Are you sure you want to delete this message?')) {
                    $('#viewMessageModal').modal('hide');
                    deleteMessage(message.id);
                }
            });

            // Show modal
            const viewMessageModal = new bootstrap.Modal(document.getElementById('viewMessageModal'));
            viewMessageModal.show();
        }

        // Delete message
        function deleteMessage(id) {
            const token = checkAuth();

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
                            // Remove row from table
                            $(`tr[data-id="${id}"]`).remove();

                            // Show success message
                            alert('Message deleted successfully!');

                            // If no more messages, reload to show "No messages found"
                            if ($('#messagesTableBody tr').length === 0) {
                                loadMessages();
                            }
                        } else {
                            alert(result.message || 'Failed to delete message.');
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        alert('An error occurred while deleting the message.');
                    }
                },
                error: function() {
                    alert('Failed to delete message. Please try again.');
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
            checkAuth();
            loadMessages();
        });
    </script>
</body>

</html>