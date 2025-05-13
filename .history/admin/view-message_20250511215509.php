<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database configuration
require_once '../php/config.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: messages.php");
    exit;
}

$id = $_GET['id'];

// Get message data
$sql = "SELECT * FROM messages WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows != 1) {
    header("Location: messages.php");
    exit;
}

$message = $result->fetch_assoc();
?>

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
        .sidebar a:hover, .sidebar a.active {
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
                    <a href="index.php">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="works.php">
                        <i class="fas fa-briefcase me-2"></i> Works
                    </a>
                    <a href="testimonials.php">
                        <i class="fas fa-quote-left me-2"></i> Testimonials
                    </a>
                    <a href="messages.php" class="active">
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
                    <h1 class="h2">View Message</h1>
                    <div>
                        <a href="messages.php" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i> Back to Messages
                        </a>
                        <a href="mailto:<?php echo $message['email']; ?>" class="btn btn-primary">
                            <i class="fas fa-reply me-2"></i> Reply
                        </a>
                    </div>
                </div>
                
                <!-- Message details -->
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>From:</h5>
                                <p class="mb-0"><?php echo $message['name']; ?> (<?php echo $message['email']; ?>)</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h5>Date:</h5>
                                <p class="mb-0"><?php echo date('F d, Y H:i', strtotime($message['created_at'])); ?></p>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5>Message:</h5>
                        <div class="message-content">
                            <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <a href="messages.php?delete=<?php echo $message['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">
                                <i class="fas fa-trash me-2"></i> Delete
                            </a>
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
</body>
</html>
