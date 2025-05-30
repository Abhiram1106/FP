<?php
include 'header.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSportHub</title>
    <link rel="stylesheet" href="contact.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- For icons -->
</head>
<body>
    <!-- Contact Page Hero Section -->
    <section class="contact-hero-section">
        <div class="contact-hero-content">
            <h1>Contact Us</h1>
            <p>We'd love to hear from you! Reach out to us for any inquiries or feedback.</p>
        </div>
    </section>

    <!-- Contact Form and Details Section -->
    <section class="contact-section">
        <div class="contact-container">
            <!-- Contact Form -->
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <form action="https://api.web3forms.com/submit" method="POST">

                <input type="hidden" name="access_key" value="5492413d-8afb-4b38-b50a-66898ad155dd">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject:</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="submit-button">Send Message</button>
                </form>
            </div>

            <!-- Contact Details -->
            <div class="contact-details">
                <h2>Contact Information</h2>
                <div class="contact-info">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Tenali,Andhra Pradesh</p>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <p>+91 8125551995</p>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <p>UniSportHub@gmail.com</p>
                    </div>
                </div>
                <div class="social-links">
                    <h3>Follow Us</h3>
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Embedded Map -->
    <section class="map-section">
        <h2>Find Us on the Map</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3830.7482994546344!2d80.5483329751394!3d16.23337458447018!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a4a0953a362f945%3A0x11aa0de9063844ab!2sVignan&#39;s%20Foundation%20for%20Science%2C%20Technology%20%26%20Research%20(Deemed%20to%20be%20University)!5e0!3m2!1sen!2sin!4v1740597437819!5m2!1sen!2sin" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </section>

</body>
</html>