<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "card_users";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_errno) {
        throw new Exception("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data)) {
        try {
            $temperature = $data['temperature'] ?? '';
            $temperature_duration = $data['temperature_duration'] ?? '';
            $chronic_diseases = implode(", ", $data['chronic_diseases'] ?? []);
            $risks = implode(", ", $data['risks'] ?? []);
            $full_name = $_SESSION['full_name'] ?? 'ไม่พบผู้ใช้';

            $symptoms = json_encode($data['symptoms'], JSON_UNESCAPED_UNICODE);

            $sql = "INSERT INTO assessments (full_name, temperature, temperature_duration, chronic_diseases, risks, symptoms) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("การเตรียมคำสั่งล้มเหลว: " . $conn->error);
            }

            $stmt->bind_param("ssssss", $full_name, $temperature, $temperature_duration, $chronic_diseases, $risks, $symptoms);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
            } else {
                throw new Exception("การบันทึกข้อมูลล้มเหลว: " . $stmt->error);
            }

            $stmt->close();

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่มีข้อมูล']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'วิธีการร้องขอไม่ถูกต้อง']);
}

$conn->close();


?>
