<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "card_users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$sql = "SELECT full_name, temperature, temperature_duration, chronic_diseases, risks, symptoms FROM assessments ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ชื่อผู้ใช้</th>
                <th>อุณหภูมิ</th>
                <th>ระยะเวลาอุณหภูมิ</th>
                <th>โรคประจำตัว</th>
                <th>ความเสี่ยง</th>
                <th>อาการ</th>
                <th>ความรุนแรง</th>
            </tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["full_name"]) . "</td>
                <td>" . htmlspecialchars($row["temperature"]) . "</td>
                <td>" . htmlspecialchars($row["temperature_duration"]) . "</td>
                <td>" . htmlspecialchars($row["chronic_diseases"]) . "</td>
                <td>" . htmlspecialchars($row["risks"]) . "</td>";

        $symptoms = json_decode($row["symptoms"], true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($symptoms)) {
            echo "<td>";
            foreach ($symptoms as $symptom) {
                echo htmlspecialchars($symptom['name']) . "<br>";
            }
            echo "</td><td>";
            foreach ($symptoms as $symptom) {
                echo htmlspecialchars($symptom['severity']) . "<br>";
            }
            echo "</td>";
        } else {
            echo "<td>ไม่สามารถแสดงข้อมูล</td><td>ไม่สามารถแสดงข้อมูล</td>";
        }

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "ไม่พบข้อมูลในฐานข้อมูล";
}

$conn->close();
?>