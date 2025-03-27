<?php
require_once 'db.php';
require_once 'models/Customer.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Trim and sanitize input
    $name = trim($_POST['name'] ?? '');
    $contact_name = trim($_POST['contact_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $vat = trim($_POST['vat'] ?? '');
    $xero_account = trim($_POST['xero_account'] ?? '');
    $invoice_due_date = trim($_POST['invoice_due_date'] ?? '');
    $profile_picture = null; // Default null for image

    // Validate required fields
    if (empty($name) || empty($contact_name) || empty($phone) || empty($email) || empty($country)) {
        echo json_encode(["success" => false, "message" => "All required fields (name, contact_name, phone, email, country) must be filled."]);
        exit;
    }

    // Handle Profile Picture Upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "uploads/";
        $imageFileType = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        // Validate file type
        if (!in_array($imageFileType, $allowed_types)) {
            echo json_encode(["success" => false, "message" => "Invalid file format. Only JPG, JPEG, PNG, GIF allowed."]);
            exit;
        }

        // Generate unique file name
        $new_filename = uniqid() . "." . $imageFileType;
        $target_file = $target_dir . $new_filename;

        // Resize and Save Image
        if (resizeImage($_FILES["profile_picture"]["tmp_name"], $target_file, $imageFileType)) {
            $profile_picture = $target_file;
        } else {
            echo json_encode(["success" => false, "message" => "Error processing image."]);
            exit;
        }
    }

    // Initialize Customer Model
    $customerModel = new Customer($conn);

    // Add Customer with profile picture
    $result = $customerModel->addCustomer([
        'name' => $name,
        'contact_name' => $contact_name,
        'phone' => $phone,
        'email' => $email,
        'country' => $country,
        'city' => $city,
        'state' => $state,
        'postal_code' => $postal_code,
        'vat' => $vat,
        'xero_account' => $xero_account,
        'invoice_due_date' => $invoice_due_date,
        'profile_picture' => $profile_picture
    ], $_FILES['profile_picture'] ?? null);

    echo $result;
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

/**
 * Resize and Save Image using GD Library
 */
function resizeImage($source, $destination, $type, $width = 300, $height = 300) {
    list($origWidth, $origHeight) = getimagesize($source);

    // Maintain aspect ratio
    $ratio = $origWidth / $origHeight;
    if ($width / $height > $ratio) {
        $width = $height * $ratio;
    } else {
        $height = $width / $ratio;
    }

    $imageResized = imagecreatetruecolor($width, $height);

    // Create image based on type
    switch ($type) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'png':
            $image = imagecreatefrompng($source);
            break;
        case 'gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            return false;
    }

    // Resize image
    imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

    // Save image based on type
    switch ($type) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($imageResized, $destination);
            break;
        case 'png':
            imagepng($imageResized, $destination);
            break;
        case 'gif':
            imagegif($imageResized, $destination);
            break;
    }

    return true;
}
?>
