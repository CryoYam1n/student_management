<?php
session_start();
include 'config.php';
include 'functions.php';

if (!isset($_SESSION['username']) || get_user_role($conn, $_SESSION['username']) != 'student') {
    header("Location: login.php");
    exit();
}
$user_id = get_user_id($conn, $_SESSION['username']);

// Get student_id from students table
$stmt = $conn->prepare("SELECT student_id FROM students WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($student_id);
$stmt->fetch();
$stmt->close();

if (!$student_id) {
    // Student profile not found for this user
    echo "<div class='max-w-xl mx-auto mt-10 bg-white/90 rounded-3xl shadow-2xl p-10 border border-red-200 text-red-700 text-center font-bold'>
            Student profile not found. Please contact admin.
          </div>";
    $conn->close();
    exit();
}

// Handle enrollment
if (isset($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']);
    $stmt = $conn->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $course_id);
    if ($stmt->execute()) {
        $success = "Enrolled successfully!";
    } else {
        $success = "Enrollment failed or already enrolled.";
    }
    $stmt->close();
}

// Get courses not already enrolled
$sql = "SELECT c.course_id, c.course_name 
        FROM courses c
        WHERE c.course_id NOT IN (
            SELECT course_id FROM enrollments WHERE student_id = ?
        )";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$courses = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enroll in Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen">
    <div class="max-w-xl mx-auto mt-10 bg-white/90 rounded-3xl shadow-2xl p-10 border border-blue-100">
        <h2 class="text-3xl font-extrabold text-blue-700 mb-6 text-center">Enroll in a Course</h2>
        <?php if (isset($success)) echo "<div class='mb-6 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg text-center shadow'>$success</div>"; ?>
        <form method="POST" class="space-y-5">
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Available Courses</label>
                <select name="course_id" class="w-full px-4 py-2 border-2 border-blue-100 rounded-xl bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400 transition" required>
                    <option value="">Select Course</option>
                    <?php while ($row = $courses->fetch_assoc()): ?>
                        <option value="<?= $row['course_id'] ?>"><?= htmlspecialchars($row['course_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="w-full py-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-bold rounded-xl shadow-lg transition-all duration-200">Enroll</button>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>