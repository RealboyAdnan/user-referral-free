<?php
// Visitor Signup Points //
function scur_handle_signup_link( $user_id ) {
    if (isset($_COOKIE['ref'])) {
        $referer_id = (int) $_COOKIE['ref'];
        $referer = get_user_by('ID', $referer_id);
        
        if ($referer) { // Check if the referer ID is valid
            $tl_refer_signup = get_option('translate_refer_signup');
            $referral_points = get_option('signup_referral_points');
            $referer_points = (int) get_user_meta($referer_id, 'user_points', true);
            $referer_points += $referral_points;
            
            // Check if the IP address exists in the referral history table
            global $wpdb;
            $table_name = $wpdb->prefix . 'referral_history';
            $ip_address = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
            $ip_exists = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE ip_address = '$ip_address' AND type = '$tl_refer_signup'");
            
            if (!$ip_exists && $referral_points !== "00") {
                // Add points to referer //
                update_user_meta($referer_id, 'user_points', $referer_points);

                // Get referer name //
                $u_id = $referer_id;
                $u_data = get_userdata($u_id);
                if ($u_data) {
                    $login_id = $u_data->user_login;
                    $f_name = $u_data->first_name;
                    $l_name = $u_data->last_name;
                    $referer_name = "$f_name $l_name";
                } else {
                    $referer_name = "Unknown";
                }

                // Insert signup history record
                global $wpdb;
                $table_name = $wpdb->prefix . 'referral_history';
                $wpdb->insert( $table_name, array(
                    'user_id' => $referer_id,
                    'user_name' => $referer_name,
                    'type' => $tl_refer_signup,
                    'points' => $referral_points,
                    'ip_address' => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ),
                    'login_id' => $login_id,
                ));
            }
        }
    }
}
//add_action( 'user_register', 'scur_handle_signup_link' );
add_action( 'wp_login', 'scur_handle_signup_link' );

// Set referer ID as cookie
function scur_set_cookie() {
    if (isset($_GET['ref'])) {
        $referer_id = (int) $_GET['ref'];
        setcookie('ref', $referer_id, time()+3600, '/');
    }
}
add_action('init', 'scur_set_cookie');
?>