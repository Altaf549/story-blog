<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Story Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --text: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --background: #f9fafb;
            --white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --radius: 0.5rem;
            --transition: all 150ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--text);
            line-height: 1.5;
            margin: 0;
            padding: 1rem;
        }

        .container {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
        }
        
        .login-container {
            width: 100%;
            max-width: 24rem;
            height: 90vh;
            background: var(--white);
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            margin: auto;
            display: flex;
            flex-direction: column;
        }
        
        .login-content {
            padding: 2rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-header {
            text-align: center;
        }
        
        .logo {
            width: 3rem;
            height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #eef2ff;
            color: var(--primary);
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .logo i {
            font-size: 1.25rem;
        }
        
        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
            line-height: 1.25;
        }
        
        .login-header p {
            color: var(--text-light);
            font-size: 0.9375rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.625rem 0.875rem;
            font-size: 0.9375rem;
            line-height: 1.5;
            color: var(--text);
            background-color: var(--white);
            background-clip: padding-box;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            outline: none;
        }
        
        .input-group {
            position: relative;
            display: flex;
            width: 100%;
        }
        
        .input-group .form-control {
            position: relative;
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
            padding-right: 2.5rem;
        }
        
        .input-icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .input-icon:hover {
            color: var(--primary);
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            font-size: 0.875rem;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-check-input {
            width: 1rem;
            height: 1rem;
            border: 1px solid var(--border);
            border-radius: 0.25rem;
            transition: var(--transition);
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-label {
            color: var(--text);
            font-size: 0.875rem;
            cursor: pointer;
        }
        
        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.875rem;
            transition: var(--transition);
        }
        
        .forgot-password:hover {
            text-decoration: underline;
            color: var(--primary-hover);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius);
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid transparent;
            font-size: 0.9375rem;
            line-height: 1.5;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
            width: 100%;
            padding: 0.75rem 1.5rem;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
        }
        
        .login-footer {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
            color: var(--text-light);
            font-size: 0.8125rem;
        }
        
        .alert {
            padding: 0.875rem 1rem;
            margin-bottom: 1rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            background-color: #fef2f2;
            color: #b91c1c;
        }
        
        .alert i {
            font-size: 1rem;
            margin-top: 0.125rem;
        }
        
        .alert-content {
            flex: 1;
        }
        
        .btn-close {
            padding: 0.25rem;
            background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%239ca3af'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/0.75rem auto no-repeat;
            border: 0;
            border-radius: 0.25rem;
            opacity: 0.7;
            transition: var(--transition);
            cursor: pointer;
            width: 1rem;
            height: 1rem;
        }
        
        .btn-close:hover {
            opacity: 1;
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        @media (max-width: 480px) {
            .login-content {
                padding: 1.75rem 1.25rem;
            }
            
            .login-header h1 {
                font-size: 1.375rem;
            }
            
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-content">
                <div class="login-header">
                    <div class="logo">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h1>Welcome back</h1>
                    <p>Enter your credentials to access your account</p>
                </div>
                
                @if($errors->any())
                    <div class="alert" role="alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div class="alert-content">{{ $errors->first() }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.login') }}" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email address</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
                            <span class="input-icon">
                                <i class="far fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label">Password</label>
                        </div>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" 
                                   name="password" placeholder="••••••••" required>
                            <span class="input-icon toggle-password" onclick="togglePassword()">
                                <i class="far fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i> Sign in
                    </button>
                    
                    <div class="login-footer">
                        &copy; {{ date('Y') }} Story Blog. All rights reserved.
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.toggle-password i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Form validation
        (function () {
            'use strict';
            
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation');
            
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
