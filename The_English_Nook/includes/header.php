<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
// Fixed path detection for cases where project is in a subdirectory (like /edu/)
$isPhpPage = (strpos($_SERVER['PHP_SELF'], '/php/') !== false);
$prefix = $isPhpPage ? '../' : '';

function isActive(string $file, string $currentPage): string {
    return $file === $currentPage ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="The English Nook: fun and structured English learning platform.">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'The English Nook | Learn & Play'; ?></title>
    
    <!-- Consistency for Subdirectories -->
    <link rel="stylesheet" href="<?php echo $prefix; ?>css/style.css">
    <link rel="stylesheet" href="<?php echo $prefix; ?>css/themes.css">
    <link rel="stylesheet" href="<?php echo $prefix; ?>css/app.css">
    
    <link rel="icon" type="image/png" href="<?php echo $prefix; ?>favicon.png">
    
    <!-- Google Fonts for better aesthetics -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">

    <script>
        // Store the prefix globally for main.js to use
        const SITE_ROOT = '<?php echo $prefix; ?>';
    </script>
</head>

<body class="adult-mode">
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="<?php echo $prefix; ?>index.php" style="text-decoration: none; color: inherit;">
                    <span id="logo-icon">🦉</span> 
                    <span id="logo-text">The English Nook</span>
                </a>
            </div>

            <div class="mode-switch-container">
                <span class="mode-label">Scholar 🎓</span>
                <label class="switch">
                    <input type="checkbox" id="ageToggle">
                    <span class="slider round"></span>
                </label>
                <span class="mode-label">Explorer 🎈</span>
            </div>

            <nav>
                <?php if ($currentPage === 'index.php'): ?>
                    <button class="nav-btn active" onclick="showSection('home')">Home</button>
                    <button class="nav-btn adult-only" onclick="showSection('grammar')">Grammar 📘</button>
                    <button class="nav-btn adult-only" onclick="showSection('lit')">Literature ✒️</button>
                    <button class="nav-btn adult-only" onclick="showSection('vocab')">Vocab Vault</button>
                    <button class="nav-btn adult-only" onclick="showSection('classes')">Classes</button>

                    <button class="nav-btn kids-only" onclick="showSection('classes')" style="display:none;">Junior Classes 🎒</button>
                    <button class="nav-btn kids-only" onclick="showSection('kids-hub')" style="display:none;">Play Zone 🎈</button>
                <?php else: ?>
                    <a href="<?php echo $prefix; ?>index.php" class="nav-btn">Home</a>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo $prefix; ?>php/dashboard.php" class="nav-btn <?php echo isActive('dashboard.php', $currentPage); ?>">Dashboard</a>
                    <a href="<?php echo $prefix; ?>php/logout.php" class="nav-btn">Logout 🚪</a>
                <?php else: ?>
                    <a href="<?php echo $prefix; ?>php/login.php" id="login-link" class="nav-btn <?php echo isActive('login.php', $currentPage); ?>">Log In 🚪</a>
                    <a href="<?php echo $prefix; ?>php/register.php" id="register-link" class="nav-btn <?php echo isActive('register.php', $currentPage); ?>">Sign Up 📝</a>
                <?php endif; ?>
                <span id="welcome-msg" style="display:none; font-weight:bold; color:var(--primary-color); margin-left:15px;"></span>
            </nav>
        </div>
    </header>

    <main id="main-content">
