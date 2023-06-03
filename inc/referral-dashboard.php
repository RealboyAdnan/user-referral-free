<?php
// Add Custom Dashboard Widget //
function scur_add_user_points_dashboard_widget() {
    if (current_user_can('administrator')) {
        wp_add_dashboard_widget(
            'scur-user-points-widget',
            'User Points',
            'scur_render_user_points_dashboard_widget'
        );
    }
}
add_action('wp_dashboard_setup', 'scur_add_user_points_dashboard_widget');

// Render User Points Dashboard Widget //
function scur_render_user_points_dashboard_widget() {
    echo '<div class="scur-user-points-widget">';
    scur_display_user_points_count();
    scur_display_user_points_graph();
    echo '</div>';
}

// Display User Points Count //
function scur_display_user_points_count() {
    $total_points = 0;

    // Get all users //
    $users = get_users();

    // Calculate total points //
    foreach ($users as $user) {
        $user_points = get_user_meta($user->ID, 'user_points', true);
        $total_points += intval($user_points);
    }

    // Output total points count //
    echo '<div class="dashboard-points"><strong>Total User Points:</strong> <span class="button-primary">' . number_format($total_points) . '</span></div>';
}

// User Points //
function scur_display_user_points_graph() {
    $args = array(
        'meta_key' => 'user_points',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'number' => get_option('top_users_count'), // Adjust the number of top users you want to display
        'fields' => array('ID', 'display_name') // Retrieve both user ID and display name
    );

    $users = get_users($args);
    $data = array();
    $data_labels = array();
    foreach ($users as $user) {
        $user_points = intval(get_user_meta($user->ID, 'user_points', true));
        if ($user_points > 0) {
            $data[] = $user_points;
            $data_labels[] = $user->ID;
            //$data_labels[] = $user->display_name;
            //$data_labels[] = $user->display_name . ' ( ' . $user->ID . ' )';
            //$data_labels[] = $user->display_name . ' ( ID: ' . $user->ID . ' )';
        }
    }

    echo '<canvas id="user-points-chart"></canvas>';

    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script>
        var ctx = document.getElementById("user-points-chart").getContext("2d");
        var chart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ' . json_encode($data_labels) . ',
                datasets: [{
                    label: "User Points",
                    data: ' . json_encode($data) . ',
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>';
}
?>