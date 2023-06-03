<?php
// Award points for login per day //
function scur_award_points_for_login($user_login, $user) {
    $tl_daily_login = get_option('translate_daily_login');
    
    $points_awarded = get_option('points_for_daily_login'); // Change this to the desired point value
    $daily_login_limit = 1; // Change this to the desired daily limit
    $user_id = $user->ID;
    $user_points = get_user_meta($user_id, 'user_points', true);
    $daily_login_count = get_user_meta($user_id, 'daily_login_count', true);
    $last_login_date = get_user_meta($user_id, 'last_login_date', true);
    $current_date = date('Y-m-d');
            
    // Check if the IP address exists in the referral history table
    global $wpdb;
    $table_name = $wpdb->prefix . 'referral_history';
    $ip_address = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
    $ip_exists = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE ip_address = '$ip_address' AND type = '$tl_daily_login'");

    // Check if the user has logged in today
    if (!$ip_exists && $last_login_date != $current_date && $points_awarded !== "00") {
        // Award points and update daily login count and last login date
        update_user_meta($user_id, 'user_points', $user_points + $points_awarded);
        update_user_meta($user_id, 'daily_login_count', $daily_login_count + 1);
        update_user_meta($user_id, 'last_login_date', $current_date);

        // Get referer name
        $u_data = get_userdata($user_id);
        if ($u_data) {
            $login_id = $u_data->user_login;
            $f_name = $u_data->first_name;
            $l_name = $u_data->last_name;
            $referer_name = "$f_name $l_name";
        } else {
            $referer_name = "Unknown";
        }

        // Store the points and login information in the database
        global $wpdb;
        $table_name = $wpdb->prefix . 'referral_history';
        $wpdb->insert($table_name, array(
            'user_id' => $user_id,
            'user_name' => $referer_name,
            'type' => $tl_daily_login,
            'points' => $points_awarded,
            'ip_address' => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ),
            'login_id' => $login_id,
        ));
    }

    // Check if the user has reached their daily login limit
    if ($daily_login_count < $daily_login_limit && $points_awarded !== "00") {
        // Award points and update daily login count and last login date
        update_user_meta($user_id, 'user_points', $user_points + $points_awarded);
        update_user_meta($user_id, 'daily_login_count', $daily_login_count + 1);
        update_user_meta($user_id, 'last_login_date', $current_date);
    }
}

// Add action hooks
add_action('wp_login', 'scur_award_points_for_login', 10, 2);
?>