<?php
// Deduct Points //
function scur_deduct_points_shortcode( $atts ) {
    $tl_points_deduct = get_option('translate_points_deduct');

    // Extract the shortcode attributes
    $atts = shortcode_atts( array(
        'user' => 'current',
        'points' => '0',
        'type' => $tl_points_deduct,
    ), $atts, 'deduct_points' );

    // Get the user ID
    if ( $atts['user'] == 'current' ) {
        $user_id = get_current_user_id();
    } else {
        $user_id = absint( $atts['user'] );
    }

    // Get the deducted points
    $deducted_points = absint( $atts['points'] );
    $deducted_type = sanitize_text_field( $atts['type'] );

    // Get the current user's points
    $current_user_points = intval( get_user_meta( $user_id, 'user_points', true ) );

    if ( $deducted_points > $current_user_points ) {
        return '<p class="not-enough-points">Sorry, you do not have enough points to deduct.</p>';
    } else {
        // Insert referral history record
        global $wpdb;
        $table_name = $wpdb->prefix . 'referral_history';
        $referer_id = $user_id;
        $referer_name = get_user_meta( $referer_id, 'nickname', true );
        $referral_points = intval( $deducted_points );
        $ip_address = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );

        // Get owner name //
        $u_data = get_userdata( $referer_id );
        if ( $u_data ) {
            $login_id = $u_data->user_login;
            $f_name = $u_data->first_name;
            $l_name = $u_data->last_name;
            $owner_name = "$f_name $l_name";
        } else {
            $owner_name = "Unknown";
        }

        // Insert referral history record //
        $wpdb->insert($table_name, array(
            'user_id' => $referer_id,
            'user_name' => $owner_name,
            'type' => $deducted_type,
            'points' => $referral_points,
            'ip_address' => $ip_address,
            'login_id' => $login_id,
        ), array(
            '%d',
            '%s',
            '%s',
            '%d',
            '%s',
            '%s',
        ));

        // Deduct the points and update the user meta
        $new_user_points = $current_user_points - $deducted_points;
        update_user_meta( $user_id, 'user_points', $new_user_points );

        // Return success message with the updated points balance
        return '<p class="points-deducted">Points deducted successfully. Your new balance is ' . number_format( $new_user_points ) . ' points.</p>';
    }
}
add_shortcode( 'deduct_points', 'scur_deduct_points_shortcode' );
?>