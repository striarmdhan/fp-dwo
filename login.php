<?php
session_start();
if(isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    // Hardcoded login (Simpel & Cepat)
    if($user == 'admin' && $pass == 'admin') {
        $_SESSION['login'] = true;
        $_SESSION['user'] = 'Executive Manager';
        header('Location: index.php');
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - AdventureWorks DW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== CSS VARIABLES FOR DARK MODE ===== */
        :root {
            --bg-primary: #f0f2f5;
            --bg-card: #ffffff;
            --text-primary: #1a202c;
            --text-secondary: #4b5563;
            --border-color: #e5e7eb;
        }

        body.dark-mode {
            --bg-primary: #0f172a;
            --bg-card: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --border-color: #334155;
        }

        /* Smooth transitions */
        body, .login-card, input, label, .alert {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        body {
            background-color: var(--bg-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: var(--text-primary);
        }

        .login-card {
            width: 400px;
            padding: 40px;
            border-radius: 10px;
            background: var(--bg-card);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        body.dark-mode .login-card {
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        h3 {
            color: var(--text-primary);
        }

        label {
            color: var(--text-secondary);
        }

        .form-control {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        body.dark-mode .form-control {
            background-color: #0f172a;
            border-color: var(--border-color);
        }

        body.dark-mode .form-control:focus {
            background-color: #0f172a;
            border-color: #3b82f6;
            color: var(--text-primary);
        }

        body.dark-mode .alert-danger {
            background-color: #7f1d1d;
            border-color: #991b1b;
            color: #fecaca;
        }

        /* Dark Mode Toggle Button */
        .dark-mode-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
            font-size: 1.5rem;
        }

        .dark-mode-toggle:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        body.dark-mode .dark-mode-toggle {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        /* Password Toggle Icon */
        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-secondary);
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h3 class="text-center mb-4">Executive Login</h3>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="passwordInput" class="form-control" required>
                    <i class="fas fa-eye password-toggle" id="togglePassword" title="Show password"></i>
                </div>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Sign In</button>
        </form>
    </div>

    <!-- Dark Mode Toggle Button -->
    <button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
        <i class="fas fa-moon" id="darkModeIcon"></i>
    </button>

    <script>
        // Dark Mode Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            
            // Check localStorage untuk dark mode preference
            const darkMode = localStorage.getItem('darkMode');
            
            // Apply dark mode jika sudah enabled
            if (darkMode === 'enabled') {
                document.body.classList.add('dark-mode');
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
            }
            
            // Toggle dark mode saat button diklik
            darkModeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                
                // Update icon
                if (document.body.classList.contains('dark-mode')) {
                    darkModeIcon.classList.remove('fa-moon');
                    darkModeIcon.classList.add('fa-sun');
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    darkModeIcon.classList.remove('fa-sun');
                    darkModeIcon.classList.add('fa-moon');
                    localStorage.setItem('darkMode', 'disabled');
                }
            });

            // Password Toggle Functionality
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('passwordInput');
            
            togglePassword.addEventListener('click', function() {
                // Toggle password visibility
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle icon
                if (type === 'text') {
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                    this.setAttribute('title', 'Hide password');
                } else {
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                    this.setAttribute('title', 'Show password');
                }
            });
        });
    </script>
</body>
</html>