<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
                    <a href="index.html" class="active">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="works.html">
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
                    <h1 class="h2">Dashboard</h1>
                </div>

                <!-- Dashboard cards -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card dashboard-card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Total Works</h6>
                                        <h2 class="mb-0" id="workCount">-</h2>
                                    </div>
                                    <i class="fas fa-briefcase fa-3x opacity-50"></i>
                                </div>
                                <a href="works.html" class="text-white">View all <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card dashboard-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Testimonials</h6>
                                        <h2 class="mb-0" id="testimonialCount">-</h2>
                                    </div>
                                    <i class="fas fa-quote-left fa-3x opacity-50"></i>
                                </div>
                                <a href="testimonials.html" class="text-white">View all <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card dashboard-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Messages</h6>
                                        <h2 class="mb-0" id="messageCount">-</h2>
                                    </div>
                                    <i class="fas fa-envelope fa-3x opacity-50"></i>
                                </div>
                                <a href="messages.html" class="text-white">View all <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent activities -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush" id="recentActivities">
                            <li class="list-group-item text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Dashboard JS -->
    <script>
        // Check if user is logged in
        function checkAuth() {
            const token = localStorage.getItem('admin_token');
            if (!token) {
                window.location.href = 'login.html';
            }
        }

        // Load dashboard data
        function loadDashboard() {
            // Load count data
            $.ajax({
                url: 'https://farid-firdaus.lovestoblog.com/php/admin_api/get_counts.php',
                type: 'GET',
                success: function(response) {
                    const data = JSON.parse(response);
                    $('#workCount').text(data.workCount);
                    $('#testimonialCount').text(data.testimonialCount);
                    $('#messageCount').text(data.messageCount);
                },
                error: function() {
                    alert('Failed to load dashboard data');
                }
            });

            // Load recent activities
            $.ajax({
                url: 'https://farid-firdaus.lovestoblog.com/php/admin_api/get_activities.php',
                type: 'GET',
                success: function(response) {
                    const activities = JSON.parse(response);
                    let html = '';

                    if (activities.length > 0) {
                        activities.forEach(activity => {
                            let icon = '';
                            let text = '';

                            switch (activity.type) {
                                case 'work':
                                    icon = '<i class="fas fa-briefcase text-primary"></i>';
                                    text = "New work added: " + activity.name;
                                    break;
                                case 'testimonial':
                                    icon = '<i class="fas fa-quote-left text-success"></i>';
                                    text = "New testimonial from: " + activity.name;
                                    break;
                                case 'message':
                                    icon = '<i class="fas fa-envelope text-info"></i>';
                                    text = "New message from: " + activity.name;
                                    break;
                            }

                            html += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="me-3">${icon}</span>
                                            ${text}
                                        </div>
                                        <small class="text-muted">${new Date(activity.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</small>
                                    </li>`;
                        });
                    } else {
                        html = '<li class="list-group-item">No recent activities</li>';
                    }

                    $('#recentActivities').html(html);
                },
                error: function() {
                    $('#recentActivities').html('<li class="list-group-item">Failed to load activities</li>');
                }
            });
        }

        // Logout function
        function logout() {
            localStorage.removeItem('admin_token');
            window.location.href = 'login.html';
        }

        // Execute on page load
        $(document).ready(function() {
            checkAuth();
            loadDashboard();
        });
    </script>
</body>

</html>