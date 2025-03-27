<?php
include "db.php"; // Database connection

class Customer {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCustomerById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM customers_1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateCustomer($id, $data, $file = null) {
        // Fetch the existing customer data (to get the old image path)
        $existingCustomer = $this->getCustomerById($id);
        $profile_image_path = $existingCustomer['profile_image'] ?? null; // Keep old image by default
    
        // Handle new profile picture upload
        if ($file && $file['error'] == UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowed_types)) {
                return json_encode(["success" => false, "message" => "Invalid image format. Only JPG, PNG, and GIF are allowed."]);
            }
    
            // Define upload directory
            $upload_dir = "uploads/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
    
            // Generate unique file name
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name = uniqid("profile_", true) . "." . $file_extension;
            $target_path = $upload_dir . $file_name;
    
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                // Delete old profile image if exists
                if ($profile_image_path && file_exists($profile_image_path)) {
                    unlink($profile_image_path);
                }
                $profile_image_path = $target_path; // Update new profile image path
            } else {
                return json_encode(["success" => false, "message" => "Failed to upload profile image."]);
            }
        }
    
        // Update customer details in the database
        $sql = "UPDATE customers_1 SET 
                name = ?, 
                contact_name = ?, 
                phone = ?, 
                email = ?, 
                country = ?, 
                xero_account = ?, 
                profile_image = ? 
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return json_encode(["success" => false, "message" => "SQL Error: " . $this->conn->error]);
        }
    
        $stmt->bind_param(
            "sssssssi",
            $data["name"],
            $data["contact_name"],
            $data["phone"],
            $data["email"],
            $data["country"],
            $data["xero_account"],
            $profile_image_path,
            $id
        );
    
        $result = $stmt->execute();
        $stmt->close();
    
        return json_encode(["success" => $result, "message" => $result ? "Customer updated successfully!" : "Failed to update customer."]);
    }
    
    public function getCustomers($search = "", $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM customers_1 WHERE 1";
        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR contact_name LIKE ? OR email LIKE ?)";
        }
        $sql .= " LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$search%";
        if (!empty($search)) {
            $stmt->bind_param("sssii", $searchTerm, $searchTerm, $searchTerm, $limit, $offset);
        } else {
            $stmt->bind_param("ii", $limit, $offset);
        }

        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function addCustomer($data, $file) {
        // Handle missing optional fields
        $city = $data['city'] ?? null;
        $state = $data['state'] ?? null;
        $postal_code = $data['postal_code'] ?? null;
        $vat = $data['vat'] ?? null;
        $xero_account = $data['xero_account'] ?? null;
        $invoice_due_date = $data['invoice_due_date'] ?? null;
        $profile_image_path = null;

        // 🔹 Handle File Upload
        if ($file && $file['error'] == UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowed_types)) {
                return json_encode(["success" => false, "message" => "Invalid image format. Only JPG, PNG, and GIF are allowed."]);
            }

            // Define upload directory
            $upload_dir = "uploads/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate unique file name
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name = uniqid("profile_", true) . "." . $file_extension;
            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                // Resize image before saving
                $this->resizeImage($target_path, 300); // Max width 300px
                $profile_image_path = $target_path;
            } else {
                return json_encode(["success" => false, "message" => "Failed to upload profile image."]);
            }
        }

        // Prepare SQL statement
        $stmt = $this->conn->prepare("INSERT INTO customers_1 
            (name, contact_name, phone, email, country, city, state, postal_code, vat, xero_account, invoice_due_date, profile_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            return json_encode(["success" => false, "message" => "SQL Error: " . $this->conn->error]);
        }

        // Bind parameters
        $stmt->bind_param(
            "ssssssssssis",
            $data['name'],
            $data['contact_name'],
            $data['phone'],
            $data['email'],
            $data['country'],
            $city,
            $state,
            $postal_code,
            $vat,
            $xero_account,
            $invoice_due_date,
            $profile_image_path
        );

        // Execute statement
        $result = $stmt->execute();
        if (!$result) {
            return json_encode(["success" => false, "message" => "Error adding customer: " . $stmt->error]);
        }

        return json_encode(["success" => true, "message" => "Customer added successfully!", "image" => $profile_image_path]);
    }

    private function resizeImage($file, $max_width) {
        list($orig_width, $orig_height, $image_type) = getimagesize($file);
        if ($orig_width <= $max_width) return; // No resizing needed

        $ratio = $max_width / $orig_width;
        $new_width = $max_width;
        $new_height = intval($orig_height * $ratio);

        // Create image resource
        switch ($image_type) {
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($file);
                break;
            case IMAGETYPE_GIF:
                $src = imagecreatefromgif($file);
                break;
            default:
                return; // Unsupported format
        }

        $dst = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);

        // Save resized image
        imagejpeg($dst, $file, 80); // Save as JPEG with 80% quality

        // Free memory
        imagedestroy($src);
        imagedestroy($dst);
    }

    public function getTotalCustomers($search = "") {
        if (!empty($search)) {
            $sql = "SELECT COUNT(*) as total FROM customers_1 WHERE name LIKE ? OR contact_name LIKE ? OR email LIKE ?";
            $stmt = $this->conn->prepare($sql);
            $searchTerm = "%$search%";
            $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        } else {
            $sql = "SELECT COUNT(*) as total FROM customers_1";
            $stmt = $this->conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }
}
?>
