<?php
session_start();

// ✅ Alamin muna kung admin yung nag-logout
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Clear all session data
$_SESSION = [];
session_destroy();

// Clear session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ✅ Kapag admin ang nag-logout, punta sa client dashboard kahit walang login
if ($isAdmin) {
    header("Location: dashboard_client.php");
    exit();
}

// ✅ Default: punta sa login page kung hindi admin
header("Location: login.php");
exit();
?>
