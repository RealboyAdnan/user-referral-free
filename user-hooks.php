<?php
// Plugin activation callback
function scur_plugin_activate_action() {
    $default_options = array(
        'visitor_referral_points' => '00',
        'signup_referral_points' => '00',
        'signup_link' => site_url('/register/'),
        'points_for_new_register' => '00',
        'points_for_daily_login' => '00',

        'points_for_post_published' => '00',
        'points_limit_for_daily_post' => '00',
        'points_for_comment_approved' => '00',

        'custom_post_types' => 'post_type_1,post_type_2',
        'custom_post_types_point' => '00',

        'points_type_for_woocommerce_order' => 'fixed',
        'fixed_points_for_woocommerce_order_completed' => '00',
        'percentage_points_for_woocommerce_order_completed' => '00',
        'minimum_woocommerce_order_amount_required' => '00',

        'min_points_transfer_amount' => '00',
        'max_points_transfer_amount' => '00',
        
        'points_for_commission' => '00',

        'translate_refer_visitor' => 'Refer Visitor',
        'translate_refer_signup' => 'Refer Signup',
        'translate_new_register' => 'Account Creation',
        'translate_daily_login' => 'Daily Login',
        'translate_publish_post' => 'Publish Post',
        'translate_approved_comment' => 'Post Comment',
        'translate_custom_post' => 'Custom Post',
        'translate_woocommerce_order' => 'Product Order',
        'translate_commission' => 'Commission',
        'translate_points_give' => 'Points Given',
        'translate_points_deduct' => 'Points Deducted',
        'translate_points_added' => 'Points Added',
        'translate_points_removed' => 'Points Removed',
        'translate_points_transferred' => 'Points Transferred',
        'translate_points_received' => 'Points Received',

        'all_history_count' => 10,
        'top_users_count' => 10,
        'last_history_count' => 100,
        'last_history_type' => 'oldest',
    );

    foreach ($default_options as $option_name => $default_value) {
        if (get_option($option_name) === false) {
            add_option($option_name, $default_value);
        }
    }
}

// Add settings link to plugin
function scur_add_settings_link( $links ) {
    $settings_link_1 = '<a href="admin.php?page=user-referral-free-settings">Settings</a>';
    $settings_link_2 = '<a href="https://softclever.com" target="_blank"><span style="font-weight: bold;color: red;">Get Premium</span></a>';
    array_push( $links, $settings_link_1, $settings_link_2 );
    return $links;
}
?>