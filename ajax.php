<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

require_once 'config.php';

$action = $_POST['action'] ?? '';
$response = ["status" => "error", "message" => "Invalid action"];

function is_valid_student_id($sid) {
    // pattern: two digits, four capital letters, two digits (25ABCD01)
    return preg_match('/^[0-9]{2}[A-Z]{4}[0-9]{2}$/', $sid);
}

if ($action === "create" || $action === "update") {

    $id         = intval($_POST['id'] ?? 0);
    $student_id = strtoupper(trim($_POST['student_id'] ?? ''));
    $name       = trim($_POST['name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $course     = trim($_POST['course'] ?? '');
    $semester   = trim($_POST['semester'] ?? '');

    if ($student_id === '' || $name === '' || $email === '' || $course === '' || $semester === '') {
        $response['message'] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Invalid email format.";
    } elseif (!is_valid_student_id($student_id)) {
        $response['message'] = "Student ID must match pattern 25ABCD01.";
    } else {
        if ($action === "create") {
            $stmt = $conn->prepare(
                "INSERT INTO students (student_id, name, email, course, semester) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssss", $student_id, $name, $email, $course, $semester);
            if ($stmt->execute()) {
                $response = ["status" => "success", "message" => "Student added successfully."];
            } else {
                if ($conn->errno == 1062) {
                    $response['message'] = "Student ID already exists.";
                } else {
                    $response['message'] = "DB Error: " . $conn->error;
                }
            }
            $stmt->close();
        } else { // update
            $stmt = $conn->prepare(
                "UPDATE students SET student_id=?, name=?, email=?, course=?, semester=? WHERE id=?"
            );
            $stmt->bind_param("sssssi", $student_id, $name, $email, $course, $semester, $id);
            if ($stmt->execute()) {
                $response = ["status" => "success", "message" => "Student updated successfully."];
            } else {
                if ($conn->errno == 1062) {
                    $response['message'] = "Student ID already exists.";
                } else {
                    $response['message'] = "DB Error: " . $conn->error;
                }
            }
            $stmt->close();
        }
    }

} elseif ($action === "read") {

    $search = trim($_POST['search'] ?? '');
    $data = [];

    if ($search !== "") {
        $like = "%" . $search . "%";
        $stmt = $conn->prepare(
            "SELECT * FROM students
             WHERE student_id LIKE ? OR name LIKE ? OR email LIKE ? OR course LIKE ?
             ORDER BY id DESC"
        );
        $stmt->bind_param("ssss", $like, $like, $like, $like);
        $stmt->execute();
        $res = $stmt->get_result();
    } else {
        $res = $conn->query("SELECT * FROM students ORDER BY id DESC");
    }

    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
    $response = ["status" => "success", "data" => $data];

} elseif ($action === "get_single") {

    $id = intval($_POST['id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows) {
        $response = ["status" => "success", "data" => $res->fetch_assoc()];
    } else {
        $response['message'] = "Record not found.";
    }
    $stmt->close();

} elseif ($action === "delete") {

    $id = intval($_POST['id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $response = ["status" => "success", "message" => "Student deleted successfully."];
    } else {
        $response['message'] = "DB Error: " . $conn->error;
    }
    $stmt->close();
}

echo json_encode($response);
$conn->close();
