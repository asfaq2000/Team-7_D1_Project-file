<?php
$host = 'localhost';
$db   = 'daffodil';
$user = 'root';  // Default XAMPP username
$pass = '';      // Default XAMPP password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Simplified connection testing
    $test_query = $pdo->query("SELECT 1");
    if (!$test_query) {
        throw new PDOException("Connection test failed");
    }
    
    // Test if required table exists
    $table_test = $pdo->query("SHOW TABLES LIKE 'userAdmin'");
    if ($table_test->rowCount() == 0) {
        throw new PDOException("Required table 'userAdmin' not found");
    }

} catch (\PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die("Database connection failed. Please contact administrator.");
}

// Function to test connection status
function checkDatabaseConnection($pdo) {
    try {
        $pdo->query("SELECT 1");
        return true;
    } catch (\PDOException $e) {
        return false;
    }
}

// Function to verify admin credentials
function verifyAdminCredentials($pdo, $userId, $password) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM userAdmin WHERE userId = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        // Debug information
        error_log("Login attempt - UserID: " . $userId);
        error_log("User found in database: " . ($user ? 'Yes' : 'No'));
        
        if ($user) {
            // Check if password is hashed
            if (strlen($user['password']) < 60) {
                error_log("Warning: Password in database may not be hashed");
                return $user['password'] === $password;
            }
            
            $verified = password_verify($password, $user['password']);
            error_log("Password verification result: " . ($verified ? 'Success' : 'Failed'));
            return $verified ? $user : false;
        }
        return false;
    } catch (\PDOException $e) {
        error_log("Database error during login: " . $e->getMessage());
        return false;
    }
}
?>
