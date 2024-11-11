<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ฟอร์มสำหรับปริ้นต์</title>
    <link rel="stylesheet" href="ปริ้น.css"> 
    <script src="ปริ้น.js"></script>
</head>
<body>
    <div class="form-container">
        <h1>ฟอร์มการประเมินเบื้องต้น</h1>
        <div id="printable-area">
            <p><strong>ชื่อ-นามสกุล:</strong> <span id="fullName"></span></p>
            <p><strong>อายุ:</strong> <span id="age"></span></p>
            <p><strong>เลขประจำตัวประชาชน:</strong> <span id="idNumber"></span></p>
            <hr>
            <h2>ข้อมูลอาการ</h2>
            <table id="symptoms-table">
                <thead>
                    <tr>
                        <th>วันที่</th>
                        <th>เวลา</th>
                        <th>อาการ</th>
                        <th>ระดับความรุนแรง</th>
                        <th>เฉดสี</th>
                    </tr>
                </thead>
                <tbody id="symptoms-body">
                    <!-- ข้อมูลอาการจะถูกเพิ่มที่นี่ -->
                </tbody>
            </table>
            <hr>
            <h3>อุณหภูมิ</h3>
            <p id="temperature-info"></p>
            <h3>โรคประจำตัว</h3>
            <p id="chronic-diseases-info"></p>
            <h3>ความเสี่ยง</h3>
            <p id="risks-info"></p>
        </div>
        <button onclick="printForm()">ปริ้นต์ฟอร์ม</button>
    </div>
</body>
</html>