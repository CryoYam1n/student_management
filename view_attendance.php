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

$sql = "SELECT a.attendance_id, s.student_code, u.full_name, c.course_name, a.date, a.status
        FROM attendance a
        JOIN students s ON a.student_id = s.student_id
        JOIN users u ON s.user_id = u.user_id
        JOIN courses c ON a.course_id = c.course_id
        WHERE ";
if ($role == 'student') {
    $sql .= "s.user_id = ?";
} elseif ($role == 'teacher') {
    $sql .= "c.teacher_id = ?";
}
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$attendance = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a'
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-lg border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Attendance Records</h1>
                        <p class="text-sm text-gray-500">View and manage attendance data</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-user-circle"></i>
                        <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            <?php echo ucfirst($role); ?>
                        </span>
                    </div>
                    <a href="index.php" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Attendance Overview</h2>
                        <p class="text-gray-600">Track and monitor attendance records efficiently</p>
                    </div>
                    <div class="hidden sm:flex items-center space-x-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">
                                <?php 
                                $total_records = $attendance->num_rows;
                                echo $total_records;
                                ?>
                            </div>
                            <div class="text-sm text-gray-500">Total Records</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Attendance Records</h3>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class="fas fa-table"></i>
                        <span>Data Table</span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-id-card text-gray-400"></i>
                                    <span>Student Code</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-user text-gray-400"></i>
                                    <span>Full Name</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-book text-gray-400"></i>
                                    <span>Course</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-calendar text-gray-400"></i>
                                    <span>Date</span>
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-check-circle text-gray-400"></i>
                                    <span>Status</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php 
                        // Reset the result pointer to fetch data again
                        $attendance->data_seek(0);
                        $row_count = 0;
                        while ($row = $attendance->fetch_assoc()) { 
                            $row_count++;
                            $status_class = '';
                            $status_icon = '';
                            
                            switch(strtolower($row['status'])) {
                                case 'present':
                                    $status_class = 'bg-green-100 text-green-800';
                                    $status_icon = 'fas fa-check-circle';
                                    break;
                                case 'absent':
                                    $status_class = 'bg-red-100 text-red-800';
                                    $status_icon = 'fas fa-times-circle';
                                    break;
                                case 'late':
                                    $status_class = 'bg-yellow-100 text-yellow-800';
                                    $status_icon = 'fas fa-clock';
                                    break;
                                default:
                                    $status_class = 'bg-gray-100 text-gray-800';
                                    $status_icon = 'fas fa-question-circle';
                            }
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                                <span class="text-white font-semibold text-sm">
                                                    <?php echo substr($row['student_code'], -2); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($row['student_code']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($row['full_name']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?php echo htmlspecialchars($row['course_name']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?php echo date('M j, Y', strtotime($row['date'])); ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?php echo date('l', strtotime($row['date'])); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo $status_class; ?>">
                                        <i class="<?php echo $status_icon; ?> mr-1"></i>
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                        
                        <?php if ($row_count == 0) { ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">No attendance records found</h3>
                                            <p class="text-gray-500 mt-1">There are no attendance records to display at this time.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Stats -->
        <?php if ($row_count > 0) { ?>
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Records</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo $row_count; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-graduate text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Viewing As</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo ucfirst($role); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-check text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Last Updated</p>
                        <p class="text-2xl font-semibold text-gray-900"><?php echo date('M j'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </main>

    <!-- Floating Action Button (Optional) -->
    <div class="fixed bottom-8 right-8">
        <button class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <i class="fas fa-plus text-lg"></i>
        </button>
    </div>
</body>
</html>

<?php $conn->close(); ?>