<?php
header("Content-Type: application/json");
include 'koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];
$response = array();

switch ($method) {
    case 'GET':
        // Read destinations
        $sql = "SELECT id, name, image_path, description FROM destination";
        $result = $conn->query($sql);

        $destinations = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $destinations[] = $row;
            }
        }

        echo json_encode($destinations);
        break;

    case 'POST':
        // Create destination
        $data = json_decode(file_get_contents("php://input"), true);

        $name = $data['name'];
        $image_path = $data['image_path'];
        $description = $data['description'];

        $sql = "INSERT INTO destination (name, image_path, description)
                VALUES ('$name', '$image_path', '$description')";

        if ($conn->query($sql) === TRUE) {
            $response["status"] = "success";
            $response["message"] = "Destination added successfully";
        } else {
            $response["status"] = "error";
            $response["message"] = "Error: " . $sql . "<br>" . $conn->error;
        }

        echo json_encode($response);
        break;

    case 'PUT':
        // Update destination
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $name = $data['name'];
        $image_path = $data['image_path'];
        $description = $data['description'];

        $sql = "UPDATE destination SET 
                name = '$name', 
                image_path = '$image_path', 
                description = '$description'
                WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            $response["status"] = "success";
            $response["message"] = "Destination updated successfully";
        } else {
            $response["status"] = "error";
            $response["message"] = "Error: " . $sql . "<br>" . $conn->error;
        }

        echo json_encode($response);
        break;

    case 'DELETE':
        // Delete destination
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];

        $sql = "DELETE FROM destination WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            $response["status"] = "success";
            $response["message"] = "Destination deleted successfully";
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
