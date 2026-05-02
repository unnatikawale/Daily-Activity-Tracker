<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daily Activity Tracker - Plan & Track Your Daily Tasks</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .testimonial-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin: 20px 0;
        }
        .carousel-item img {
            height: 400px;
            object-fit: cover;
        }
        .footer {
            background: #2c3e50;
            color: white;
            padding: 50px 0 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tasks me-2"></i>Daily Activity Tracker
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
                <div class="ms-3">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                    @else
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Daily Activity Tracker</h1>
            <p class="lead mb-5">Plan, monitor, and manage your day-to-day tasks efficiently. Build productive habits and improve your time management.</p>
            @guest
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">Get Started Free</a>
                    <a href="#features" class="btn btn-outline-light btn-lg">Learn More</a>
                </div>
            @endguest
        </div>
    </section>

    <!-- Image Slider Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">How It Works</h2>
            <div id="imageSlider" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="2"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="https://picsum.photos/seed/activity1/1200/400.jpg" class="d-block w-100" alt="Create Tasks">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Create Your Daily Tasks</h5>
                            <p>Add activities for each day with detailed descriptions</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://picsum.photos/seed/activity2/1200/400.jpg" class="d-block w-100" alt="Track Progress">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Track Your Progress</h5>
                            <p>Mark tasks as completed and monitor your daily achievements</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://picsum.photos/seed/activity3/1200/400.jpg" class="d-block w-100" alt="Stay Organized">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>Stay Organized</h5>
                            <p>View your activities day-wise and build productive habits</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#imageSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#imageSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Key Features</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Create Tasks</h5>
                            <p class="card-text">Add, update, and delete daily activities with ease</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-check-square fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Track Completion</h5>
                            <p class="card-text">Mark tasks as completed using intuitive checkboxes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-day fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Day-wise Tracking</h5>
                            <p class="card-text">View and organize activities on a daily basis</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-mobile-alt fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Responsive Design</h5>
                            <p class="card-text">Access your tasks from any device, anywhere</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-shield-alt fa-3x text-danger mb-3"></i>
                            <h5 class="card-title">Secure Storage</h5>
                            <p class="card-text">Your data is safely stored with Laravel security</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-3x text-secondary mb-3"></i>
                            <h5 class="card-title">Progress Analytics</h5>
                            <p class="card-text">Monitor your productivity and build habits</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">User Feedback</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3">"This app has completely transformed how I organize my daily tasks. I'm more productive than ever!"</p>
                        <div class="d-flex align-items-center">
                            <img src="https://picsum.photos/seed/user1/50/50.jpg" class="rounded-circle me-3" alt="User">
                            <div>
                                <h6 class="mb-0">Sarah Johnson</h6>
                                <small class="text-muted">Marketing Manager</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3">"Simple, intuitive, and exactly what I needed to track my daily activities. Highly recommended!"</p>
                        <div class="d-flex align-items-center">
                            <img src="https://picsum.photos/seed/user2/50/50.jpg" class="rounded-circle me-3" alt="User">
                            <div>
                                <h6 class="mb-0">Mike Chen</h6>
                                <small class="text-muted">Software Developer</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3">"The checkbox feature makes it so satisfying to complete tasks. I love seeing my progress!"</p>
                        <div class="d-flex align-items-center">
                            <img src="https://picsum.photos/seed/user3/50/50.jpg" class="rounded-circle me-3" alt="User">
                            <div>
                                <h6 class="mb-0">Emily Davis</h6>
                                <small class="text-muted">Freelance Designer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-tasks me-2"></i>Daily Activity Tracker</h5>
                    <p>Your personal productivity companion for better time management and habit building.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="#features" class="text-white text-decoration-none">Features</a></li>
                        <li><a href="#testimonials" class="text-white text-decoration-none">Testimonials</a></li>
                        <li><a href="#contact" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="d-flex gap-3 mb-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                    <p>Email: info@dailyactivitytracker.com</p>
                    <p>Phone: +1 (555) 123-4567</p>
                </div>
            </div>
            <hr class="bg-white my-4">
            <div class="text-center">
                <p>&copy; 2026 Daily Activity Tracker. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
