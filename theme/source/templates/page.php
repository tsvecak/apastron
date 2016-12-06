<?php
/*
 * Template Name: Product Pages Twig
 * Description: Template created for product pages.

 *
 * @package boi_base
 */
/*
 * Dependencies
 */
use BOI\plugin\modules\posts\model\Page;
/*
 * Config
 */
Timber::$dirname = 'views/twigs';
if (defined('TWIG_CACHE_TIME')) {
    $cache = TWIG_CACHE_TIME;
} else {
    $cache = null;
}
/*
 * Get Context
 */
$data = Timber::get_context();
/*
 * Load the page
 */
$data['page'] = new Page();
/*
 * Render the template
 */
Timber::render('templates/products.twig', $data, $cache);
