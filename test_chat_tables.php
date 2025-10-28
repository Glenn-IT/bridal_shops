<?php
session_start();
require 'config.php';

header('Content-Type: text/html; charset=utf-8');

try {
    // Check if tables exist
    $tables = ['chat_conversations', 'chat_messages'];
    $results = [];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        
        if ($exists) {
            // Get row count
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            // Get structure
            $stmt = $pdo->query("DESCRIBE $table");
            $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $results[] = [
                'table' => $table,
                'exists' => true,
                'count' => $count,
                'structure' => $structure
            ];
        } else {
            $results[] = [
                'table' => $table,
                'exists' => false
            ];
        }
    }
    
    // Display results
    echo "<div style='font-family: monospace; padding: 20px;'>";
    echo "<h3>📊 Chat Tables Status</h3>";
    
    foreach ($results as $result) {
        if ($result['exists']) {
            echo "<div style='margin: 15px 0; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;'>";
            echo "<strong style='color: #155724;'>✅ Table: {$result['table']}</strong><br>";
            echo "Rows: {$result['count']}<br>";
            echo "<details><summary>Show Structure</summary>";
            echo "<pre style='background: #272822; color: #f8f8f2; padding: 10px; border-radius: 3px; margin: 10px 0;'>";
            print_r($result['structure']);
            echo "</pre></details>";
            echo "</div>";
        } else {
            echo "<div style='margin: 15px 0; padding: 15px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;'>";
            echo "<strong style='color: #721c24;'>❌ Table: {$result['table']} - NOT FOUND</strong>";
            echo "</div>";
        }
    }
    
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px; color: #721c24;'>";
    echo "<strong>❌ Database Error:</strong><br>";
    echo htmlspecialchars($e->getMessage());
    echo "</div>";
}
?>
