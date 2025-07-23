<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php';
include 'functions.php';

if (!$conn) {
    die("Connection not established in config.php");
}

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    $sql = "SELECT user_id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid credentials (password mismatch)";
        }
    } else {
        $error = "Invalid credentials (no user found)";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-auto bg-white/90 rounded-3xl shadow-2xl p-10 border border-blue-100 backdrop-blur-md">
        <div class="flex flex-col items-center mb-8">
            <!-- Logo Placeholder -->
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-3 shadow">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-blue-700 mb-1">Welcome Back</h2>
            <p class="text-gray-500">Sign in to your Blaze account</p>
        </div>
        <?php if (isset($error)): ?>
            <div class="mb-6 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-center shadow">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-6">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Username</label>
                <input type="text" name="username" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <button type="submit" name="login" class="w-full py-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg transition-all duration-200">Login</button>
        </form>
        <div class="mt-8 text-center">
            <a href="register.php" class="inline-block text-blue-600 hover:underline font-medium">Register New User</a>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>