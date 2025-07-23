<?php 
session_start(); 
include 'config.php'; 
include 'functions.php';  

if (!isset($_SESSION['username']) || get_user_role($conn, $_SESSION['username']) != 'admin') {     
    header("Location: login.php");     
    exit(); 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Management Portal</title>
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
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'float': 'float 3s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-5px)' }
                        },
                        glow: {
                            '0%': { boxShadow: '0 0 5px rgba(59, 130, 246, 0.5)' },
                            '100%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.8)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .mesh-bg {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(236, 72, 153, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(34, 197, 94, 0.1) 0%, transparent 50%);
        }
        
        .icon-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen mesh-bg">
    <!-- Navigation -->
    <nav class="glass-effect backdrop-blur-md border-b border-white/20 sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg animate-glow">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-700 to-purple-700 bg-clip-text text-transparent">
                            Admin Portal
                        </h1>
                        <p class="text-sm text-gray-600">Management Dashboard</p>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="hidden md:flex items-center space-x-2 bg-white/20 rounded-full px-4 py-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-sm text-gray-700 font-medium">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </div>
                    <div class="flex space-x-3">
                        <a href="index.php" class="px-4 py-2 text-gray-700 hover:text-blue-600 transition-all duration-200 hover:bg-white/20 rounded-lg font-medium">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back
                        </a>
                        <a href="logout.php" class="px-4 py-2 text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 transition-all duration-200 rounded-lg shadow-lg font-medium">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-12">
        <!-- Header Section -->
        <div class="text-center mb-16 animate-fade-in">
            <h2 class="text-5xl font-bold bg-gradient-to-r from-gray-800 via-blue-800 to-purple-800 bg-clip-text text-transparent mb-4">
                Administrative Center
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Manage your system with powerful tools designed for efficiency and control
            </p>
            <div class="mt-8 w-24 h-1 bg-gradient-to-r from-blue-500 to-purple-500 mx-auto rounded-full"></div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12 animate-slide-up">
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">1,234</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Courses</p>
                        <p class="text-2xl font-bold text-gray-900">45</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Students</p>
                        <p class="text-2xl font-bold text-gray-900">892</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Reports</p>
                        <p class="text-2xl font-bold text-gray-900">127</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2"></path>
                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 animate-slide-up">
            <!-- Add User Card -->
            <a href="add_user.php" class="group card-hover bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 text-white rounded-3xl shadow-2xl p-8 flex flex-col items-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 group-hover:animate-float relative z-10">
                    <svg class="w-8 h-8 icon-pulse" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 relative z-10">Add User</h3>
                <p class="text-sm text-center opacity-90 relative z-10">Create new user accounts and manage permissions</p>
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/5 rounded-full transform translate-x-8 translate-y-8"></div>
            </a>

            <!-- Add Course Card -->
            <a href="add_course.php" class="group card-hover bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 text-white rounded-3xl shadow-2xl p-8 flex flex-col items-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 group-hover:animate-float relative z-10">
                    <svg class="w-8 h-8 icon-pulse" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 relative z-10">Add Course</h3>
                <p class="text-sm text-center opacity-90 relative z-10">Design and publish new educational content</p>
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/5 rounded-full transform translate-x-8 translate-y-8"></div>
            </a>

            <!-- Add Student Card -->
            <a href="add_student.php" class="group card-hover bg-gradient-to-br from-purple-500 via-pink-500 to-rose-600 text-white rounded-3xl shadow-2xl p-8 flex flex-col items-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 group-hover:animate-float relative z-10">
                    <svg class="w-8 h-8 icon-pulse" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 relative z-10">Add Student</h3>
                <p class="text-sm text-center opacity-90 relative z-10">Enroll new students and track progress</p>
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/5 rounded-full transform translate-x-8 translate-y-8"></div>
            </a>

            <!-- Generate Report Card -->
            <a href="generate_report.php" class="group card-hover bg-gradient-to-br from-amber-500 via-orange-500 to-red-500 text-white rounded-3xl shadow-2xl p-8 flex flex-col items-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 group-hover:animate-float relative z-10">
                    <svg class="w-8 h-8 icon-pulse" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2"></path>
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <path d="M7 8h10M7 12h4"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 relative z-10">Generate Report</h3>
                <p class="text-sm text-center opacity-90 relative z-10">Create detailed analytics and insights</p>
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/5 rounded-full transform translate-x-8 translate-y-8"></div>
            </a>
        </div>

        <!-- Quick Actions Bar -->
        <div class="mt-16 bg-white/70 backdrop-blur-sm rounded-3xl p-8 border border-white/20 shadow-lg animate-fade-in">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="mb-6 lg:mb-0">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Quick Actions</h3>
                    <p class="text-gray-600">Shortcuts to frequently used functions</p>
                </div>
                <div class="flex flex-wrap gap-4">
                    <button class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105 font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Data
                    </button>
                    <button class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105 font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Data
                    </button>
                    <button class="px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105 font-medium">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-20 py-8 border-t border-white/20">
        <div class="container mx-auto px-6 text-center">
            <p class="text-gray-600">&copy; 2025 Admin Portal. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php $conn->close(); ?>