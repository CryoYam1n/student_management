<?php

session_start();
include 'config.php';
include 'functions.php';

if (!isset($_SESSION['username']) || get_user_role($conn, $_SESSION['username']) != 'student') {
    header("Location: login.php");
    exit();
}
$user_id = get_user_id($conn, $_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Academic Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s forwards;
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .ripple {
            position: absolute;
            border-radius: 9999px;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
            background: rgba(37, 99, 235, 0.1);
        }
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 min-h-screen font-sans">
    <!-- Navigation -->
    <nav class="bg-white/70 backdrop-blur-lg shadow-lg border-b border-white/30">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a class="flex items-center gap-3 font-extrabold text-blue-800 text-2xl tracking-tight" href="#">
                <span class="bg-blue-100 rounded-full p-2 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0 0H6m6 0h6"/>
                    </svg>
                </span>
                Academic Portal
            </a>
            <div class="flex gap-2">
                <a class="px-4 py-2 rounded-lg font-medium text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition" href="index.php">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v6m0 0h6m-6 0H5m6 0v-6"/>
                    </svg>
                    Home
                </a>
                <a class="px-4 py-2 rounded-lg font-medium text-gray-700 hover:bg-blue-100 hover:text-blue-700 transition" href="logout.php">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-800 to-blue-500 text-white p-10 relative">
                <div class="absolute right-0 top-0 w-52 h-52 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                <span class="inline-flex items-center gap-2 bg-white/20 border border-white/30 rounded-full px-5 py-1.5 font-semibold mb-6 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0 0H6m6 0h6"/>
                    </svg>
                    Student Portal
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold mb-2 z-10 relative drop-shadow-lg">Welcome Back!</h1>
                <p class="text-lg opacity-90 z-10 relative font-medium">Manage your academic activities and track your progress</p>
            </div>

            <!-- Content Section -->
            <div class="p-10 space-y-10">
                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                    <div class="bg-white rounded-2xl shadow flex flex-col items-center py-8 hover:shadow-xl transition">
                        <div class="mb-3 animate-bounce">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-xl font-semibold text-gray-700">Attendance Tracking</div>
                    </div>
                    <div class="bg-white rounded-2xl shadow flex flex-col items-center py-8 hover:shadow-xl transition">
                        <div class="mb-3 animate-bounce">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8"/>
                            </svg>
                        </div>
                        <div class="text-xl font-semibold text-gray-700">Progress Reports</div>
                    </div>
                    <div class="bg-white rounded-2xl shadow flex flex-col items-center py-8 hover:shadow-xl transition">
                        <div class="mb-3 animate-bounce">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div class="text-xl font-semibold text-gray-700">Notifications</div>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-3 border-b pb-3 mb-6 tracking-tight">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Available Actions
                </h2>

                <!-- Action Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- View Attendance -->
                    <a href="view_attendance.php" class="relative group bg-white rounded-2xl shadow-lg p-7 flex flex-col items-center transition hover:-translate-y-1 hover:shadow-2xl fade-in-up cursor-pointer overflow-hidden action-card">
                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-blue-400 mb-4 transition group-hover:scale-110 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-lg font-semibold text-gray-700 mb-1">View My Attendance</div>
                        <div class="text-gray-500 text-sm text-center">Track your attendance records, view detailed reports, and monitor your presence statistics across all subjects and sessions.</div>
                    </a>

                    <!-- Enroll in Course -->
                    <a href="enroll_course.php" class="relative group bg-white rounded-2xl shadow-lg p-7 flex flex-col items-center transition hover:-translate-y-1 hover:shadow-2xl fade-in-up cursor-pointer overflow-hidden action-card">
                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-gradient-to-br from-green-600 to-green-400 mb-4 transition group-hover:scale-110 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20l9-5-9-5-9 5 9 5zm0 0V5a2 2 0 012-2h6a2 2 0 012 2v11"/>
                            </svg>
                        </div>
                        <div class="text-lg font-semibold text-gray-700 mb-1">Enroll in Course</div>
                        <div class="text-gray-500 text-sm text-center">Browse available courses and enroll to expand your learning opportunities.</div>
                    </a>

                    <!-- Academic Reports (Coming Soon) -->
                    <div class="relative bg-gray-100 rounded-2xl shadow p-7 flex flex-col items-center opacity-60 cursor-not-allowed fade-in-up overflow-hidden">
                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-gray-400 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="text-lg font-semibold text-gray-600 mb-1">Academic Reports</div>
                        <div class="text-gray-500 text-sm text-center">
                            Access your academic performance reports, grades, and progress summaries.
                            <span class="block mt-2 text-xs text-gray-400">Coming Soon</span>
                        </div>
                    </div>

                    <!-- Class Schedule (Coming Soon) -->
                    <div class="relative bg-gray-100 rounded-2xl shadow p-7 flex flex-col items-center opacity-60 cursor-not-allowed fade-in-up overflow-hidden">
                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-gray-400 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="text-lg font-semibold text-gray-600 mb-1">Class Schedule</div>
                        <div class="text-gray-500 text-sm text-center">
                            View your daily class schedule, upcoming events, and important academic dates.
                            <span class="block mt-2 text-xs text-gray-400">Coming Soon</span>
                        </div>
                    </div>

                    <!-- Profile Settings (Coming Soon) -->
                    <div class="relative bg-gray-100 rounded-2xl shadow p-7 flex flex-col items-center opacity-60 cursor-not-allowed fade-in-up overflow-hidden">
                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-gray-400 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17a4 4 0 100-8 4 4 0 000 8zm-7 4a9 9 0 1118 0H4z"/>
                            </svg>
                        </div>
                        <div class="text-lg font-semibold text-gray-600 mb-1">Profile Settings</div>
                        <div class="text-gray-500 text-sm text-center">
                            Update your personal information, contact details, and account preferences.
                            <span class="block mt-2 text-xs text-gray-400">Coming Soon</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Fade-in animation for action cards
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.fade-in-up').forEach((card, i) => {
            card.style.animationDelay = `${i * 0.1}s`;
        });

        // Ripple effect for clickable cards
        document.querySelectorAll('.action-card, a[href="view_attendance.php"], a[href="enroll_course.php"]').forEach(card => {
            if (!card.classList.contains('cursor-not-allowed')) {
                card.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = card.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    ripple.className = 'ripple';
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    card.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 600);
                });
            }
        });
    });
    </script>
</body>
</html>

<?php $conn->close(); ?>