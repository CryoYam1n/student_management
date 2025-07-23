<?php
session_start();
include 'config.php';
include 'functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$user_id = get_user_id($conn, $_SESSION['username']);
$role = get_user_role($conn, $_SESSION['username']);

if (isset($_POST['generate'])) {
    $start_date = sanitize_input($_POST['start_date']);
    $end_date = sanitize_input($_POST['end_date']);
    $sql = "SELECT a.attendance_id, s.student_code, u.full_name, c.course_name, a.date, a.status
            FROM attendance a
            JOIN students s ON a.student_id = s.student_id
            JOIN users u ON s.user_id = u.user_id
            JOIN courses c ON a.course_id = c.course_id
            WHERE ";
    $params = [];
    $types = "";

    if ($role == 'student') {
        $sql .= "s.user_id = ? AND ";
        $types .= "i";
        $params[] = $user_id;
    } elseif ($role == 'teacher') {
        $sql .= "c.teacher_id = ? AND ";
        $types .= "i";
        $params[] = $user_id;
    }
    $sql .= "a.date BETWEEN ? AND ?";
    $types .= "ss";
    $params[] = $start_date;
    $params[] = $end_date;

    $stmt = $conn->prepare($sql);
    // Dynamically bind parameters
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $attendance = $stmt->get_result();

    // Stylish Tailwind table output
    echo '<div class="max-w-4xl mx-auto mt-10 bg-white rounded-2xl shadow-xl p-8 border border-blue-100">';
    echo "<h3 class='text-2xl font-bold text-blue-700 mb-6 text-center'>Attendance Report</h3>";
    echo "<div class='overflow-x-auto'><table class='min-w-full text-sm text-left text-gray-700 border border-blue-100 rounded-xl'>";
    echo "<thead class='bg-blue-100 text-blue-700 uppercase text-xs'>";
    echo "<tr><th class='px-4 py-2'>Student Code</th><th class='px-4 py-2'>Full Name</th><th class='px-4 py-2'>Course</th><th class='px-4 py-2'>Date</th><th class='px-4 py-2'>Status</th></tr>";
    echo "</thead><tbody>";
    while ($row = $attendance->fetch_assoc()) {
        echo "<tr class='border-b hover:bg-blue-50 transition'>";
        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['student_code']) . "</td>";
        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['full_name']) . "</td>";
        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['course_name']) . "</td>";
        echo "<td class='px-4 py-2'>" . htmlspecialchars($row['date']) . "</td>";
        echo "<td class='px-4 py-2'>";
        if ($row['status'] == 'present') {
            echo "<span class='inline-block px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold'>Present</span>";
        } elseif ($row['status'] == 'absent') {
            echo "<span class='inline-block px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold'>Absent</span>";
        } else {
            echo "<span class='inline-block px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold'>Late</span>";
        }
        echo "</td></tr>";
    }
    echo "</tbody></table></div></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen">
    <nav class="bg-white shadow">
        <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-2xl font-bold text-blue-700" href="#">Generate Report</a>
            <div class="flex space-x-4">
                <a class="text-gray-700 hover:text-blue-600 transition" href="index.php">Back</a>
            </div>
        </div>
    </nav>

    <div class="max-w-xl mx-auto mt-10 bg-white/90 rounded-3xl shadow-2xl p-10 border border-blue-100 backdrop-blur-md">
        <h2 class="text-3xl font-extrabold text-blue-700 mb-6 text-center">Generate Attendance Report</h2>
        <form method="POST" class="space-y-6">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Start Date</label>
                <input type="date" name="start_date" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold text-gray-700">End Date</label>
                <input type="date" name="end_date" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 bg-blue-50 transition" required>
            </div>
            <button type="submit" name="generate" class="w-full py-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg transition-all duration-200">Generate Report</button>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>