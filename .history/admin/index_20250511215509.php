<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database configuration
require_once '../php/config.php';

// Get counts for dashboard
$workCount = $conn->query("SELECT COUNT(*) as count FROM works")->fetch_assoc()['count'];
$testimonialCount = $conn->query("SELECT COUNT(*) as count FROM testimonials")->fetch_assoc()['count'];
$messageCount = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
?>

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
        .sidebar a:hover, .sidebar a.active {
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
                    <a href="index.php" class="active">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="works.php">
                        <i class="fas fa-briefcase me-2"></i> Works
                    </a>
                    <a href="testimonials.php">
                        <i class="fas fa-quote-left me-2"></i> Testimonials
                    </a>
                    <a href="messages.php">
                        <i class="fas fa-envelope me-2"></i> Messages
                    </a>
                    <a href="logout.php" class="mt-5 text-danger">
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
                                        <h2 class="mb-0"><?php echo $workCount; ?></h2>
                                    </div>
                                    <i class="fas fa-briefcase fa-3x opacity-50"></i>
                                </div>
                                <a href="works.php" class="text-white">View all <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card dashboard-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Testimonials</h6>
                                        <h2 class="mb-0"><?php echo $testimonialCount; ?></h2>
                                    </div>
                                    <i class="fas fa-quote-left fa-3x opacity-50"></i>
                                </div>
                                <a href="testimonials.php" class="text-white">View all <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card dashboard-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Messages</h6>
                                        <h2 class="mb-0"><?php echo $messageCount; ?></h2>
                                    </div>
                                    <i class="fas fa-envelope fa-3x opacity-50"></i>
                                </div>
                                <a href="messages.php" class="text-white">View all <i class="fas fa-arrow-right ms-1"></i></a>
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
                        <ul class="list-group list-group-flush">
                            <?php
                            // Get recent activities
                            $sql = "SELECT 'work' as type, title as name, created_at FROM works 
                                    UNION 
                                    SELECT 'testimonial' as type, name, created_at FROM testimonials 
                                    UNION 
                                    SELECT 'message' as type, name, created_at FROM messages 
                                    ORDER BY created_at DESC LIMIT 5";
                            $result = $conn->query($sql);
                            
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $icon = '';
                                    $text = '';
                                    
                                    switch($row['type']) {
                                        case 'work':
                                            $icon = '<i class="fas fa-briefcase text-primary"></i>';
                                            $text = "New work added: " . $row['name'];
                                            break;
                                        case 'testimonial':
                                            $icon = '<i class="fas fa-quote-left text-success"></i>';
                                            $text = "New testimonial from: " . $row['name'];
                                            break;
                                        case 'message':
                                            $icon = '<i class="fas fa-envelope text-info"></i>';
                                            $text = "New message from: " . $row['name'];
                                            break;
                                    }
                                    
                                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="me-3">' . $icon . '</span>
                                                ' . $text . '
                                            </div>
                                            <small class="text-muted">' . date('M d, Y', strtotime($row['created_at'])) . '</small>
                                          </li>';
                                }
                            } else {
                                echo '<li class="list-group-item">No recent activities</li>';
                            }
                            ?>
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
</body>
</html>
