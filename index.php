<?php
session_start();
include 'config.php';
include 'functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = get_user_role($conn, $_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Attendance Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--dark-color);
        }

        /* Modern Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            text-decoration: none;
        }

        .navbar-brand i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .nav-link-custom {
            color: var(--secondary-color) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .nav-link-custom:hover {
            background-color: var(--light-bg);
            color: var(--primary-color) !important;
        }

        /* Main Container */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Welcome Card */
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            font-size: 1.1rem;
            color: var(--secondary-color);
            margin-bottom: 2rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .user-details h3 {
            margin-bottom: 0.25rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .user-role {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .role-admin {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .role-teacher {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .role-student {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        /* Action Button */
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 0.75rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .btn-admin {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-admin:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        .btn-teacher {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .btn-teacher:hover {
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-student {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
        }

        .btn-student:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .stat-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }
            
            .welcome-card {
                padding: 1.5rem;
            }
            
            .user-info {
                flex-direction: column;
                text-align: center;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Fade In Animation */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap"></i>
                University Attendance System
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link-custom" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Welcome Card -->
        <div class="welcome-card fade-in">
            <div class="welcome-title">Welcome Back!</div>
            <div class="welcome-subtitle">Access your personalized dashboard and manage your university attendance</div>
            
            <!-- User Information -->
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 2)); ?>
                </div>
                <div class="user-details">
                    <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                    <span class="user-role role-<?php echo $role; ?>">
                        <i class="fas fa-<?php 
                            echo $role == 'admin' ? 'crown' : ($role == 'teacher' ? 'chalkboard-teacher' : 'user-graduate'); 
                        ?> me-1"></i>
                        <?php echo ucfirst($role); ?>
                    </span>
                </div>
            </div>

            <!-- Action Button -->
            <?php
            if ($role == 'admin') {
                echo '<a href="admin_dashboard.php" class="action-btn btn-admin">
                        <i class="fas fa-cog"></i>
                        Access Admin Panel
                      </a>';
            } elseif ($role == 'teacher') {
                echo '<a href="teacher_dashboard.php" class="action-btn btn-teacher">
                        <i class="fas fa-chalkboard-teacher"></i>
                        Access Teacher Panel
                      </a>';
            } elseif ($role == 'student') {
                echo '<a href="student_dashboard.php" class="action-btn btn-student">
                        <i class="fas fa-user-graduate"></i>
                        Access Student Panel
                      </a>';
            }
            ?>
        </div>

        <!-- Quick Stats -->
        <div class="stats-container fade-in">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(37, 99, 235, 0.1); color: var(--primary-color);">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-title">Quick Access</div>
                <div class="stat-value">Dashboard</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-title">Session Status</div>
                <div class="stat-value">Active</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-title">Role Level</div>
                <div class="stat-value"><?php echo ucfirst($role); ?></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Add smooth scrolling and enhanced interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading state to buttons
            const actionBtn = document.querySelector('.action-btn');
            if (actionBtn) {
                actionBtn.addEventListener('click', function(e) {
                    const icon = this.querySelector('i');
                    const originalClass = icon.className;
                    
                    // Add loading animation
                    icon.className = 'fas fa-spinner fa-spin';
                    
                    // Reset after navigation (fallback)
                    setTimeout(() => {
                        icon.className = originalClass;
                    }, 2000);
                });
            }

            // Add hover effects to stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.boxShadow = 'var(--shadow-md)';
                });
            });

            // Add dynamic greeting based on time
            const welcomeTitle = document.querySelector('.welcome-title');
            const hour = new Date().getHours();
            let greeting = 'Welcome Back!';
            
            if (hour < 12) greeting = 'Good Morning!';
            else if (hour < 17) greeting = 'Good Afternoon!';
            else greeting = 'Good Evening!';
            
            welcomeTitle.textContent = greeting;
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>