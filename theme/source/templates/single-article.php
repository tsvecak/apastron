<?php
/**
 * The template for displaying all single posts.
 *
 * @package boi_base
 */
/*
 * Dependencies
 */
use BOI\jaja\constants\PostType;
use BOI\jaja\Controller\ArticleController;
use BOI\jaja\Controller\PensionArticleController;
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
$data['page'] = new Page();
$data['pagename'] = $pagename;
$data['pageid'] = 'article';

$raw_article = Timber::get_post();
$articleController = new PensionArticleController($raw_article);
$breadcrumbGenerator = new BreadcrumbGenerator($post);
$data['breadcrumbs'] = $breadcrumbGenerator->getBreadcrumbs();

$categories = Timber::get_terms(Taxonomy::ARTICLE_CATEGORY);

foreach ($categories as $category) {
    if ($category->slug == Taxonomy::options(Taxonomy::ARTICLE_CATEGORY)[0]) {
        $data['taxonomy'] = $category;
        $acf_fields = [get_field('sections', $category->taxonomy . '_' . $category->term_id )];
        foreach ($acf_fields[0] as $section ) {
            if($section['acf_fc_layout']  == Section::TABS){
                $data['tabs'] = $section['tabs'];
            }
        }
        break;
    }
}
$data['article'] = $articleController->get_card();
$articleController->render_single($data);
