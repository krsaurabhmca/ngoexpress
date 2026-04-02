<?php
// Auto Database Sync & Upgrade Script
// This file runs autonomously during a system update.
// Put database ALTER queries here securely based on version definitions!

// Example migration format:
/*
$current_db_version = get_setting('db_version') ?: '1.0.0';

if (version_compare($current_db_version, '1.0.1', '<')) {
    // Apply changes for 1.0.1
    // mysqli_query($conn, "ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL");
    
    set_setting('db_version', '1.0.1');
}
*/

// Set the global database setting to the current CORE_VERSION to finalize upgrade
if (defined('CORE_VERSION')) {
    set_setting('db_version', CORE_VERSION);
}
?>
