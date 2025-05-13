<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database configuration
require_once '../php/config.php';

// Initialize variables
$id = '';
$title = '';
$description = '';
$category = 'website';
$image = '';
$imageType = 'local'; // Default to local image
$imageUrl = ''; // For external URLs
$error = '';
$success = '';
$isEdit = false;

// Check if it's an edit request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $isEdit = true;
    
    // Get work data
    $sql = "SELECT * FROM works WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $work = $result->fetch_assoc();
        $title = $work['title'];
        $description = $work['description'];
        $category = $work['category'];
        $image = $work['image'];
        
        // Determine if it's an external URL
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            $imageType = 'external';
            $imageUrl = $image;
        }
    } else {
        header("Location: works.php");
        exit;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $category = $conn->real_escape_string($_POST['category']);
    $imageType = $_POST['imageType'];
    
    if ($imageType == 'external') {
        // Handle external URL
        if (!empty($_POST['imageUrl'])) {
            $imageUrl = $conn->real_escape_string($_POST['imageUrl']);
            // Validate URL
            if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                $image = $imageUrl;
            } else {
                $error = "Please enter a valid URL";
            }
        } else {
            $error = "Please enter an image URL";
        }
    } else {
        // Handle local file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            $filename = $_FILES['image']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($ext), $allowed)) {
                // Create uploads directory if it doesn't exist
                if (!file_exists('../uploads/')) {
                    mkdir('../uploads/', 0755, true);
                }
                
                $new_filename = uniqid() . '.' . $ext;
                $upload_path = '../uploads/' . $new_filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // If editing and new image is uploaded, delete old image if it's a local file
                    if ($isEdit && !empty($image) && !filter_var($image, FILTER_VALIDATE_URL)) {
                        if (file_exists("../uploads/" . $image)) {
                            unlink("../uploads/" . $image);
                        }
                    }
                    
                    $image = $new_filename;
                } else {
                    $error = "Failed to upload image";
                }
            } else {
                $error = "Invalid file format. Allowed formats: jpg, jpeg, png, gif";
            }
        } else if (!$isEdit) {
            $error = "Please select an image";
        }
    }
    
    // If no errors, save to database
    if (empty($error)) {
        $created_at = date('Y-m-d H:i:s');
        
        if ($isEdit) {
            $sql = "UPDATE works SET 
                    title = '$title', 
                    description = '$description', 
                    category = '$category'";
            
            if (!empty($image)) {
                $sql .= ", image = '$image'";
            }
            
            $sql .= " WHERE id = $id";
        } else {
            $sql = "INSERT INTO works (title, description, category, image, created_at) 
                    VALUES ('$title', '$description', '$category', '$image', '$created_at')";
        }
        
        if ($conn->query($sql) === TRUE) {
            $success = $isEdit ? "Work updated successfully" : "Work added successfully";
            
            if (!$isEdit) {
                // Clear form after successful add
                $title = '';
                $description = '';
                $category = 'website';
                $image = '';
                $imageUrl = '';
            }
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> Work</title>
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
        .preview-image {
            max-width: 200px;
            max-height: 120px;
            border-radius: 5px;
            margin-top: 10px;
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
                    <h1 class="h2"><?php echo $isEdit ? 'Edit' : 'Add'; ?> Work</h1>
                    <a href="works.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Works
                    </a>
                </div>
                
                <!-- Form -->
                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . ($isEdit ? "?id=$id" : "")); ?>" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $description; ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="website" <?php echo $category == 'website' ? 'selected' : ''; ?>>Website</option>
                                    <option value="design" <?php echo $category == 'design' ? 'selected' : ''; ?>>Design</option>
                                    <option value="other" <?php echo $category == 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Image Source</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="imageType" id="imageTypeLocal" value="local" <?php echo $imageType == 'local' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="imageTypeLocal">
                                        Upload Local Image
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="imageType" id="imageTypeExternal" value="external" <?php echo $imageType == 'external' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="imageTypeExternal">
                                        External Image URL
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3" id="localImageDiv" <?php echo $imageType == 'external' ? 'style="display:none;"' : ''; ?>>
                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" id="image" name="image" <?php echo ($isEdit && $imageType == 'local') ? '' : 'required'; ?>>
                                <small class="text-muted">Recommended size: 800x600 pixels</small>
                                
                                <?php if ($isEdit && !empty($image) && $imageType == 'local'): ?>
                                    <div class="mt-2">
                                        <p>Current image:</p>
                                        <img src="../uploads/<?php echo $image; ?>" class="preview-image" alt="Current Image">
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3" id="externalImageDiv" <?php echo $imageType == 'local' ? 'style="display:none;"' : ''; ?>>
                                <label for="imageUrl" class="form-label">Image URL</label>
                                <input type="url" class="form-control" id="imageUrl" name="imageUrl" value="<?php echo $imageUrl; ?>" placeholder="https://example.com/image.jpg">
                                <small class="text-muted">Enter the full URL to an image (including https://)</small>
                                
                                <?php if ($isEdit && !empty($image) && $imageType == 'external'): ?>
                                    <div class="mt-2">
                                        <p>Current image:</p>
                                        <img src="<?php echo $image; ?>" class="preview-image" alt="Current Image">
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> <?php echo $isEdit ? 'Update' : 'Save'; ?> Work
                            </button>
                        </form>
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
    $(document).ready(function() {
        // Toggle image upload fields
        $('input[name="imageType"]').change(function() {
            if ($(this).val() === 'local') {
                $('#localImageDiv').show();
                $('#externalImageDiv').hide();
                if (!$('#imageTypeExternal').is(':checked')) {
                    $('#image').prop('required', true);
                    $('#imageUrl').prop('required', false);
                }
            } else {
                $('#localImageDiv').hide();
                $('#externalImageDiv').show();
                $('#image').prop('required', false);
                $('#imageUrl').prop('required', true);
            }
        });
    });
    </script>
</body>
</html>