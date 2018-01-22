<?php
/*
 * Template Name: Apastron Home
 */

/* Dependencies */
use Timber\Timber;

/* Config */
Timber::$dirname = 'views/';
if (defined('TWIG_CACHE_TIME')) {
    $cache = TWIG_CACHE_TIME;
} else {
    $cache = null;
}
/* Get Context */
$data = Timber::get_context();

/* Render the template */
Timber::render('index.twig', $data);