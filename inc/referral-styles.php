<?php
// Add stylesheet to plugin
function scur_enqueue_styles() {
    wp_enqueue_style( 'scur-style', plugins_url( '../assets/css/style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'scur_enqueue_styles' );
?>