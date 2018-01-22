<?php
use Timber\Timber;
/*=====================================================
=            Enqueueing styles and scripts            =
=====================================================*/
function apastron_js_css() {
	// Enqueue scripts
	wp_enqueue_script('vendor-scripts', get_stylesheet_directory_uri() . '/assets/vendor/js/vendor.js', false, true);
    wp_enqueue_script( 'scripts', get_template_directory_uri() . '/scripts.js', array( 'jquery' ) );
    wp_enqueue_script('apastronNG', get_stylesheet_directory_uri() . '/assets/js/app/apastron.js', false, true);

    
    // Enqueue styles
    wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'apastron_js_css' );


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

// Hide admin bar
show_admin_bar(false);
