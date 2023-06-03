<?php
// Get daily login list only //
function scur_referral_daily_login_list_only_shortcode($atts) {
    $tl_daily_login = get_option('translate_daily_login');

    // Get the user ID from the shortcode attribute or current user ID //
    $user_id = isset($atts['user_id']) && !empty($atts['user_id']) ? absint($atts['user_id']) : get_current_user_id();

    // Sanitize and validate user ID //
    if ($user_id <= 0) {
        return 'Invalid user ID!';
    }

    // Get the daily login records for the user //
    global $wpdb;
    $table_name = $wpdb->prefix . 'referral_history';
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_id = %d AND type = %s AND points >= 1 ORDER BY created_at DESC",
            $user_id,
            $tl_daily_login
        )
    );

    // Check if there are no daily login records for the user //
    if (empty($results)) {
        return "<p class='scur_no_login_history_found'>No login history found!</p>";
    }

    // Set the number of records to display per page //
    $per_page = get_option('all_history_count');

    // Get the current page number //
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;

    // Calculate the offset based on the current page number and number of records to display per page //
    $offset = ($paged - 1) * $per_page;

    // Get the total number of records. //
    $total_records = count($results);

    // Calculate the total number of pages. //
    $total_pages = ceil($total_records / $per_page);

    // Limit the results to the current page. //
    $results = array_slice($results, $offset, $per_page);

    // Build the HTML output for the daily login table. //
    $output = '<table class="referral-history"><thead><tr><th>Type</th><th>Points</th><th>Date</th></tr></thead><tbody>';
    foreach ($results as $row) {
        $output .= '<tr>';
        $output .= '<td>' . esc_html($row->type) . '</td>';
        $output .= '<td>' . number_format($row->points) . '</td>';
        //$output .= '<td>' . date_i18n(get_option('date_format') . ' \a\t ' . get_option('time_format'), strtotime($row->created_at)) . '</td>';
        $output .= '<td>' . esc_html(str_replace(array('am', 'pm'), array('AM', 'PM'), date_i18n(get_option('date_format') . ' \a\t ' . get_option('time_format'), strtotime($row->created_at)))) . '</td>';
        $output .= '</tr>';
    }
    $output .= '</tbody></table>';

    // Build the HTML output for the pagination links. //
    $page_links = paginate_links(array(
        'base' => add_query_arg('paged', '%#%'),
        'format' => '',
        'prev_text' => __('&laquo; Prev'),
        'next_text' => __('Next &raquo;'),
        'total' => $total_pages,
        'current' => $paged,
        'add_args' => array(
            'user_id' => $user_id, // Preserve other query parameters //
        ),
    ));

    if ($page_links) {
        $output .= '<div class="referral-pagination">' . $page_links . '</div>';
    }

    return $output;
}
add_shortcode('referral_daily_login_list_only', 'scur_referral_daily_login_list_only_shortcode');
?>