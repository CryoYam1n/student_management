<?php
session_start();
include 'config.php';
include 'functions.php';

if (!isset($_SESSION['username']) || get_user_role($conn, $_SESSION['username']) != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_student'])) {
    $student_code = sanitize_input($_POST['student_code']);
    $department = sanitize_input($_POST['department']);
    $year = sanitize_input($_POST['year']);
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    $full_name = sanitize_input($_POST['full_name']);
    $email = sanitize_input($_POST['email']);
    
    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO users (username, password, role, full_name, email) VALUES (?, ?, 'student', ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $password, $full_name, $email);
        $stmt->execute();
        $user_id = $conn->insert_id;
        
        $sql = "INSERT INTO students (user_id, student_code, department, year) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $user_id, $student_code, $department, $year);
        $stmt->execute();
        $conn->commit();
        header("Location: admin_dashboard.php");
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen">
    <nav class="bg-white shadow">
        <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-2xl font-bold text-blue-700" href="#">Add Student</a>
            <div class="flex space-x-4">
                <a class="text-gray-700 hover:text-blue-600 transition" href="admin_dashboard.php">Back</a>
            </div>
        </div>
    </nav>

    <div class="max-w-xl mx-auto mt-10 bg-white/90 rounded-3xl shadow-2xl p-10 border border-blue-100 backdrop-blur-md">
        <h2 class="text-3xl font-extrabold text-blue-700 mb-6 text-center">Add New Student</h2>
        <form method="POST" class="space-y-5">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Student Code</label>
                <input type="text" name="student_code" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Department</label>
                <input type="text" name="department" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Year</label>
                <input type="number" name="year" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Username</label>
                <input type="text" name="username" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Full Name</label>
                <input type="text" name="full_name" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <button type="submit" name="add_student" class="w-full py-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg transition-all duration-200">Add Student</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>