function collectSymptomData() {
    const symptomsData = [];
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(symptom => {
        const symptomName = symptom.value;
        symptomsData.push({ name: symptomName });
    });
    return symptomsData;
}


function saveData() {
    const symptomsData = collectSymptomData();
    const data = {
        temperature: document.querySelector('input[name="temperature"]:checked')?.value,
        temperature_duration: document.getElementById("duration-select")?.value,
        chronic_diseases: Array.from(document.querySelectorAll('input[name="disease"]:checked')).map(d => d.value),
        risks: Array.from(document.querySelectorAll('input[name="risk"]:checked')).map(r => r.value),
        symptoms: symptomsData
    };

    fetch("./save_data.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("loading").style.display = "none"; // ซ่อนข้อความเมื่อเสร็จสิ้น
        alert(data.success ? "บันทึกข้อมูลสำเร็จ" : `เกิดข้อผิดพลาด: ${data.message}`);
    })
    .catch(error => {
        document.getElementById("loading").style.display = "none"; // ซ่อนข้อความหากเกิดข้อผิดพลาด
        console.error("Error:", error);
        alert("ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ กรุณาลองใหม่");
    });
}

// ฟังก์ชันจัดการการเปลี่ยนแปลงของ Checkbox
function handleCheckboxChange() {
    const alcoholCheckbox = document.getElementById('alcohol');
    const smokingCheckbox = document.getElementById('smoking');
    const noRiskCheckbox = document.getElementById('no_risk');
}

// ฟังก์ชันจัดการอุณหภูมิ
function handleTemperatureChange() {
    const temperatureDurationOptions = document.getElementById('temperature-duration');
    const tempLabels = {
        temp1: 'temp-green',
        temp2: 'temp-yellow',
        temp3: 'temp-orange',
        temp4: 'temp-red'
    };

    resetLabelColors();
    toggleTemperatureDuration();

    Object.keys(tempLabels).forEach(tempId => {
        if (document.getElementById(tempId).checked) {
            document.querySelector(`label[for="${tempId}"]`).classList.add(tempLabels[tempId]);
        }
    });
}

// รีเซ็ตสีของ Label
function resetLabelColors() {
    document.querySelectorAll('.checkbox-label').forEach(label => label.className = 'checkbox-label');
}

// แสดง/ซ่อนตัวเลือกระยะเวลาอุณหภูมิ
function toggleTemperatureDuration() {
    const temp2 = document.getElementById('temp2');
    const temp3 = document.getElementById('temp3');
    const temp4 = document.getElementById('temp4');
    document.getElementById('temperature-duration').style.display = (temp2.checked || temp3.checked || temp4.checked) ? 'block' : 'none';
}

// แสดง Slider เมื่อเลือก Checkbox
function toggleSlider(id) {
    const slider = document.getElementById(`slider-${id}`);
    const valueLabel = document.getElementById(`value-${id}`);
    const checkbox = document.getElementById(id);

    if (checkbox.checked) {
        slider.style.display = 'block';
        valueLabel.style.display = 'inline';
        updateSlider(slider.id, valueLabel.id);
    } else {
        slider.style.display = 'none';
        valueLabel.style.display = 'none';
    }
}

// อัปเดต Slider ตามระดับความรุนแรง
function updateSlider(sliderId, valueId) {
    const slider = document.getElementById(sliderId);
    const valueLabel = document.getElementById(valueId);
    const severity = slider.value;
    const colors = ['lightgreen', 'yellow', 'orange', 'red'];
    const severityTexts = ['เล็กน้อย', 'ปานกลาง', 'สูง', 'รุนแรงมาก'];
    
    slider.style.background = colors[Math.floor(severity / 3)];
    valueLabel.textContent = `ระดับ: ${severityTexts[Math.floor(severity / 3)]} ${severity}`;
}

function getColorValue(severityLevel) {
    const levels = { green: 2, yellow: 5, orange: 8, red: 10 };
    return severityLevel <= levels.green ? 'green' :
           severityLevel <= levels.yellow ? 'yellow' :
           severityLevel <= levels.orange ? 'orange' : 'red';
}
