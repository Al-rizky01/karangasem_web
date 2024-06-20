<?php
header("Content-Type: application/json");
include 'koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];
$response = array();

switch ($method) {
    case 'GET':
        // Read packages
        $sql = "SELECT id, name, description, price, image_path, duration, created_at, updated_at FROM package";
        $result = $conn->query($sql);

        $packages = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $packages[] = $row;
            }
        }

        echo json_encode($packages);
        break;

    case 'POST':
        // Create package
        $data = json_decode(file_get_contents("php://input"), true);

        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $image_path = $data['image_path'];
        $duration = $data['duration'];

        $sql = "INSERT INTO package (name, description, price, image_path, duration, created_at, updated_at)
                VALUES ('$name', '$description', '$price', '$image_path', '$duration', NOW(), NOW())";

        if ($conn->query($sql) === TRUE) {
            $response["status"] = "success";
            $response["message"] = "Package added successfully";
        } else {
            $response["status"] = "error";
            $response["message"] = "Error: " . $sql . "<br>" . $conn->error;
        }

        echo json_encode($response);
        break;

    case 'PUT':
        // Update package
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $image_path = $data['image_path'];
        $duration = $data['duration'];

        $sql = "UPDATE package SET 
                name = '$name', 
                description = '$description', 
                price = '$price', 
                image_path = '$image_path', 
                duration = '$duration', 
                updated_at = NOW() 
                WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            $response["status"] = "success";
            $response["message"] = "Package updated successfully";
        } else {
            $response["status"] = "error";
            $response["message"] = "Error: " . $sql . "<br>" . $conn->error;
        }

        echo json_encode($response);
        break;

    case 'DELETE':
        // Delete package
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];

        $sql = "DELETE FROM package WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            $response["status"] = "success";
            $response["message"] = "Package deleted successfully";
        } else {
            $response["status"] = "error";
            $response["message"] = "Error: " . $sql . "<br>" . $conn->error;
        }

        echo json_encode($response);
        break;

    default:
        $response["status"] = "error";
        $response["message"] = "Invalid request method";
        echo json_encode($response);
        break;
}

$conn->close();
?>
