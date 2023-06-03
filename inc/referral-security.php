<?php
// Security Menu //
function scur_add_security() {
    add_submenu_page(
        'user-referral-free-settings',
        'Security Settings',
        'Security',
        'manage_options',
        'user-referral-free-security',
        'scur_render_security'
    );
}
add_action('admin_menu', 'scur_add_security');

// Referral Security //
function scur_render_security() { 
    // Delete referral history from the database
    if (isset($_POST['reset_referral_history']) && check_admin_referer('reset_referral_history')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'referral_history';
        $wpdb->query("TRUNCATE TABLE $table_name");
        echo '<div class="notice notice-success is-dismissible"><p>Referral history successfully deleted!</p></div>';
    }

    // Reset user points meta value to 0 for all users
    if (isset($_POST['reset_user_points']) && check_admin_referer('reset_user_points')) {
        $users = get_users();
        foreach ($users as $user) {
            delete_user_meta($user->ID, 'user_points');
            update_user_meta($user->ID, 'user_points', 0);
        }
        echo '<div class="notice notice-success is-dismissible"><p>User points successfully deleted!</p></div>';
    }
    
    // Get total user points //
    function scur_get_total_user_points() {
        $user_points = 0;
        $users = get_users();
        foreach ($users as $user) {
            $user_points += (int) get_user_meta($user->ID, 'user_points', true);
        }
        return $user_points;
    }
    
    // Get total user history //
    function scur_get_total_referral_history() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'referral_history';
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        return $total_items;
    }
    
    // Get data //
    $total_user_points = scur_get_total_user_points();
    $total_referral_history = scur_get_total_referral_history();

    //echo get_option('delete_data_on_deactivate');

    // Get the stylesheet //
    scur_enqueue_styles();

    // Limit the history //
    $history_limit = get_option('last_history_count');
?>
<div class="section-divider">
    <div class="referral-system">
        <h2><?php _e('Security Settings', 'user-referral-free'); ?></h2>

        <h4><?php _e('Reset History', 'user-referral-free'); ?> ( <small><?php _e('Total:', 'user-referral-free'); ?> <?php echo number_format($total_referral_history); ?></small> )</h4>
        <p><?php _e('Click the button below to reset all the referral history.', 'user-referral-free'); ?></p>
        <form method="post" action="">
            <input type="hidden" name="reset_referral_history" value="1">
            <?php wp_nonce_field( 'reset_referral_history' ); ?>
            <p><input type="submit" value="Reset Referral History" class="button button-primary" onclick="return confirm('Are you sure you want to reset the referral history?');"></p>
        </form>

        <hr>

        <h4><?php _e('Reset Points', 'user-referral-free'); ?> ( <small><?php _e('Total:', 'user-referral-free'); ?> <?php echo number_format($total_user_points); ?></small> )</h4>
        <p><?php _e('Click the button below to reset all the user referral points.', 'user-referral-free'); ?></p>
        <form method="post" action="">
            <input type="hidden" name="reset_user_points" value="1">
            <?php wp_nonce_field( 'reset_user_points' ); ?>
            <p><input type="submit" value="Reset User Points" class="button button-primary" onclick="return confirm('Are you sure you want to reset all user referral points?');"></p>
        </form>

        <hr>

        <h4><?php _e('Delete History', 'user-referral-free'); ?> ( <small><?php _e('Total:', 'user-referral-free'); ?> <?php echo number_format($total_referral_history); ?></small> )</h4>
        <p><?php _e('Delete the last '.$history_limit.' referral history.', 'user-referral-free'); ?></p>
        <form method="post" action="" class="premium">
            <input type="hidden" name="delete_last_history" value="1">
            <?php wp_nonce_field( 'delete_last_history' ); ?>
            <p><input type="submit" value="Delete <?php echo $history_limit; ?> History" class="button button-primary" onclick="return confirm('Are you sure you want to delete the last <?php echo $history_limit; ?> history?');"></p>
        </form>

        <hr>

        <h4><?php _e('Delete Data', 'user-referral-free'); ?></h4>
        <p><?php _e('Delete plugin data (eg: history, points) when the plugin is deactivated.', 'user-referral-free'); ?></p>
        <form method="post" action="" class="premium">
            <input type="radio" id="delete_data_on_deactivate_on" name="delete_data_on_deactivate" value="on" <?php checked(get_option('delete_data_on_deactivate'), 'on'); ?>>
            <label for="delete_data_on_deactivate_on"><?php _e('Erase (On)', 'user-referral-free'); ?></label>
            
            <span class="radio-spacing"></span>

            <input type="radio" id="delete_data_on_deactivate_off" name="delete_data_on_deactivate" value="off" <?php checked(get_option('delete_data_on_deactivate'), 'off'); ?>>
            <label for="delete_data_on_deactivate_off"><?php _e('Erase (Off)', 'user-referral-free'); ?></label><br>
            <?php wp_nonce_field( 'delete_data_on_deactivate' ); ?>

            <div class="top-spacing"></div>
            
            <div class="button-space">
                <input type="submit" value="<?php _e('Save Changes', 'user-referral-free'); ?>" class="button button-primary">
            </div>
        </form>
    </div>
</div>
<?php } ?>