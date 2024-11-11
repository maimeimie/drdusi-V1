<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

$host = 'localhost';
$dbname = 'card_users';
$username = 'root';
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

$full_name = "";
$identification = "";
$birthday = "";
$sex = "";
$age = ""; 
$formatted_birthday = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_card'])) {
        $id_card = $_POST['id_card'];

        $query = "SELECT * FROM `users_dusi` WHERE id_card = :id_card";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_card', $id_card);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $name_title = htmlspecialchars($result['name_title']);
            $name = htmlspecialchars($result['name']);
            $surname = htmlspecialchars($result['surname']);
            $identification = htmlspecialchars($result['identification']);
            $birthday = htmlspecialchars($result['birthday']);
            $sex = htmlspecialchars($result['sex']);

            $full_name = htmlspecialchars($result['name_title'] . ' ' . $result['name'] . ' ' . $result['surname']);
            $_SESSION['full_name'] = $full_name;

            // คำนวณอายุ
            $birthDate = new DateTime($birthday);
            $currentDate = new DateTime();
            $age = $birthDate->diff($currentDate)->y;

            // แปลงวันเกิดให้เป็นปี พ.ศ.
            if ($birthday) {
                $date = new DateTime($birthday);
                $year = $date->format('Y');
                $thai_year = $year + 543;
                
                $thai_months = [
                    "January" => "มกราคม", "February" => "กุมภาพันธ์", "March" => "มีนาคม",
                    "April" => "เมษายน", "May" => "พฤษภาคม", "June" => "มิถุนายน",
                    "July" => "กรกฎาคม", "August" => "สิงหาคม", "September" => "กันยายน",
                    "October" => "ตุลาคม", "November" => "พฤศจิกายน", "December" => "ธันวาคม"
                ];

                // แสดงวัน เดือน และปี พ.ศ.
                $formatted_birthday = $date->format('d ') . $thai_months[$date->format('F')] . " " . $thai_year; 
            }
        } else {
            echo "ไม่พบข้อมูลในระบบ";
        }
    }
    $logo = ($sex === 'หญิง') ? 'drtest/image/logo_female.png' : 'drtest/image/logo_male.png';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login_css/user.css">
    <title>Login</title>
</head>
<body>
            <div class="login-box">
                <div class="login-header">
                    <img src="<?php echo $logo; ?>" alt="Logo" class="logo">
                </div>
                <form action="drtest/Home.html" method="POST">
                    <div class="input-box">
                        <div class="centered-id">
                            <input type="text" name="identification" class="input-field" readonly 
                                value="<?php echo 'หมายเลขประจำตัวประชาชน ' . htmlspecialchars($identification); ?>">
                        </div>
                        <div class="two-columns">
                            <div class="column">
                                <input type="text" name="full_name" class="input-field" readonly 
                                    value="<?php echo htmlspecialchars($full_name); ?>">
                                <input type="text" name="sex" class="input-field" readonly 
                                    value="<?php echo 'เพศ ' . htmlspecialchars($sex); ?>">
                            </div>
                            <div class="column">
                                <input type="text" name="birthday" class="input-field" readonly 
                                    value="<?php echo 'วันเกิด ' . htmlspecialchars($formatted_birthday); ?>">
                                <input type="text" name="age" class="input-field" readonly 
                                    value="<?php echo 'อายุ ' . htmlspecialchars($age) . ' ปี'; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="input-submit">
                        <button type="submit" class="submit-btn" id="submit">ยืนยันข้อมูล</button>
                    </div>
                </form>
            </div>
            </div>
        </form>
    </div>
</body>
</html>