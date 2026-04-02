<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overflow: hidden;
            height: 100vh;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #EBF4FF 0%, #C3DAFE 100%);
            height: 100vh;
            min-height: 100vh;
            max-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .auth-container {
            width: 100%;
            max-width: 480px;
            height: 100vh;
            max-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-height: 95vh;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .auth-card::-webkit-scrollbar {
            display: none;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .auth-header h1 {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .auth-header p {
            font-size: 14px;
            color: #6b7280;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 7px;
        }

        .form-input-wrapper {
            position: relative;
        }

        .form-input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 18px;
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 11px 16px 11px 46px;
            font-size: 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            outline: none;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            background: #fafafa;
            height: 42px;
        }

        .form-input:focus {
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
            font-size: 18px;
            transition: color 0.2s;
            display: flex;
            align-items: center;
        }

        .password-toggle:hover {
            color: #6b7280;
        }

        .invalid-feedback {
            display: block;
            margin-top: 6px;
            font-size: 13px;
            color: #ef4444;
            font-weight: 500;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            gap: 12px;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #3b82f6;
            flex-shrink: 0;
        }

        .form-checkbox label {
            font-size: 13px;
            color: #374151;
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
        }

        .form-link {
            font-size: 13px;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
            white-space: nowrap;
        }

        .form-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .btn-primary {
            width: 100%;
            padding: 11px 24px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 18px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1.5px solid #e5e7eb;
        }

        .divider span {
            padding: 0 16px;
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
        }

        .btn-google {
            width: 100%;
            padding: 11px 24px;
            background: #ffffff;
            color: #374151;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
        }

        .btn-google:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .btn-google img {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1.5px solid #f3f4f6;
        }

        .auth-footer p {
            font-size: 13px;
            color: #6b7280;
        }

        .auth-footer a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .auth-footer a:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .alert {
            padding: 11px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            transition: opacity 0.5s ease;
        }

        .alert .icon {
            flex-shrink: 0;
            width: 18px;
            height: 18px;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border: 1.5px solid #bbf7d0;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1.5px solid #fecaca;
        }

        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1.5px solid #fde68a;
        }

        .alert-info {
            background: #eff6ff;
            color: #1e40af;
            border: 1.5px solid #bfdbfe;
        }

        .grecaptcha-badge {
            visibility: visible !important;
            opacity: 1 !important;
            z-index: 9999 !important;
        }

        .recaptcha-notice {
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
            margin-top: 12px;
            line-height: 1.5;
        }

        .recaptcha-notice a {
            color: #3b82f6;
            text-decoration: none;
        }

        .recaptcha-notice a:hover {
            text-decoration: underline;
        }

        /* Loading spinner */
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 2px solid #ffffff;
            width: 16px;
            height: 16px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .icon {
            display: inline-block;
            width: 1em;
            height: 1em;
            vertical-align: middle;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-container {
                padding: 16px;
            }

            .auth-card {
                padding: 28px 24px;
                border-radius: 20px;
            }
        }

        @media (max-width: 400px) {
            .auth-card {
                padding: 24px 18px;
                border-radius: 16px;
            }
        }

        @media (max-width: 768px) and (orientation: landscape) {
            .auth-card {
                padding: 20px 24px;
                max-height: 95vh;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            @yield('content')
        </div>
    </div>

    @stack('scripts')
</body>

</html>