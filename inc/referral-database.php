<?php
// Create database for referral history //
function scur_create_history_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'referral_history';
    $charset_collate = $wpdb->get_charset_collate();

    // Check if the table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) UNSIGNED NOT NULL,
            login_id VARCHAR(50) NOT NULL,
            user_name VARCHAR(50) NOT NULL,
            type VARCHAR(50) NOT NULL,
            points INT(11) NOT NULL,
            ip_address VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// Run the function to create the referral history table if it doesn't exist
scur_create_history_table();
?>