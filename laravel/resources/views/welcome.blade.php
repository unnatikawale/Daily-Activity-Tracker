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
    
    <!-- AOS Animation Library -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --bg-light: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Enhanced Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
        }

        .navbar-nav .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: var(--primary-color);
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
            left: 0;
        }

        /* Enhanced Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: white;
            padding: 120px 0 100px;
            position: relative;
            overflow: hidden;
            min-height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            animation: fadeInUp 1s ease 0.2s;
            animation-fill-mode: both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-hero {
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem;
            animation: fadeInUp 1s ease 0.4s;
            animation-fill-mode: both;
        }

        .btn-primary-hero {
            background: white;
            color: var(--primary-color);
            border: 2px solid white;
        }

        .btn-primary-hero:hover {
            background: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .btn-outline-hero {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-outline-hero:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        /* Enhanced Features Section */
        .features-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            text-align: center;
            margin-bottom: 3rem;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
            border: none;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            position: relative;
        }

        .feature-icon.primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .feature-icon.success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .feature-icon.info {
            background: linear-gradient(135deg, #17a2b8, #007bff);
        }

        .feature-icon.warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .feature-icon.danger {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
        }

        .feature-icon.secondary {
            background: linear-gradient(135deg, #6c757d, #495057);
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--text-light);
            line-height: 1.6;
        }

        /* Enhanced Image Slider */
        .slider-section {
            padding: 80px 0;
            background: white;
        }

        .carousel {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .carousel-item img {
            height: 500px;
            object-fit: cover;
            width: 100%;
            display: block;
        }

        .carousel-caption {
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
        }

        .carousel-caption h5 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Enhanced Testimonials */
        .testimonials-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .testimonial-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
            height: 100%;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.15);
        }

        .stars {
            color: #ffc107;
            margin-bottom: 1rem;
        }

        .testimonial-text {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-style: italic;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 1rem;
            border: 3px solid rgba(255,255,255,0.3);
        }

        .user-details h6 {
            margin: 0;
            font-weight: 600;
        }

        .user-details small {
            opacity: 0.8;
        }

        /* Enhanced Footer */
        .footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 60px 0 30px;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: white;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        .social-icons {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-icons a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .carousel-item img {
                height: 300px;
            }
        }

        /* Contact Section Styles */
        .contact-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .contact-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .contact-icon {
            transition: all 0.3s ease;
        }

        .contact-card:hover .contact-icon {
            transform: scale(1.1);
        }

        /* Loading Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-calendar-check me-2"></i>Daily Activity Tracker
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
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Feedback</a>
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
        <div class="container hero-content text-center">
            <h1 class="hero-title">Daily Activity Tracker</h1>
            <p class="hero-subtitle">Transform your productivity with smart task management. Plan, track, and conquer your daily goals with ease.</p>
            @guest
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('register') }}" class="btn-hero btn-primary-hero">Get Started Free</a>
                    <a href="#features" class="btn-hero btn-outline-hero">Learn More</a>
                </div>
            @endguest
        </div>
    </section>

    <!-- Image Slider Section -->
    <section id="how-it-works" class="slider-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">How It Works</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Simple steps to boost your productivity</p>
            <div id="imageSlider" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#imageSlider" data-bs-slide-to="2"></button>
                </div>
                <div class="carousel-inner" data-aos="fade-up" data-aos-delay="200">
                    <div class="carousel-item active">
                        <img src="https://images.unsplash.com/photo-1611224923853-80b0c58c5eb6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Create Tasks" onerror="this.src='https://picsum.photos/seed/taskplanning/1200/500.jpg'">
                        <div class="carousel-caption">
                            <h5><i class="fas fa-plus-circle me-2"></i>Create Your Daily Tasks</h5>
                            <p>Add activities for each day with detailed descriptions and priority levels</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1554469384-e58e5507d953?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Track Progress" onerror="this.src='https://picsum.photos/seed/progress/1200/500.jpg'">
                        <div class="carousel-caption">
                            <h5><i class="fas fa-check-square me-2"></i>Track Your Progress</h5>
                            <p>Mark tasks as completed and monitor your daily achievements with satisfaction</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="d-block w-100" alt="Stay Organized" onerror="this.src='https://picsum.photos/seed/organized/1200/500.jpg'">
                        <div class="carousel-caption">
                            <h5><i class="fas fa-calendar-day me-2"></i>Stay Organized</h5>
                            <p>View your activities day-wise and build productive habits for long-term success</p>
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
    <section id="features" class="features-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Powerful Features</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Everything you need to stay productive and organized</p>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon primary">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h5 class="feature-title">Create Tasks</h5>
                        <p class="feature-description">Add, update, and delete daily activities with ease and precision</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon success">
                            <i class="fas fa-check-square"></i>
                        </div>
                        <h5 class="feature-title">Track Completion</h5>
                        <p class="feature-description">Mark tasks as completed using intuitive checkboxes and track your progress</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon info">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <h5 class="feature-title">Day-wise Tracking</h5>
                        <p class="feature-description">View and organize activities on a daily basis with smart filtering</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon warning">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5 class="feature-title">Responsive Design</h5>
                        <p class="feature-description">Access your tasks from any device, anywhere with our mobile-first approach</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon danger">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="feature-title">Secure Storage</h5>
                        <p class="feature-description">Your data is safely stored with enterprise-grade Laravel security</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-card">
                        <div class="feature-icon secondary">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5 class="feature-title">Progress Analytics</h5>
                        <p class="feature-description">Monitor your productivity patterns and build lasting habits</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials-section">
        <div class="container">
            <h2 class="section-title text-white" data-aos="fade-up">What Our Users Say</h2>
            <p class="section-subtitle text-white" data-aos="fade-up" data-aos-delay="100">Real feedback from our amazing community</p>
            <div class="row g-4">
                @if($recentFeedbacks->count() > 0)
                    @foreach($recentFeedbacks as $index => $feedback)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                            <div class="testimonial-card">
                                <div class="stars mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $feedback->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="testimonial-text">"{{ $feedback->message }}"</p>
                                <div class="user-info">
                                    <img src="https://picsum.photos/seed/{{ Str::slug($feedback->name) }}/100/100.jpg" class="user-avatar" alt="{{ $feedback->name }}">
                                    <div class="user-details">
                                        <h6>{{ $feedback->name }}</h6>
                                        <small>Verified User</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center" data-aos="fade-up">
                        <div class="testimonial-card">
                            <i class="fas fa-comment-slash fa-4x text-white mb-4"></i>
                            <h4 class="text-white mb-3">No Feedbacks Yet</h4>
                            <p class="text-white mb-4">Be the first to share your experience with our Daily Activity Tracker!</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('feedback.index') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-comment-dots me-2"></i>Share Your Feedback
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section py-5">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Get In Touch</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Have questions? We'd love to hear from you!</p>
            
            <div class="row g-4 justify-content-center">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="contact-icon mb-3">
                            <i class="fas fa-envelope fa-3x text-primary"></i>
                        </div>
                        <h5>Email Us</h5>
                        <p class="text-muted">unnatikawale43@gmail.com</p>
                        <a href="{{ route('contact.show') }}" class="btn btn-outline-primary mt-2">Send Message</a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="contact-icon mb-3">
                            <i class="fas fa-phone fa-3x text-success"></i>
                        </div>
                        <h5>Call Us</h5>
                        <p class="text-muted">+1 (555) 123-4567</p>
                        <p class="small text-muted">Mon-Fri: 9AM-6PM</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="contact-card text-center p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="contact-icon mb-3">
                            <i class="fas fa-comments fa-3x text-info"></i>
                        </div>
                        <h5>Live Chat</h5>
                        <p class="text-muted">Chat with our support team</p>
                        <button class="btn btn-outline-info mt-2" disabled>Coming Soon</button>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('contact.show') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane me-2"></i>Contact Us Now
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="footer-brand"><i class="fas fa-calendar-check me-2"></i>Daily Activity Tracker</h5>
                    <p>Your personal productivity companion for better time management and habit building. Start achieving more today.</p>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#testimonials">Testimonials</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="mb-3">Connect With Us</h5>
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <p class="mb-2"><i class="fas fa-envelope me-2"></i>unnatikawale43@gmail.com</p>
                    <p class="mb-2"><i class="fas fa-phone me-2"></i>+1 (555) 123-4567</p>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">
            <div class="text-center">
                <p class="mb-0">&copy; 2026 Daily Activity Tracker. All rights reserved. Made with <i class="fas fa-heart text-danger"></i> for productivity enthusiasts.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.padding = '0.5rem 0';
                navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,0.15)';
            } else {
                navbar.style.padding = '1rem 0';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            }
        });

        // Add loading animation to elements
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
