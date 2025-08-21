<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Daffodil University</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 2rem;
        }

        .hero-content {
            max-width: 800px;
        }

        .logo-container img {
            max-width: 200px;
            margin-bottom: 2rem;
            animation: float 3s ease-in-out infinite;
        }

        .welcome-text {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            opacity: 0;
            animation: fadeIn 1s ease forwards 0.5s;
        }

        .btn-custom {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 500;
            border-radius: 50px;
            margin: 0.5rem;
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeIn 1s ease forwards 1s;
        }

        .btn-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="hero-content">
            <div class="logo-container">
                <img src="logo.png" alt="Daffodil University Logo" class="img-fluid">
            </div>
            <h1 class="welcome-text">Welcome to Daffodil University Portal</h1>
            <div class="button-group">
                <a href="studentlogin.php" class="btn btn-light btn-custom">Student Portal</a>
                <a href="adminlogin.php" class="btn btn-outline-light btn-custom">Admin Portal</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>