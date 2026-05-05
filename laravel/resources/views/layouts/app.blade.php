<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Daily Tracker') }}</title>
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <style>
            body {
                background-color: #f8f9fa;
                background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
            }
            .navbar-brand {
                font-weight: bold;
                transition: transform 0.2s ease;
            }
            .navbar-brand:hover {
                transform: translateY(-2px);
            }
            .card {
                border: none;
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                transition: all 0.3s ease;
            }
            .card:hover {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                transform: translateY(-2px);
            }
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
                transform: translateY(-2px);
                box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.4);
            }
            .btn-warning {
                transition: all 0.3s ease;
            }
            .btn-warning:hover {
                transform: translateY(-2px);
                box-shadow: 0 0.5rem 1rem rgba(255, 193, 7, 0.4);
            }
            .btn-danger {
                transition: all 0.3s ease;
            }
            .btn-danger:hover {
                transform: translateY(-2px);
                box-shadow: 0 0.5rem 1rem rgba(220, 53, 69, 0.4);
            }
            .form-control {
                transition: all 0.3s ease;
            }
            .form-control:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }
            .nav-link {
                transition: all 0.2s ease;
            }
            .nav-link:hover {
                transform: translateY(-1px);
            }
            .alert {
                border: none;
                border-radius: 0.5rem;
            }
            .modal-content {
                border: none;
                border-radius: 0.75rem;
                box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
            }
        </style>
    </head>
    <body>
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow-sm">
                <div class="container py-4">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
