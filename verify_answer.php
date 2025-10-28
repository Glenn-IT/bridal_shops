<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $answer = hash("sha256", $_POST["answer"]);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND security_answer=?");
    $stmt->bind_param("ss", $username, $answer);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        echo "<h3>Your password is: " . htmlspecialchars($_POST['username']) . " (Reset manually in real systems)</h3>";
    } else {
        echo "<p style='color:red;'>Incorrect answer.</p><a href='forgot_password.php'>Try again</a>";
    }
}
?>
