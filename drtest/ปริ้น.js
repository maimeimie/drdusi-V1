window.onload = async function() {
    try {
        const userData = await fetchUserData();
        if (userData) {
            displayUserData(userData);
            displaySymptomsData(userData.symptoms);
            displayAdditionalData(userData);
        } else {
            alert('ไม่พบข้อมูลผู้ใช้');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการดึงข้อมูล');
    }
};

// ฟังก์ชันดึงข้อมูลผู้ใช้จากฐานข้อมูล
async function fetchUserData() {
    const response = await fetch('get_user_data.php');
    const result = await response.json();
    return result.success ? result.data : null;
}

// ฟังก์ชันแสดงข้อมูลผู้ใช้
function displayUserData(data) {
    document.getElementById('fullName').textContent = data.fullName || '';
    document.getElementById('age').textContent = data.age || '';
    document.getElementById('idNumber').textContent = data.idNumber || '';
}

// ฟังก์ชันแสดงข้อมูลอาการ
function displaySymptomsData(symptomsData) {
    const symptomsBody = document.getElementById('symptoms-body');
    symptomsData.forEach(item => {
        const [date, time] = item.time.split(' ');
        const severityLevelText = getSeverityLevelText(item.severity);
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${date}</td>
            <td>${time}</td>
            <td>${item.symptom}</td>
            <td>${severityLevelText} (${item.severity})</td>
            <td style="background-color: ${item.color}">${item.color}</td>
        `;
        symptomsBody.appendChild(row);
    });
}

// ฟังก์ชันแสดงข้อมูลเพิ่มเติม
function displayAdditionalData(data) {
    document.getElementById('temperature-info').textContent = data.temperature_duration ? `${data.temperature} (${data.temperature_duration})` : data.temperature;
    document.getElementById('chronic-diseases-info').textContent = data.chronic_diseases.length > 0 ? data.chronic_diseases.join(', ') : 'ไม่มี';
    document.getElementById('risks-info').textContent = data.risk_duration ? `${data.risks.join(', ')} (${data.risk_duration})` : (data.risks.length > 0 ? data.risks.join(', ') : 'ไม่มี');
}

// ฟังก์ชันแปลงระดับความรุนแรงเป็นข้อความ
function getSeverityLevelText(severity) {
    if (severity >= 0 && severity <= 2) return 'ระดับเล็กน้อย';
    else if (severity >= 3 && severity <= 5) return 'ระดับปานกลาง';
    else if (severity >= 6 && severity <= 8) return 'ระดับสูง';
    else if (severity >= 9 && severity <= 10) return 'ระดับรุนแรงมาก';
    return '';
}