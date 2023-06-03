<?php
// Docs Menu //
function scur_add_docs() {
    add_submenu_page(
        'user-referral-free-settings',
        'Documentation',
        'Docs',
        'manage_options',
        'user-referral-free-docs',
        'scur_render_docs'
    );
}
add_action('admin_menu', 'scur_add_docs');

// Get the stylesheet //
scur_enqueue_styles();

// Documentation //
function scur_render_docs() { ?>
    <div class="section-divider">
        <div class="referral-system">
            <h2><?php _e('Documentation', 'user-referral-free'); ?></h2>
            
            <div class="docs-section">
                <h4><?php _e('Referral Link', 'user-referral-free'); ?></h4>
                <p>To display the referral link for the current user, use the following shortcode:</p>
                <code>[referral_link]</code>
                <p><strong>Output:</strong> <code><?php echo do_shortcode('[referral_link]'); ?></code></p>
                <p>When a user clicks on this link and signs up, the user will be recorded as the referer and the user who clicked on the link will be recorded as the referred user.</p>
            </div>
            
            <div class="docs-section">
                <h4><?php _e('Referral History', 'user-referral-free'); ?></h4>
                <p>To display all the referral history for the current user, use the following shortcode:</p>
                <code>[referral_history]</code>
                <p>This will display a table showing the users who have signed up using the current user's referral link and the date and time of each sign-up.</p>
                <p>You can also show specific history using the following shortcode:</p>
                <p><code>[referral_visitor_list_only]</code> = Show only referral visitor table list.</p>
                <p><code>[referral_signup_list_only]</code> = Show only referral signup table list.</p>
                <p><code>[referral_daily_login_list_only]</code> = Show only the daily login table list.</p>
                <p><code>[referral_published_post_list_only]</code> = Show only published post table list.</p>
                <p><code>[referral_approved_comment_list_only]</code> = Show only approved comment table list.</p>
                <p><code>[referral_custom_post_list_only]</code> = Show only custom post table list.</p>
                <p><code>[referral_woocommerce_order_list_only]</code> = Show only woocommerce order table list.</p>
                <p><code>[referral_commission_list_only]</code> = Show only the referral commission table list.</p>
            </div>
            
            <div class="docs-section">
                <h4><?php _e('User Points', 'user-referral-free'); ?></h4>
                <p>To display the current user's points balance, use the following shortcode:</p>
                <p><code>[referral_points] = Your current balance is 10 points.</code></p>
                <p><code>[referral_points_num] = 10.</code></p>
                <p>This will display the number of points the current user has earned through referrals.</p>
                <p>You can also show the numeric balance in front using this code: <code>&lt;?php echo $current_user->user_points; ?&gt;</code></p>
            </div>
            
            <div class="docs-section">
                <h4><?php _e('Points Table', 'user-referral-free'); ?></h4>
                <p>The <code>[referral_points_table]</code> shortcode allows you to display a table that shows the types of actions users can take to earn points, along with the number of points that will be awarded for each action.</p>

                <h5>Example:</h5>
                <ul>
                    <li><strong>Visitor Referral</strong>: Displays the number of points earned when someone clicks on the user's referral link.</li>
                    <li><strong>Signup Referral</strong>: Displays the number of points earned when someone signs up using the user's referral link.</li>
                    <li><strong>Daily Login</strong>: Displays the number of points earned when the user logs in daily.</li>
                    <li><strong>Publish Post</strong>: Displays the number of points earned when the users publish a post that is approved by an admin.</li>
                    <li><strong>Post Comment</strong>: Displays the number of points earned when the user's comment on a post is approved by an admin.</li>
                    <li><strong>Commission</strong>: Displays the number of points earned when someone referred by the user gains points.</li>
                </ul>

                <p>Each row displays the point value that is earned for each action.</p>
            </div>
            
            <div class="docs-section">
                <h4><?php _e('Give Points', 'user-referral-free'); ?></h4>
                <p>The point giveaway system is a feature that allows users to earn points by visiting a page with a shortcode.</p>
                <p>You can use the following code to give points to any user to implement on any page:</p>
                <p><code>[give_points user="current" type="Points Given" amount="10"]</code></p>
                <p><code>[give_points user="123" type="Points Given" amount="10"]</code></p>

                <p>You can also set the daily limit points given for the current or specific user.</p>

                <h5>Example:</h5>
                <p><code>[give_points limit="10" user="current" type="Points Given" amount="10"]</code></p>
                <p><code>[give_points limit="10" user="123" type="Points Given" amount="10"]</code></p>
            </div>
            
            <div class="docs-section">
                <h4><?php _e('Deduct Points', 'user-referral-free'); ?></h4>
                <p>You can deduct user points by using the following shortcode:</p>
                <p><code>[deduct_points user="current" type="Points Deducted" points="10"]</code> <p>
                <p><code>[deduct_points user="123" type="Points Deducted" points="10"]</code> <p>

                <h5>Attributes:</h5>
                <ul>
                    <li><strong>'user' (optional)</strong>: The ID of the user whose points are to be deducted. The default value is <code>"current"</code>, which deducts points from the currently logged-in user. If a user ID is specified, the points will be deducted from that user's account.</li>
                    <li><strong>'type' (optional)</strong>: Specify the deducted points type.</li>
                    <li><strong>'points' (required)</strong>: The number of points to be deducted.</li>
                </ul>

                <h5>Usage:</h5>
                <p>To use the <code>[deduct_points]</code> shortcode, simply add it to any post, page or text widget. You can customize the shortcode by specifying values for the user and points attributes.</p>
            </div>
            
            <div class="docs-section">
                <h4><?php _e('Transfer Points', 'user-referral-free'); ?></h4>
                <p>To transfer points from a current user to another user by user id, follow these steps:</p>
                <p>Place the <code>[transfer_points]</code> shortcode on a page or post where you want to display the points transfer form.</p>
                <h5>The form will include two fields:</h5>
                <ul>
                    <li><strong>User ID:</strong> Enter the ID of the recipient user to whom you want to transfer points.</li>
                    <li><strong>Amount:</strong> Enter the number of points you wish to transfer.</li>
                </ul>
                <p>Click the "Transfer" button to initiate the transfer process.</p>
            </div>
            
            <div class="docs-section-last">
                <h4><?php _e('Leaderboard', 'user-referral-free'); ?></h4>
                <php>To display the top <?php echo get_option('top_users_count'); ?> users by points with rank #1, and #2, you can use the following shortcode:</p>
                <code>[referral_top_users]</code>

                <h5>Example:</h5>
                <ul>
                    <li><strong>Rank</strong>: The user's rank is based on their points, starting from 1.</li>
                    <li><strong>User ID</strong>: The user's unique ID on the website.</li>
                    <li><strong>Name</strong>: The user's display name.</li>
                </ul>

                <p>This will display a table with the above columns.</p>
            </div>
        </div>
    </div>
<?php } ?>