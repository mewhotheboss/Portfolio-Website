<?php

require 'config/database.php';

// hero section
$sql = "SELECT * FROM hero_section WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $hero = $result->fetch_assoc();
} else {
    die("No hero content found!");
}

// service section
$sql2 = "SELECT * FROM service";
$service_result = $conn->query($sql2);

$services = [];
while ($row = $service_result->fetch_assoc()) {
    $services[] = $row;
}

// testimonial section
$sql3 = "SELECT * FROM testimonial";
$testimonial_result = $conn->query($sql3);

$testimonials = [];
while ($row1 = $testimonial_result->fetch_assoc()) {
    $testimonials[] = $row1;
}

// stats section
$sql4 = "SELECT * FROM stats";
$stats_result = $conn->query($sql4);

$stats = [];
while ($row2 = $stats_result->fetch_assoc()) {
    $stats[] = $row2;
}

// about section
$sql5 = "SELECT * FROM about WHERE id = 1";
$about_result = $conn->query($sql5);

if ($about_result->num_rows > 0) {
    $about = $about_result->fetch_assoc();
} else {
    die("No about content found!");
}

// project section
$sql6 = "SELECT * FROM projects";
$projects_result = $conn->query($sql6);

$projects = [];
while ($row3 = $projects_result->fetch_assoc()) {
    $projects[] = $row3;
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKIBUL HASAN RAFI</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header>
        <button class="hamburger-btn" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>

    <main>
        <section id="hero" class="hero">
            <div class="hero-container container">
                <div class="hero-text">
                    <div class="myname">
                        <p>I'M <?= $hero['name']; ?></p>
                    </div>
                    <h1><?= $hero['title']; ?></h1>
                    <p><?= $hero['para']; ?></p>
                    <div class="buttons">
                        <a href="#" class="btn-primary">See My Works</a>
                        <a href="#" class="btn-play">&#9658;</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="assets/img/me.png" alt="Amy Photo" width="400" height="400" loading="lazy" decoding="async">
                </div>
            </div>
        </section>

        <section id="services" class="services">
            <div class="container">
                <h2>SERVICES</h2>
                <div class="service-container">
                    <?php foreach ($services as $service): ?>
                        <div class="service">
                            <div class="icon">
                                <i class="<?= $service['icon']; ?>"></i>
                            </div>
                            <h3><?= $service['title']; ?></h3>
                            <p><?= $service['para']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="testimonials" class="testimonials">
            <div class="container">
                <h2>TESTIMONIAL</h2>
                <div class="testimonial-container">
                    <div class="testimonial-grid">
                        <?php foreach ($testimonials as $testimonial): ?>
                            <div class="testimonial <?= $testimonial['card'] ?>">
                                <img src="assets/img/me.png" alt="AMY" class="about-img" width="90" height="90" loading="lazy"
                                    decoding="async">
                                <h3><?= $testimonial['title'] ?></h3>
                                <h5><?= $testimonial['subtitle'] ?></h5><br>
                                <p><?= $testimonial['para'] ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="states" class="states">
            <div class="container">
                <h2>STATS</h2>
                <div class="states-container">
                    <div class="state-grid">
                        <?php foreach ($stats as $stat): ?>
                            <div class="state">
                                <h3><?= $stat['title'] ?></h3>
                                <h5><?= $stat['subtitle'] ?></h5><br>
                                <p><?= $stat['para'] ?></p>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="abouts" class="abouts">
            <div class="container">
                <h2>ABOUT</h2>
                <div class="about-grid-container">
                    <div class="about-card">
                        <img src="assets/img/me.png" alt="AMY" class="about-img" width="100" height="100" loading="lazy"
                            decoding="async">
                        <p class="about-intro-text"><?= $about['card_para']; ?></p>
                        <div class="about-signature">
                            <h3><?= $hero['name']; ?></h3>
                            <h5><?= $about['card_title']; ?></h5>
                        </div>
                    </div>

                    <div class="about-content">
                        <div class="myname">
                            <p>Hi! This is <?= $hero['name']; ?></p>
                        </div>
                        <h3><?= $about['title']; ?></h3>
                        <p class="about-bio"><?= $about['para']; ?></p>
                        <div class="buttons about-buttons">
                            <a href="#" class="btn-primary">Download Resume</a>
                            <a href="#" class="button-secondary">Hire Me</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="projects" class="projects">
            <div class="container">
                <h2>PROJECTS</h2>
                <div class="project-container">
                    <?php foreach ($projects as $project): ?>
                        <div class="<?= $project['div_class'] ?>">
                            <div class="project-image">
                                <img src="assets/img/me.png" alt="Real Chat Project" loading="lazy" decoding="async">
                            </div>
                            <div class="project-info">
                                <h3><?= $project['title'] ?></h3>
                                <h5><?= $project['subtitle'] ?></h5>
                                <p class="project-paragraph"><?= $project['para'] ?></p>
                                <div class="project-buttons">
                                    <a href="#" class="btn-primary">Know More</a>
                                    <a href="#" class="button-secondary">Preview ↗</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </section>
    </main>

    <footer id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo-col">
                    <a href="#" class="footer-logo">SHR</a>
                    <p>A freelance web designer and developer from Chittagong, Bangladesh. I generally make websites
                        that have extraordinary designs and also have a good performance rate.</p>
                </div>
                <div class="footer-col footer-links">
                    <h4>IMPORTANT LINKS</h4>
                    <ul>
                        <li><a href="#hero">Home</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#testimonials">Testimonial</a></li>
                        <li><a href="#states">Stats</a></li>
                        <li><a href="#abouts">About</a></li>
                        <li><a href="#projects">Project</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col footer-links">
                    <h4>CONTACT INFO</h4>
                    <ul>
                        <li><a href="mailto:hello@gmail.com">hello@gmail.com</a></li>
                        <li><a href="tel:018000000000">+88018000000000</a></li>
                        <li>Bogura, Rajshahi, Bangladesh</li>
                    </ul>
                </div>
                <div class="footer-col social-links">
                    <h4>SOCIAL LINKS</h4>
                    <div class="links">
                        <span><a href="https://github.com">GitHub</a></span>
                        <span><a href="https://facebook.com">Facebook</a></span>
                        <span><a href="https://leetcode.com">LetCode</a></span>
                        <span><a href="https://linkedin.com">LinkedIn</a></span>
                        <span><a href="https://codeforces.com">Codeforces</a></span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="footer-bottom">
        <div class="container">
            <p>&copy; Copyright 2025 Sakibul Hasan Rafi. All right Reserved</p>
            <a href="#" class="scroll-top" aria-label="Scroll to top">&uarr;</a>
        </div>
    </div>

    <div class="popup-menu">
        <button class="btn-close" aria-label="Close menu">&times;</button>
        <nav class="popup-nav">
            <ul>
                <li>
                    <h1>MENU</h1>
                </li>
                <li><a href="#hero">Home</a></li>
                <li><a href="#abouts">About</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#testimonials">Testimonial</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </div>

    <script>
        const popupMenu = document.querySelector('.popup-menu');
        const toggleButtons = document.querySelectorAll('.hamburger-btn, .btn-close');
        const menuLinks = document.querySelectorAll('.popup-nav a');
        toggleButtons.forEach(button => {
            button.addEventListener('click', () => popupMenu.classList.toggle('active'));
        });
        menuLinks.forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const href = link.getAttribute('href');
                const targetId = href.substring(1); // Get "hero"
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
                popupMenu.classList.remove('active');
            });
        });
    </script>

</body>

</html>