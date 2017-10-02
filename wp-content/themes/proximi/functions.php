<?php
/**
 * proximi child theme.
 *
 * Place any custom functionality/code snippets here.
 *
 * @since proximi 1.0.0
 */
function jobify_child_styles() {
    wp_enqueue_style( 'jobify-child', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'jobify_child_styles', 20 );

// function apply_form_shortcode(){				
// 	get_template_part('template-parts/apply_form');			
// }
// add_shortcode('application_form', 'apply_form_shortcode');
/*
 * Add columns to exhibition post list
 */
