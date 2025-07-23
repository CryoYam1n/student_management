<?php 
session_start(); 
include 'config.php'; 
include 'functions.php';  

if (!isset($_SESSION['username']) || get_user_role($conn, $_SESSION['username']) != 'teacher') {     
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
    <title>Teacher Dashboard - Education Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teacher: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e'
                        },
                        accent: {
                            50: '#fef7ff',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7c3aed'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.7s ease-out',
                        'bounce-soft': 'bounceSoft 2s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'shimmer': 'shimmer 2s linear infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        bounceSoft: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-8px)' }
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .card-hover:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.3);
        }
        
        .teacher-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .mesh-bg {
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(14, 165, 233, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(168, 85, 247, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 60%, rgba(34, 197, 94, 0.1) 0%, transparent 50%);
        }
        
        .icon-rotate {
            transition: transform 0.3s ease;
        }
        
        .icon-rotate:hover {
            transform: rotate(5deg) scale(1.1);
        }
        
        .shimmer-bg {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            background-size: 200% 100%;
        }
        
        .action-card {
            position: relative;
            overflow: hidden;
        }
        
        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-card:hover::before {
            left: 100%;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-100 min-h-screen mesh-bg">
    <!-- Navigation -->
    <nav class="glass-effect backdrop-blur-md border-b border-white/20 sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl animate-pulse-slow">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-700 to-purple-700 bg-clip-text text-transparent">
                            Teacher Portal
                        </h1>
                        <p class="text-sm text-gray-600">Education Management System</p>
                    </div>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="hidden md:flex items-center space-x-3 bg-white/25 rounded-full px-5 py-2 shadow-lg">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Welcome Back!</p>
                            <p class="text-xs text-gray-600"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="index.php" class="px-5 py-2 text-gray-700 hover:text-blue-600 transition-all duration-200 hover:bg-white/25 rounded-xl font-medium group">
                            <svg class="w-4 h-4 inline mr-2 group-hover:animate-bounce-soft" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Home
                        </a>
                        <a href="logout.php" class="px-5 py-2 text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 transition-all duration-200 rounded-xl shadow-lg font-medium group">
                            <svg class="w-4 h-4 inline mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
            <h2 class="text-5xl font-bold bg-gradient-to-r from-blue-800 via-purple-800 to-indigo-800 bg-clip-text text-transparent mb-4">
                Teaching Center
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Manage your classes, track student progress, and create meaningful educational experiences
            </p>
            <div class="mt-8 flex justify-center">
                <div class="w-32 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-500 rounded-full"></div>
            </div>
        </div>

        <!-- Today's Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 animate-slide-up">
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 border border-white/30 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-800">Today</p>
                        <p class="text-sm text-gray-600"><?php echo date('M d, Y'); ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Classes</span>
                        <span class="font-semibold text-blue-600">5</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Students</span>
                        <span class="font-semibold text-purple-600">127</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 border border-white/30 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-800">95%</p>
                        <p class="text-sm text-gray-600">Attendance Rate</p>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full" style="width: 95%"></div>
                </div>
            </div>
            
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 border border-white/30 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-800">23</p>
                        <p class="text-sm text-gray-600">Reports Generated</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">This Month</span>
                    <span class="text-green-600 font-semibold">+18%</span>
                </div>
            </div>
        </div>

        <!-- Teacher Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 animate-slide-up">
            <!-- Take Attendance Card -->
            <a href="take_attendance.php" class="group action-card card-hover bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600 text-white rounded-3xl shadow-2xl p-10 flex flex-col items-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center mb-8 group-hover:animate-float relative z-10">
                    <svg class="w-10 h-10 icon-rotate" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-2xl mb-3 relative z-10">Take Attendance</h3>
                <p class="text-center opacity-90 text-lg relative z-10 leading-relaxed">
                    Mark student attendance for today's classes
                </p>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>
                <div class="absolute -top-5 -left-5 w-20 h-20 bg-white/5 rounded-full"></div>
            </a>

            <!-- View Attendance Card -->
            <a href="view_attendance.php" class="group action-card card-hover bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 text-white rounded-3xl shadow-2xl p-10 flex flex-col items-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center mb-8 group-hover:animate-float relative z-10">
                    <svg class="w-10 h-10 icon-rotate" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-2xl mb-3 relative z-10">View Attendance</h3>
                <p class="text-center opacity-90 text-lg relative z-10 leading-relaxed">
                    Review attendance records and patterns
                </p>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>
                <div class="absolute -top-5 -left-5 w-20 h-20 bg-white/5 rounded-full"></div>
            </a>

            <!-- Generate Report Card -->
            <a href="generate_report.php" class="group action-card card-hover bg-gradient-to-br from-amber-500 via-orange-500 to-red-500 text-white rounded-3xl shadow-2xl p-10 flex flex-col items-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center mb-8 group-hover:animate-float relative z-10">
                    <svg class="w-10 h-10 icon-rotate" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2"></path>
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <path d="M7 8h10M7 12h7M7 16h4"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-2xl mb-3 relative z-10">Generate Report</h3>
                <p class="text-center opacity-90 text-lg relative z-10 leading-relaxed">
                    Create detailed student progress reports
                </p>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>
                <div class="absolute -top-5 -left-5 w-20 h-20 bg-white/5 rounded-full"></div>
            </a>
        </div>

        <!-- Quick Insights -->
        <div class="mt-16 bg-white/80 backdrop-blur-sm rounded-3xl p-10 border border-white/30 shadow-xl animate-fade-in">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="mb-8 lg:mb-0">
                    <h3 class="text-3xl font-bold text-gray-800 mb-3">Quick Insights</h3>
                    <p class="text-gray-600 text-lg">Stay informed about your classroom performance</p>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">4.8</p>
                        <p class="text-sm text-gray-600">Avg Rating</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <path d="M20 8v6M23 11l-3 3-3-3"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">127</p>
                        <p class="text-sm text-gray-600">Students</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">8</p>
                        <p class="text-sm text-gray-600">Courses</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">95%</p>
                        <p class="text-sm text-gray-600">Engagement</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-20 py-8 border-t border-white/20">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-600 mb-4 md:mb-0">&copy; 2025 Teacher Portal. Empowering Education.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Help</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Support</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">Feedback</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

<?php $conn->close(); ?>