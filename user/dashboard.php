<?php
include "../includes/session.php";
include "../includes/connection.php";

$user_id = $_SESSION['user_id'];

// Optimized Count Queries
function getUserComplaintCount($conn, $user_id, $status = null) {
    if ($status) {
        $stmt = $conn->prepare("SELECT COUNT(id) AS total FROM complaints WHERE user_id=? AND status=?");
        $stmt->bind_param("is", $user_id, $status);
    } else {
        $stmt = $conn->prepare("SELECT COUNT(id) AS total FROM complaints WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}

$total = getUserComplaintCount($conn, $user_id);
$pending = getUserComplaintCount($conn, $user_id, 'Pending');
$progress = getUserComplaintCount($conn, $user_id, 'In Progress');
$resolved = getUserComplaintCount($conn, $user_id, 'Resolved');

// Unread Notifications Count
$stmt_notif = $conn->prepare("SELECT COUNT(id) AS unread FROM notifications WHERE user_id=? AND status='Unread'");
$stmt_notif->bind_param("i", $user_id);
$stmt_notif->execute();
$unread_notifications = $stmt_notif->get_result()->fetch_assoc()['unread'];

// Secure Query for Recent Complaints
$stmt_recent = $conn->prepare("SELECT * FROM complaints WHERE user_id=? ORDER BY id DESC LIMIT 5");
$stmt_recent->bind_param("i", $user_id);
$stmt_recent->execute();
$recent = $stmt_recent->get_result();

// Dynamic Greeting based on time
$hour = date('H');
if ($hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour < 17) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}

// Include Global Header and Sidebar
include "../includes/header.php";
include "../includes/sidebar.php";
?>

<!-- Main Content Wrapper to accommodate the fixed sidebar and sticky footer -->
<div class="content d-flex flex-column" style="margin-left: 260px; background: var(--bg-body); min-height: 100vh;">

    <div class="container-fluid py-4 flex-grow-1">
        
        <!-- Header Area -->
        <div class="dashboard-header mb-5" data-aos="fade-down">
            <div class="header-mesh"></div>
            <div class="header-content d-flex justify-content-between align-items-center flex-wrap gap-4">
                <div>
                    <h2 class="fw-bold mb-2 display-6"><i class="fas fa-grip me-3"></i>User Dashboard</h2>
                    <p class="mb-0 fs-5 text-white-50">
                        <?php echo $greeting; ?>, <strong class="text-white"><?php echo htmlspecialchars($_SESSION['name']); ?></strong>! Welcome back.
                    </p>
                </div>
                <div>
                    <a href="notifications.php" class="btn btn-light position-relative rounded-pill px-4 py-3 shadow-lg fw-bold d-flex align-items-center gap-2 transition-transform" style="transform-style: preserve-3d;" data-tilt data-tilt-scale="1.05">
                        <i class="fas fa-bell text-warning fa-lg <?php echo ($unread_notifications > 0) ? 'bell-shake' : ''; ?>"></i> 
                        <span>Notifications</span>
                        <?php if ($unread_notifications > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light border-2 px-2 py-1 shadow-sm">
                                <?php echo $unread_notifications; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Statistics Row -->
        <div class="row g-4 mb-5">
            
            <!-- Profile Summary Card -->
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="profile-card h-100" data-tilt data-tilt-max="5" data-tilt-glare data-tilt-max-glare="0.2">
                    <div class="profile-avatar"><i class="fas fa-user-astronaut"></i></div>
                    <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($_SESSION['name']); ?></h3>
                    <p class="text-muted small mb-4 fw-semibold tracking-wide text-uppercase">Client / Citizen Account</p>
                    <div class="border-top border-opacity-10 pt-4 mt-2" style="border-color: var(--border-color) !important;">
                        <span class="text-muted text-uppercase fw-bold small">Total Complaints Logged</span>
                        <!-- Animated Counter applied here -->
                        <h1 class="text-primary fw-black display-3 mt-2 mb-0 counter" data-target="<?php echo $total; ?>">0</h1>
                    </div>
                </div>
            </div>

            <!-- Status Cards Grid -->
            <div class="col-lg-8">
                <div class="row g-4 h-100">
                    
                    <!-- Pending Card -->
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card p-4 h-100 d-flex flex-column justify-content-between" data-tilt data-tilt-max="10" data-tilt-glare data-tilt-max-glare="0.2">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-bold text-muted text-uppercase mb-0">Pending</h6>
                                    <div class="p-3 bg-warning bg-opacity-10 rounded-circle text-warning shadow-sm">
                                        <i class="fas fa-clock fa-lg"></i>
                                    </div>
                                </div>
                                <h2 class="text-warning fw-bold mb-1 display-5 counter" data-target="<?php echo $pending; ?>">0</h2>
                            </div>
                            <div>
                                <div class="progress my-3">
                                    <div class="progress-bar bg-warning" style="width: 0%;" data-progress="<?php echo ($total>0)?($pending/$total)*100:0; ?>%"></div>
                                </div>
                                <small class="text-muted fw-semibold"><?php echo ($total>0)?round(($pending/$total)*100):0; ?>% of total tickets</small>
                            </div>
                        </div>
                    </div>

                    <!-- In Progress Card -->
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="card p-4 h-100 d-flex flex-column justify-content-between" data-tilt data-tilt-max="10" data-tilt-glare data-tilt-max-glare="0.2">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-bold text-muted text-uppercase mb-0">In Progress</h6>
                                    <div class="p-3 bg-info bg-opacity-10 rounded-circle text-info shadow-sm">
                                        <i class="fas fa-spinner fa-lg fa-spin-pulse"></i>
                                    </div>
                                </div>
                                <h2 class="text-info fw-bold mb-1 display-5 counter" data-target="<?php echo $progress; ?>">0</h2>
                            </div>
                            <div>
                                <div class="progress my-3">
                                    <div class="progress-bar bg-info" style="width: 0%;" data-progress="<?php echo ($total>0)?($progress/$total)*100:0; ?>%"></div>
                                </div>
                                <small class="text-muted fw-semibold"><?php echo ($total>0)?round(($progress/$total)*100):0; ?>% currently active</small>
                            </div>
                        </div>
                    </div>

                    <!-- Resolved Card -->
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="card p-4 h-100 d-flex flex-column justify-content-between" data-tilt data-tilt-max="10" data-tilt-glare data-tilt-max-glare="0.2">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-bold text-muted text-uppercase mb-0">Resolved</h6>
                                    <div class="p-3 bg-success bg-opacity-10 rounded-circle text-success shadow-sm">
                                        <i class="fas fa-check-circle fa-lg"></i>
                                    </div>
                                </div>
                                <h2 class="text-success fw-bold mb-1 display-5 counter" data-target="<?php echo $resolved; ?>">0</h2>
                            </div>
                            <div>
                                <div class="progress my-3">
                                    <div class="progress-bar bg-success" style="width: 0%;" data-progress="<?php echo ($total>0)?($resolved/$total)*100:0; ?>%"></div>
                                </div>
                                <small class="text-muted fw-semibold"><?php echo ($total>0)?round(($resolved/$total)*100):0; ?>% resolution rate</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Recent Complaints Table Box -->
        <div class="table-box" data-aos="fade-up" data-aos-delay="600">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: var(--border-color) !important;">
                <h4 class="fw-bold mb-0"><i class="fas fa-history text-primary me-3"></i>Recent Complaints</h4>
                <a href="my_complaints.php" class="btn btn-sm btn-outline-primary fw-bold px-4 py-2 rounded-pill">View All History</a>
            </div>
            
            <div class="table-responsive">
                <?php if ($recent->num_rows > 0): ?>
                    <table class="table table-hover align-middle mb-0 border-0">
                        <thead class="text-muted small text-uppercase" style="background: transparent;">
                            <tr>
                                <th class="py-3 border-0 fw-bold">Ticket ID</th>
                                <th class="py-3 border-0 fw-bold">Complaint Title</th>
                                <th class="py-3 border-0 fw-bold">Current Status</th>
                                <th class="py-3 border-0 fw-bold">Priority Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $recent->fetch_assoc()): ?>
                            <tr style="transition: all 0.2s ease;">
                                <td class="py-4 fw-bold" style="color: var(--secondary);">#<?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="py-4 fw-semibold"><?php echo htmlspecialchars($row['title']); ?></td>
                                <td class="py-4">
                                    <?php 
                                        $status = htmlspecialchars($row['status']);
                                        $badgeClass = 'bg-secondary';
                                        if ($status === 'Pending') $badgeClass = 'bg-warning text-dark';
                                        if ($status === 'In Progress') $badgeClass = 'bg-info text-white';
                                        if ($status === 'Resolved' || $status === 'Closed') $badgeClass = 'bg-success text-white';
                                    ?>
                                    <span class="badge badge-custom <?php echo $badgeClass; ?> shadow-sm"><?php echo $status; ?></span>
                                </td>
                                <td class="py-4">
                                    <?php 
                                        $priority = htmlspecialchars($row['priority']);
                                        $pClass = 'text-success';
                                        if ($priority === 'High') $pClass = 'text-danger';
                                        if ($priority === 'Medium') $pClass = 'text-warning';
                                    ?>
                                    <span class="fw-bold <?php echo $pClass; ?> d-flex align-items-center gap-2">
                                        <i class="fas fa-circle" style="font-size:8px;"></i> <?php echo $priority; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-4 d-inline-block p-4 rounded-circle" style="background: var(--border-color);">
                            <i class="fas fa-folder-open fa-3x text-muted opacity-50"></i>
                        </div>
                        <h5 class="text-muted fw-bold">No recent complaints found</h5>
                        <p class="text-muted mb-4">When you submit a new ticket, it will instantly appear in this feed.</p>
                        <a href="add_complaint.php" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm">Submit Your First Complaint</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Centered White Footer -->
    <footer class="bg-white border-top py-3 mt-auto w-100">
        <div class="container text-center text-muted small">
            &copy; <?php echo date("Y"); ?> Complaint Management System. All Rights Reserved.
        </div>
    </footer>

</div>

<!-- Core Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Animation Libraries -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        
        // 1. Initialize AOS Animations
        AOS.init({ once: true, offset: 20, duration: 800, easing: 'ease-out-cubic' });

        // 2. Dark Mode Toggle
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        
        const currentTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            if (themeIcon) themeIcon.classList.replace('fa-moon', 'fa-sun');
        }

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                let theme = document.documentElement.getAttribute('data-theme');
                if (theme === 'dark') {
                    document.documentElement.removeAttribute('data-theme');
                    if (themeIcon) themeIcon.classList.replace('fa-sun', 'fa-moon');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    if (themeIcon) themeIcon.classList.replace('fa-moon', 'fa-sun');
                    localStorage.setItem('theme', 'dark');
                }
            });
        }

        // 3. Number Counters Animation
        const counters = document.querySelectorAll('.counter');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    const counter = entry.target;
                    const target = +counter.getAttribute('data-target');
                    if (target === 0) return; 
                    
                    const updateCount = () => {
                        const count = +counter.innerText;
                        const inc = target / 60; 
                        if (count < target) {
                            counter.innerText = Math.ceil(count + inc);
                            setTimeout(updateCount, 20);
                        } else { 
                            counter.innerText = target; 
                        }
                    };
                    updateCount();
                    observer.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(counter => observer.observe(counter));

        // 4. Animate Progress Bars
        setTimeout(() => {
            document.querySelectorAll('.progress-bar').forEach(bar => {
                const width = bar.getAttribute('data-progress');
                bar.style.width = width;
            });
        }, 500);

    });
</script>
</body>
</html>