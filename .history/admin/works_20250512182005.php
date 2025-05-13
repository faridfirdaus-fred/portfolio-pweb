<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database configuration
require_once '../php/config.php';

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];

    // Get image filename before deleting
    $result = $conn->query("SELECT image FROM works WHERE id = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = $row['image'];

        // Only delete local files, not external URLs
        if (!filter_var($image, FILTER_VALIDATE_URL) && file_exists("../uploads/" . $image)) {
            unlink("../uploads/" . $image);
        }
    }

    // Delete from database
    $conn->query("DELETE FROM works WHERE id = $id");

    // Redirect to refresh page
    header("Location: works.php");
    exit;
}

// Get all work items
$sql = "SELECT * FROM works ORDER BY id DESC";
$result = $conn->query($sql);
?>

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
                    <a href="works.php" class="active">
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
                    <h1 class="h2">Manage Works</h1>
                    <a href="work-form.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add New Work
                    </a>
                </div>

                <!-- Works table -->
                <div class="card">
                    <div class="card-body">
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
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            // Determine if image is external URL or local file
                                            $imgSrc = filter_var($row['image'], FILTER_VALIDATE_URL) ?
                                                $row['image'] : "../uploads/" . $row['image'];

                                            echo '<tr>
                                                    <td>' . $row['id'] . '</td>
                                                    <td><img src="' . $imgSrc . '" class="work-image" alt="' . $row['title'] . '"></td>
                                                    <td>' . $row['title'] . '</td>
                                                    <td><span class="badge bg-primary">' . $row['category'] . '</span></td>
                                                    <td>' . date('M d, Y', strtotime($row['created_at'])) . '</td>
                                                    <td>
                                                        <a href="work-form.php?id=' . $row['id'] . '" class="btn btn-sm btn-info me-2">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="works.php?delete=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this work?\')">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center">No works found</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
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