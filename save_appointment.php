<?php
/**
 * Backend Appointment Booking Handler (save_appointment.php)
 * Doctor Appointment Booking System
 * 
 * Handles POST requests from AJAX / Form submission,
 * validates user inputs, checks against past dates,
 * verifies doctor existence, and securely stores the appointment.
 */

// Force response type to JSON
header('Content-Type: application/json; charset=UTF-8');

// Include database connection
require_once 'db.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Only POST requests are allowed.'
    ]);
    exit;
}

// -------------------------------------------------------------
// 1. Sanitize & Retrieve Input Data
// -------------------------------------------------------------
$patient_name     = isset($_POST['patient_name']) ? trim($_POST['patient_name']) : '';
$phone            = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$doctor_id        = isset($_POST['doctor_id']) ? filter_var($_POST['doctor_id'], FILTER_VALIDATE_INT) : false;
$appointment_date = isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : '';
$appointment_time = isset($_POST['appointment_time']) ? trim($_POST['appointment_time']) : '';

// -------------------------------------------------------------
// 2. Comprehensive Input Validation
// -------------------------------------------------------------
$errors = [];

// Validate Patient Name
if (empty($patient_name)) {
    $errors[] = 'Patient full name is required.';
} elseif (strlen($patient_name) < 2 || strlen($patient_name) > 100) {
    $errors[] = 'Patient name must be between 2 and 100 characters.';
}

// Validate Phone Number (Allows 10-15 digits, optional + prefix, spaces or dashes)
if (empty($phone)) {
    $errors[] = 'Contact phone number is required.';
} elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
    $errors[] = 'Please enter a valid phone number.';
}

// Validate Doctor ID
if (!$doctor_id || $doctor_id <= 0) {
    $errors[] = 'Please select a valid doctor from the list.';
}

// Validate Appointment Date
if (empty($appointment_date)) {
    $errors[] = 'Appointment date is required.';
} else {
    $date_parts = explode('-', $appointment_date);
    if (count($date_parts) !== 3 || !checkdate((int)$date_parts[1], (int)$date_parts[2], (int)$date_parts[0])) {
        $errors[] = 'Invalid date format. Expected YYYY-MM-DD.';
    } else {
        // Prevent booking for past dates
        $today = date('Y-m-d');
        if ($appointment_date < $today) {
            $errors[] = 'Appointment date cannot be in the past. Please select today or a future date.';
        }
    }
}

// Validate Appointment Time Slot
if (empty($appointment_time)) {
    $errors[] = 'Please select an appointment time slot.';
}

// Return errors if any validation failed
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode([
        'status' => 'error',
        'message' => implode(' ', $errors),
        'errors' => $errors
    ]);
    exit;
}

// -------------------------------------------------------------
// 3. Verify Doctor Exists in Database
// -------------------------------------------------------------
$doc_stmt = $conn->prepare("SELECT id, name, specialization, consultation_fee FROM doctors WHERE id = ?");
if (!$doc_stmt) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Internal Server Error: Failed to prepare doctor verification query.'
    ]);
    exit;
}

$doc_stmt->bind_param("i", $doctor_id);
$doc_stmt->execute();
$doc_result = $doc_stmt->get_result();

if ($doc_result->num_rows === 0) {
    $doc_stmt->close();
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Selected doctor was not found in the clinic database.'
    ]);
    exit;
}

$doctor = $doc_result->fetch_assoc();
$doc_stmt->close();

// -------------------------------------------------------------
// 4. Insert Appointment Using Prepared Statements
// -------------------------------------------------------------
$insert_stmt = $conn->prepare(
    "INSERT INTO appointments (patient_name, phone, doctor_id, appointment_date, appointment_time) 
     VALUES (?, ?, ?, ?, ?)"
);

if (!$insert_stmt) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Internal Server Error: Failed to prepare appointment insertion query.'
    ]);
    exit;
}

$insert_stmt->bind_param("ssiss", $patient_name, $phone, $doctor_id, $appointment_date, $appointment_time);

if ($insert_stmt->execute()) {
    $new_appointment_id = $insert_stmt->insert_id;
    $insert_stmt->close();

    // Format human-readable date for confirmation (e.g. 24 Oct 2026)
    $formatted_date = date("d M Y", strtotime($appointment_date));

    // Return success response with complete confirmation payload
    http_response_code(201);
    echo json_encode([
        'status' => 'success',
        'message' => 'Appointment booked successfully!',
        'data' => [
            'appointment_id'    => $new_appointment_id,
            'patient_name'      => htmlspecialchars($patient_name),
            'phone'             => htmlspecialchars($phone),
            'doctor_name'       => $doctor['name'],
            'specialization'    => $doctor['specialization'],
            'consultation_fee'  => number_format((float)$doctor['consultation_fee'], 2, '.', ''),
            'appointment_date'  => $formatted_date,
            'raw_date'          => $appointment_date,
            'appointment_time'  => htmlspecialchars($appointment_time),
            'booking_time'      => date("d M Y, h:i A")
        ]
    ]);
    exit;
} else {
    $error_msg = $insert_stmt->error;
    $insert_stmt->close();
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to save appointment. Please try again. ' . $error_msg
    ]);
    exit;
}
?>
