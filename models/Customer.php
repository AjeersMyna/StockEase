<?php
include "db.php"; // Database connection

class Customer {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCustomerById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM customers_1 WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCustomer($id, $data, $file = null) {
        try {
            $existingCustomer = $this->getCustomerById($id);
            $profile_image_path = $existingCustomer['profile_image'] ?? null;

            // Handle new profile picture upload
            if ($file && $file['error'] == UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) {
                    return json_encode(["success" => false, "message" => "Invalid image format. Only JPG, PNG, and GIF are allowed."]);
                }

                $upload_dir = "uploads/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $file_name = uniqid("profile_", true) . "." . $file_extension;
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    if ($profile_image_path && file_exists($profile_image_path)) {
                        unlink($profile_image_path);
                    }
                    $profile_image_path = $target_path;
                } else {
                    return json_encode(["success" => false, "message" => "Failed to upload profile image."]);
                }
            }

            $sql = "UPDATE customers_1 SET 
                    name = ?, contact_name = ?, phone = ?, email = ?, 
                    country = ?, xero_account = ?, profile_image = ? 
                    WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                $data["name"], $data["contact_name"], $data["phone"], 
                $data["email"], $data["country"], $data["xero_account"], 
                $profile_image_path, $id
            ]);

            return json_encode(["success" => $result, "message" => $result ? "Customer updated successfully!" : "Failed to update customer."]);
        } catch (Exception $e) {
            return json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
        }
    }

    public function getCustomers($search = "", $limit = 10, $offset = 0) {
        $sql = "SELECT * FROM customers_1 WHERE 1";
        $params = [];
    
        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR contact_name LIKE ? OR email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
    
        // ✅ Fix: Directly concatenate LIMIT & OFFSET (safe because they are integers)
        $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function addCustomer($data, $file) {
        try {
            $profile_image_path = null;
            if ($file && $file['error'] == UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($file['type'], $allowed_types)) {
                    return json_encode(["success" => false, "message" => "Invalid image format."]);
                }

                $upload_dir = "uploads/";
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $file_name = uniqid("profile_", true) . "." . $file_extension;
                $target_path = $upload_dir . $file_name;

                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    $this->resizeImage($target_path, 300);
                    $profile_image_path = $target_path;
                } else {
                    return json_encode(["success" => false, "message" => "Failed to upload profile image."]);
                }
            }

            $sql = "INSERT INTO customers_1 
                    (name, contact_name, phone, email, country, city, state, postal_code, vat, xero_account, invoice_due_date, profile_image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                $data['name'], $data['contact_name'], $data['phone'], 
                $data['email'], $data['country'], $data['city'] ?? null, 
                $data['state'] ?? null, $data['postal_code'] ?? null, 
                $data['vat'] ?? null, $data['xero_account'] ?? null, 
                $data['invoice_due_date'] ?? null, $profile_image_path
            ]);

            return json_encode(["success" => true, "message" => "Customer added successfully!", "image" => $profile_image_path]);
        } catch (Exception $e) {
            return json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
        }
    }

    private function resizeImage($file, $max_width) {
        list($orig_width, $orig_height, $image_type) = getimagesize($file);
        if ($orig_width <= $max_width) return;

        $ratio = $max_width / $orig_width;
        $new_width = $max_width;
        $new_height = intval($orig_height * $ratio);

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
                return;
        }

        $dst = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);

        imagejpeg($dst, $file, 80);
        imagedestroy($src);
        imagedestroy($dst);
    }

    public function getTotalCustomers($search = "") {
        $sql = "SELECT COUNT(*) as total FROM customers_1";
        $params = [];

        if (!empty($search)) {
            $sql .= " WHERE name LIKE ? OR contact_name LIKE ? OR email LIKE ?";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
