<?php
// Show user points table //
function scur_points_table_shortcode( $atts ) {
    $output = '<table class="referral-history"><thead><tr><th>Type</th><th>Segment</th><th>Points</th></tr></thead><tbody>';
    
    $data_1 = get_option('visitor_referral_points');
    $data_2 = get_option('signup_referral_points');
    $data_3 = get_option('points_for_daily_login');
    $data_4 = get_option('points_for_comment_approved');
    $data_5 = get_option('points_for_commission');

    if($data_1 == '0') {} else {
        $output .= '<tr>';
        $output .= '<td>Visitor Referral</td>';
        $output .= '<td>When someone clicks on your referral link.</td>';
        $output .= '<td>' . get_option('visitor_referral_points') . '</td>';
        $output .= '</tr>';
    }

    if($data_2 == '0') {} else {
        $output .= '<tr>';
        $output .= '<td>Signup Referral</td>';
        $output .= '<td>When someone signup using your referral link.</td>';
        $output .= '<td>' . get_option('signup_referral_points') . '</td>';
        $output .= '</tr>';
    }

    if($data_3 == '0') {} else {
        $output .= '<tr>';
        $output .= '<td>Daily Login</td>';
        $output .= '<td>When you have login per day.</td>';
        $output .= '<td>' . get_option('points_for_daily_login') . '</td>';
        $output .= '</tr>';
    }

    if($data_4 == '0') {} else {
        $output .= '<tr>';
        $output .= '<td>Post Comment</td>';
        $output .= '<td>When you put a comment on any post.</td>';
        $output .= '<td>' . get_option('points_for_comment_approved') . '</td>';
        $output .= '</tr>';
    }

    if($data_5 == '0') {} else {
        $output .= '<tr>';
        $output .= '<td>Commission</td>';
        $output .= '<td>When your referred user earn points you will received a percentage.</td>';
        $output .= '<td>' . get_option('points_for_commission') . '%</td>';
        $output .= '</tr>';
    }

    if ($data_1 == '0' && $data_2 == '0' && $data_3 == '0' && $data_4 == '0' && $data_5 == '0') {
        $output = "There is no segment available!";
    }

    $output .= '</tbody></table>';

    return $output;
}
add_shortcode( 'referral_points_table', 'scur_points_table_shortcode' );
?>