# Live Chat Feature - Setup & Usage Guide

## Overview

A real-time live chat system has been implemented to allow clients to communicate directly with administrators.

## Features Implemented

### 1. Client Side (contact.php)

- **Live Chat Interface**: Replaced the "Send Us a Message" form with a real-time chat system
- **Real-time Updates**: Messages automatically refresh every 2 seconds
- **User-Friendly UI**: Modern chat bubble interface with avatars and timestamps
- **Login Required**: Non-logged-in users see a prompt to login

### 2. Admin Side (messages.php)

- **New Messages Page**: Added to admin sidebar navigation
- **Conversation Management**: View all active client conversations
- **Unread Indicators**: See which conversations have unread messages
- **Real-time Chat**: Chat with clients in real-time
- **Multi-conversation Support**: Handle multiple client conversations

## Files Created/Modified

### New Files:

1. **db/create_chat_tables.sql** - Database schema for chat system
2. **chat_api.php** - Backend API for all chat operations
3. **messages.php** - Admin messages dashboard

### Modified Files:

1. **contact.php** - Replaced contact form with live chat
2. **dashboard_admin.php** - Added "Messages" menu item
3. **login.php** - Added user_id to session for chat tracking

## Database Tables

### chat_conversations

- Stores conversation threads between clients and admin
- Tracks conversation status (active/closed)
- Records last message timestamp

### chat_messages

- Stores individual messages
- Tracks read/unread status
- Links to conversation and sender

## How to Use

### For Clients:

1. Login to your account
2. Navigate to Contact page
3. Start typing in the chat box
4. Messages are sent instantly
5. Admin responses appear automatically

### For Admin:

1. Login to admin dashboard
2. Click "Messages" in the sidebar
3. See all client conversations in the left panel
4. Click on a conversation to view/reply
5. Unread message counts are displayed
6. Type and send responses instantly

## API Endpoints (chat_api.php)

- **get_or_create_conversation**: Initialize chat for client
- **send_message**: Send a chat message
- **get_messages**: Retrieve messages (polling)
- **get_conversations**: Get all conversations (admin)
- **get_unread_count**: Get unread message count

## Technical Details

### Real-time Updates:

- Client side: Polls every 2 seconds for new messages
- Admin side: Polls conversations every 5 seconds, messages every 2 seconds
- Uses AJAX/Fetch API for seamless updates

### Security:

- Session-based authentication
- SQL injection protection via prepared statements
- XSS protection via HTML escaping
- Role-based access control

### UI Features:

- Responsive design
- Dark mode support (admin)
- Message timestamps
- Avatar initials
- Smooth animations
- Auto-scroll to latest message

## Testing Steps

1. **Setup Database**:

   - The chat tables have been created automatically
   - Verify tables exist: `chat_conversations`, `chat_messages`

2. **Test Client Chat**:

   - Login as a client
   - Go to http://localhost/bridal_shops/contact.php
   - Send a test message
   - Should appear instantly in chat window

3. **Test Admin Response**:

   - Login as admin
   - Go to http://localhost/bridal_shops/messages.php
   - See the client conversation
   - Reply to the message
   - Check unread count updates

4. **Test Real-time Updates**:
   - Keep both windows open (client and admin)
   - Send messages from both sides
   - Verify messages appear without refresh

## Troubleshooting

### Messages not appearing:

- Check browser console for JavaScript errors
- Verify chat_api.php is accessible
- Check database tables were created
- Ensure user_id is set in session

### Permission errors:

- Verify user is logged in
- Check session has user_id set
- Verify database user permissions

### Styling issues:

- Clear browser cache
- Check CSS is loading properly
- Verify Font Awesome icons are loading

## Future Enhancements (Optional)

- WebSocket implementation for true real-time updates
- File/image sharing in chat
- Chat history archiving
- Typing indicators
- Message notifications
- Email notifications for offline messages
- Chat analytics and reporting

## Support

If you encounter any issues, check:

1. Browser console for errors
2. PHP error logs
3. Database connection
4. Session configuration

---

**Note**: Make sure XAMPP/Apache and MySQL are running for the chat system to work properly.
