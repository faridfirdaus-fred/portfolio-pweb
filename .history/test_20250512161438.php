<?php
$password = 'newpassword123'; // Password asli
$hash = '$2y$10$eImiTXuWVxfM37uY4JANjQe5Jxq2z1p5f5l9z9eWl9z9eWl9z9eWl'; // Hash dari database

if (password_verify($password, $hash)) {
    echo "Password matches!";
} else {
    echo "Password does not match!";
}
exit; // Hentikan eksekusi untuk debugging
