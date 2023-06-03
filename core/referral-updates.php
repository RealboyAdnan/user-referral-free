<?php
// Check latest version //
function scur_check_plugin_update() {
    session_start();

    if (isset($_POST['VersionHidden'])) {
        $_SESSION['VersionHide'] = true;
    }

    if (!isset($_SESSION['VersionHide'])) {
        $current_version = '1.0'; // Replace with the current version of your plugin
        $api_url = 'https://softclever.com/ur-license/'; // Replace with the URL of your update check file

        $response = wp_remote_get($api_url);
        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if (isset($data['version']) && version_compare($data['version'], $current_version, '>')) {
                // The latest version is higher than the current version
                add_action('admin_notices', 'scur_display_update_notice');
            }
        }
    }
}
add_action('admin_init', 'scur_check_plugin_update');

// Display update notice //
function scur_display_update_notice() {
    global $pagenow;
    if (is_admin() && $pagenow === 'index.php' && current_user_can('manage_options')) {
        session_start();

        if (isset($_POST['VersionHidden'])) {
            $_SESSION['VersionHide'] = true;
        }

        if (!isset($_SESSION['VersionHide'])) {
            $notice = sprintf(
                '<div class="notice notice-info version-notice is-dismissible">
                    <p>A new updated version is available for <strong>User Referral</strong> plugin. <a href="%s" target="_blank">Download Now</a></p>
                    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>',
                'https://softclever.com/downloads/'
            );
            echo $notice;
        }
    }
}

// Add JavaScript/jQuery code to handle the notice dismissal //
function scur_update_notice_script() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var closeButton = document.querySelector('.version-notice .notice-dismiss');

            closeButton.addEventListener('click', function() {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', window.location.href);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('VersionHidden=1');
                var notice = document.querySelector('.version-notice');
                notice.style.display = 'none';
            });
        });
    </script>
    <?php
}

// Add the script only when the notice is being displayed
if (isset($_COOKIE['scur_update_notice_hidden']) || !isset($_GET['scur_update_notice']) || $_GET['scur_update_notice'] !== 'hide') {
    add_action('admin_footer', 'scur_update_notice_script');
}
?>