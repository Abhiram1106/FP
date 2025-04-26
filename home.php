<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>University Sports Management System</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    /* General Styles */
    
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 4.9rem 0 0;
      background: #f8f9fa;
      color: #333;
      overflow-x: hidden;
    }

    ::selection {
      background: #0077b6;
      color: white;
    }

    /* Popup Styles */
    .popup {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: #ff4444;
      color: white;
      padding: 15px 20px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(255, 68, 68, 0.7);
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      animation: glow 1.5s infinite alternate;
      z-index: 1000;
    }

    .popup i {
      font-size: 1.2rem;
    }

    .popup:hover {
      background: #ff0000;
    }

    @keyframes glow {
      from {
        box-shadow: 0 0 10px rgba(255, 68, 68, 0.7);
      }
      to {
        box-shadow: 0 0 30px rgba(255, 68, 68, 0.9);
      }
    }

    /* Hero Section */
    .hero {
      position: relative;
      width: 100%;
      height: 100vh;
      background: url('https://images.unsplash.com/photo-1546519638-68e109498ffc?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80') no-repeat center center/cover;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
    }

    .hero-overlay {
      position: relative;
      z-index: 2;
      max-width: 800px;
      padding: 20px;
      animation: fadeIn 2s ease-in-out;
    }

    .hero h1 {
      font-size: 4.5rem;
      margin-bottom: 20px;
      color: white;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
      animation: slideInDown 1.5s ease-in-out;
    }

    .hero p {
      font-size: 1.5rem;
      margin-bottom: 40px;
      color: rgba(255, 255, 255, 0.9);
      animation: slideInUp 1.5s ease-in-out;
    }

    /* Features Section */
    .features {
      text-align: center;
      padding: 100px 20px;
      background: white;
    }

    .features h2 {
      font-size: 3rem;
      margin-bottom: 60px;
      color: #0077b6;
      animation: fadeIn 2s ease-in-out;
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .feature-item {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      animation: fadeInUp 2s ease-in-out;
    }

    .feature-item:hover {
      transform: translateY(-10px);
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
    }

    .feature-item i {
      font-size: 3rem;
      color: #0077b6;
      margin-bottom: 20px;
    }

    .feature-item h3 {
      font-size: 1.8rem;
      margin-bottom: 15px;
      color: #333;
    }

    .feature-item p {
      font-size: 1rem;
      color: #666;
      line-height: 1.6;
    }

    /* Future Enhancements Section */
    .future-enhancements {
      text-align: center;
      padding: 100px 20px;
      background: #f8f9fa;
    }

    .future-enhancements h2 {
      font-size: 3rem;
      margin-bottom: 60px;
      color: #0077b6;
      animation: fadeIn 2s ease-in-out;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 3rem;
      }

      .hero p {
        font-size: 1.2rem;
      }

      .features h2,
      .future-enhancements h2 {
        font-size: 2.5rem;
      }

      .feature-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      }
    }

    @media (max-width: 480px) {
      .hero h1 {
        font-size: 2.5rem;
      }

      .hero p {
        font-size: 1rem;
      }

      .features h2,
      .future-enhancements h2 {
        font-size: 2rem;
      }

      .feature-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes slideInDown {
      from {
        transform: translateY(-100px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes slideInUp {
      from {
        transform: translateY(100px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes fadeInUp {
      from {
        transform: translateY(20px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
  </style>
</head>

<body>

  <!-- Popup -->
  <div class="popup" onclick="window.location.href='guidelines.html';">
    <i class="fas fa-exclamation-triangle"></i>
    <span>Read Guidelines</span>
  </div>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-overlay">
      <h1>University Sports Management System</h1>
      <p>Empowering universities to manage sports teams, events, and performance with cutting-edge technology.</p>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features">
    <h2>Key Features</h2>
    <div class="feature-grid">
      <div class="feature-item">
        <i class="fas fa-calendar-alt"></i>
        <h3>Match Scheduling</h3>
        <p>Easily schedule and manage matches, tournaments, and events for both athletics and team games.</p>
      </div>
      <div class="feature-item">
        <i class="fas fa-running"></i>
        <h3>Faster Sports Management</h3>
        <p>Optimize sports management for both athletics and team games with seamless workflows.</p>
      </div>
      <div class="feature-item">
        <i class="fas fa-user-plus"></i>
        <h3>Registrations</h3>
        <p>Streamline player and team registrations with an intuitive and user-friendly interface.</p>
      </div>
      <div class="feature-item">
        <i class="fas fa-database"></i>
        <h3>Data Handling</h3>
        <p>Efficiently store, manage, and retrieve sports-related data with robust data handling tools.</p>
      </div>
    </div>
  </section>

  <!-- Future Enhancements Section -->
  <section class="future-enhancements">
    <h2>Future Enhancements</h2>
    <div class="feature-grid">
      <div class="feature-item">
        <i class="fas fa-trophy"></i>
        <h3>Real-Time Match Updates</h3>
        <p>Get live scores, key moments, and instant updates for all your favorite matches. Never miss a goal, wicket, or winning shot!</p>
      </div>
      <div class="feature-item">
        <i class="fas fa-users"></i>
        <h3>Open Platform</h3>
        <p>An inclusive platform for event organizers (hosts) and players (students/alumni) to connect and collaborate.</p>
      </div>
      <div class="feature-item">
        <i class="fas fa-trophy"></i>
        <h3>Achievement Tracking</h3>
        <p>Track and celebrate individual and team achievements with a dedicated achievements module.</p>
      </div>
      <div class="feature-item">
        <i class="fas fa-bullhorn"></i>
        <h3>Event Promotion</h3>
        <p>Promote sports events and tournaments with built-in marketing and communication tools.</p>
      </div>
    </div>
  </section>

</body>

</html>