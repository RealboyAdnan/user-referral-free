<?php
// Add custom user field "user_points" with an input field
function scur_add_user_points_field($user) {
    $current_user = wp_get_current_user();
    if (!current_user_can('administrator') && $current_user->ID !== $user->ID) {
        $user_points = intval(get_user_meta($user->ID, 'user_points', true));
        echo '<h3>' . __('Current Points', 'user-referral-free') . '</h3>';
        echo '<p>' . number_format($user_points) . ' Points</p>';
    } else {
        $user_points = esc_attr(get_the_author_meta('user_points', $user->ID));
        $referral_id = esc_attr(get_the_author_meta('referral_id', $user->ID));
        ?>
        <h3><?php _e('Current Points', 'user-referral-free'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="user_points"><?php _e('Points', 'user-referral-free'); ?></label></th>
                <td>
                    <input type="number" id="user_points" name="user_points" value="<?php echo $user_points; ?>" class="regular-text" <?php if (!current_user_can('administrator')) echo 'readonly style="background: #ddd; cursor:not-allowed;"'; ?> /><br />
                </td>
            </tr>
            <tr>
                <th><label for="referral_id"><?php _e('Referral ID', 'user-referral-free'); ?></label></th>
                <td>
                    <input type="number" id="referral_id" name="referral_id" value="<?php echo $referral_id; ?>" class="regular-text" <?php if (!current_user_can('administrator')) echo 'readonly style="background: #ddd; cursor:not-allowed;"'; ?> /><br />
                </td>
            </tr>
        </table>
        <?php
    }
}
add_action('show_user_profile', 'scur_add_user_points_field');
add_action('edit_user_profile', 'scur_add_user_points_field');

// Save custom user field "user_points"
function scur_save_user_points_field($user_id) {
    if (!current_user_can('administrator')) {
        return false;
    }
    update_user_meta($user_id, 'user_points', sanitize_text_field($_POST['user_points']));
    update_user_meta($user_id, 'referral_id', sanitize_text_field($_POST['referral_id']));
}
add_action('personal_options_update', 'scur_save_user_points_field');
add_action('edit_user_profile_update', 'scur_save_user_points_field');

// Add custom user field "user_points" to user column
function scur_add_user_points_column($columns) {
    $columns['user_points'] = __('Points', 'sc');
    return $columns;
}
add_filter('manage_users_columns', 'scur_add_user_points_column');

// User Points Column //
function scur_display_user_points_column($value, $column_name, $user_id) {
    if ('user_points' === $column_name) {
        $popup_link = esc_js(admin_url("admin.php?page=user-referral-free-adjust&user_id="));

        $curr_points = get_user_meta($user_id, 'user_points', true);
        if (empty($curr_points)) {
            $curr_points = '0.00';
        }
        
        $adjust_link = number_format($curr_points) . '<div class="row-actions"><span><a href="'. $popup_link .'' . $user_id . '" target="_blank">Adjust Points</a></span></div>';
        return $adjust_link;
    }
    return $value;
}
add_filter('manage_users_custom_column', 'scur_display_user_points_column', 10, 3);

// Make the "Points" column sortable
function scur_make_user_points_column_sortable($columns) {
    $columns['user_points'] = 'user_points';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'scur_make_user_points_column_sortable');

// Display referral points in plain text
function scur_display_points() {
    $current_user = wp_get_current_user();
    if ($current_user->ID === 0) {
        return; // User is not logged in
    }
    $user_points = intval(get_user_meta($current_user->ID, 'user_points', true));
    $points_message = sprintf(__('Your current balance is %d points.', 'user-referral-free'), $user_points);
    return '<p>' . $points_message . '</p>';
}
add_shortcode('referral_points', 'scur_display_points');

// Display referral points as a number
function scur_display_points_num() {
    $current_user = wp_get_current_user();
    if ($current_user->ID === 0) {
        return; // User is not logged in
    }
    $user_points = intval(get_user_meta($current_user->ID, 'user_points', true));
    $points_message = sprintf('%d', $user_points);
    return $points_message;
}
add_shortcode('referral_points_num', 'scur_display_points_num');
?>