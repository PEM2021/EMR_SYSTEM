<?php
// Only accept POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json"); // Respond with JSON

    // Read and decode JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Validate that data was received and required fields are present
    $requiredFields = [
        'patientName', 'dob', 'visitDate', 'provider', 'chiefComplaint',
        'hpi', 'ros', 'vitals', 'physicalExam', 'diagnosis',
        'plan', 'cptCodes', 'hcpcsCodes', 'modifiers'
    ];

    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Missing required field: $field"
            ]);
            exit;
        }
    }

    // Database configuration
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db   = 'emr_db';

    // Connect to MySQL
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Database connection failed."
        ]);
        exit;
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("
        INSERT INTO emr_records (
            patient_name, dob, visit_date, provider, chief_complaint, hpi, ros, 
            vitals, physical_exam, diagnosis, plan, cpt_codes, hcpcs_codes, modifiers
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Failed to prepare SQL statement."
        ]);
        exit;
    }

    // Bind parameters
    $stmt->bind_param("ssssssssssssss",
        $data['patientName'],
        $data['dob'],
        $data['visitDate'],
        $data['provider'],
        $data['chiefComplaint'],
        $data['hpi'],
        $data['ros'],
        $data['vitals'],
        $data['physicalExam'],
        $data['diagnosis'],
        $data['plan'],
        $data['cptCodes'],
        $data['hcpcsCodes'],
        $data['modifiers']
    );

    // Execute and return response
    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "✅ EMR successfully saved to database.",
            "record_id" => $stmt->insert_id
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "❌ Failed to save: " . $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    header("Content-Type: application/json");
    echo json_encode([
        "status" => "error",
        "message" => "Method not allowed. Use POST."
    ]);
}
?>
