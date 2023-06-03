<?php
// Award points when new user registered //
function scur_award_points_for_signup($user_id) {
    // Only award points for new signups (exclude user updates)
    if ( ! get_user_meta( $user_id, 'has_signed_up', true ) ) {
        $tl_new_register = get_option('translate_new_register');

        // Set the point value to award
        $points_awarded = get_option('points_for_new_register'); // Change this to the desired point value
            
        // Check if the IP address exists in the referral history table
        global $wpdb;
        $table_name = $wpdb->prefix . 'referral_history';
        $ip_address = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
        $ip_exists = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE ip_address = '$ip_address' AND type = '$tl_new_register'");

        if (!$ip_exists && $points_awarded !== "00") {
            // Award points and mark user as signed up
            //update_user_meta($user_id, 'user_points', get_user_meta($user_id, 'user_points', true) + $points_awarded);
            $user_points = intval(get_user_meta($user_id, 'user_points', true));
            update_user_meta($user_id, 'user_points', $user_points + $points_awarded);
            update_user_meta($user_id, 'has_signed_up', true);

            // Get registerer name
            $u_data = get_userdata($user_id);
            if ($u_data) {
                $login_id = $u_data->user_login;
                $f_name = $u_data->first_name;
                $l_name = $u_data->last_name;
                $registerer_name = "$f_name $l_name";
            } else {
                $registerer_name = "Unknown";
            }

            // Store the points and signup information in the database //
            global $wpdb;
            $table_name = $wpdb->prefix . 'referral_history';
            $wpdb->insert($table_name, array(
                'user_id' => $user_id,
                'user_name' => $registerer_name,
                'type' => $tl_new_register,
                'points' => $points_awarded,
                'ip_address' => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ),
                'login_id' => $login_id,
            ));
        }
    }
}

// Add action hooks
add_action('user_register', 'scur_award_points_for_signup');
?>