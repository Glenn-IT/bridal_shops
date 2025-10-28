<?php
session_start();
require 'config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated - please login']);
    exit();
}

// Get user_id if not set in session
if (!isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found - please logout and login again']);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_or_create_conversation':
            // Get existing conversation or create new one
            $stmt = $pdo->prepare("SELECT id FROM chat_conversations WHERE user_id = ? AND status = 'active' ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([$userId]);
            $conversation = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$conversation) {
                // Create new conversation
                $stmt = $pdo->prepare("INSERT INTO chat_conversations (user_id) VALUES (?)");
                $stmt->execute([$userId]);
                $conversationId = $pdo->lastInsertId();
            } else {
                $conversationId = $conversation['id'];
            }
            
            echo json_encode(['success' => true, 'conversation_id' => $conversationId]);
            break;
            
        case 'send_message':
            $conversationId = $_POST['conversation_id'] ?? 0;
            $message = trim($_POST['message'] ?? '');
            
            if (empty($message)) {
                echo json_encode(['success' => false, 'message' => 'Message cannot be empty']);
                exit();
            }
            
            // Verify conversation belongs to user or user is admin
            if ($userRole === 'client') {
                $stmt = $pdo->prepare("SELECT id FROM chat_conversations WHERE id = ? AND user_id = ?");
                $stmt->execute([$conversationId, $userId]);
            } else {
                $stmt = $pdo->prepare("SELECT id FROM chat_conversations WHERE id = ?");
                $stmt->execute([$conversationId]);
            }
            
            if (!$stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Invalid conversation']);
                exit();
            }
            
            // Insert message
            $stmt = $pdo->prepare("INSERT INTO chat_messages (conversation_id, sender_id, message) VALUES (?, ?, ?)");
            $stmt->execute([$conversationId, $userId, $message]);
            
            // Update conversation's last_message_at
            $stmt = $pdo->prepare("UPDATE chat_conversations SET last_message_at = NOW() WHERE id = ?");
            $stmt->execute([$conversationId]);
            
            echo json_encode(['success' => true, 'message' => 'Message sent']);
            break;
            
        case 'get_messages':
            $conversationId = $_GET['conversation_id'] ?? 0;
            $lastMessageId = $_GET['last_message_id'] ?? 0;
            
            // Verify conversation access
            if ($userRole === 'client') {
                $stmt = $pdo->prepare("SELECT id FROM chat_conversations WHERE id = ? AND user_id = ?");
                $stmt->execute([$conversationId, $userId]);
            } else {
                $stmt = $pdo->prepare("SELECT id FROM chat_conversations WHERE id = ?");
                $stmt->execute([$conversationId]);
            }
            
            if (!$stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Invalid conversation']);
                exit();
            }
            
            // Get messages
            $stmt = $pdo->prepare("
                SELECT 
                    cm.id, 
                    cm.message, 
                    cm.created_at,
                    cm.sender_id,
                    u.firstname,
                    u.lastname,
                    u.role
                FROM chat_messages cm
                JOIN users u ON cm.sender_id = u.id
                WHERE cm.conversation_id = ? AND cm.id > ?
                ORDER BY cm.created_at ASC
            ");
            $stmt->execute([$conversationId, $lastMessageId]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Mark messages as read if user is viewing them
            if ($userRole === 'admin') {
                $stmt = $pdo->prepare("UPDATE chat_messages SET is_read = 1 WHERE conversation_id = ? AND sender_id != ? AND is_read = 0");
                $stmt->execute([$conversationId, $userId]);
            } else {
                $stmt = $pdo->prepare("UPDATE chat_messages SET is_read = 1 WHERE conversation_id = ? AND sender_id != ? AND is_read = 0");
                $stmt->execute([$conversationId, $userId]);
            }
            
            echo json_encode(['success' => true, 'messages' => $messages]);
            break;
            
        case 'get_conversations':
            // Admin only - get all conversations
            if ($userRole !== 'admin') {
                echo json_encode(['success' => false, 'message' => 'Access denied']);
                exit();
            }
            
            $stmt = $pdo->prepare("
                SELECT 
                    cc.id,
                    cc.user_id,
                    cc.created_at,
                    cc.last_message_at,
                    cc.status,
                    CONCAT(u.firstname, ' ', u.lastname) as client_name,
                    (SELECT COUNT(*) FROM chat_messages WHERE conversation_id = cc.id AND sender_id != ? AND is_read = 0) as unread_count,
                    (SELECT message FROM chat_messages WHERE conversation_id = cc.id ORDER BY created_at DESC LIMIT 1) as last_message
                FROM chat_conversations cc
                JOIN users u ON cc.user_id = u.id
                WHERE cc.status = 'active'
                ORDER BY cc.last_message_at DESC
            ");
            $stmt->execute([$userId]);
            $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'conversations' => $conversations]);
            break;
            
        case 'get_unread_count':
            if ($userRole === 'admin') {
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as count 
                    FROM chat_messages cm
                    JOIN chat_conversations cc ON cm.conversation_id = cc.id
                    WHERE cm.sender_id != ? AND cm.is_read = 0 AND cc.status = 'active'
                ");
                $stmt->execute([$userId]);
            } else {
                $stmt = $pdo->prepare("
                    SELECT COUNT(*) as count 
                    FROM chat_messages cm
                    JOIN chat_conversations cc ON cm.conversation_id = cc.id
                    WHERE cc.user_id = ? AND cm.sender_id != ? AND cm.is_read = 0
                ");
                $stmt->execute([$userId, $userId]);
            }
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'count' => $result['count']]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
