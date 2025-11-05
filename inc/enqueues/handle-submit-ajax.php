<?php

function ajax_submit_comment() {
    if ( ! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'ajax-comment-nonce') ) {
        wp_send_json_error(array('message' => 'Invalid nonce'));
        wp_die();
    }

    $comment_post_ID = isset($_POST['comment_post_ID']) ? intval($_POST['comment_post_ID']) : 0;
    $comment_parent  = isset($_POST['comment_parent']) ? intval($_POST['comment_parent']) : 0;
    $comment_content = isset($_POST['comment']) ? wp_kses_post($_POST['comment']) : ''; 
    $user            = wp_get_current_user();

    if ( ! $comment_post_ID || empty($comment_content) ) {
        wp_send_json_error(array('message' => 'Missing comment or post ID'));
        wp_die();
    }

    remove_filter('comment_duplicate_trigger', '__return_true', 10);

    $comment_data = array(
        'comment_post_ID'      => $comment_post_ID,
        'comment_content'      => $comment_content,
        'comment_parent'       => $comment_parent,
        'user_id'              => $user->ID,
        'comment_author'       => $user->ID ? $user->display_name : sanitize_text_field($_POST['author']),
        'comment_author_email' => $user->ID ? $user->user_email : sanitize_email($_POST['email']),
        'comment_approved'     => 0,
    );

    $comment_id = wp_new_comment($comment_data);

    if ( ! $comment_id ) {
        wp_send_json_error(array('message' => 'Failed to submit comment'));
        wp_die();
    }

    $comment = get_comment($comment_id);

    ob_start();
    wp_list_comments(array(
        'callback'    => 'generate_comment',
        'style'       => 'ul',
        'avatar_size' => 50,
        'echo'        => true,
        'max_depth'   => get_option('thread_comments_depth')
    ), array($comment));
    $comment_html = ob_get_clean();

    wp_send_json_success(array(
        'message'       => 'Comment submitted successfully.',
        'comment'       => $comment_html,
        'parent'        => $comment_parent,
        'comment_count' => get_comments_number($comment_post_ID)
    ));
}

add_action('wp_ajax_submit_comment', 'ajax_submit_comment');
add_action('wp_ajax_nopriv_submit_comment', 'ajax_submit_comment');
