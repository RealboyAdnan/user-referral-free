<?php
// Add javascript to plugin
function scur_enqueue_scripts() {
    wp_enqueue_script( 'scur-script', plugins_url( '../assets/js/script.js', __FILE__ ), array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'scur_enqueue_scripts' );
?>