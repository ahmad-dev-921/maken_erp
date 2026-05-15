<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Maken Solar Energy</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --maken-amber: #fbbf24;
            --maken-amber-dark: #d97706;
            --maken-slate: #0f172a;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 1100px;
            background: white;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            display: flex;
            min-height: 650px;
            border-radius: 30px;
        }

        .login-sidebar {
            background: radial-gradient(circle at top right, #fbbf24 0%, #d97706 30%, #20375c 70%);
            width: 45%;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            position: relative;
        }

        .login-form-section {
            flex: 1;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 1rem;
            border-radius: 20px;
            display: inline-flex;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .form-control {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 1rem 1rem 1rem 3rem;
            border-radius: 15px;
            font-weight: 500;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.1);
            border-color: var(--maken-amber);
        }

        .input-group-text {
            background: transparent;
            border: none;
            position: absolute;
            z-index: 10;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .btn-login {
            background: var(--maken-slate);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
            color: white;
        }

        .error-feedback {
            font-size: 0.8rem;
            color: #ef4444;
            margin-top: 0.5rem;
            font-weight: 600;
        }

        @media (max-width: 992px) {
            .login-sidebar { display: none; }
            .login-container { max-width: 500px; margin: 1rem; }
            .login-form-section { padding: 3rem 2rem; }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Sidebar Branding -->
        <div class="login-sidebar d-none d-lg-flex">
            <div>
                <div class="d-flex align-items-center">
                    <div class="brand-badge mb-4 me-3">
                        <i data-lucide="zap" class="text-white"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold italic text-uppercase mb-1">Maken Solar Energy</h4>
                        <p class="text-white-50 fw-bold small text-uppercase tracking-widest mb-0">
                            Premium Solar Solutions
                        </p>
                    </div>
                </div>
                
                <h1 class="display-4 mt-5 lh-1" style="font-weight: 800;">Premium<br><span class="text-warning">Solar</span><br>Solutions</h1>
                <p class="mt-4 text-white-50 fs-5">Secure access to the Sargodha enterprise management hub.</p>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25">
                    <i data-lucide="award" class="text-warning"></i>
                </div>
                <div>
                    <p class="mb-0 fw-bold">Certified Quality</p>
                    <small class="text-white-50">Authorized Personnel Only</small>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="login-form-section">
            <div class="mb-5 text-center text-lg-start">
                <h2 class="fw-bold text-dark display-6 tracking-tighter">Sign In</h2>
                <p class="text-muted fw-500">Welcome back! Please enter your details.</p>
            </div>

<form method="POST" action="/login">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-4 fw-bold" style="font-size:0.85rem;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-4 position-relative">
        <label class="form-label small fw-bold text-uppercase text-muted ms-1">Email Address</label>
        <div class="position-relative">
            <span class="input-group-text"><i data-lucide="mail" size="18"></i></span>
            <input type="text" name="email" class="form-control"
                   placeholder="name@company.com"
                   value="{{ old('email') }}" required>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label small fw-bold text-uppercase text-muted ms-1">Password</label>
        <div class="position-relative">
            <span class="input-group-text"><i data-lucide="lock" size="18"></i></span>
            <input type="password" name="password" class="form-control"
                   placeholder="••••••••" required>
        </div>
    </div>

    <button type="submit" class="btn btn-login w-100 mb-4">
        Sign In <i data-lucide="arrow-right" class="ms-2" size="18"></i>
    </button>
</form>

            <div class="mt-auto pt-5 border-top d-flex justify-content-center gap-4">
                <a href="#" class="text-muted text-decoration-none d-flex align-items-center gap-2">
                    <i data-lucide="headphones" size="16"></i> <span class="small fw-bold text-uppercase">Support</span>
                </a>
                <a href="#" class="text-muted text-decoration-none d-flex align-items-center gap-2">
                    <i data-lucide="shield-check" size="16"></i> <span class="small fw-bold text-uppercase">Admin</span>
                </a>
            </div>
        </div>
    </div>

<script>
document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    try {
        let response = await fetch("/api/login", {
            method: "POST",
            headers: {
                "Accept": "application/json"
            },
            body: formData
        });

        let data = await response.json();

        if (response.ok) {
            // Save token
            localStorage.setItem("token", data.token);

            // Redirect to dashboard
            window.location.href = "/dashboard";
        } else {
            showPopup(data.message || "Login failed", "error");
        }

    } catch (error) {
        console.error(error);
        showPopup("Something went wrong", "error");
    }   
});
</script>
</body>
</html>
