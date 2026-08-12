<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complaint Management System</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        /* CSS Variables for Dark/Light Mode */
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --accent: #38bdf8;
            --gradient-1: linear-gradient(135deg, #38bdf8, #2563eb);
            
            /* Light Theme (Default) */
            --bg-color: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --card-bg: #ffffff;
            --card-border: rgba(0, 0, 0, 0.05);
            --nav-bg: rgba(255, 255, 255, 0.7);
            --feature-icon-bg: #f1f5f9;
        }

        [data-theme="dark"] {
            /* Dark Theme */
            --bg-color: #0b1120;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --card-bg: #1e293b;
            --card-border: rgba(255, 255, 255, 0.08);
            --nav-bg: rgba(11, 17, 32, 0.7);
            --feature-icon-bg: #0f172a;
        }

        /* Smooth Theme Transition */
        body, .card, .navbar, .feature-icon-wrapper, p, h1, h2, h3, h4, h5, h6 {
            transition: background-color 0.5s ease, color 0.5s ease, border-color 0.5s ease, box-shadow 0.5s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        
        body {
            background: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-color); }
        ::-webkit-scrollbar-thumb { background: var(--text-muted); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }
        html { scroll-behavior: smooth; }

        /* Scroll Progress Bar */
        .scroll-progress {
            position: fixed;
            top: 0; left: 0;
            width: 0%; height: 4px;
            background: var(--gradient-1);
            z-index: 999999;
            transition: width 0.1s ease;
        }

        /* Text Gradient Utility */
        .text-gradient {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Preloader */
        #preloader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: var(--bg-color);
            z-index: 999999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.6s ease;
        }
        .loader-ring {
            width: 60px; height: 60px;
            border: 4px solid transparent;
            border-top-color: var(--primary);
            border-right-color: var(--accent);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Glassmorphism Navbar with Noise */
        .navbar {
            background: transparent;
            padding: 20px 0;
        }
        .navbar.navbar-scrolled {
            background: var(--nav-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            padding: 12px 0;
            border-bottom: 1px solid var(--card-border);
        }
        .navbar.navbar-scrolled::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('data:image/svg+xml,%3Csvg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"%3E%3Cfilter id="noiseFilter"%3E%3CfeTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/%3E%3C/filter%3E%3Crect width="100%25" height="100%25" filter="url(%23noiseFilter)" opacity="0.05"/%3E%3C/svg%3E');
            pointer-events: none;
            z-index: -1;
        }

        .navbar-brand {
            font-size: 26px; font-weight: 800;
            color: var(--text-main) !important;
        }
        .nav-link {
            color: var(--text-muted) !important;
            margin: 0 10px; font-weight: 600; font-size: 15px; position: relative;
        }
        .nav-link::after {
            content: ''; position: absolute;
            width: 0; height: 2px; bottom: -4px; left: 50%;
            background-color: var(--primary);
            transition: all 0.3s ease; transform: translateX(-50%);
        }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        .nav-link:hover { color: var(--text-main) !important; }

        /* Buttons & Shine Effect */
        .btn-custom {
            border-radius: 50px;
            padding: 12px 32px;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none; position: relative; overflow: hidden; z-index: 1;
            display: inline-block;
        }
        .btn-custom::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-25deg); transition: all 0.6s ease; z-index: -1;
        }
        .btn-custom:hover::before { left: 200%; }
        .btn-primary-custom {
            background: var(--primary); color: white;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }
        .btn-primary-custom:hover {
            background: var(--primary-hover); color: white;
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.4);
        }

        /* Hero Aurora Background */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center;
            background-color: #0b1120;
            color: white;
            padding: 120px 0 80px 0;
            position: relative; overflow: hidden;
        }
        
        .hero-mesh {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 15% 50%, rgba(37, 99, 235, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(56, 189, 248, 0.2) 0%, transparent 50%);
            z-index: 0;
            animation: breathe 10s ease-in-out infinite alternate;
        }
        @keyframes breathe {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.1); opacity: 1; }
        }
        .hero-content { position: relative; z-index: 2; }
        .hero h1 { font-size: clamp(2.8rem, 5vw, 4.5rem); font-weight: 800; line-height: 1.15; margin-bottom: 25px; }
        .hero p { font-size: 1.15rem; color: #cbd5e1; margin-bottom: 40px; font-weight: 400; line-height: 1.8; }
        
        .hero img {
            width: 100%; max-width: 600px;
            animation: float 6s ease-in-out infinite;
            filter: drop-shadow(0 30px 40px rgba(0,0,0,0.4));
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        /* Modern Cards */
        .card-hover {
            border: 1px solid var(--card-border);
            background: var(--card-bg);
            position: relative; z-index: 1;
        }
        .card-hover::after {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            border-radius: inherit;
            box-shadow: 0 30px 60px rgba(0,0,0,0.1);
            opacity: 0; transition: opacity 0.4s ease; z-index: -1;
        }
        [data-theme="dark"] .card-hover::after { box-shadow: 0 30px 60px rgba(0,0,0,0.4); }
        .card-hover:hover { border-color: var(--primary); }
        .card-hover:hover::after { opacity: 1; }

        .section-title { font-weight: 800; font-size: 2.8rem; margin-bottom: 15px; color: var(--text-main); }
        .section-subtitle { color: var(--text-muted); font-size: 1.15rem; font-weight: 500; }

        .feature-icon-wrapper {
            width: 80px; height: 80px; border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 25px auto;
            background: var(--feature-icon-bg);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .card-hover:hover .feature-icon-wrapper {
            transform: scale(1.15) translateZ(30px);
            background: var(--primary); color: white !important;
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3);
        }

        /* Contact Form */
        .form-control {
            border-radius: 12px; padding: 18px 20px;
            border: 1px solid var(--card-border);
            background: var(--bg-color); color: var(--text-main);
            font-size: 1rem; font-weight: 500; transition: all 0.3s ease;
        }
        .form-control:focus {
            background: var(--card-bg);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
            border-color: var(--primary); color: var(--text-main);
        }

        /* Floating Theme Toggle */
        .theme-toggle-btn {
            position: fixed; bottom: 30px; left: 30px;
            width: 55px; height: 55px; border-radius: 50%;
            background: var(--card-bg); color: var(--text-main);
            border: 1px solid var(--card-border);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 999; font-size: 1.2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-toggle-btn:hover { transform: scale(1.1) rotate(15deg); border-color: var(--primary); }

        .back-to-top {
            opacity: 0; visibility: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: scale(0.8) translateY(20px);
        }
        .back-to-top.active {
            opacity: 1; visibility: visible;
            transform: scale(1) translateY(0);
        }
    </style>
</head>
<body>

<!-- Scroll Progress Bar -->
<div class="scroll-progress" id="scrollProgress"></div>

<!-- Preloader -->
<div id="preloader">
    <div class="loader-ring"></div>
</div>

<!-- Theme Toggle Button -->
<button class="theme-toggle-btn" id="themeToggle" aria-label="Toggle Dark Mode">
    <i class="fas fa-moon" id="themeIcon"></i>
</button>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#home">
            <i class="fas fa-shield-alt me-2 text-info"></i> Complaint CMS
        </a>
        <button class="navbar-toggler bg-light border-0 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                <a href="login.php" class="nav-link border-0 bg-transparent fw-bold px-0">Login</a>
                <a href="register.php" class="btn btn-primary-custom btn-custom text-white shadow-sm">Get Started</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero">
    <div class="hero-mesh"></div>
    <div class="container hero-content">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0" data-aos="fade-up" data-aos-duration="1200">
                <div class="badge bg-white bg-opacity-10 text-white rounded-pill px-4 py-2 mb-4 fw-bold border border-white border-opacity-25 backdrop-blur">
                    <i class="fas fa-bolt text-warning me-2"></i> Fast, Secure, Reliable
                </div>
                <h1>Modern <br> <span id="typed-text" class="text-gradient"></span></h1>
                <p>An intelligent, highly-responsive platform to register, track, and resolve organizational complaints efficiently with dedicated dashboards.</p>
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-4">
                    <a href="login.php" class="btn btn-primary-custom btn-custom btn-lg">
                        <i class="fas fa-right-to-bracket me-2"></i> User Login
                    </a>
                    <a href="register.php" class="btn btn-light btn-custom btn-lg text-dark">
                        <i class="fas fa-user-plus me-2"></i> Create Account
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center hero-img-wrapper" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="200">
                <img src="https://undraw.co/api/illustrations/complaint.svg" onerror="this.src='https://via.placeholder.com/600x450/0f172a/ffffff?text=Complaint+Management';" alt="Dashboard Illustration" class="img-fluid" data-tilt data-tilt-max="5" data-tilt-speed="1000">
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 position-relative" style="margin-top: -60px; z-index: 10; background: transparent;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card card-hover border-0 text-center p-4 rounded-4 h-100 shadow-sm" data-tilt data-tilt-max="10" data-tilt-glare data-tilt-max-glare="0.3">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h2 class="fw-bold counter" data-target="1500">0</h2>
                    <p class="text-muted mb-0 fw-medium">Registered Users</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card card-hover border-0 text-center p-4 rounded-4 h-100 shadow-sm" data-tilt data-tilt-max="10" data-tilt-glare data-tilt-max-glare="0.3">
                    <i class="fas fa-file-circle-exclamation fa-3x text-danger mb-3"></i>
                    <h2 class="fw-bold counter" data-target="3200">0</h2>
                    <p class="text-muted mb-0 fw-medium">Complaints Filed</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card card-hover border-0 text-center p-4 rounded-4 h-100 shadow-sm" data-tilt data-tilt-max="10" data-tilt-glare data-tilt-max-glare="0.3">
                    <i class="fas fa-circle-check fa-3x text-success mb-3"></i>
                    <h2 class="fw-bold counter" data-target="2900">0</h2>
                    <p class="text-muted mb-0 fw-medium">Issues Resolved</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card card-hover border-0 text-center p-4 rounded-4 h-100 shadow-sm" data-tilt data-tilt-max="10" data-tilt-glare data-tilt-max-glare="0.3">
                    <i class="fas fa-headset fa-3x text-warning mb-3"></i>
                    <h2 class="fw-bold">24/7</h2>
                    <p class="text-muted mb-0 fw-medium">Active Support</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-5 py-lg-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&q=80" class="img-fluid rounded-4 shadow-lg" alt="About CMS" data-tilt data-tilt-max="5">
                    <div class="position-absolute bottom-0 end-0 bg-white p-4 rounded-4 shadow-lg mb-n4 me-n4 d-none d-md-block" data-aos="zoom-in" data-aos-delay="200" style="background: var(--card-bg);">
                        <h4 class="fw-bold text-gradient mb-0">100%</h4>
                        <p class="text-muted mb-0 small fw-bold">Secure Platform</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-3 fw-bold">About The Platform</div>
                <h2 class="section-title">Streamline Your Issue Resolution</h2>
                <p class="text-muted fs-5 mb-4">Complaint Management System is an advanced web application designed to simplify registration, tracking, and resolution. Experience seamless communication across all organizational tiers.</p>
                
                <div class="row g-4 mt-2">
                    <div class="col-sm-6 d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3"><i class="fas fa-check text-success fs-5"></i></div>
                        <h6 class="mb-0 fw-bold">Easy Registration</h6>
                    </div>
                    <div class="col-sm-6 d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3"><i class="fas fa-satellite-dish text-primary fs-5"></i></div>
                        <h6 class="mb-0 fw-bold">Live Status Tracking</h6>
                    </div>
                    <div class="col-sm-6 d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3"><i class="fas fa-chart-pie text-warning fs-5"></i></div>
                        <h6 class="mb-0 fw-bold">Reports & Analytics</h6>
                    </div>
                    <div class="col-sm-6 d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3"><i class="fas fa-shield-halved text-danger fs-5"></i></div>
                        <h6 class="mb-0 fw-bold">Secure Login System</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 py-lg-5">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-3 fw-bold">Core Features</div>
            <h2 class="section-title">Why Choose <span class="text-gradient">Complaint CMS?</span></h2>
            <p class="section-subtitle">A simple, secure, and powerful workflow tailored for your organization.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card card-hover border-0 shadow-sm rounded-4 h-100 p-2" data-tilt data-tilt-max="15" data-tilt-speed="400">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon-wrapper text-primary"><i class="fas fa-user-shield fa-2x"></i></div>
                        <h4 class="fw-bold mb-3">Secure Login</h4>
                        <p class="text-muted mb-0">Role-based authentication granting specific access to Admins, Managers, Staff, and Users.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card card-hover border-0 shadow-sm rounded-4 h-100 p-2" data-tilt data-tilt-max="15">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon-wrapper text-warning"><i class="fas fa-bell fa-2x"></i></div>
                        <h4 class="fw-bold mb-3">Live Notifications</h4>
                        <p class="text-muted mb-0">Receive instant updates and email alerts the moment a complaint's status changes.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card card-hover border-0 shadow-sm rounded-4 h-100 p-2" data-tilt data-tilt-max="15">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon-wrapper text-success"><i class="fas fa-chart-line fa-2x"></i></div>
                        <h4 class="fw-bold mb-3">Analytics & Reports</h4>
                        <p class="text-muted mb-0">Generate comprehensive PDF and Excel reports with visual data charts instantly.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card card-hover border-0 shadow-sm rounded-4 h-100 p-2" data-tilt data-tilt-max="15">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon-wrapper text-danger"><i class="fas fa-file-circle-plus fa-2x"></i></div>
                        <h4 class="fw-bold mb-3">Easy Submission</h4>
                        <p class="text-muted mb-0">Users can categorize and submit detailed complaints with attachments in a few clicks.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="card card-hover border-0 shadow-sm rounded-4 h-100 p-2" data-tilt data-tilt-max="15">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon-wrapper text-info"><i class="fas fa-clock fa-2x"></i></div>
                        <h4 class="fw-bold mb-3">Track Status</h4>
                        <p class="text-muted mb-0">Monitor the progression of your ticket in real-time from 'Pending' to 'Resolved'.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="card card-hover border-0 shadow-sm rounded-4 h-100 p-2" data-tilt data-tilt-max="15">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon-wrapper text-secondary"><i class="fas fa-mobile-screen-button fa-2x"></i></div>
                        <h4 class="fw-bold mb-3">Fully Responsive</h4>
                        <p class="text-muted mb-0">A seamless, app-like experience optimized for Mobile, Tablet, and Desktop displays.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-5 py-lg-5" style="background: #0b1120;">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-5 text-white" data-aos="fade-right">
                <span class="badge bg-primary bg-opacity-25 text-info rounded-pill px-3 py-2 mb-3 fw-bold border border-info border-opacity-50">Get in Touch</span>
                <h2 class="fw-bold mb-4 fs-1 text-gradient">We're Here to Help</h2>
                <p class="text-white-50 mb-5 fs-5">Have a question about the platform? Reach out to our support team and we'll get back to you shortly.</p>
                
                <div class="d-flex align-items-center mb-4" data-tilt data-tilt-max="10" data-tilt-axis="x">
                    <div class="bg-white bg-opacity-10 p-3 rounded-circle me-4"><i class="fas fa-envelope fa-lg text-info"></i></div>
                    <div><h5 class="mb-1 fw-bold">Email Us</h5><p class="mb-0 text-white-50">support@complaintcms.com</p></div>
                </div>
                <div class="d-flex align-items-center mb-4" data-tilt data-tilt-max="10" data-tilt-axis="x">
                    <div class="bg-white bg-opacity-10 p-3 rounded-circle me-4"><i class="fas fa-phone-alt fa-lg text-success"></i></div>
                    <div><h5 class="mb-1 fw-bold">Call Us</h5><p class="mb-0 text-white-50">+91 8329890440</p></div>
                </div>
                <div class="d-flex align-items-center" data-tilt data-tilt-max="10" data-tilt-axis="x">
                    <div class="bg-white bg-opacity-10 p-3 rounded-circle me-4"><i class="fas fa-map-marker-alt fa-lg text-danger"></i></div>
                    <div><h5 class="mb-1 fw-bold">Location</h5><p class="mb-0 text-white-50">Maharashtra, India</p></div>
                </div>
            </div>
            
            <div class="col-lg-7" data-aos="fade-left">
                <div class="card border-0 shadow-lg p-4 p-md-5 rounded-4" style="background: var(--card-bg);">
                    <form id="contactForm">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control" placeholder="john@example.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Your Message</label>
                                <textarea class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
                            </div>
                            <div class="col-12">
                                <!-- REMOVED the "magnetic" class from here so it stays completely still -->
                                <button type="submit" class="btn btn-primary-custom btn-custom w-100 py-3 rounded-3 fs-5 mt-2">
                                    Send Message <i class="fas fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-black text-white py-5 border-top border-secondary border-opacity-25">
    <div class="container py-4">
        <div class="row justify-content-between align-items-center g-4">
            <div class="col-md-5 text-center text-md-start">
                <h4 class="mb-3 fw-bold"><i class="fas fa-shield-alt text-info me-2"></i>Complaint CMS</h4>
                <p class="text-white-50 mb-0">Delivering organizational transparency and efficiency through an advanced complaint resolution platform.</p>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <div class="d-flex justify-content-center justify-content-md-end gap-3 mb-4">
                    <a href="#" class="btn btn-outline-secondary border-0 rounded-circle text-white" style="background: rgba(255,255,255,0.05);"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-secondary border-0 rounded-circle text-white" style="background: rgba(255,255,255,0.05);"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="btn btn-outline-secondary border-0 rounded-circle text-white" style="background: rgba(255,255,255,0.05);"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="btn btn-outline-secondary border-0 rounded-circle text-white" style="background: rgba(255,255,255,0.05);"><i class="fab fa-github"></i></a>
                </div>
                <p class="text-white-50 small mb-0">&copy; <?php echo date("Y"); ?> Complaint Management System. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Back To Top -->
<a href="#home" id="backToTop" class="back-to-top btn btn-primary-custom shadow-lg rounded-circle position-fixed d-flex align-items-center justify-content-center" 
   style="bottom:30px; right:95px; width:55px; height:55px; z-index:999;">
    <i class="fas fa-chevron-up"></i>
</a>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>

<script>
    // --- 1. PRELOADER LOGIC ---
    const hidePreloader = () => {
        const preloader = document.getElementById('preloader');
        if (preloader && preloader.style.display !== 'none') {
            preloader.style.opacity = '0';
            setTimeout(() => { preloader.style.display = 'none'; }, 600);
        }
    };
    document.addEventListener('DOMContentLoaded', hidePreloader);
    window.addEventListener('load', hidePreloader);
    setTimeout(hidePreloader, 2000); // Safety fallback

    // --- 2. DARK MODE TOGGLE ---
    const themeToggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    
    // Check saved theme or system preference
    const currentTheme = localStorage.getItem('theme') || 
                        (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    
    if (currentTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        themeIcon.classList.replace('fa-moon', 'fa-sun');
    }

    themeToggleBtn.addEventListener('click', () => {
        let theme = document.documentElement.getAttribute('data-theme');
        if (theme === 'dark') {
            document.documentElement.removeAttribute('data-theme');
            themeIcon.classList.replace('fa-sun', 'fa-moon');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
            localStorage.setItem('theme', 'dark');
        }
    });

    // --- 3. SCROLL PROGRESS BAR & NAVBAR ---
    const scrollProgress = document.getElementById('scrollProgress');
    const navbar = document.getElementById('mainNav');
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
        // Progress Bar Calculation
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrollPercentage = (scrollTop / scrollHeight) * 100;
        scrollProgress.style.width = `${scrollPercentage}%`;

        // Navbar & BackToTop Logic
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }

        if (window.scrollY > 300) {
            backToTop.classList.add('active');
        } else {
            backToTop.classList.remove('active');
        }
    });

    // --- (MAGNETIC EFFECT CODE REMOVED ENTIRELY) ---

    // --- 4. INITIALIZE LIBRARIES ---
    AOS.init({ once: true, offset: 50, duration: 800, easing: 'ease-out-cubic' });

    new Typed('#typed-text', {
        strings: ['Complaint Management', 'Issue Resolution', 'Ticket Tracking'],
        typeSpeed: 60, backSpeed: 40, backDelay: 1500, loop: true, showCursor: true, cursorChar: '|'
    });

    // --- 5. NUMBER COUNTERS ---
    const counters = document.querySelectorAll('.counter');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                const counter = entry.target;
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / 200;
                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 15);
                    } else { counter.innerText = target; }
                };
                updateCount();
                observer.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });
    counters.forEach(counter => observer.observe(counter));

    // --- 6. FORM SUBMISSION ---
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault(); 
        const btn = this.querySelector('button[type="submit"]');
        const originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        // (REMOVED glitchy reset transform code)
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-check"></i> Message Sent!';
            btn.style.background = '#10b981';
            this.reset();
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.style.background = '';
                btn.disabled = false;
            }, 3000);
        }, 1500);
    });
</script>

</body>
</html>