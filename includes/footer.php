<footer class="footer-section mt-auto">
    <div class="container">
        <div class="row pt-5 pb-4">
            
            <!-- Logo & About -->
            <div class="col-lg-4 col-md-6 mb-4 pe-lg-5">
                <h4 class="footer-title d-flex align-items-center mb-3">
                    <div class="icon-wrapper me-2">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    Complaint CMS
                </h4>
                <p class="footer-text">
                    Our Complaint Management System empowers users to seamlessly submit complaints, track progress in real-time, and ensure rapid issue resolution.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-4 col-md-6 mb-4 px-lg-5">
                <h5 class="footer-heading text-uppercase tracking-wider">Quick Links</h5>
                <ul class="footer-links">

                    <li>
                        <a href="/Backhand/complaint-management-system/index.php">Home</a>
                    </li>

                    <li>
                        <a href="/Backhand/complaint-management-system/profile.php">Profile</a>
                    </li>

                    <li>
                        <a href="/Backhand/complaint-management-system/login.php">Login</a>
                    </li>

                    <li>
                        <a href="/Backhand/complaint-management-system/register.php">Register</a>
                    </li>

                </ul>
            </div>

            <!-- Contact & Socials -->
            <div class="col-lg-4 col-md-12 mb-4">
                <h5 class="footer-heading text-uppercase tracking-wider">Contact Us</h5>
                <ul class="footer-contact list-unstyled footer-text mb-4">
                    <li class="mb-2"><i class="fas fa-envelope me-3 text-info"></i>support@complaintcms.com</li>
                    <li class="mb-2"><i class="fas fa-phone me-3 text-info"></i>+91 8329890440</li>
                    <li class="mb-2"><i class="fas fa-location-dot me-3 text-info"></i>Maharashtra, India</li>
                </ul>
                
                <div class="social-icons">
                    <a href="https://www.facebook.com/share/1DATJhyLqo/" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/_sawkar__?igsh=MW9rdXF2Y3F1dTBxeQ==" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/vedant-labhshetwar-884165425" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://github.com/Vedant-Sawkar" target="_blank" aria-label="GitHub"><i class="fab fa-github"></i></a>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom text-center py-4">
            <p class="copyright mb-0 small">
                © <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved.
            </p>
        </div>
    </div>
</footer>

<style>
/* Professional Footer Styling */
.footer-section {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #ffffff;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    margin-top: auto; /* Pushes footer to bottom if body is flex */
}

.footer-title {
    font-weight: 700;
    font-size: 1.5rem;
    letter-spacing: -0.5px;
}

.icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: rgba(56, 189, 248, 0.1);
    border-radius: 8px;
    color: #38bdf8;
}

.footer-text {
    color: #94a3b8;
    line-height: 1.7;
    font-size: 0.95rem;
}

.footer-heading {
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 1px;
    color: #f8fafc;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-links a {
    text-decoration: none;
    color: #94a3b8;
    transition: all 0.2s ease-in-out;
    font-weight: 500;
    display: inline-block;
}

.footer-links a:hover {
    color: #38bdf8;
    transform: translateX(5px);
}

.footer-contact li {
    display: flex;
    align-items: center;
}

.social-icons {
    display: flex;
    gap: 12px;
}

.social-icons a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    color: #cbd5e1;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.social-icons a:hover {
    background: #38bdf8;
    color: #ffffff;
    border-color: #38bdf8;
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);
}

.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.copyright {
    color: #64748b;
    font-weight: 500;
    letter-spacing: 0.5px;
}
</style>

<!-- Closing tags from your original layout -->
</div> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>