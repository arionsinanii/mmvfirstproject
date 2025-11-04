<?php 
function tailwind_enqueue_styles() {
    wp_enqueue_style('tailwind', get_template_directory_uri() . '/assets/css/output.css');
}
add_action('wp_enqueue_scripts', 'tailwind_enqueue_styles');

