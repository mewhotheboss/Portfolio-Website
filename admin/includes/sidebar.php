<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="profile.php" class="logo">
                <span class="navbar-brand text-white fw-bold">My Portfolio</span>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- PROFILE LINK (Active if on profile.php) -->
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
                    <a href="profile.php">
                        <i class="fas fa-user"></i>
                        <p>User Profile</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Management</h4>
                </li>
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'hero.php') ? 'active' : ''; ?>">
                    <a href="hero.php">
                        <i class="fas fa-home"></i>
                        <p>Hero Section</p>
                    </a>
                </li>

                <!-- PROJECT LINKS (Active if on add, manage, or update) -->
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                $is_project_page = ($current_page == 'manage.php' || $current_page == 'add.php' || $current_page == 'update.php');
                ?>
                <li class="nav-item <?php echo $is_project_page ? 'active submenu' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#projects">
                        <i class="fas fa-layer-group"></i>
                        <p>Projects</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?php echo $is_project_page ? 'show' : ''; ?>" id="projects">
                        <ul class="nav nav-collapse">
                            <li class="<?php echo ($current_page == 'manage.php') ? 'active' : ''; ?>">
                                <a href="manage.php">
                                    <span class="sub-item">Manage Projects</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'service.php') ? 'active' : ''; ?>">
                    <a href="service.php">
                        <i class="fas fa-briefcase"></i>
                        <p>Services</p>
                    </a>
                </li>
                </li>

                <!-- LOGOUT -->
                <li class="nav-item">
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>