<?php
// CORS Headers for cross-domain access
header("Access-Control-Allow-Origin: https://faridfirdaus-fred.github.io/portfolio-pweb");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}