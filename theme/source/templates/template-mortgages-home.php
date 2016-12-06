

<?php


/*
 * Template Name: Mortgages Home
 * Description: Template created for Mortgages homepage.
 *
 * @package boi_base
 */
/*
 * Dependencies
 */
use BOI\jaja\Controller\ArticleCategoryController;
use BOI\jaja\controllers\BreadcrumbGenerator;
use BOI\plugin\modules\posts\model\Page;
use Timber\Timber;
use BOI\jaja\constants\Taxonomy;
use BOI\jaja\constants\Section;



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
$data['pagename'] = $pagename;
$data['pageid'] = $pagename;
$breadcrumbGenerator = new BreadcrumbGenerator($post);
$data['breadcrumbs'] = $breadcrumbGenerator->getBreadcrumbs();

/*
 * Render the template
 */
Timber::render('templates/mortgages-home.twig', $data); //, $cache

