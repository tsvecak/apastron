<?php
use Timber\Timber;
/*=====================================================
=            Enqueueing styles and scripts            =
=====================================================*/
function my_javascripts() {
    wp_enqueue_script( 'scripts', get_template_directory_uri() . '/scripts.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'my_javascripts' );


// Hide admin bar
show_admin_bar(false);
