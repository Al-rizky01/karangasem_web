<?php
header("Content-Type: application/json");
include 'koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];
$response = array();

switch ($method) {
    case 'GET':
        // Read products
        $sql = "SELECT id, name, description, price, image_path, created_at, updated_at FROM product";
        $result = $conn->query($sql);

        $products = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }

        echo json_encode($products);
        break;

    case 'POST':
        // Create product
        $data = json_decode(file_get_contents("php://input"), true);

        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $image_path = $data['image_path'];

        $sql = "INSERT INTO product (name, description, price, image_path, created_at, updated_at)
                VALUES ('$name', '$description', '$price', '$image_path', NOW(), NOW())";

        if ($conn->query($sql) === TRUE) {
            $response["status"] = "success";
            $response["message"] = "Product added successfully";
        } else {
            $response["status"] = "error";
            $response["message"] = "Error: " . $sql . "<br>" . $conn->error;
        }

        echo json_encode($response);
        break;

    case 'PUT':
        // Update product
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $image_path = $data['image_path'];

        $sql = "UPDATE product SET 
                name = '$name', 
                description = '$description', 
                price = '$price', 
                image_path = '$image_path', 
                updated_at = NOW() 
                WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            $response["status"] = "success";
            $response["message"] = "Product updated successfully";
        } else {
            $response["status"] = "error";
            $response["message"] = "Error: " . $sql . "<br>" . $conn->error;
        }

        echo json_encode($response);
        break;

    case 'DELETE':
        // Delete product
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];

        $sql = "DELETE FROM product WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            $response["status"] = "success";
            $response["message"] = "Product deleted successfully";
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
