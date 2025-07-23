<?php
session_start();
include 'config.php';
include 'functions.php';

if (!isset($_SESSION['username']) || get_user_role($conn, $_SESSION['username']) != 'teacher') {
    header("Location: login.php");
    exit();
}
$user_id = get_user_id($conn, $_SESSION['username']);

$sql = "SELECT c.course_id, c.course_name FROM courses c WHERE c.teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$courses = $stmt->get_result();

$selected_course = isset($_POST['course_id']) ? sanitize_input($_POST['course_id']) : '';
$selected_date = isset($_POST['date']) ? sanitize_input($_POST['date']) : date('Y-m-d');

if (isset($_POST['submit_attendance'])) {
    foreach ($_POST['attendance'] as $student_id => $status) {
        $status = sanitize_input($status);
        $sql = "INSERT INTO attendance (student_id, course_id, date, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $student_id, $selected_course, $selected_date, $status);
        $stmt->execute();
    }
    header("Location: teacher_dashboard.php");
    exit();
}

// Only fetch students if a course is selected
$students = [];
if ($selected_course) {
    $sql = "SELECT s.student_id, s.student_code, u.full_name 
            FROM students s 
            JOIN users u ON s.user_id = u.user_id 
            JOIN enrollments e ON s.student_id = e.student_id 
            WHERE e.course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_course);
    $stmt->execute();
    $students = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen">
    <nav class="bg-white shadow">
        <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-2xl font-bold text-blue-700" href="#">Take Attendance</a>
            <div class="flex space-x-4">
                <a class="text-gray-700 hover:text-blue-600 transition" href="teacher_dashboard.php">Back</a>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto mt-10 bg-white/90 rounded-3xl shadow-2xl p-10 border border-blue-100 backdrop-blur-md">
        <h2 class="text-3xl font-extrabold text-blue-700 mb-6 text-center">Take Attendance</h2>
        <form method="POST" class="space-y-6">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Course</label>
                <select name="course_id" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" required onchange="this.form.submit()">
                    <option value="">Select Course</option>
                    <?php
                    // Reset courses pointer for re-use
                    mysqli_data_seek($courses, 0);
                    while ($row = $courses->fetch_assoc()) {
                        $selected = ($selected_course == $row['course_id']) ? 'selected' : '';
                        echo "<option value='{$row['course_id']}' $selected>{$row['course_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Date</label>
                <input type="date" name="date" value="<?= htmlspecialchars($selected_date) ?>" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required onchange="this.form.submit()">
            </div>
            <?php if ($selected_course): ?>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Students</label>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 border border-blue-100 rounded-xl">
                        <thead class="bg-blue-100 text-blue-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-2">Student Code</th>
                                <th class="px-4 py-2">Full Name</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($students && $students->num_rows > 0): ?>
                            <?php while ($row = $students->fetch_assoc()): ?>
                                <tr class="border-b hover:bg-blue-50 transition">
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['student_code']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($row['full_name']) ?></td>
                                    <td class="px-4 py-2">
                                        <select name="attendance[<?= $row['student_id'] ?>]" class="w-full px-2 py-1 border rounded focus:outline-none" required>
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="late">Late</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="px-4 py-2 text-center text-gray-400">No students enrolled in this course.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <button type="submit" name="submit_attendance" class="w-full py-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg transition-all duration-200 mt-4">Submit Attendance</button>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>