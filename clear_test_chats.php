<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    die("Unauthorized");
}

try {
    // Delete all chat messages
    $pdo->exec("DELETE FROM chat_messages");
    
    // Delete all chat conversations
    $pdo->exec("DELETE FROM chat_conversations");
    
    echo "✅ All test chat data has been cleared successfully!<br>";
    echo "- Chat messages deleted<br>";
    echo "- Chat conversations deleted<br>";
    
} catch (PDOException $e) {
    echo "❌ Error clearing data: " . htmlspecialchars($e->getMessage());
}
?>
