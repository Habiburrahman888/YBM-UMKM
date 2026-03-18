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
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

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
            max-width: 1400px;
            height: 100vh;
            max-height: 100vh;
            display: flex;
            position: relative;
            z-index: 1;
            gap: 0;
            overflow: hidden;
        }

        /* LEFT SIDE - IMAGE ONLY */
        .auth-left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .auth-image {
            width: 100%;
            max-width: 500px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* RIGHT SIDE - FORM WITH CARD */
        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 36px 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 440px;
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Custom scrollbar for card */
        .auth-card::-webkit-scrollbar {
            width: 6px;
        }

        .auth-card::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .auth-card::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .auth-card::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .auth-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .auth-header p {
            font-size: 15px;
            color: #6b7280;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
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
            padding: 12px 16px 12px 46px;
            font-size: 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            outline: none;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            background: #fafafa;
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
            margin-bottom: 20px;
            gap: 12px;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #3b82f6;
            flex-shrink: 0;
        }

        .form-checkbox label {
            font-size: 14px;
            color: #374151;
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
        }

        .form-link {
            font-size: 14px;
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
            padding: 12px 24px;
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
            margin: 24px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1.5px solid #e5e7eb;
        }

        .divider span {
            padding: 0 16px;
            font-size: 13px;
            color: #9ca3af;
            font-weight: 500;
        }

        .btn-google {
            width: 100%;
            padding: 12px 24px;
            background: #ffffff;
            color: #374151;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
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
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1.5px solid #f3f4f6;
        }

        .auth-footer p {
            font-size: 14px;
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
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            transition: opacity 0.5s ease;
        }

        .alert .icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
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
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
            margin-top: 16px;
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Icons */
        .icon {
            display: inline-block;
            width: 1em;
            height: 1em;
            vertical-align: middle;
        }

        /* ===== RESPONSIVE BREAKPOINTS ===== */

        /* Large Desktop */
        @media (max-width: 1400px) {
            .auth-left {
                padding: 30px;
            }

            .auth-image {
                max-width: 450px;
            }
        }

        /* Desktop/Tablet */
        @media (max-width: 1024px) {
            body {
                padding: 0;
            }

            .auth-container {
                height: 100vh;
            }

            .auth-left {
                display: none;
            }

            .auth-right {
                flex: 1;
                padding: 20px;
            }

            .auth-card {
                max-width: 440px;
                max-height: 95vh;
                padding: 36px 32px;
            }
        }

        /* Tablet */
        @media (max-width: 768px) {
            body {
                padding: 0;
            }

            .auth-right {
                padding: 16px;
            }

            .auth-card {
                padding: 32px 28px;
                border-radius: 20px;
                max-height: 96vh;
            }

            .auth-header h1 {
                font-size: 28px;
            }

            .auth-header p {
                font-size: 14px;
            }

            .form-input {
                padding: 13px 16px 13px 46px;
                font-size: 15px;
            }

            .btn-primary,
            .btn-google {
                padding: 13px 20px;
                font-size: 14px;
            }
        }

        /* Mobile */
        @media (max-width: 640px) {
            body {
                padding: 0;
            }

            .auth-right {
                padding: 12px;
            }

            .auth-card {
                padding: 28px 24px;
                border-radius: 18px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
                max-height: 97vh;
            }

            .auth-header {
                margin-bottom: 28px;
            }

            .auth-header h1 {
                font-size: 26px;
            }

            .auth-header p {
                font-size: 13px;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-label {
                font-size: 13px;
                margin-bottom: 7px;
            }

            .form-input {
                padding: 12px 14px 12px 44px;
                font-size: 15px;
                border-radius: 10px;
            }

            .form-input-icon {
                left: 14px;
                font-size: 16px;
            }

            .password-toggle {
                right: 14px;
                font-size: 16px;
            }

            .form-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                margin-bottom: 20px;
            }

            .form-checkbox label,
            .form-link {
                font-size: 13px;
            }

            .btn-primary,
            .btn-google {
                padding: 12px 18px;
                font-size: 14px;
                border-radius: 10px;
            }

            .divider {
                margin: 24px 0;
            }

            .divider span {
                font-size: 12px;
                padding: 0 12px;
            }

            .auth-footer {
                margin-top: 28px;
                padding-top: 20px;
            }

            .auth-footer p {
                font-size: 13px;
            }

            .alert {
                padding: 12px 14px;
                font-size: 13px;
                border-radius: 10px;
                margin-bottom: 20px;
            }

            .recaptcha-notice {
                font-size: 11px;
                margin-top: 14px;
            }
        }

        /* Extra Small Mobile */
        @media (max-width: 400px) {
            body {
                padding: 0;
            }

            .auth-card {
                padding: 24px 20px;
                border-radius: 16px;
                max-height: 98vh;
            }

            .auth-header h1 {
                font-size: 24px;
            }

            .auth-header p {
                font-size: 12px;
            }

            .form-input {
                padding: 11px 12px 11px 42px;
                font-size: 14px;
            }

            .btn-primary,
            .btn-google {
                padding: 11px 16px;
                font-size: 13px;
            }

            .btn-google img {
                width: 18px;
                height: 18px;
            }
        }

        /* Landscape Mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            body {
                padding: 0;
            }

            .auth-container {
                height: 100vh;
            }

            .auth-card {
                padding: 20px 24px;
                margin: 0;
                max-height: 95vh;
            }

            .auth-header {
                margin-bottom: 20px;
            }

            .auth-header h1 {
                font-size: 24px;
            }

            .form-group {
                margin-bottom: 14px;
            }

            .form-row {
                margin-bottom: 16px;
            }

            .divider {
                margin: 20px 0;
            }

            .auth-footer {
                margin-top: 20px;
                padding-top: 16px;
            }
        }

        /* High Resolution Screens */
        @media (min-width: 1600px) {
            .auth-container {
                max-width: 1600px;
            }

            .auth-image {
                max-width: 600px;
            }

            .auth-card {
                max-width: 460px;
                padding: 48px 40px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="auth-container">
        {{-- LEFT SIDE - IMAGE ONLY (Hidden on mobile) --}}
        <div class="auth-left">
            <lottie-player src="{{ asset('Auth/Pin code Password Protection, Secure Login animation.json') }}"
                background="transparent" speed="1" class="auth-image" loop autoplay></lottie-player>
        </div>

        {{-- RIGHT SIDE - FORM WITH CARD --}}
        <div class="auth-right">
            <div class="auth-card">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
