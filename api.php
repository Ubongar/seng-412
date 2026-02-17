<?php
/**
 * CRUD API Handler
 * Handles all Create, Read, Update, Delete operations via AJAX
 */
header('Content-Type: application/json');
include 'db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$entity = $_POST['entity'] ?? $_GET['entity'] ?? '';

$response = ['success' => false, 'message' => 'Invalid request'];

try {
    switch ($entity) {

        // ===================== MEMBERS =====================
        case 'member':
            switch ($action) {
                case 'create':
                    $stmt = $conn->prepare("INSERT INTO members (matric_no, full_name, blood_group, state_of_origin, phone, hobbies) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $_POST['matric_no'], $_POST['full_name'], $_POST['blood_group'], $_POST['state_of_origin'], $_POST['phone'], $_POST['hobbies']);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'Member added successfully', 'id' => $conn->insert_id];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to add member: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'update':
                    $stmt = $conn->prepare("UPDATE members SET matric_no=?, full_name=?, blood_group=?, state_of_origin=?, phone=?, hobbies=? WHERE id=?");
                    $stmt->bind_param("ssssssi", $_POST['matric_no'], $_POST['full_name'], $_POST['blood_group'], $_POST['state_of_origin'], $_POST['phone'], $_POST['hobbies'], $_POST['id']);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'Member updated successfully'];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to update member: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'delete':
                    $stmt = $conn->prepare("DELETE FROM members WHERE id=?");
                    $stmt->bind_param("i", $_POST['id']);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'Member deleted successfully'];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to delete member: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'get':
                    $stmt = $conn->prepare("SELECT * FROM members WHERE id=?");
                    $stmt->bind_param("i", $_GET['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $member = $result->fetch_assoc();
                    if ($member) {
                        $response = ['success' => true, 'data' => $member];
                    } else {
                        $response = ['success' => false, 'message' => 'Member not found'];
                    }
                    $stmt->close();
                    break;
            }
            break;

        // ===================== COURSES =====================
        case 'course':
            switch ($action) {
                case 'create':
                    $stmt = $conn->prepare("INSERT INTO courses (course_code, course_title, credit_units, department, lecturer) VALUES (?, ?, ?, ?, ?)");
                    $units = floatval($_POST['credit_units']);
                    $stmt->bind_param("ssdss", $_POST['course_code'], $_POST['course_title'], $units, $_POST['department'], $_POST['lecturer']);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'Course added successfully', 'id' => $conn->insert_id];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to add course: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'update':
                    $stmt = $conn->prepare("UPDATE courses SET course_code=?, course_title=?, credit_units=?, department=?, lecturer=? WHERE id=?");
                    $units = floatval($_POST['credit_units']);
                    $stmt->bind_param("ssdssi", $_POST['course_code'], $_POST['course_title'], $units, $_POST['department'], $_POST['lecturer'], $_POST['id']);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'Course updated successfully'];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to update course: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'delete':
                    $stmt = $conn->prepare("DELETE FROM courses WHERE id=?");
                    $stmt->bind_param("i", $_POST['id']);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'Course deleted successfully'];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to delete course: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'get':
                    $stmt = $conn->prepare("SELECT * FROM courses WHERE id=?");
                    $stmt->bind_param("i", $_GET['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $course = $result->fetch_assoc();
                    if ($course) {
                        $response = ['success' => true, 'data' => $course];
                    } else {
                        $response = ['success' => false, 'message' => 'Course not found'];
                    }
                    $stmt->close();
                    break;
            }
            break;

        // ===================== EMPLOYEES =====================
        case 'employee':
            switch ($action) {
                case 'create':
                    $stmt = $conn->prepare("INSERT INTO employees (emp_id, full_name, department, hours_worked, hourly_rate, deductions) VALUES (?, ?, ?, ?, ?, ?)");
                    $hours = floatval($_POST['hours_worked']);
                    $rate = floatval($_POST['hourly_rate']);
                    $ded = floatval($_POST['deductions']);
                    $stmt->bind_param("sssddd", $_POST['emp_id'], $_POST['full_name'], $_POST['department'], $hours, $rate, $ded);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'Employee added successfully', 'id' => $conn->insert_id];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to add employee: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'update':
                    $stmt = $conn->prepare("UPDATE employees SET emp_id=?, full_name=?, department=?, hours_worked=?, hourly_rate=?, deductions=? WHERE id=?");
                    $hours = floatval($_POST['hours_worked']);
                    $rate = floatval($_POST['hourly_rate']);
                    $ded = floatval($_POST['deductions']);
                    $stmt->bind_param("sssdddi", $_POST['emp_id'], $_POST['full_name'], $_POST['department'], $hours, $rate, $ded, $_POST['id']);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'Employee updated successfully'];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to update employee: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'delete':
                    $stmt = $conn->prepare("DELETE FROM employees WHERE id=?");
                    $stmt->bind_param("i", $_POST['id']);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'Employee deleted successfully'];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to delete employee: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'get':
                    $stmt = $conn->prepare("SELECT * FROM employees WHERE id=?");
                    $stmt->bind_param("i", $_GET['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $emp = $result->fetch_assoc();
                    if ($emp) {
                        $response = ['success' => true, 'data' => $emp];
                    } else {
                        $response = ['success' => false, 'message' => 'Employee not found'];
                    }
                    $stmt->close();
                    break;
            }
            break;

        // ===================== GPA RECORDS =====================
        case 'gpa':
            switch ($action) {
                case 'create':
                    $score = intval($_POST['score']);
                    // Compute grade and grade_point from score
                    if ($score >= 80) { $grade = 'A'; $gp = 5; }
                    elseif ($score >= 60) { $grade = 'B'; $gp = 4; }
                    elseif ($score >= 50) { $grade = 'C'; $gp = 3; }
                    elseif ($score >= 45) { $grade = 'D'; $gp = 2; }
                    elseif ($score >= 40) { $grade = 'E'; $gp = 1; }
                    else { $grade = 'F'; $gp = 0; }

                    $stmt = $conn->prepare("INSERT INTO gpa_records (member_id, course_id, score, grade, grade_point) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("iiisi", $_POST['member_id'], $_POST['course_id'], $score, $grade, $gp);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'GPA record added successfully', 'id' => $conn->insert_id];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to add GPA record: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'update':
                    $score = intval($_POST['score']);
                    if ($score >= 80) { $grade = 'A'; $gp = 5; }
                    elseif ($score >= 60) { $grade = 'B'; $gp = 4; }
                    elseif ($score >= 50) { $grade = 'C'; $gp = 3; }
                    elseif ($score >= 45) { $grade = 'D'; $gp = 2; }
                    elseif ($score >= 40) { $grade = 'E'; $gp = 1; }
                    else { $grade = 'F'; $gp = 0; }

                    $stmt = $conn->prepare("UPDATE gpa_records SET member_id=?, course_id=?, score=?, grade=?, grade_point=? WHERE id=?");
                    $stmt->bind_param("iiisii", $_POST['member_id'], $_POST['course_id'], $score, $grade, $gp, $_POST['id']);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'GPA record updated successfully'];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to update GPA record: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'delete':
                    $stmt = $conn->prepare("DELETE FROM gpa_records WHERE id=?");
                    $stmt->bind_param("i", $_POST['id']);
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'GPA record deleted successfully'];
                    } else {
                        $response = ['success' => false, 'message' => 'Failed to delete GPA record: ' . $stmt->error];
                    }
                    $stmt->close();
                    break;

                case 'get':
                    $stmt = $conn->prepare("SELECT * FROM gpa_records WHERE id=?");
                    $stmt->bind_param("i", $_GET['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $rec = $result->fetch_assoc();
                    if ($rec) {
                        $response = ['success' => true, 'data' => $rec];
                    } else {
                        $response = ['success' => false, 'message' => 'GPA record not found'];
                    }
                    $stmt->close();
                    break;
            }
            break;
    }

} catch (Exception $e) {
    $response = ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
}

$conn->close();
echo json_encode($response);
