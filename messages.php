<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get user_id if not set in session
if (!isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
    } else {
        die("User not found. Please logout and login again.");
    }
}

$adminId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Messages - Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --bg: #f4f6f9;
      --text: #2f3542;
      --card-bg: #fff;
      --sidebar-bg: linear-gradient(135deg, #1e272e, #34495e);
      --sidebar-text: #ecf0f1;
      --primary: #3498db;
      --secondary: #2ecc71;
    }

    body.dark {
      --bg: #18191a;
      --text: #f1f2f6;
      --card-bg: #242526;
      --sidebar-bg: linear-gradient(135deg, #111, #222);
      --sidebar-text: #ccc;
    }

    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
      transition: background 0.3s, color 0.3s;
    }

    body {
      margin: 0;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      overflow-x: hidden;
    }

    .menu-toggle {
      display: none;
      position: absolute;
      top: 1rem;
      left: 1rem;
      background: #3498db;
      color: white;
      border: none;
      padding: 10px 15px;
      font-size: 18px;
      cursor: pointer;
      z-index: 999;
      border-radius: 6px;
    }

    .sidebar {
      width: 250px;
      background: var(--sidebar-bg);
      color: var(--sidebar-text);
      padding: 2rem 1rem;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      transform: translateX(0);
      transition: transform 0.3s ease-in-out;
      z-index: 998;
      box-shadow: 4px 0 12px rgba(0,0,0,0.2);
      overflow-y: auto;
    }

    .sidebar.closed {
      transform: translateX(-100%);
    }

    .sidebar img {
      display: block;
      margin: 0 auto 1rem;
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      background-color: #fff;
      padding: 6px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 2rem;
      font-size: 24px;
      font-weight: 600;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      color: var(--sidebar-text);
      padding: 12px 16px;
      border-radius: 10px;
      text-decoration: none;
      margin-bottom: 10px;
      font-weight: 500;
      position: relative;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
    }

    .main {
      margin-left: 250px;
      padding: 2rem;
      transition: margin-left 0.3s ease-in-out;
    }

    .main.full {
      margin-left: 0;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .toggle-btn {
      background: #3498db;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
    }

    .chat-container {
      display: grid;
      grid-template-columns: 350px 1fr;
      gap: 1.5rem;
      height: calc(100vh - 150px);
    }

    .conversations-panel {
      background: var(--card-bg);
      border-radius: 12px;
      padding: 1.5rem;
      overflow-y: auto;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .conversations-panel h3 {
      margin-top: 0;
      margin-bottom: 1rem;
      color: var(--text);
    }

    .conversation-item {
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 0.5rem;
      cursor: pointer;
      transition: background 0.2s;
      border: 2px solid transparent;
    }

    .conversation-item:hover {
      background: rgba(52, 152, 219, 0.1);
    }

    .conversation-item.active {
      background: rgba(52, 152, 219, 0.2);
      border-color: var(--primary);
    }

    .conversation-item .client-name {
      font-weight: 600;
      margin-bottom: 0.3rem;
    }

    .conversation-item .last-message {
      font-size: 0.85rem;
      color: #7f8c8d;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .conversation-item .unread-badge {
      display: inline-block;
      background: #e74c3c;
      color: white;
      padding: 2px 8px;
      border-radius: 12px;
      font-size: 0.75rem;
      margin-left: 0.5rem;
    }

    .chat-panel {
      background: var(--card-bg);
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      overflow: hidden;
    }

    .chat-header {
      padding: 1.5rem;
      border-bottom: 2px solid #ecf0f1;
      background: linear-gradient(135deg, #3498db, #2980b9);
      color: white;
    }

    .chat-header h3 {
      margin: 0;
    }

    .chat-messages {
      flex: 1;
      padding: 1.5rem;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .message {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      max-width: 70%;
    }

    .message.sent {
      align-self: flex-end;
      flex-direction: row-reverse;
    }

    .message.received {
      align-self: flex-start;
    }

    .message-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, #3498db, #2980b9);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      flex-shrink: 0;
    }

    .message-content {
      background: #ecf0f1;
      padding: 0.75rem 1rem;
      border-radius: 12px;
      word-wrap: break-word;
    }

    .message.sent .message-content {
      background: linear-gradient(135deg, #3498db, #2980b9);
      color: white;
    }

    .message-time {
      font-size: 0.7rem;
      color: #7f8c8d;
      margin-top: 0.25rem;
    }

    .chat-input-area {
      padding: 1.5rem;
      border-top: 2px solid #ecf0f1;
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    .chat-input {
      flex: 1;
      padding: 0.75rem 1rem;
      border: 2px solid #ecf0f1;
      border-radius: 25px;
      font-size: 1rem;
      outline: none;
      transition: border-color 0.3s;
    }

    .chat-input:focus {
      border-color: var(--primary);
    }

    .send-btn {
      background: linear-gradient(135deg, #3498db, #2980b9);
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      cursor: pointer;
      font-weight: 600;
      transition: transform 0.2s;
    }

    .send-btn:hover {
      transform: scale(1.05);
    }

    .empty-state {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100%;
      color: #95a5a6;
      text-align: center;
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
      .menu-toggle { display: block; }
      .main { margin-left: 0; }
      .chat-container {
        grid-template-columns: 1fr;
      }
      .conversations-panel {
        height: 300px;
      }
    }
  </style>
</head>
<body>
  <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>

  <div class="sidebar" id="sidebar">
    <img src="images/logo.webp" alt="Logo">
    <h2>Admin Panel</h2>
    <a href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="manage_user.php"><i class="fas fa-users"></i> Manage Users</a>
    <a href="manage_events.php"><i class="fas fa-calendar-alt"></i> Manage Events</a>
    <a href="reservations.php"><i class="fas fa-book"></i> New Reservations</a>
    <a href="confirmations.php"><i class="fas fa-check-circle"></i> Confirmations</a>
    <a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a>
    <a href="messages.php" class="active"><i class="fas fa-comments"></i> Messages</a>
    <a href="#" onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main" id="main">
    <div class="topbar">
      <h1><i class="fas fa-comments"></i> Messages</h1>
      <button class="toggle-btn" onclick="toggleDarkMode()">Toggle Dark Mode</button>
    </div>

    <div class="chat-container">
      <div class="conversations-panel">
        <h3>Conversations</h3>
        <div id="conversationsList">
          <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>Loading conversations...</p>
          </div>
        </div>
      </div>

      <div class="chat-panel" id="chatPanel">
        <div class="empty-state">
          <i class="fas fa-comments"></i>
          <p>Select a conversation to start messaging</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentConversationId = null;
    let lastMessageId = 0;
    let messageCheckInterval = null;

    function toggleDarkMode() {
      document.body.classList.toggle('dark');
    }

    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('closed');
      document.getElementById('main').classList.toggle('full');
    }

    function confirmLogout() {
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    }

    function loadConversations() {
      fetch('chat_api.php?action=get_conversations')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            displayConversations(data.conversations);
          }
        });
    }

    function displayConversations(conversations) {
      const list = document.getElementById('conversationsList');
      
      if (conversations.length === 0) {
        list.innerHTML = '<div class="empty-state"><i class="fas fa-inbox"></i><p>No conversations yet</p></div>';
        return;
      }

      list.innerHTML = conversations.map(conv => `
        <div class="conversation-item ${currentConversationId == conv.id ? 'active' : ''}" onclick="openConversation(${conv.id}, '${conv.client_name}')">
          <div class="client-name">
            ${conv.client_name}
            ${conv.unread_count > 0 ? `<span class="unread-badge">${conv.unread_count}</span>` : ''}
          </div>
          <div class="last-message">${conv.last_message || 'No messages yet'}</div>
        </div>
      `).join('');
    }

    function openConversation(conversationId, clientName) {
      currentConversationId = conversationId;
      lastMessageId = 0;
      
      // Update UI
      document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('active');
      });
      event.target.closest('.conversation-item').classList.add('active');
      
      // Setup chat panel
      const chatPanel = document.getElementById('chatPanel');
      chatPanel.innerHTML = `
        <div class="chat-header">
          <h3><i class="fas fa-user"></i> ${clientName}</h3>
        </div>
        <div class="chat-messages" id="chatMessages"></div>
        <div class="chat-input-area">
          <input type="text" class="chat-input" id="messageInput" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
          <button class="send-btn" onclick="sendMessage()"><i class="fas fa-paper-plane"></i> Send</button>
        </div>
      `;
      
      // Load messages
      loadMessages();
      
      // Start polling for new messages
      if (messageCheckInterval) {
        clearInterval(messageCheckInterval);
      }
      messageCheckInterval = setInterval(loadMessages, 2000);
    }

    function loadMessages() {
      if (!currentConversationId) return;
      
      fetch(`chat_api.php?action=get_messages&conversation_id=${currentConversationId}&last_message_id=${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
          if (data.success && data.messages.length > 0) {
            displayMessages(data.messages);
            lastMessageId = data.messages[data.messages.length - 1].id;
            loadConversations(); // Refresh conversation list to update unread counts
          }
        });
    }

    function displayMessages(messages) {
      const container = document.getElementById('chatMessages');
      
      messages.forEach(msg => {
        // Check if message already exists (avoid duplicates)
        if (document.querySelector(`[data-message-id="${msg.id}"]`)) {
          return; // Skip this message, it's already displayed
        }
        
        const isAdmin = msg.role === 'admin';
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isAdmin ? 'sent' : 'received'}`;
        messageDiv.setAttribute('data-message-id', msg.id); // Add unique identifier
        
        // Handle empty names
        const firstname = msg.firstname || 'Admin';
        const lastname = msg.lastname || 'User';
        const initials = firstname && lastname ? (firstname[0] + lastname[0]).toUpperCase() : firstname[0].toUpperCase();
        const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        messageDiv.innerHTML = `
          <div class="message-avatar">${initials}</div>
          <div>
            <div class="message-content">${escapeHtml(msg.message)}</div>
            <div class="message-time">${time}</div>
          </div>
        `;
        
        container.appendChild(messageDiv);
      });
      
      // Scroll to bottom
      container.scrollTop = container.scrollHeight;
    }

    function sendMessage() {
      const input = document.getElementById('messageInput');
      const message = input.value.trim();
      
      if (!message || !currentConversationId) {
        console.error('Cannot send message:', {message, currentConversationId});
        return;
      }
      
      console.log('Sending message:', message, 'to conversation:', currentConversationId);
      
      const formData = new FormData();
      formData.append('conversation_id', currentConversationId);
      formData.append('message', message);
      
      fetch('chat_api.php?action=send_message', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        console.log('Response status:', response.status);
        return response.json();
      })
      .then(data => {
        console.log('Send response:', data);
        if (data.success) {
          input.value = '';
          loadMessages();
        } else {
          console.error('Failed to send message:', data.message);
          alert('Failed to send message: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error sending message:', error);
        alert('Error sending message. Check console for details.');
      });
    }

    function handleKeyPress(event) {
      if (event.key === 'Enter') {
        sendMessage();
      }
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    // Initial load
    loadConversations();
    
    // Refresh conversations every 5 seconds
    setInterval(loadConversations, 5000);
  </script>
</body>
</html>
