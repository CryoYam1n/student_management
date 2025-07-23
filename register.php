<?php
session_start();
include 'config.php';
include 'functions.php';

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['register'])) {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    $role = sanitize_input($_POST['role']);
    $full_name = sanitize_input($_POST['full_name']);
    $email = sanitize_input($_POST['email']);

    // Allow admin registration too
    if (!in_array($role, ['student', 'teacher', 'admin'])) {
        $error = "Invalid role selected.";
    } else {
        // Check for duplicate username or email
        $check_sql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_stmt->store_result();
        if ($check_stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password, role, full_name, email) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssss", $username, $hashed_password, $role, $full_name, $email);
                if ($stmt->execute()) {
                    $success = "Registration successful! Please log in.";
                } else {
                    $error = "Registration failed: " . $conn->error;
                }
                $stmt->close();
            } else {
                $error = "Prepare failed: " . $conn->error;
            }
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-lg mx-auto bg-white/90 rounded-3xl shadow-2xl p-10 border border-blue-100 backdrop-blur-md">
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-3 shadow">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-blue-700 mb-1">Create Account</h2>
            <p class="text-gray-500">Register for your Blaze account</p>
        </div>
        <?php if (isset($error)): ?>
            <div class="mb-6 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-center shadow">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="mb-6 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg text-center shadow">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-5">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Username</label>
                <input type="text" name="username" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Role</label>
                <select name="role" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" required>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Full Name</label>
                <input type="text" name="full_name" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <button type="submit" name="register" class="w-full py-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg transition-all duration-200">Register</button>
            <a href="login.php" class="w-full block text-center mt-4 text-blue-600 hover:underline font-medium">Back to Login</a>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>