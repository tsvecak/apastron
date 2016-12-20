<?php
use Timber\Timber;
/*=====================================================
=            Enqueueing styles and scripts            =
=====================================================*/
function apastron_js_css() {
	// Enqueue scripts
    wp_enqueue_script( 'scripts', get_template_directory_uri() . '/scripts.js', array( 'jquery' ) );
    
    // Enqueue styles
    wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'apastron_js_css' );


// Hide admin bar
show_admin_bar(false);
