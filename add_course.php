<?php
session_start();
include 'config.php';
include 'functions.php';

if (!isset($_SESSION['username']) || get_user_role($conn, $_SESSION['username']) != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch teachers for dropdown
$teachers = [];
$result = $conn->query("SELECT user_id, full_name FROM users WHERE role='teacher'");
while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;
}

if (isset($_POST['add_course'])) {
    $course_code = sanitize_input($_POST['course_code']);
    $course_name = sanitize_input($_POST['course_name']);
    $teacher_id = sanitize_input($_POST['teacher_id']);
    $sql = "INSERT INTO courses (course_code, course_name, teacher_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $course_code, $course_name, $teacher_id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen">
    <nav class="bg-white shadow">
        <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-2xl font-bold text-blue-700" href="#">Add Course</a>
            <div class="flex space-x-4">
                <a class="text-gray-700 hover:text-blue-600 transition" href="admin_dashboard.php">Back</a>
            </div>
        </div>
    </nav>

    <div class="max-w-xl mx-auto mt-10 bg-white/90 rounded-3xl shadow-2xl p-10 border border-blue-100 backdrop-blur-md">
        <h2 class="text-3xl font-extrabold text-blue-700 mb-6 text-center">Add New Course</h2>
        <?php if (isset($error)) echo "<div class='mb-6 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-center shadow'>" . htmlspecialchars($error) . "</div>"; ?>
        <form method="POST" class="space-y-5">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Course Code</label>
                <input type="text" name="course_code" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Course Name</label>
                <input type="text" name="course_name" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Teacher</label>
                <select name="teacher_id" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" required>
                    <option value="">Select Teacher</option>
                    <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= $teacher['user_id'] ?>"><?= htmlspecialchars($teacher['full_name']) ?> (ID: <?= $teacher['user_id'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="add_course" class="w-full py-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg transition-all duration-200">Add Course</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>