<?php
/* Register Referral */
function scur_add_settings() {
    add_menu_page(
        'Referral Settings', // page title
        'Referral', // menu title
        'manage_options', // capability
        'user-referral-free-settings', // menu slug
        'scur_render_settings', // callback function
        //'dashicons-admin-links' // icon url or icon class name
        plugins_url( '../img/plugin-icon.png', __FILE__ ) // icon url or icon class name
    );
}
add_action('admin_menu', 'scur_add_settings');

// Save Settings //
function scur_render_settings() { 
    $visitor_referral_points = get_option('visitor_referral_points', '00');
    $signup_referral_points = get_option('signup_referral_points', '00');
    $signup_link = get_option('signup_link', site_url('/register/'));

    $points_for_new_register = get_option('points_for_new_register', '00');
    $points_for_daily_login = get_option('points_for_daily_login', '00');

    $points_for_post_published = get_option('points_for_post_published', '00');
    $points_limit_for_daily_post = get_option('points_limit_for_daily_post', '00');
    $points_for_comment_approved = get_option('points_for_comment_approved', '00');

    $custom_post_types = get_option('custom_post_types', 'post_type_1,post_type_2');
    $custom_post_types_point = get_option('custom_post_types_point', '00');

    $points_type_for_woocommerce_order = get_option('points_type_for_woocommerce_order', 'fixed');

    // Get the selected option (if any)
    $fixed_checked = '';
    $percentage_checked = '';
    
    if ($points_type_for_woocommerce_order == 'fixed') {
        $fixed_checked = 'checked';
    } elseif ($points_type_for_woocommerce_order == 'percentage') {
        $percentage_checked = 'checked';
    }

    $fixed_points_for_woocommerce_order_completed = get_option('fixed_points_for_woocommerce_order_completed', '00');
    $percentage_points_for_woocommerce_order_completed = get_option('percentage_points_for_woocommerce_order_completed', '00');
    $minimum_woocommerce_order_amount_required = get_option('minimum_woocommerce_order_amount_required', '00');

    $min_points_transfer_amount = get_option('min_points_transfer_amount', '10');
    $max_points_transfer_amount = get_option('max_points_transfer_amount', '50');

    $points_for_commission = get_option('points_for_commission', '00');

    $translate_refer_visitor = get_option('translate_refer_visitor', 'Refer Visitor');
    $translate_refer_signup = get_option('translate_refer_signup', 'Refer Signup');
    $translate_new_register = get_option('translate_new_register', 'Account Creation');
    $translate_daily_login = get_option('translate_daily_login', 'Daily Login');
    $translate_publish_post = get_option('translate_publish_post', 'Publish Post');
    $translate_approved_comment = get_option('translate_approved_comment', 'Post Comment');
    $translate_custom_post = get_option('translate_custom_post', 'Custom Post');
    $translate_woocommerce_order = get_option('translate_woocommerce_order', 'Product Order');
    $translate_commission = get_option('translate_commission', 'Commission');
    $translate_points_give = get_option('translate_points_give', 'Points Given');
    $translate_points_deduct = get_option('translate_points_deduct', 'Points Deducted');
    $translate_points_added = get_option('translate_points_added', 'Points Added');
    $translate_points_removed = get_option('translate_points_removed', 'Points Removed');
    $translate_points_transferred = get_option('translate_points_transferred', 'Points Transferred');
    $translate_points_received = get_option('translate_points_received', 'Points Received');

    $all_history_count = get_option('all_history_count', 10);
    $top_users_count = get_option('top_users_count', 10);
    $last_history_count = get_option('last_history_count', 100);
    $last_history_type = get_option('last_history_type', 'oldest');

    // Get the stylesheet //
    scur_enqueue_styles();
?>
<div class="section-divider">
    <?php 
        if (isset($_GET['status']) && $_GET['status'] == 1) { 
        $message = '<div class="error notice"><p>Please make sure you fill all fields!</p></div>';
        } elseif (isset($_GET['updated']) && $_GET['updated'] == 'true') {
        $message = '<div class="updated notice"><p>Your changes have been saved.</p></div>';
        }
        echo $message; 
    ?>
    <div class="referral-system">
        <h2><?php _e('Referral Settings', 'user-referral-free'); ?></h2>
        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <input type="hidden" name="action" value="scur_save_system_settings" />
			
            <table class="general-table">
                <tr class="settings-head">
                    <th>
                        <label for="head_label">
                            <strong>
                                <?php _e('Visitor & Signup', 'user-referral-free'); ?>
                            </strong>
                        </label>
                    </th>
                </tr>
				<tr>
					<th><label for="visitor_referral_points"><?php _e('Visitor Referral', 'user-referral-free'); ?></label></th>
					<td><input type="number" id="visitor_referral_points" name="visitor_referral_points" value="<?php echo esc_attr($visitor_referral_points); ?>"/><span><?php _e('Points for clicking the referral link..', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($visitor_referral_points))) { 
                            update_option('visitor_referral_points', '00');
                        }
                    ?>
				</tr>

                <tr>
                    <th><label for="signup_referral_points"><?php _e('Signup Referral', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="signup_referral_points" name="signup_referral_points" value="<?php echo esc_attr($signup_referral_points); ?>"/><span><?php _e('Points for unique signup and successfully logging in.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($signup_referral_points))) { 
                            update_option('signup_referral_points', '00');
                        }
                    ?>
                </tr>

                <tr>
                    <th><label for="signup_link"><?php _e('Signup Link', 'user-referral-free'); ?></label></th>
                    <td><input type="url" id="signup_link" name="signup_link" value="<?php echo esc_attr(get_option('signup_link', site_url('/register/'))); ?>"/><span><?php _e('Modify the signup link for your page.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($signup_link))) { 
                            update_option('signup_link', site_url('/register/'));
                        }
                    ?>
                </tr>
            </table>

            <table class="general-table">
                <tr class="settings-head">
                    <th>
                        <label for="head_label">
                            <strong>
                                <?php _e('Register & Login', 'user-referral-free'); ?>
                            </strong>
                        </label>
                    </th>
                </tr>
                <tr>
                    <th><label for="points_for_new_register"><?php _e('New Register', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="points_for_new_register" name="points_for_new_register" value="<?php echo esc_attr($points_for_new_register); ?>"/><span><?php _e('Points for creating a new account.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($points_for_new_register))) { 
                            update_option('points_for_new_register', '00');
                        }
                    ?>
                </tr>

                <tr>
                    <th><label for="points_for_daily_login"><?php _e('Daily Login', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="points_for_daily_login" name="points_for_daily_login" value="<?php echo esc_attr($points_for_daily_login); ?>"/><span><?php _e('Points for daily login to the account.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($points_for_daily_login))) { 
                            update_option('points_for_daily_login', '00');
                        }
                    ?>
                </tr>
            </table>

            <table class="general-table">
                <tr class="settings-head">
                    <th>
                        <label for="head_label">
                            <strong>
                                <?php _e('Default Post', 'user-referral-free'); ?>
                            </strong>
                        </label>
                    </th>
                </tr>
                <tr>
                    <th><label for="points_for_post_published"><?php _e('Publish Post', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="points_for_post_published" name="points_for_post_published" value="<?php echo esc_attr($points_for_post_published); ?>"/><span><?php _e('Points for publishing a new post.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($points_for_post_published))) { 
                            update_option('points_for_post_published', '00');
                        }
                    ?>
                </tr>

                <tr>
                    <th><label for="points_limit_for_daily_post"><?php _e('Daily Post Limit', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="points_limit_for_daily_post" name="points_limit_for_daily_post" value="<?php echo esc_attr($points_limit_for_daily_post); ?>"/><span><?php _e('Limit posts to earn points per day.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($points_limit_for_daily_post))) { 
                            update_option('points_limit_for_daily_post', '00');
                        }
                    ?>
                </tr>

                <tr>
                    <th><label for="points_for_comment_approved"><?php _e('Post Comment', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="points_for_comment_approved" name="points_for_comment_approved" value="<?php echo esc_attr($points_for_comment_approved); ?>"/><span><?php _e('Points for a comment that is approved from pending.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($points_for_comment_approved))) { 
                            update_option('points_for_comment_approved', '00');
                        }
                    ?>
                </tr>
            </table>

            <table class="general-table">
                <tr class="settings-head">
                    <th>
                        <label for="head_label">
                            <strong>
                                <?php _e('Custom Post', 'user-referral-free'); ?>
                            </strong>
                        </label>
                    </th>
                </tr>
                <tr class="premium">
                    <th><label for="custom_post_types"><?php _e('Custom Post Types', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="custom_post_types" name="custom_post_types" value="<?php echo esc_attr($custom_post_types); ?>"/><span><?php _e('Points for publishing custom posts ( multiple post type by comma ).', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($custom_post_types))) { 
                            update_option('custom_post_types', 'post_type_1,post_type_2');
                        }
                    ?>
                </tr>

                <tr class="premium">
                    <th><label for="custom_post_types_point"><?php _e('Custom Posts Point', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="custom_post_types_point" name="custom_post_types_point" value="<?php echo esc_attr($custom_post_types_point); ?>"/><span><?php _e('Points for custom post types once approved.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($custom_post_types_point))) { 
                            update_option('custom_post_types_point', '00');
                        }
                    ?>
                </tr>
            </table>

            <table class="general-table">
                <tr class="settings-head">
                    <th>
                        <label for="head_label">
                            <strong>
                                <?php _e('WooCommerce Order', 'user-referral-free'); ?>
                            </strong>
                        </label>
                    </th>
                </tr>
                <tr class="premium">
                    <th><label for="points_type_for_woocommerce_order"><?php _e('Points Type', 'user-referral-free'); ?></label></th>
                    <td>
                        <input type="radio" id="points_type_fixed" name="points_type_for_woocommerce_order" value="fixed" <?php echo $fixed_checked; ?>>
                        <label for="points_type_fixed" class="small-space"><?php _e('Fixed', 'user-referral-free'); ?></label>

                        <input type="radio" id="points_type_percentage" name="points_type_for_woocommerce_order" value="percentage" <?php echo $percentage_checked; ?>>
                        <label for="points_type_percentage"><?php _e('Percentage', 'user-referral-free'); ?></label>
                        <span><?php _e('Points type for woocommerce product order.', 'user-referral-free'); ?></span>
                    </td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($points_type_for_woocommerce_order))) { 
                            update_option('points_type_for_woocommerce_order', 'fixed');
                        }
                    ?>
                </tr>
                <tr class="premium fixed-points-row" <?php if ($points_type_for_woocommerce_order !== 'fixed') echo 'style="display: none;"'; ?>>
                    <th><label for="fixed_points_for_woocommerce_order_completed"><?php _e('Order Points ( Fixed )', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="fixed_points_for_woocommerce_order_completed" name="fixed_points_for_woocommerce_order_completed" value="<?php echo esc_attr($fixed_points_for_woocommerce_order_completed); ?>"/><span><?php _e('Fixed points for woocommerce product orders that were completed.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($fixed_points_for_woocommerce_order_completed))) { 
                            update_option('fixed_points_for_woocommerce_order_completed', '00');
                        }
                    ?>
                </tr>
                <tr class="premium percentage-points-row" <?php if ($points_type_for_woocommerce_order !== 'percentage') echo 'style="display: none;"'; ?>>
                    <th><label for="percentage_points_for_woocommerce_order_completed"><?php _e('Order Points ( Percentage )', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="percentage_points_for_woocommerce_order_completed" name="percentage_points_for_woocommerce_order_completed" value="<?php echo esc_attr($percentage_points_for_woocommerce_order_completed); ?>" max="100"/><span><?php _e('Percentage points for woocommerce product orders that were completed.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($percentage_points_for_woocommerce_order_completed))) { 
                            update_option('percentage_points_for_woocommerce_order_completed', '00');
                        }
                    ?>
                </tr>
                <script>
                    jQuery(document).ready(function($) {
                        // Get the current selected points type from PHP
                        var selectedPointsType = '<?php echo esc_js($points_type_for_woocommerce_order); ?>';

                        // Initially hide the input field rows
                        $('.fixed-points-row').hide();
                        $('.percentage-points-row').hide();

                        // Show or hide the input field rows based on the selected radio button value
                        $('input[name="points_type_for_woocommerce_order"]').on('change', function() {
                            var selectedValue = $(this).val();
                            if (selectedValue === 'fixed') {
                                $('.fixed-points-row').show();
                                $('.percentage-points-row').hide();
                            } else if (selectedValue === 'percentage') {
                                $('.fixed-points-row').hide();
                                $('.percentage-points-row').show();
                            } else {
                                $('.fixed-points-row').hide();
                                $('.percentage-points-row').hide();
                            }
                        });

                        // Check the current selected points type and show the corresponding input field row
                        if (selectedPointsType === 'fixed') {
                            $('#points_type_fixed').prop('checked', true).change();
                        } else if (selectedPointsType === 'percentage') {
                            $('#points_type_percentage').prop('checked', true).change();
                        }
                    });
                </script>
                <tr class="premium">
                    <th><label for="minimum_woocommerce_order_amount_required"><?php _e('Minimum Order Amount', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="minimum_woocommerce_order_amount_required" name="minimum_woocommerce_order_amount_required" value="<?php echo esc_attr($minimum_woocommerce_order_amount_required); ?>"/><span><?php _e('Minimum amount of order should be completed.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($minimum_woocommerce_order_amount_required))) { 
                            update_option('minimum_woocommerce_order_amount_required', '00');
                        }
                    ?>
                </tr>
            </table>

            <table class="general-table">
                <tr class="settings-head">
                    <th>
                        <label for="head_label">
                            <strong>
                                <?php _e('Points Transfer', 'user-referral-free'); ?>
                            </strong>
                        </label>
                    </th>
                </tr>
                <tr class="premium">
                    <th><label for="min_points_transfer_amount"><?php _e('Transfer Amount ( <small>Minimum</small> )', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="min_points_transfer_amount" name="min_points_transfer_amount" value="<?php echo esc_attr($min_points_transfer_amount); ?>"/><span><?php _e('Set the minimum amount of points that users can transfer.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($min_points_transfer_amount))) { 
                            update_option('min_points_transfer_amount', '00');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="max_points_transfer_amount"><?php _e('Transfer Amount ( <small>Maximum</small> )', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="max_points_transfer_amount" name="max_points_transfer_amount" value="<?php echo esc_attr($max_points_transfer_amount); ?>"/><span><?php _e('Set the maximum amount of points that users can transfer.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($max_points_transfer_amount))) { 
                            update_option('max_points_transfer_amount', '00');
                        }
                    ?>
                </tr>
            </table>

            <table class="general-table">
                <tr class="settings-head">
                    <th>
                        <label for="head_label">
                            <strong>
                                <?php _e('Commission', 'user-referral-free'); ?>
                            </strong>
                        </label>
                    </th>
                </tr>
                <tr class="premium">
                    <th><label for="points_for_commission"><?php _e('Commission Points', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="points_for_commission" name="points_for_commission" value="<?php echo esc_attr($points_for_commission); ?>" max="100"/><span><?php _e('The person who referred the user will get a percentage of the earnings.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($points_for_commission))) { 
                            update_option('points_for_commission', '00');
                        }
                    ?>
                </tr>
            </table>

            <table class="general-table">
                <tr class="settings-head">
                    <th>
                        <label for="head_label">
                            <strong>
                                <?php _e('Translation', 'user-referral-free'); ?>
                            </strong>
                        </label>
                    </th>
                </tr>
                <tr class="premium">
                    <th><label for="translate_refer_visitor"><?php _e('Refer Visitor ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_refer_visitor" name="translate_refer_visitor" value="<?php echo esc_attr($translate_refer_visitor); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_refer_visitor))) { 
                            update_option('translate_refer_visitor', 'Refer Visitor');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_refer_signup"><?php _e('Refer Signup ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_refer_signup" name="translate_refer_signup" value="<?php echo esc_attr($translate_refer_signup); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_refer_signup))) { 
                            update_option('translate_refer_signup', 'Refer Signup');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_new_register"><?php _e('New Register ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_new_register" name="translate_new_register" value="<?php echo esc_attr($translate_new_register); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_new_register))) { 
                            update_option('translate_new_register', 'Welcome Bonus');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_daily_login"><?php _e('Daily Login ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_daily_login" name="translate_daily_login" value="<?php echo esc_attr($translate_daily_login); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_daily_login))) { 
                            update_option('translate_daily_login', 'Welcome Bonus');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_publish_post"><?php _e('Publish Post ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_publish_post" name="translate_publish_post" value="<?php echo esc_attr($translate_publish_post); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_publish_post))) { 
                            update_option('translate_publish_post', 'Publish Post');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_approved_comment"><?php _e('Approved Comment ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_approved_comment" name="translate_approved_comment" value="<?php echo esc_attr($translate_approved_comment); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_approved_comment))) { 
                            update_option('translate_approved_comment', 'Post Comment');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_custom_post"><?php _e('Custom Post ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_custom_post" name="translate_custom_post" value="<?php echo esc_attr($translate_custom_post); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_custom_post))) { 
                            update_option('translate_custom_post', 'Custom Post');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_woocommerce_order"><?php _e('WooCommerce Order ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_woocommerce_order" name="translate_woocommerce_order" value="<?php echo esc_attr($translate_woocommerce_order); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_woocommerce_order))) { 
                            update_option('translate_woocommerce_order', 'Product Order');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_commission"><?php _e('Commission ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_commission" name="translate_commission" value="<?php echo esc_attr($translate_commission); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_commission))) { 
                            update_option('translate_commission', 'Commission');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_points_give"><?php _e('Points Give ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_points_give" name="translate_points_give" value="<?php echo esc_attr($translate_points_give); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_points_give))) { 
                            update_option('translate_points_give', 'Points Given');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_points_deduct"><?php _e('Points Deduct ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_points_deduct" name="translate_points_deduct" value="<?php echo esc_attr($translate_points_deduct); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_points_deduct))) { 
                            update_option('translate_points_deduct', 'Points Deducted');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_points_added"><?php _e('Points Add ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_points_added" name="translate_points_added" value="<?php echo esc_attr($translate_points_added); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_points_added))) { 
                            update_option('translate_points_added', 'Points Added');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_points_removed"><?php _e('Points Remove ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_points_removed" name="translate_points_removed" value="<?php echo esc_attr($translate_points_removed); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_points_removed))) { 
                            update_option('translate_points_removed', 'Points Removed');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_points_transferred"><?php _e('Points Transfer ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_points_transferred" name="translate_points_transferred" value="<?php echo esc_attr($translate_points_transferred); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_points_transferred))) { 
                            update_option('translate_points_transferred', 'Points Transferred');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="translate_points_received"><?php _e('Points Receive ( Type )', 'user-referral-free'); ?></label></th>
                    <td><input type="text" id="translate_points_received" name="translate_points_received" value="<?php echo esc_attr($translate_points_received); ?>"/></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($translate_points_received))) { 
                            update_option('translate_points_received', 'Points Received');
                        }
                    ?>
                </tr>
            </table>

            <table class="general-table">
                <tr class="settings-head">
                    <th>
                        <label for="head_label">
                            <strong>
                                <?php _e('Pagination', 'user-referral-free'); ?>
                            </strong>
                        </label>
                    </th>
                </tr>
                <tr>
                    <th><label for="all_history_count"><?php _e('All History', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="all_history_count" name="all_history_count" value="<?php echo esc_attr($all_history_count); ?>"/><span><?php _e('Table of all history count per page.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($all_history_count))) { 
                            update_option('all_history_count', '10');
                        }
                    ?>
                </tr>

                <tr>
                    <th><label for="top_users_count"><?php _e('Top Users', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="top_users_count" name="top_users_count" value="<?php echo esc_attr($top_users_count); ?>"/><span><?php _e('Table of top user counts per page.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($top_users_count))) { 
                            update_option('top_users_count', '10');
                        }
                    ?>
                </tr>
			</table>

            <table class="general-table">
                <tr class="settings-head">
                    <th>
                        <label for="head_label">
                            <strong>
                                <?php _e('Delete History', 'user-referral-free'); ?>
                            </strong>
                        </label>
                    </th>
                </tr>
                <tr class="premium">
                    <th><label for="last_history_type"><?php _e('Delete History', 'user-referral-free'); ?></label></th>
                    <td>
                        <select id="last_history_type" name="last_history_type">
                            <option value="oldest" <?php selected(esc_attr($last_history_type), 'oldest'); ?>><?php _e('Oldest History', 'user-referral-free'); ?></option>
                            <option value="newest" <?php selected(esc_attr($last_history_type), 'newest'); ?>><?php _e('Newest History', 'user-referral-free'); ?></option>
                        </select>
                        <span><?php _e('Select the type of history delete.', 'user-referral-free'); ?></span>
                    </td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($last_history_type))) { 
                            update_option('last_history_type', 'oldest');
                        }
                    ?>
                </tr>
                <tr class="premium">
                    <th><label for="last_history_count"><?php _e('Delete History', 'user-referral-free'); ?></label></th>
                    <td><input type="number" id="last_history_count" name="last_history_count" value="<?php echo esc_attr($last_history_count); ?>"/><span><?php _e('Delete the last specific amount of history.', 'user-referral-free'); ?></span></td>

                    <?php
                        // Auto filled if empty //
                        if(empty(esc_attr($last_history_count))) { 
                            update_option('last_history_count', '100');
                        }
                    ?>
                </tr>
			</table>

            <?php wp_nonce_field( 'scur_save_system_settings' ); ?>
            <div class="button-space">
                <input type="submit" value="Save Changes" class="button button-primary" />
                <input type="submit" value="Reset" style="color: #fff! important;" class="button button-reset" onclick="document.getElementsByName('signup_referral_points')[00].value='00';document.getElementsByName('visitor_referral_points')[00].value='00';document.getElementsByName('points_for_new_register')[00].value='00';document.getElementsByName('points_for_daily_login')[00].value='00';document.getElementsByName('points_for_post_published')[00].value='00';document.getElementsByName('points_limit_for_daily_post')[00].value='00';document.getElementsByName('points_for_comment_approved')[00].value='00';document.getElementsByName('custom_post_types')[00].value='post_type_1,post_type_2';document.getElementsByName('custom_post_types_point')[00].value='00';document.getElementsByName('points_type_for_woocommerce_order')[00].value='fixed';document.getElementsByName('fixed_points_for_woocommerce_order_completed')[00].value='00';document.getElementsByName('percentage_points_for_woocommerce_order_completed')[00].value='00';document.getElementsByName('minimum_woocommerce_order_amount_required')[00].value='00';document.getElementsByName('min_points_transfer_amount')[00].value='00';document.getElementsByName('max_points_transfer_amount')[00].value='00';document.getElementsByName('points_for_commission')[00].value='00';document.getElementsByName('translate_refer_visitor')[00].value='Refer Visitor';document.getElementsByName('translate_refer_signup')[00].value='Refer Signup';document.getElementsByName('translate_new_register')[00].value='Account Creation';document.getElementsByName('translate_daily_login')[00].value='Daily Login';document.getElementsByName('translate_publish_post')[00].value='Publish Post';document.getElementsByName('translate_approved_comment')[00].value='Post Comment';document.getElementsByName('translate_custom_post')[00].value='Custom Post';document.getElementsByName('translate_woocommerce_order')[00].value='Product Order';document.getElementsByName('translate_commission')[00].value='Commission';document.getElementsByName('translate_points_give')[00].value='Points Given';document.getElementsByName('translate_points_deduct')[00].value='Points Deducted';document.getElementsByName('translate_points_added')[00].value='Points Added';document.getElementsByName('translate_points_removed')[00].value='Points Removed';document.getElementsByName('translate_points_transferred')[00].value='Points Transferred';document.getElementsByName('translate_points_received')[00].value='Points Received';document.getElementsByName('all_history_count')[00].value='10';document.getElementsByName('top_users_count')[00].value='10';document.getElementsByName('last_history_count')[00].value='100';document.getElementsByName('last_history_type')[00].value='oldest';document.getElementsByName('signup_link')[0].value='<?php echo esc_attr(site_url('/register/')); ?>';"/>
            </div>
        </form>
    </div>
</div>
<?php }
// Save Settings //
function scur_save_system_settings() {
    check_admin_referer( 'scur_save_system_settings' );
    
    // Visitor & Signup //
    $visitor_referral_points = isset( $_POST['visitor_referral_points'] ) ? sanitize_text_field( $_POST['visitor_referral_points'] ) : '';
    if ( $visitor_referral_points ) {
        update_option( 'visitor_referral_points', $visitor_referral_points );
    }
    $signup_referral_points = isset( $_POST['signup_referral_points'] ) ? sanitize_text_field( $_POST['signup_referral_points'] ) : '';
    if ( $signup_referral_points ) {
        update_option( 'signup_referral_points', $signup_referral_points );
    }
    $signup_link = isset( $_POST['signup_link'] ) ? sanitize_text_field( $_POST['signup_link'] ) : '';
    if ( $signup_link ) {
        update_option( 'signup_link', $signup_link );
    }
    
    // Register & Login //
    $points_for_new_register = isset( $_POST['points_for_new_register'] ) ? sanitize_text_field( $_POST['points_for_new_register'] ) : '';
    if ( $points_for_new_register ) {
        update_option( 'points_for_new_register', $points_for_new_register );
    }
    $points_for_daily_login = isset( $_POST['points_for_daily_login'] ) ? sanitize_text_field( $_POST['points_for_daily_login'] ) : '';
    if ( $points_for_daily_login ) {
        update_option( 'points_for_daily_login', $points_for_daily_login );
    }
    
    // Default Post //
    $points_for_post_published = isset( $_POST['points_for_post_published'] ) ? sanitize_text_field( $_POST['points_for_post_published'] ) : '';
    if ( $points_for_post_published ) {
        update_option( 'points_for_post_published', $points_for_post_published );
    }
    $points_limit_for_daily_post = isset( $_POST['points_limit_for_daily_post'] ) ? sanitize_text_field( $_POST['points_limit_for_daily_post'] ) : '';
    if ( $points_limit_for_daily_post ) {
        update_option( 'points_limit_for_daily_post', $points_limit_for_daily_post );
    }
    $points_for_comment_approved = isset( $_POST['points_for_comment_approved'] ) ? sanitize_text_field( $_POST['points_for_comment_approved'] ) : '';
    if ( $points_for_comment_approved ) {
        update_option( 'points_for_comment_approved', $points_for_comment_approved );
    }

    // Custom Post //
    $custom_post_types = isset( $_POST['custom_post_types'] ) ? sanitize_text_field( $_POST['custom_post_types'] ) : '';
    if ( $custom_post_types ) {
        update_option( 'custom_post_types', $custom_post_types );
    }
    $custom_post_types_point = isset( $_POST['custom_post_types_point'] ) ? sanitize_text_field( $_POST['custom_post_types_point'] ) : '';
    if ( $custom_post_types_point ) {
        update_option( 'custom_post_types_point', $custom_post_types_point );
    }

    // WooCommerce Order //
    $points_type_for_woocommerce_order = isset( $_POST['points_type_for_woocommerce_order'] ) ? sanitize_text_field( $_POST['points_type_for_woocommerce_order'] ) : '';
    if ( $points_type_for_woocommerce_order ) {
        update_option( 'points_type_for_woocommerce_order', $points_type_for_woocommerce_order );
    }
    $fixed_points_for_woocommerce_order_completed = isset( $_POST['fixed_points_for_woocommerce_order_completed'] ) ? sanitize_text_field( $_POST['fixed_points_for_woocommerce_order_completed'] ) : '';
    if ( $fixed_points_for_woocommerce_order_completed ) {
        update_option( 'fixed_points_for_woocommerce_order_completed', $fixed_points_for_woocommerce_order_completed );
    }
    $percentage_points_for_woocommerce_order_completed = isset( $_POST['percentage_points_for_woocommerce_order_completed'] ) ? sanitize_text_field( $_POST['percentage_points_for_woocommerce_order_completed'] ) : '';
    if ( $percentage_points_for_woocommerce_order_completed ) {
        update_option( 'percentage_points_for_woocommerce_order_completed', $percentage_points_for_woocommerce_order_completed );
    }
    $minimum_woocommerce_order_amount_required = isset( $_POST['minimum_woocommerce_order_amount_required'] ) ? sanitize_text_field( $_POST['minimum_woocommerce_order_amount_required'] ) : '';
    if ( $minimum_woocommerce_order_amount_required ) {
        update_option( 'minimum_woocommerce_order_amount_required', $minimum_woocommerce_order_amount_required );
    }
    
    // Transfer Amount //
    $min_points_transfer_amount = isset( $_POST['min_points_transfer_amount'] ) ? sanitize_text_field( $_POST['min_points_transfer_amount'] ) : '';
    if ( $min_points_transfer_amount ) {
        update_option( 'min_points_transfer_amount', $min_points_transfer_amount );
    }
    $max_points_transfer_amount = isset( $_POST['max_points_transfer_amount'] ) ? sanitize_text_field( $_POST['max_points_transfer_amount'] ) : '';
    if ( $max_points_transfer_amount ) {
        update_option( 'max_points_transfer_amount', $max_points_transfer_amount );
    }
    
    // Commission //
    $points_for_commission = isset( $_POST['points_for_commission'] ) ? sanitize_text_field( $_POST['points_for_commission'] ) : '';
    if ( $points_for_commission ) {
        update_option( 'points_for_commission', $points_for_commission );
    }

    // Translation //
    $translate_refer_visitor = isset( $_POST['translate_refer_visitor'] ) ? sanitize_text_field( $_POST['translate_refer_visitor'] ) : '';
    if ( $translate_refer_visitor ) {
        update_option( 'translate_refer_visitor', $translate_refer_visitor );
    }
    $translate_refer_signup = isset( $_POST['translate_refer_signup'] ) ? sanitize_text_field( $_POST['translate_refer_signup'] ) : '';
    if ( $translate_refer_signup ) {
        update_option( 'translate_refer_signup', $translate_refer_signup );
    }
    $translate_new_register = isset( $_POST['translate_new_register'] ) ? sanitize_text_field( $_POST['translate_new_register'] ) : '';
    if ( $translate_new_register ) {
        update_option( 'translate_new_register', $translate_new_register );
    }
    $translate_daily_login = isset( $_POST['translate_daily_login'] ) ? sanitize_text_field( $_POST['translate_daily_login'] ) : '';
    if ( $translate_daily_login ) {
        update_option( 'translate_daily_login', $translate_daily_login );
    }
    $translate_publish_post = isset( $_POST['translate_publish_post'] ) ? sanitize_text_field( $_POST['translate_publish_post'] ) : '';
    if ( $translate_publish_post ) {
        update_option( 'translate_publish_post', $translate_publish_post );
    }
    $translate_approved_comment = isset( $_POST['translate_approved_comment'] ) ? sanitize_text_field( $_POST['translate_approved_comment'] ) : '';
    if ( $translate_approved_comment ) {
        update_option( 'translate_approved_comment', $translate_approved_comment );
    }
    $translate_custom_post = isset( $_POST['translate_custom_post'] ) ? sanitize_text_field( $_POST['translate_custom_post'] ) : '';
    if ( $translate_custom_post ) {
        update_option( 'translate_custom_post', $translate_custom_post );
    }
    $translate_woocommerce_order = isset( $_POST['translate_woocommerce_order'] ) ? sanitize_text_field( $_POST['translate_woocommerce_order'] ) : '';
    if ( $translate_woocommerce_order ) {
        update_option( 'translate_woocommerce_order', $translate_woocommerce_order );
    }
    $translate_commission = isset( $_POST['translate_commission'] ) ? sanitize_text_field( $_POST['translate_commission'] ) : '';
    if ( $translate_commission ) {
        update_option( 'translate_commission', $translate_commission );
    }
    $translate_points_give = isset( $_POST['translate_points_give'] ) ? sanitize_text_field( $_POST['translate_points_give'] ) : '';
    if ( $translate_points_give ) {
        update_option( 'translate_points_give', $translate_points_give );
    }
    $translate_points_deduct = isset( $_POST['translate_points_deduct'] ) ? sanitize_text_field( $_POST['translate_points_deduct'] ) : '';
    if ( $translate_points_deduct ) {
        update_option( 'translate_points_deduct', $translate_points_deduct );
    }
    $translate_points_added = isset( $_POST['translate_points_added'] ) ? sanitize_text_field( $_POST['translate_points_added'] ) : '';
    if ( $translate_points_added ) {
        update_option( 'translate_points_added', $translate_points_added );
    }
    $translate_points_removed = isset( $_POST['translate_points_removed'] ) ? sanitize_text_field( $_POST['translate_points_removed'] ) : '';
    if ( $translate_points_removed ) {
        update_option( 'translate_points_removed', $translate_points_removed );
    }
    $translate_points_transferred = isset( $_POST['translate_points_transferred'] ) ? sanitize_text_field( $_POST['translate_points_transferred'] ) : '';
    if ( $translate_points_transferred ) {
        update_option( 'translate_points_transferred', $translate_points_transferred );
    }
    $translate_points_received = isset( $_POST['translate_points_received'] ) ? sanitize_text_field( $_POST['translate_points_received'] ) : '';
    if ( $translate_points_received ) {
        update_option( 'translate_points_received', $translate_points_received );
    }
    
    
    // Pagination //
    $all_history_count = isset( $_POST['all_history_count'] ) ? sanitize_text_field( $_POST['all_history_count'] ) : '';
    if ( $all_history_count ) {
        update_option( 'all_history_count', $all_history_count );
    }
    $top_users_count = isset( $_POST['top_users_count'] ) ? sanitize_text_field( $_POST['top_users_count'] ) : '';
    if ( $top_users_count ) {
        update_option( 'top_users_count', $top_users_count );
    }

    // Delete History //
    $last_history_count = isset( $_POST['last_history_count'] ) ? sanitize_text_field( $_POST['last_history_count'] ) : '';
    if ( $last_history_count ) {
        update_option( 'last_history_count', $last_history_count );
    }
    $last_history_type = isset( $_POST['last_history_type'] ) ? sanitize_text_field( $_POST['last_history_type'] ) : '';
    if ( $last_history_type ) {
        update_option( 'last_history_type', $last_history_type );
    }

    // Check if empty //
    if (empty ( get_option('visitor_referral_points') ) || empty ( get_option('signup_referral_points') ) || empty ( get_option('signup_link') ) || empty ( get_option('points_for_new_register') ) || empty ( get_option('points_for_daily_login') ) || empty ( get_option('points_for_comment_approved') ) || empty ( get_option('custom_post_types') ) || empty ( get_option('custom_post_types_point') ) || empty ( get_option('points_type_for_woocommerce_order') ) || empty ( get_option('fixed_points_for_woocommerce_order_completed') ) || empty ( get_option('percentage_points_for_woocommerce_order_completed') ) || empty ( get_option('minimum_woocommerce_order_amount_required') ) || empty ( get_option('min_points_transfer_amount') ) || empty ( get_option('max_points_transfer_amount') ) || empty ( get_option('points_for_commission') ) || empty ( get_option('translate_refer_visitor') ) || empty ( get_option('all_history_count') ) || empty ( get_option('top_users_count') ) || empty ( get_option('last_history_count') ) || empty ( get_option('last_history_type') ) ) {
        wp_redirect( add_query_arg( array( 'page' => 'user-referral-free-settings', 'updated' => 'false', 'status' => 1 ), admin_url( 'admin.php' ) ) );
        exit;
    } else {
        wp_redirect( add_query_arg( array( 'page' => 'user-referral-free-settings', 'updated' => 'true', 'status' => 0 ), admin_url( 'admin.php' ) ) );
        exit;
    }
}
add_action('admin_post_scur_save_system_settings', 'scur_save_system_settings');
?>