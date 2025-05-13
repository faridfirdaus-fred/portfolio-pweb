<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Testimonials</title>
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

        .testimonial-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        #loadingSpinner {
            display: flex;
            justify-content: center;
            padding: 30px;
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
                    <a href="testimonials.html" class="active">
                        <i class="fas fa-quote-left me-2"></i> Testimonials
                    </a>
                    <a href="messages.html">
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
                    <h1 class="h2">Manage Testimonials</h1>
                    <a href="testimonial-form.html" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add New Testimonial
                    </a>
                </div>

                <!-- Alert messages -->
                <div id="alertMessage" class="alert alert-success" style="display: none;"></div>

                <!-- Testimonials table -->
                <div class="card">
                    <div class="card-body">
                        <div id="loadingSpinner">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div id="testimonialsTable" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Content</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="testimonialsTableBody">
                                        <!-- Testimonials will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
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
                month: 'short',
                day: 'numeric'
            };
            return date.toLocaleDateString('en-US', options);
        }

        // Truncate text
        function truncateText(text, length) {
            if (text.length <= length) return text;
            return text.substring(0, length) + '...';
        }

        // Load all testimonials
        function loadTestimonials() {
            const token = checkAuth();
            if (!token) return;

            $('#loadingSpinner').show();
            $('#testimonialsTable').hide();

            $.ajax({
                url: 'https://farid-firdaus.lovestoblog.com/php/admin_api/get_testimonials_admin.php',
                type: 'GET',
                headers: {
                    'Authorization': token
                },
                success: function(response) {
                    $('#loadingSpinner').hide();
                    $('#testimonialsTable').show();

                    try {
                        const testimonials = JSON.parse(response);
                        let html = '';

                        if (testimonials.length > 0) {
                            testimonials.forEach(testimonial => {
                                html += `
                                    <tr>
                                        <td>${testimonial.id}</td>
                                        <td><img src="https://farid-firdaus.lovestoblog.com/uploads/${testimonial.image}" class="testimonial-image" alt="${testimonial.name}"></td>
                                        <td>${testimonial.name}</td>
                                        <td>${testimonial.position}</td>
                                        <td>${truncateText(testimonial.content, 50)}</td>
                                        <td>${formatDate(testimonial.created_at)}</td>
                                        <td>
                                            <a href="testimonial-form.html?id=${testimonial.id}" class="btn btn-sm btn-info me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger" onclick="deleteTestimonial(${testimonial.id})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            html = '<tr><td colspan="7" class="text-center">No testimonials found</td></tr>';
                        }

                        $('#testimonialsTableBody').html(html);

                        // Check for success message in URL
                        const urlParams = new URLSearchParams(window.location.search);
                        if (urlParams.has('success')) {
                            $('#alertMessage').text(urlParams.get('message')).show();

                            // Remove parameters from URL without reloading
                            window.history.replaceState({}, document.title, 'testimonials.html');

                            // Hide message after 3 seconds
                            setTimeout(() => {
                                $('#alertMessage').fadeOut();
                            }, 3000);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        $('#testimonialsTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading testimonials</td></tr>');
                    }
                },
                error: function() {
                    $('#loadingSpinner').hide();
                    $('#testimonialsTable').show();
                    $('#testimonialsTableBody').html('<tr><td colspan="7" class="text-center text-danger">Failed to load testimonials</td></tr>');
                }
            });
        }

        // Delete testimonial
        function deleteTestimonial(id) {
            if (!confirm('Are you sure you want to delete this testimonial?')) return;

            const token = checkAuth();
            if (!token) return;

            $.ajax({
                url: 'https://farid-firdaus.lovestoblog.com/php/admin_api/delete_testimonial.php',
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
                            $('#alertMessage').removeClass('alert-danger').addClass('alert-success').text('Testimonial deleted successfully').show();
                            loadTestimonials();

                            // Hide message after 3 seconds
                            setTimeout(() => {
                                $('#alertMessage').fadeOut();
                            }, 3000);
                        } else {
                            $('#alertMessage').removeClass('alert-success').addClass('alert-danger').text(result.message || 'Error deleting testimonial').show();
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        $('#alertMessage').removeClass('alert-success').addClass('alert-danger').text('Error processing server response').show();
                    }
                },
                error: function() {
                    $('#alertMessage').removeClass('alert-success').addClass('alert-danger').text('Failed to delete testimonial').show();
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
            loadTestimonials();
        });
    </script>
</body>

</html>