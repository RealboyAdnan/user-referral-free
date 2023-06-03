<?php
// Help Menu //
function scur_add_help() {
    add_submenu_page(
        'user-referral-free-settings',
        'Support Center',
        'Help',
        'manage_options',
        'user-referral-free-help',
        'scur_render_help'
    );
}
add_action('admin_menu', 'scur_add_help');

// Get the stylesheet //
scur_enqueue_styles();

// Referral Help //
function scur_render_help() { ?>
    <div class="section-divider">
        <div class="referral-system">
            <h2><?php _e('Support Center', 'user-referral-free'); ?></h2>

            <p>If you need any help or support with the referral system, please email me at <a href="mailto:contact@softclever.com">contact@softclever.com</a></p>

            <p>If you'd like to support the project, you can treat me to a coffee at <a href="https://www.buymeacoffee.com/RealboyAdnan" target="_blank">https://www.buymeacoffee.com/RealboyAdnan</a></p>

            <p>If you require urgent assistance, please call me at <a href="tel:+8801710-900622">+8801710-900622</a> (WhatsApp)</p>
        </div>
    </div>
    <?php
}
?>
