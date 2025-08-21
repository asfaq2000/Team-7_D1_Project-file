<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Scheme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #f8fafc;
            --accent-color: #818cf8;
            --success-color: #10b981;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--text-primary);
            line-height: 1.7;
        }

        .container {
            max-width: 1100px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }

        header {
            background: white;
            padding: 1.5rem 2.5rem !important;
            border-radius: 1.2rem;
            box-shadow: var(--shadow-lg);
            margin-bottom: 2.5rem;
            border: 1px solid var(--border-color);
        }

        .payment-scheme {
            background: white;
            padding: 3rem;
            border-radius: 1.2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
        }

        .course-input {
            background: var(--secondary-color);
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2.5rem;
            border: 1px solid var(--border-color);
        }

        .course-input h5 {
            color: var(--primary-color);
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
            font-weight: 600;
        }

        #courseList {
            margin-top: 2rem;
        }

        .course-item {
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            background-color: white;
            margin-bottom: 1.25rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1.25rem;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .course-item:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
            border-color: var(--accent-color);
        }

        .payment-details {
            margin-top: 3rem;
            padding: 2.5rem;
            background: white;
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            display: none;
        }

        .payment-details.show {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .payment-total {
            font-weight: 600;
            margin-top: 1.5rem;
            font-size: 1.1rem;
            color: var(--primary-color);
        }

        .negative-amount {
            color: var(--success-color);
            font-weight: 600;
        }

        h4 {
            color: var(--text-primary);
            margin-bottom: 1.75rem;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .form-control, .form-select {
            border: 2px solid var(--border-color);
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
            outline: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.875rem 1.75rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(20px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="logo.png" alt="Logo" width="120">
            </div>
            <a href="student.php">
                <button class="btn btn-primary px-4 py-2">Back to Dashboard</button>
            </a>
        </header>

        <div class="payment-scheme">
            <h4>Payment Scheme</h4>
            <div class="mb-3">
                <label for="semester" class="form-label">Select Semester</label>
                <select class="form-select" id="semester">
                    <option value="Spring 2025">Spring 2025</option>
                    <option value="Summer 2025">Summer 2025</option>
                    <option value="Fall 2025">Fall 2025</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="waiver" class="form-label">Waiver Percentage</label>
                <input type="number" class="form-select" id="waiver" min="0" max="100" value="0" placeholder="Enter waiver percentage">
            </div>
            <div class="course-input">
                <h5>Add Course</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" id="courseName" placeholder="Course Name">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="courseType">
                            <option value="Lab">Lab</option>
                            <option value="Departmental">Departmental</option>
                            <option value="Non Departmental">Non Departmental</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" id="courseCredit" placeholder="Credit" step="0.5" min="0.5" max="4">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" onclick="addCourse()">Add Course</button>
                    </div>
                </div>
            </div>
            <div id="courseList" class="mt-3"></div>
            
            <div class="text-center mt-4">
                <button class="btn btn-primary btn-lg px-5 py-3 fs-4 fw-bold" onclick="showPaymentScheme()">View Payment Scheme</button>
            </div>
            <div id="paymentDetails" class="payment-details"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let courses = [];
        let editIndex = -1;
        const MAX_COURSES = 6;

        function addCourse() {
            const courseName = document.getElementById("courseName").value.trim();
            const courseType = document.getElementById("courseType").value;
            const courseCredit = parseFloat(document.getElementById("courseCredit").value);

            if (!courseName) {
                alert("Please enter a course name");
                return;
            }
            if (courseCredit < 0.5 || courseCredit > 3) {
                alert("Credit hours must be between 0.5 and 3");
                return;
            }

            if (courses.length >= MAX_COURSES && editIndex === -1) {
                alert(`You cannot add more than ${MAX_COURSES} courses per semester.`);
                return;
            }

            if (courseName && courseType && !isNaN(courseCredit) && courseCredit > 0) {
                if (editIndex === -1) {
                    courses.push({ name: courseName, type: courseType, credit: courseCredit });
                } else {
                    courses[editIndex] = { name: courseName, type: courseType, credit: courseCredit };
                    editIndex = -1;
                }
                updateCourseList();
                clearCourseInput();
            } else {
                alert("Please fill in all fields correctly.");
            }
        }

        function updateCourseList() {
            const courseList = document.getElementById("courseList");
            courseList.innerHTML = "<h5>Added Courses</h5>";
            courses.forEach((course, index) => {
                courseList.innerHTML += `
                <div class="course-item">
                    <div>
                        <strong>${index + 1}.</strong> ${course.name} (${course.type}, ${course.credit} Credits)
                    </div>
                    <div>
                        <button class="btn btn-sm btn-warning" onclick="editCourse(${index})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteCourse(${index})">Delete</button>
                    </div>
                </div>`;
            });
        }

        function clearCourseInput() {
            document.getElementById("courseName").value = "";
            document.getElementById("courseType").value = "Lab";
            document.getElementById("courseCredit").value = "";
        }

        function editCourse(index) {
            const course = courses[index];
            document.getElementById("courseName").value = course.name;
            document.getElementById("courseType").value = course.type;
            document.getElementById("courseCredit").value = course.credit;
            editIndex = index;
        }

        function deleteCourse(index) {
            courses.splice(index, 1);
            updateCourseList();
        }

        function showPaymentScheme() {
            if (courses.length === 0) {
                alert("Please add courses before viewing payment scheme.");
                return;
            }

            const semester = document.getElementById("semester").value;
            const waiverPercentage = parseFloat(document.getElementById("waiver").value) || 0;
            let labCredits = 0;
            let deptCredits = 0;
            let nonDeptCredits = 0;

            courses.forEach(course => {
                switch(course.type) {
                    case "Lab":
                        labCredits += course.credit;
                        break;
                    case "Departmental":
                        deptCredits += course.credit;
                        break;
                    case "Non Departmental":
                        nonDeptCredits += course.credit;
                        break;
                }
            });

            // Calculate fees based on credit hours and rates
            const labFees = labCredits * 5000;
            const deptFees = deptCredits * 4900;
            const nonDeptFees = nonDeptCredits * 3300;
            
            const totalTuitionFees = labFees + deptFees + nonDeptFees;
            const registrationAmount = 13500;
            const additionalPayment = 16000;

            // Apply waiver to tuition fees only
            const waiverAmount = (totalTuitionFees * waiverPercentage) / 100;
            const tuitionAfterWaiver = totalTuitionFees - waiverAmount;

            // First payment is fixed
            const registrationPayment = registrationAmount + additionalPayment;

            // Second payment is the tuition after waiver minus additional payment
            const finalExamPayment = tuitionAfterWaiver - additionalPayment;

            // Calculate total payable with minimum of 13500
            let totalPayable = registrationPayment;
            if (finalExamPayment < 0) {
                totalPayable = Math.max(13500, registrationPayment + finalExamPayment);
            } else {
                totalPayable = registrationPayment + finalExamPayment;
            }

            const secondPaymentDisplay = finalExamPayment < 0 
                ? `<span class="negative-amount">-${Math.abs(finalExamPayment).toFixed(2)} Tk</span>`
                : `${finalExamPayment.toFixed(2)} Tk`;

            const paymentStatusText = tuitionAfterWaiver < additionalPayment ? 
                "(Amount adjusted with additional payment)" : "";

            const paymentDetails = document.getElementById("paymentDetails");
            paymentDetails.innerHTML = `
                <h4>Payment Scheme Details - ${semester}</h4>
                <div class="payment-row">
                    <span>Registration Amount:</span>
                    <span>${registrationAmount.toFixed(2)} Tk</span>
                </div>
                <div class="payment-row">
                    <span>Additional Payment:</span>
                    <span>${additionalPayment.toFixed(2)} Tk</span>
                </div>
                <div class="payment-row">
                    <span>Total Tuition Fees:</span>
                    <span>${totalTuitionFees.toFixed(2)} Tk</span>
                </div>
                <div class="payment-row">
                    <span>Waiver Amount (${waiverPercentage}% of Tuition Fees):</span>
                    <span>${waiverAmount.toFixed(2)} Tk ${paymentStatusText}</span>
                </div>
                <div class="payment-row payment-total">
                    <span>First Payment (During Registration):</span>
                    <span>${registrationPayment.toFixed(2)} Tk</span>
                </div>
                <div class="payment-row payment-total">
                    <span>Second Payment (Before Final Exam):</span>
                    <span>${secondPaymentDisplay}</span>
                </div>
                <div class="payment-row payment-total">
                    <span>Total Payable:</span>
                    <span>${totalPayable.toFixed(2)} Tk</span>
                </div>
            `;
            paymentDetails.classList.add('show');
        }
    </script>
</body>
</html>
