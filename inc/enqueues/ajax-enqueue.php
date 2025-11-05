<?php

function enqueue_ajax_comments() {
    wp_enqueue_script(
        'ajax-comments',
        get_template_directory_uri() . '/assets/js/ajax-comments.js'
    );

    wp_localize_script('ajax-comments', 'ajaxComments', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ajax-comment-nonce'),
        'messages' => array(
            'success' => 'Comment submitted successfully!',
            'error'   => 'There was an error submitting your comment.'
        )
    ));
    
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_comments');
