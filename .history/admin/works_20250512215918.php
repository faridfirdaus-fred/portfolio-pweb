<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Works</title>
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

        .work-image {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
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
                    <a href="works.html" class="active">
                        <i class="fas fa-briefcase me-2"></i> Works
                    </a>
                    <a href="testimonials.html">
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
                    <h1 class="h2">Manage Works</h1>
                    <a href="work-form.html" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add New Work
                    </a>
                </div>

                <!-- Alert messages -->
                <div id="alertMessage" class="alert alert-success" style="display: none;"></div>

                <!-- Works table -->
                <div class="card">
                    <div class="card-body">
                        <!-- Loading spinner -->
                        <div id="loadingSpinner">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div id="worksTableContainer" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="worksTableBody">
                                        <!-- Works will be loaded here -->
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

        // Load all works
        function loadWorks() {
            const token = checkAuth();
            if (!token) return;

            $('#loadingSpinner').show();
            $('#worksTableContainer').hide();

            $.ajax({
                url: 'https://farid-firdaus.lovestoblog.com/php/admin_api/get_works_admin.php',
                type: 'GET',
                headers: {
                    'Authorization': token
                },
                success: function(response) {
                    $('#loadingSpinner').hide();
                    $('#worksTableContainer').show();

                    try {
                        const works = JSON.parse(response);
                        let html = '';

                        if (works.length > 0) {
                            works.forEach(work => {
                                // Determine if image is external URL or local file
                                const imgSrc = work.image.startsWith('http://') || work.image.startsWith('https://') ?
                                    work.image :
                                    'https://farid-firdaus.lovestoblog.com/uploads/' + work.image;

                                html += `
                                    <tr>
                                        <td>${work.id}</td>
                                        <td><img src="${imgSrc}" class="work-image" alt="${work.title}"></td>
                                        <td>${work.title}</td>
                                        <td><span class="badge bg-primary">${work.category}</span></td>
                                        <td>${formatDate(work.created_at)}</td>
                                        <td>
                                            <a href="work-form.html?id=${work.id}" class="btn btn-sm btn-info me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger" onclick="deleteWork(${work.id})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            html = '<tr><td colspan="6" class="text-center">No works found</td></tr>';
                        }

                        $('#worksTableBody').html(html);

                        // Check for success message in URL
                        const urlParams = new URLSearchParams(window.location.search);
                        if (urlParams.has('success')) {
                            $('#alertMessage').text(urlParams.get('message') || 'Operation successful').show();

                            // Remove parameters from URL without reloading
                            window.history.replaceState({}, document.title, 'works.html');

                            // Hide message after 3 seconds
                            setTimeout(() => {
                                $('#alertMessage').fadeOut();
                            }, 3000);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        $('#worksTableBody').html('<tr><td colspan="6" class="text-center text-danger">Error loading works</td></tr>');
                    }
                },
                error: function() {
                    $('#loadingSpinner').hide();
                    $('#worksTableContainer').show();
                    $('#worksTableBody').html('<tr><td colspan="6" class="text-center text-danger">Failed to load works</td></tr>');
                }
            });
        }

        // Delete work
        function deleteWork(id) {
            if (!confirm('Are you sure you want to delete this work?')) return;

            const token = checkAuth();
            if (!token) return;

            $.ajax({
                url: 'https://farid-firdaus.lovestoblog.com/php/admin_api/delete_work.php',
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
                            $('#alertMessage').removeClass('alert-danger').addClass('alert-success').text('Work deleted successfully').show();
                            loadWorks();

                            // Hide message after 3 seconds
                            setTimeout(() => {
                                $('#alertMessage').fadeOut();
                            }, 3000);
                        } else {
                            $('#alertMessage').removeClass('alert-success').addClass('alert-danger').text(result.message || 'Error deleting work').show();
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        $('#alertMessage').removeClass('alert-success').addClass('alert-danger').text('Error processing server response').show();
                    }
                },
                error: function() {
                    $('#alertMessage').removeClass('alert-success').addClass('alert-danger').text('Server error. Please try again.').show();
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
            loadWorks();
        });
    </script>
</body>

</html>