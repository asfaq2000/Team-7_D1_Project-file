<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CGPA Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
            font-family: 'Inter', sans-serif;
        }

        .container {
            max-width: 1200px;
            width: 95%;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .card-body {
            padding: 3rem;
        }

        .card-title {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 2.5rem;
            font-size: 2.25rem;
        }

        .course {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 16px;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .course:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
        }

        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 12px 18px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.2s ease;
        }

        .btn-success {
            background: #8b5cf6;
            border: none;
        }

        .btn-success:hover {
            background: #7c3aed;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid #8b5cf6;
            color: #8b5cf6;
        }

        .btn-outline-primary:hover {
            background: #8b5cf6;
            border-color: #8b5cf6;
            transform: translateY(-2px);
        }

        header {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto 2rem auto;
            background: rgba(255, 255, 255, 0.98);
            padding: 1.5rem 2.5rem !important;
            border-radius: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        #result {
            font-size: 2rem;
            color: #1e293b;
            font-weight: 700;
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 16px;
            text-align: center;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .logo img {
            width: 160px;
            height: auto;
            transition: transform 0.2s ease;
        }

        .logo img:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <header class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <img src="logo.png" alt="Logo">
            </div>
            <a href="student.php">
                <button class="btn btn-primary px-4 py-2">Back to Dashboard</button>
            </a>
        </header>

        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">CGPA Calculator</h1>
                <div id="courses">
                    <div class="course mb-3">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <input type="text" class="form-control course-name" placeholder="Course Name">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select grade">
                                    <option value="4.00">A+</option>
                                    <option value="3.75">A</option>
                                    <option value="3.50">A-</option>
                                    <option value="3.25">B+</option>
                                    <option value="3.00">B</option>
                                    <option value="2.75">B-</option>
                                    <option value="2.50">C+</option>
                                    <option value="2.25">C</option>
                                    <option value="2.00">D</option>
                                    <option value="0.00">F</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control credits" placeholder="Credits" min="1" step="0.5">
                            </div>
                        </div>
                    </div>
                </div>
                <button id="add-course" class="btn btn-outline-primary w-100 mb-3">+ Add Course</button>
                <button id="calculate" class="btn btn-success w-100 mb-3">Calculate CGPA</button>
                <h2 id="result" class="text-center mt-4"></h2>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('add-course').addEventListener('click', function () {
            const coursesDiv = document.getElementById('courses');
            const newCourse = document.createElement('div');
            newCourse.className = 'course mb-3';
            newCourse.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" class="form-control course-name" placeholder="Course Name">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select grade">
                            <option value="4.00">A+</option>
                            <option value="3.75">A</option>
                            <option value="3.50">A-</option>
                            <option value="3.25">B+</option>
                            <option value="3.00">B</option>
                            <option value="2.75">B-</option>
                            <option value="2.50">C+</option>
                            <option value="2.25">C</option>
                            <option value="2.00">D</option>
                            <option value="0.00">F</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control credits" placeholder="Credits" min="1" step="0.5">
                    </div>
                </div>
            `;
            coursesDiv.appendChild(newCourse);
        });

        document.getElementById('calculate').addEventListener('click', function () {
            const courses = document.querySelectorAll('.course');
            let totalCredits = 0;
            let totalGradePoints = 0;
            let hasError = false;

            document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
            document.querySelectorAll('.error-message').forEach(el => el.remove());

            courses.forEach(course => {
                const creditsInput = course.querySelector('.credits');
                const credits = parseFloat(creditsInput.value);
                const grade = parseFloat(course.querySelector('.grade').value);

                if (isNaN(credits) || credits <= 0) {
                    creditsInput.classList.add('error');
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message';
                    errorMsg.textContent = 'Please enter valid credit hours (greater than 0)';
                    creditsInput.parentNode.appendChild(errorMsg);
                    hasError = true;
                } else {
                    totalGradePoints += grade * credits;
                    totalCredits += credits;
                }
            });

            const resultElement = document.getElementById('result');
            if (!hasError && totalCredits > 0) {
                const cgpa = totalGradePoints / totalCredits;
                resultElement.style.color = '#1e293b';
                resultElement.innerText = `Your CGPA is: ${cgpa.toFixed(2)}`;
            } else if (hasError) {
                resultElement.style.color = '#ef4444';
                resultElement.innerText = 'Please correct the errors above.';
            } else {
                resultElement.style.color = '#ef4444';
                resultElement.innerText = 'Please enter valid credit hours.';
            }
        });
    </script>
</body>
</html>
