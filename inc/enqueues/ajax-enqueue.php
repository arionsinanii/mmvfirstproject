<?php

function mytheme_enqueue_ajax_comments() {
    wp_enqueue_script(
        'ajax-comments',
        get_template_directory_uri() . '/assets/js/ajax-comments.js'
    );

    // Localize AJAX URL, nonce, and messages
    wp_localize_script('ajax-comments', 'ajaxComments', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ajax-comment-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_ajax_comments');
