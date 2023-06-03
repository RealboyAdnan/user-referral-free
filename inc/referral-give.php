<?php
// Page Give Points //
function scur_give_points_shortcode($atts) {
    $tl_points_give = get_option('translate_points_give');
    $tl_commission = get_option('translate_commission');

    // Get the user ID from the shortcode attribute or current user ID.
    if (isset($atts['user']) && $atts['user'] != 'current') {
        $user_id = intval($atts['user']);
    } else {
        $user_id = get_current_user_id();
    }

    // Get the amount of give points from the shortcode attribute.
    $amount = isset($atts['amount']) && $atts['amount'] != '' ? (int)$atts['amount'] : 0;

    // Get the type of give points from the shortcode attribute.
    $type = isset($atts['type']) && $atts['type'] != '' ? $atts['type'] : $tl_points_give;

    // Get the limit of give points from the shortcode attribute.
    $limit = isset($atts['limit']) && $atts['limit'] != '' ? (int)$atts['limit'] : 0;

    // Check if the limit has been reached for the given type and date.
    $date = date('Y-m-d');
    $points_given_today = (int)get_user_meta($user_id, 'points_given_today_' . $type . '_' . $date, true);

    if ($limit === 0 || $points_given_today < $limit) {
        // Add the give points to the user's points.
        $current_points = (int)get_user_meta($user_id, 'user_points', true);
        $new_points = $current_points + $amount;
        update_user_meta($user_id, 'user_points', $new_points);

        // Commission section //
        $percentage = get_option('points_for_commission');
        $divider = $amount / 100;
        $commission = $divider * $percentage;
        $referer_id = get_user_meta($user_id, 'referral_id', true);
        $current_points = get_user_meta($referer_id, 'user_points', true);
        $final_points =  $commission + $current_points;

        update_user_meta($referer_id, 'user_points', $final_points);

        // Get referer name //
        $r_id = $referer_id;
        $r_data = get_userdata($r_id);
        if ($r_data) {
            $rlogin_id = $r_data->user_login;
            $f_name = $r_data->first_name;
            $l_name = $r_data->last_name;
            $referer_name = "$f_name $l_name";
        } else {
            $referer_name = "Unknown";
        }

        // Get user name //
        $c_id = $user_id;
        $c_data = get_userdata($c_id);
        if ($c_data) {
            $clogin_id = $c_data->user_login;
            $f_name = $c_data->first_name;
            $l_name = $c_data->last_name;
            $user_name = "$f_name $l_name";
        } else {
            $user_name = "Unknown";
        }

        // Add the give points to the referral history table.
        global $wpdb;
        $table_name = $wpdb->prefix . 'referral_history';
        $wpdb->insert($table_name, array(
            'user_id' => $user_id,
            'user_name' => $user_name,
            'type' => $type,
            'points' => $amount,
            'ip_address' => sanitize_text_field($_SERVER['REMOTE_ADDR']),
            'login_id' => $clogin_id,
        ));

        if (!empty($referer_id) && $percentage !== "00") {
            // Add the give points to the referral history table.
            global $wpdb;
            $table_name = $wpdb->prefix . 'referral_history';
            $wpdb->insert($table_name, array(
                'user_id' => $referer_id,
                'user_name' => $referer_name,
                'type' => $tl_commission,
                'points' => $commission,
                'ip_address' => sanitize_text_field($_SERVER['REMOTE_ADDR']),
                'login_id' => $rlogin_id,
            ));
        }

        // Update the points given today count for the given type and date.
        if ($type !== $tl_points_give) {
            update_user_meta($user_id, 'points_given_today_' . $type . '_' . $date, $points_given_today + 1);
        }

        return 'You have received ' . $amount . ' points!';
    } else {
        return 'You have reached the daily limit for ' . $type . ' points.';
    }
}
add_shortcode('give_points', 'scur_give_points_shortcode');

?>