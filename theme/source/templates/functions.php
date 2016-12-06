<?php
use \BOI\jaja\constants\PostType;
use BOI\jaja\constants\Taxonomy;
use BOI\jaja\constants\ThumbnailSize;
use BOI\jaja\factory\BoiApiManagerFactory;
use BOI\jaja\util\JaJaLibraryImporter;
use \BOI\plugin\modules\posts\Factory;
use BOI\plugin\modules\posts\Toolkit;
use Timber\Timber;


$file_path = locate_template('business-logic/util/JaJaLibraryImporter.php');
if (file_exists($file_path)){
    include_once $file_path;
}
$jaJaLibraryImporter = new JaJaLibraryImporter();

/*=====================================================
=            Enqueueing styles and scripts            =
=====================================================*/
// Registering custom CSS
function child_theme_css()
{
    global $wp_styles;

    // Deenqueing the child theme css
    wp_dequeue_style('boi-style');
    wp_deregister_style('boi-style');

    // Registering new app-ppg.css and again the child theme css with the app-ppg.css as a dependency
    wp_register_style('base-theme-styles', get_template_directory_uri() . '/assets/dist/css/app-ppg.css');
    wp_register_style('boi-style', get_stylesheet_directory_uri() . '/style.css', array('base-theme-styles'));

    // Re enqueuing both styles
    wp_enqueue_style('base-theme-styles');
    wp_enqueue_style('boi-style');

    // Enqueuing IE9 files
    wp_enqueue_style( 'base-theme-ie9-app-ppg-custom', get_template_directory_uri() . '/assets/dist/css/ie9-app-ppg-custom.css');
    wp_style_add_data( 'base-theme-ie9-app-ppg-custom', 'conditional', 'lt IE 10' );

    wp_enqueue_style( 'base-theme-ie9-app-ppg-foundation', get_template_directory_uri() . '/assets/dist/css/ie9-app-ppg-foundation.css');
    wp_style_add_data( 'base-theme-ie9-app-ppg-foundation', 'conditional', 'lt IE 10' );

    // Enqueuing css generated for IE
    wp_enqueue_style( 'ie-style', get_stylesheet_directory_uri() . "/assets/ieCSS/style.css");
    wp_style_add_data( 'ie-style', 'conditional', 'lt IE 10' );

    wp_enqueue_style( 'ie-style2', get_stylesheet_directory_uri() . "/assets/ieCSS/style-part1.css");
    wp_style_add_data( 'ie-style2', 'conditional', 'lt IE 10' );
}
add_action('wp_enqueue_scripts', 'child_theme_css', 100);


function removing_base_theme_css(){


    wp_dequeue_style('boi-styles-PRODUCTION');
    wp_dequeue_style('boi-styles-PRODUCTION-foundation-ie9');
    wp_dequeue_style('boi-styles-PRODUCTION-custom-ie9');

    wp_deregister_style('boi-styles-PRODUCTION');
    wp_deregister_style('boi-styles-PRODUCTION-foundation-ie9');
    wp_deregister_style('boi-styles-PRODUCTION-custom-ie9');

}

add_action('wp_enqueue_scripts', 'removing_base_theme_css', 11);


// Enqueueing Scripts
function child_theme_scripts(){
    $parentScripts = [];
    $parentScripts[] = 'boi-scripts-PRODUCTION';


  wp_enqueue_script('vendor-scripts', get_stylesheet_directory_uri() . '/assets/vendor/js/vendor.js', $parentScripts, false, true);

    if (is_singular(PostType::ARTICLE)) {
        wp_enqueue_script('ppMigration-app', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationPension.app.js', $parentScripts, false, true);
        wp_enqueue_script('related-articles-directive', get_stylesheet_directory_uri() . '/assets/js/app/related-articles.directive.js', $parentScripts, false, true);
        wp_enqueue_script('select-taxonomy-directive', get_stylesheet_directory_uri() . '/assets/js/app/drop-down-redirect.directive.js', $parentScripts, false, true);
        wp_enqueue_script('back-link-directive', get_stylesheet_directory_uri() . '/assets/js/app/back-link.directive.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-services', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationServices.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-filters', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationFilters.js', $parentScripts, false, true);
    }else if (is_page_template('template-pensions-articles.php')) {
        wp_enqueue_script('ppMigration-app', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationPension.app.js', $parentScripts, false, true);
        wp_enqueue_script('select-taxonomy-directive', get_stylesheet_directory_uri() . '/assets/js/app/drop-down-redirect.directive.js', $parentScripts, false, true);
        wp_enqueue_script('search-articles-directive', get_stylesheet_directory_uri() . '/assets/js/app/search-articles.directive.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-services', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationServices.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-filters', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationFilters.js', $parentScripts, false, true);
    }else if (is_page_template('template-pensions-home.php') || is_page_template('template-retirement-planning-calculator.php')) {
        wp_enqueue_script('ppMigration-app', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationPension.app.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-services', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationServices.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-filters', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationFilters.js', $parentScripts, false, true);
        wp_enqueue_script('segment-cards-directive', get_stylesheet_directory_uri() . '/assets/js/app/segment-cards.directive.js', $parentScripts, false, true);
        wp_enqueue_script('segment-sentences-directive', get_stylesheet_directory_uri() . '/assets/js/app/segment-sentences.directive.js', $parentScripts, false, true);
        wp_enqueue_script('related-articles-directive', get_stylesheet_directory_uri() . '/assets/js/app/related-articles.directive.js', $parentScripts, false, true);
        wp_enqueue_script('slick-slides-directive', get_stylesheet_directory_uri() . '/assets/js/app/slick-slides.directive.js', $parentScripts, false, true);
        wp_enqueue_script('calculator-pension-directive', get_stylesheet_directory_uri() . '/assets/js/app/calculator-pension.directive.js', $parentScripts, false, true);
        wp_enqueue_script('segment-articles-directive', get_stylesheet_directory_uri() . '/assets/js/app/segment-articles.directive.js', $parentScripts, false, true);
    }else if (is_page_template('template-mortgages-home.php')) {
        wp_enqueue_script('ppMigration-app', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationMortgage.app.js', $parentScripts, false, true);
        wp_enqueue_script('mortgage-segment-cards', get_stylesheet_directory_uri() . '/assets/js/app/mortgage-segment-cards.directive.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-services', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationServices.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-filters', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationFilters.js', $parentScripts, false, true);
    }else if (is_page_template('template-mortgages-calculator.php') || is_page_template('template-mortgage-calculator-centre.php')) {
        wp_enqueue_script('ppMigration-app', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationMortgage.app.js', $parentScripts, false, true);
        wp_enqueue_script('mortgage-segment-toolkit', get_stylesheet_directory_uri() . '/assets/js/app/mortgage-segment-toolkit.directive.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-services', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationServices.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-filters', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationFilters.js', $parentScripts, false, true);
    }else if (is_page_template('template-mortgages-articles.php')) {
        wp_enqueue_script('ppMigration-app', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationMortgage.app.js', $parentScripts, false, true);
        wp_enqueue_script('mortgage-segment-toolkit', get_stylesheet_directory_uri() . '/assets/js/app/mortgage-segment-toolkit.directive.js', $parentScripts, false, true);
        wp_enqueue_script('search-mortgage-articles-directive', get_stylesheet_directory_uri() . '/assets/js/app/search-mortgage-articles.directive.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-services', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationServices.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-filters', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationFilters.js', $parentScripts, false, true);
    }else if (is_page_template('template-mortgage-protection-calculator.php')) {
        wp_enqueue_script('ppMigration-app', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationMortgage.app.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-services', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationServices.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-filters', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationFilters.js', $parentScripts, false, true);
        wp_enqueue_script('slick-slides-directive', get_stylesheet_directory_uri() . '/assets/js/app/slick-slides.directive.js', $parentScripts, false, true);
        wp_enqueue_script('calculator-mortgage-protection-directive', get_stylesheet_directory_uri() . '/assets/js/app/calculator-mortgage-protection.directive.js', $parentScripts, false, true);
    }else if (is_singular(PostType::MORTGAGE_ARTICLE)) {
        wp_enqueue_script('ppMigration-app', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationMortgage.app.js', $parentScripts, false, true);
        wp_enqueue_script('mortgage-related-articles-directive', get_stylesheet_directory_uri() . '/assets/js/app/mortgage-related-articles.directive.js', $parentScripts, false, true);
        wp_enqueue_script('mortgage-segment-toolkit', get_stylesheet_directory_uri() . '/assets/js/app/mortgage-segment-toolkit.directive.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-services', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationServices.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-filters', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationFilters.js', $parentScripts, false, true);
    }else if (is_page_template('template-mortgages-content.php')) {
        wp_enqueue_script('ppMigration-app', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationMortgage.app.js', $parentScripts, false, true);
        wp_enqueue_script('mortgage-segment-toolkit', get_stylesheet_directory_uri() . '/assets/js/app/mortgage-segment-toolkit.directive.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-services', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationServices.js', $parentScripts, false, true);
        wp_enqueue_script('ppMigration-filters', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationFilters.js', $parentScripts, false, true);
    }else{
        wp_enqueue_script('ppMigration-app', get_stylesheet_directory_uri() . '/assets/js/app/ppMigrationGeneral.app.js', $parentScripts, false, true);
    }

  wp_enqueue_script('child-scripts', get_stylesheet_directory_uri() . '/scripts.js', $parentScripts, false, true);


    /**
     * Define theme path in js to allow theme to be portable
     */
    wp_localize_script( 'ppMigration-app', 'theme', array(
        'path' => get_stylesheet_directory_uri()
    ));

    //Enqueueing Chosen
    wp_enqueue_script('boi-chosen', get_template_directory_uri() . '/assets/dist/js/chosen.jquery.min.js', array('jquery'), false, true );
    wp_enqueue_script('wp-boi-chosen', get_template_directory_uri() . '/assets/dist/js/boi_chosen.js', array('boi-chosen', 'jquery'), false, true);
    // Inserting JS object with parameters that will be used on boi_chosen.js
    wp_localize_script( 'wp-boi-chosen', 'boi_chosen_object', array(' '));
}
add_action('wp_enqueue_scripts', 'child_theme_scripts');
/*-----  End of Enqueueing styles and scripts  ------*/

/**
 * Display At a Glance post type dashboard item
 */
add_action( 'dashboard_glance_items', 'cpad_at_glance_content_table_end' );
function cpad_at_glance_content_table_end() {
    $args = array(
        'public' => true,
        '_builtin' => false
    );
    $output = 'object';
    $operator = 'and';

    $post_types = get_post_types( $args, $output, $operator );
    foreach ( $post_types as $post_type ) {
        $num_posts = wp_count_posts( $post_type->name );
        $num = number_format_i18n( $num_posts->publish );
        $text = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish ) );
        if ( current_user_can( 'edit_posts' ) ) {
            $output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $num . ' ' . $text . '</a>';
            echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
        } else {
            $output = '<span>' . $num . ' ' . $text . '</span>';
            echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
        }
    }
}
/**
 * END : Display At a Glance
 */

/*==================================================
=            Register Rate widgets area            =
==================================================*/
function reuters_widgets_area() {
    register_sidebar( array(
        'name'          => 'Help Region Widgets',
        'id'            => 'help_region_widgets_area',
        'before_widget' => '<aside class="helpSection__widgets"><div class="boi-card-widget %2$s">',
        'after_widget'  => '</div></div></aside>',
        'before_title'  => '<h4 class="js-togglerCollapseOnMobile">',
        'after_title'   => '</h4><div class="js-collapseOnMobile">',
    ) );
}
add_action( 'widgets_init', 'reuters_widgets_area');

/*=====  End of Section comment  ======-*/

add_filter( 'timber_context', 'jaja_theme_context'  );

function jaja_theme_context( $context ) {
    // GTM
    if( defined( "BOI_GTM_CODE" ) ) {
        $context['BOI_GTM_CODE'] = BOI_GTM_CODE;
    }
    // Theme options
    $options = [];
    // Logos
    $logos = [];
    $logos['main']    = Factory::getField('boi_baseThemeLogo', 'options');
    $logos['footer']  = Factory::getField('boi_baseThemeFooterLogo', 'options');
    $options['logos'] = $logos;
    // Header
    $header = [];
    $header['icon_list'] = Factory::getField('header_icon_list', 'options');
    $header['icon_sets'] = Factory::getField('header_icon_sets', 'options');
    $options['header'] = $header;
    // Other
    $options['social_icons']    = Factory::getField('boi_baseThemeFooterSocialIcons', 'options');
    $options['regulatory_text'] = Factory::getField('boi_baseThemeRegulatoryText', 'options');
    $options['copyright_text']  = Factory::getField('boi_baseThemeCopyrightText', 'options');
    // Assign options
    $context['theme']  = $options;
    return $context;
}

add_filter('get_twig', 'addTwigFilters');

function addTwigFilters($twig) {
    /* this is where you can add your own fuctions to twig */
    $twig->addExtension(new Twig_Extension_StringLoader());
    /*Debug*/
    $twig->addExtension(new Twig_Extension_Debug());
    /* Markdown */
    $twig->addFilter(new Twig_SimpleFilter('markdown', 'markdownFilter'));
    /* To Button */
    $twig->addFilter(new Twig_SimpleFilter('button', 'buttonFilter'));
    /* To Widget */
    $twig->addFilter(new Twig_SimpleFilter('widget', 'widgetFilter'));
    /* Footnotes */
    $twig->addFilter(new Twig_SimpleFilter('footnotes', 'footnoteFilter'));
    $twig->addFunction(new Twig_SimpleFunction('footnotes', 'footnotesFunction'));
    /* Performance Timer */
    $twig->addFunction(new Twig_SimpleFunction('startTimer', 'startTimerFunction'));
    $twig->addFunction(new Twig_SimpleFunction('stopTimer', 'stopTimerFunction'));
    $twig->addFunction(new Twig_SimpleFunction('getTimer', 'getTimerFunction'));
    /* returns classname of object */
    $twig->addFunction(new Twig_SimpleFunction('class', 'getClassname'));

    return $twig;
}

function markdownFilter($text) {
    $parser = new Parsedown();
    $parser->setMarkupEscaped(false);
    return $parser->text($text);
}

function widgetFilter($id) {
    $wid   = 'widget_' . $id;
    $parts = explode('-', $wid);
    $intID = array_pop($parts);
    $optionsName = implode('-', $parts);
    $className   = null;
    $widget_factory = $GLOBALS['wp_widget_factory'];

    foreach ($widget_factory->widgets as $key => $item) {
        if (!is_null($className)) continue;

        if ($item->option_name == $optionsName) {
            $className = $key;
        }
    }
    if ($className) {
        $opts = get_option($optionsName);
        $args = array_merge(Toolkit::get($opts, $intID, []), Toolkit::rtnArray(Factory::getFields('widget_' . $id)));
        ob_start();
        the_widget($className, $args, $args);
        $widget = ob_get_clean();
        return $widget;
    }
    return $id;
}

function faFilter($text, $class = '') {
    $template = '<span class="%s"></span>';
    $classes  = ['fa'];
    if (preg_match('/\[([a-z0-9\-\_]+)\]/i', $text, $match)) {
        $classes[] = 'fa-' . $match[1];
        $extra     = is_array($class) ? $class : explode(' ', $class);

        return sprintf($template, implode(' ', array_merge($classes, $extra)));
    }
    return $text;
}

function buttonFilter($data, $event = []) {
    $btn = new \BOI\plugin\modules\posts\model\pageComponent\Button();
    $btn->import($data);
    if (!empty($event)) {
        $btn->registerEvent($event);
    }
    return $btn;
}

function footnoteFilter($text, $notes) {
    return \BOI\plugin\modules\posts\model\pageSection\Footnotes::parseFootnotes($text, $notes);
}

function footnotesFunction() {
    $notes = new \BOI\plugin\modules\posts\model\pageSection\Footnotes();
    return $notes->getNotes();
}

$total_time = [];
$previous_time = [];

function startTimerFunction ($label) {
    global $total_time, $previous_time;
    $now = microtime(true);

    if (!array_key_exists($label, $total_time)) {
        $total_time[$label] = 0;
    }

    $previous_time[$label] = $now;
    return;
}

function stopTimerFunction($label) {
    global $total_time, $previous_time;
    $now = microtime(true);
    $out = $now - $previous_time[$label];
    $total_time[$label] .= $out;
    return;
}


function getTimerFunction($label = null) {
    global $total_time;
    $times = $total_time;
    $times['__total_time'] = array_sum($total_time);
    return is_null($label) ? $times : $times[$label];
}

function getClassname($object) {
    return is_object($object) ? (new \ReflectionClass($object))->getShortName() : null;
}



add_filter('table_generator_shortcode_data', 'filter_shortcode_country_flag');

function filter_shortcode_country_flag($data)
{
    if ($data['table']->table_name == 'country_currencies') {
        foreach ($data['headers'] as &$header) {
            if ($header['label'] == 'country_code') {
                $header['label'] = 'flag';
            }
        }

        //Adding new column "flag" to each row
        foreach ($data['rows'] as &$row) {
            $code = isset($row['country_code']) ? $row['country_code'] : '';
            $row['country_code'] = "<div class='flag-boi'><span class='flag-icon flag-icon-{$code}'></span></div>";
        }
    }
    return $data;
}


//Hooking into the TableDropDown field
if (class_exists("\\BOI\\plugin\\modules\\gravityforms\\boi_fields\\TableDropDown")) {
    add_filter(\BOI\plugin\modules\gravityforms\boi_fields\TableDropDown::$hook_filter_data_before_compile, 'filter_table_drop_down_country_currency',10, 6);
}

/**
 * Filtering table drop down generated for table currency-by-country,
 * to define some currencies as first options
 *
 * @param $data
 * @param $input
 * @param $field
 * @param $value
 * @param $lead_id
 * @param $form_id
 *
 * @return mixed
 */
function filter_table_drop_down_country_currency($data, $input, $field, $value, $lead_id, $form_id)
{
    if (strpos($field->cssClass, 'currency-by-country') > -1) {
        $options = $data['options'];
        //United Kingdom, United States, Australia, Canada, Poland, New Zeland, South Africa, Switzerland, United Arab Emirates
        $pop_these_options = array('AED', 'CHF', 'ZAR', 'NZD', 'PLN', 'CAD', 'AUD', 'USD', 'GBP');
        foreach ($pop_these_options as $pop) {
            foreach ($data['options'] as $ind => $opt) {
                if ($opt['value'] == $pop) {
                    unset($options[$ind]);
                    $options = array($ind => $opt) + $options;
                }
            }
        }
        $data['options'] = $options;
    }

    return $data;
}


//Filtering FX Currency Rates table (Generated by Lookup Table Generator Shortcode)
if(class_exists("\\BOI\\plugin\\modules\\tool\\BoiTableGenerator")){
    add_filter(\BOI\plugin\modules\tool\BoiTableGenerator::$table_generator_shortcode_hook, 'filter_shortcode_table_generator_fx_currency_rates');
    add_filter(\BOI\plugin\modules\tool\BoiTableGenerator::$table_generator_shortcode_hook, 'filter_shortcode_table_generator_global_mortgage_rates_and_formulas_update');
}

function filter_shortcode_table_generator_fx_currency_rates($data)
{
    if ($data['table']->table_name == "global_mortgage_rates_and_formulas_update") {

        //if (isset($data['filters']['tier'])) {

            //Defining "before" for filter options
            $data['filters']['tier']['before'] = '< €';

            //Defining "1250" as selected option when having this tier filter
            $data['extra_html'] = "
                  <script>
                      jQuery(document).ready(function(){
                          var tier_field = jQuery('#{$data['table_id']} .custom-filter .mortgages_type_name select');
                          tier_field.val('1250').trigger('change');
                          if( jQuery().chosen != undefined ){
                              tier_field.trigger('chosen:updated');
                          }
                      });
                  </script>";
        //}

        //Reordering rows to display first United Kingdom / USA them all others
        $countries = array('United States', 'United Kingdom');

        foreach ($countries as $country) {
            $rows = $data['rows'];
            foreach ($rows as $i => $row) {
                if (isset($row['country'])) {
                    if ($row['country'] == $country) {
                        $aux = $row;
                        unset($data['rows'][$i]);
                        array_unshift($data['rows'], $aux);
                    }
                }
            }
        }
    }

    return $data;
}

/*********
** Updating default selected option Mortgage Rates table
*********/
function filter_shortcode_table_generator_global_mortgage_rates_and_formulas_update($data)
{
    if ($data['table']->table_name == "global_mortgage_rates_and_formulas_update") {

        if (isset($data['filters']['ltv_rule'])) {

            //Defining "before" for filter options
            // $data['filters']['ltv_rule']['before'] = '< €';

            //Defining "1250" as selected option when having this ltv_rule filter
            $data['extra_html'] = "
                  <script>
                      jQuery(document).ready(function(){
                          var ltv = jQuery('#{$data['table_id']} .custom-filter .ltv_rule select');
                          ltv.val('>80%').trigger('change');
                          if( jQuery().chosen != undefined ){
                              ltv.trigger('chosen:updated');
                          }
                          var fixedorvar = jQuery('#{$data['table_id']} .custom-filter .fixed_or_variable select');
                          fixedorvar.val('fixed').trigger('change');
                          if( jQuery().chosen != undefined ){
                              fixedorvar.trigger('chosen:updated');
                          }
                      });
                  </script>";
        }

    }

    return $data;
}

/***
 * Telmo 2016-05-19 PPG-793 Events widget
 */
add_action( 'init', 'create_post_type' );
function create_post_type() {
    register_post_type( 'events-feed',
        array(
            'labels' => array(
                'name' => __( 'Events' ),
                'singular_name' => __( 'Event' )
            ),
            'public' => true,
            'has_archive' => true,
        )
    );
}

/***
 * Telmo 2016-05-23
 */
global $investis_data;
if( class_exists('\BOI\plugin\modules\Investis') ) {
    $module_is_active = get_option('investis-integration-is-active');
    if( $module_is_active ) {
        $investis = new \BOI\plugin\modules\Investis(true);
        $responses = $investis->get_xml_object();

        $investis_data['dublin'] = isset($responses[\BOI\plugin\modules\Investis::$request_exchange_dublin]) ? (Array)$responses[\BOI\plugin\modules\Investis::$request_exchange_dublin] : array();
        $investis_data['london'] = isset($responses[\BOI\plugin\modules\Investis::$request_exchange_london]) ? (Array)$responses[\BOI\plugin\modules\Investis::$request_exchange_london] : array();
    }
}




/*==================================================================
=            Allowing SVGs uploads to the media library            =
==================================================================*/

// Ref: https://css-tricks.com/snippets/wordpress/allow-svg-through-wordpress-media-uploader/

function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

/*=====  End of Allowing SVGs uploads to the media library  ======*/

// Register Custom Post Type
function custom_post_type() {
    register_post_type(PostType::HERO_SLIDE,
        create_custom_post_type(
            PostType::singlularName(PostType::HERO_SLIDE),
            PostType::pluralName(PostType::HERO_SLIDE),
            15,
            array(),
            PostType::supports(PostType::HERO_SLIDE),
            PostType::insertInto(PostType::HERO_SLIDE),
            PostType::icon(PostType::HERO_SLIDE)));
    register_post_type(PostType::ARTICLE,
        create_custom_post_type(
            PostType::singlularName(PostType::ARTICLE),
            PostType::pluralName(PostType::ARTICLE),
            16,
            array(),
            PostType::supports(PostType::ARTICLE),
            PostType::insertInto(PostType::ARTICLE),
            PostType::icon(PostType::ARTICLE),
            PostType::permalink(PostType::ARTICLE).PostType::slug(PostType::ARTICLE)));
    register_post_type(PostType::MORTGAGE_ARTICLE,
        create_custom_post_type(
            PostType::singlularName(PostType::MORTGAGE_ARTICLE),
            PostType::pluralName(PostType::MORTGAGE_ARTICLE),
            17,
            array(),
            PostType::supports(PostType::MORTGAGE_ARTICLE),
            PostType::insertInto(PostType::MORTGAGE_ARTICLE),
            PostType::icon(PostType::MORTGAGE_ARTICLE),
            PostType::permalink(PostType::MORTGAGE_ARTICLE).PostType::slug(PostType::MORTGAGE_ARTICLE)));
    flush_rewrite_rules( false );
}
add_action( 'init', 'custom_post_type', 0 );

function custom_taxonomies() {
    register_taxonomy( Taxonomy::AGE_PROFILE, array( PostType::ARTICLE ), create_custom_taxonomy(Taxonomy::singlularName(Taxonomy::AGE_PROFILE), Taxonomy::pluralName(Taxonomy::AGE_PROFILE)));
    register_taxonomy( Taxonomy::EMPLOYMENT_STATUS, array( PostType::ARTICLE ), create_custom_taxonomy(Taxonomy::singlularName(Taxonomy::EMPLOYMENT_STATUS), Taxonomy::pluralName(Taxonomy::EMPLOYMENT_STATUS)));
    register_taxonomy( Taxonomy::ARTICLE_CATEGORY, array( PostType::ARTICLE ), create_custom_taxonomy(Taxonomy::singlularName(Taxonomy::ARTICLE_CATEGORY), Taxonomy::pluralName(Taxonomy::ARTICLE_CATEGORY)));
    register_taxonomy( Taxonomy::TOPIC, array( PostType::ARTICLE), create_custom_taxonomy(Taxonomy::singlularName(Taxonomy::TOPIC), Taxonomy::pluralName(Taxonomy::TOPIC)));
    register_taxonomy( Taxonomy::ARTICLE_FORMAT, array( PostType::ARTICLE ), create_custom_taxonomy(Taxonomy::singlularName(Taxonomy::ARTICLE_FORMAT), Taxonomy::pluralName(Taxonomy::ARTICLE_FORMAT)));
    register_taxonomy( Taxonomy::MORTGAGE_SEGMENT, array( PostType::MORTGAGE_ARTICLE ), create_custom_taxonomy(Taxonomy::singlularName(Taxonomy::MORTGAGE_SEGMENT), Taxonomy::pluralName(Taxonomy::MORTGAGE_SEGMENT)));
    register_taxonomy( Taxonomy::MORTGAGE_TOPIC, array(PostType::MORTGAGE_ARTICLE ), create_custom_taxonomy(Taxonomy::singlularName(Taxonomy::MORTGAGE_TOPIC), Taxonomy::pluralName(Taxonomy::MORTGAGE_TOPIC)));
}
add_action( 'init', 'custom_taxonomies' );

function create_custom_taxonomy($taxonomy_type_singular, $taxonomy_type_plural, $tax_type_path = ''){
    $labels = array(
        'name'              => _x( $taxonomy_type_plural, 'taxonomy general name' ),
        'singular_name'     => _x( $taxonomy_type_singular, 'taxonomy singular name' ),
        'search_items'      => __( 'Search '.$taxonomy_type_plural ),
        'all_items'         => __( 'All '.$taxonomy_type_plural ),
        'parent_item'       => __( 'Parent '.$taxonomy_type_singular ),
        'parent_item_colon' => __( 'Parent '.$taxonomy_type_singular ),
        'edit_item'         => __( 'Edit '.$taxonomy_type_singular ),
        'update_item'       => __( 'Update '.$taxonomy_type_singular ),
        'add_new_item'      => __( 'Add New '.$taxonomy_type_singular ),
        'new_item_name'     => __( 'New '.$taxonomy_type_singular.' Name' ),
        'menu_name'         => __( $taxonomy_type_plural ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => $tax_type_path == '',
        'show_admin_column' => true,
        'query_var'         => $taxonomy_type_singular
    );

    if(!empty($tax_type_path)){
        $args['rewrite'] = array( 'slug' => $tax_type_path,'with_front' => false );
    }
    return $args;
}

function create_custom_post_type($post_type_singular, $post_type_plural, $menu_position, $taxonomies, $supports, $insert_into = 'item', $menu_icon = '', $post_type_path=''){
    $labels = array(
        'name'                  => _x( $post_type_plural, 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( $post_type_singular, 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( $post_type_plural, 'text_domain' ),
        'name_admin_bar'        => __( $post_type_plural, 'text_domain' ),
        'archives'              => __( $post_type_singular.' Archives', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent '.$post_type_singular.':', 'text_domain' ),
        'all_items'             => __( 'All '.$post_type_plural, 'text_domain' ),
        'add_new_item'          => __( 'Add New '.$post_type_singular, 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New '.$post_type_singular, 'text_domain' ),
        'edit_item'             => __( 'Edit '.$post_type_singular, 'text_domain' ),
        'update_item'           => __( 'Update '.$post_type_singular, 'text_domain' ),
        'view_item'             => __( 'View '.$post_type_singular, 'text_domain' ),
        'search_items'          => __( 'Search '.$post_type_singular, 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into '.$insert_into, 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
        'items_list'            => __( $post_type_plural.' list', 'text_domain' ),
        'items_list_navigation' => __( $post_type_plural.' list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter '.$post_type_plural.' list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( $post_type_singular, 'text_domain' ),
        'description'           => __( $post_type_singular, 'text_domain' ),
        'labels'                => $labels,
        'supports'              => $supports,
        'taxonomies'            => $taxonomies,
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => $menu_position,
        'menu_icon'             => $menu_icon,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
        'rest_controller_class' => 'WP_REST_Posts_Controller'
    );

    if(!empty($post_type_path)){
        $args['rewrite'] = array( 'slug' => $post_type_path,'with_front' => false );
    }

    return $args;

}

add_action( 'rest_api_init', 'add_custom_articles_api');
remove_action( 'rest_api_init', 'create_initial_rest_routes', 0 );

function add_custom_articles_api(){
    $boiApiManager = BoiApiManagerFactory::create();
    $boiApiManager->register_routes();
}

if ( !function_exists( 'get_thumbnail_support_items' ) ):
    function get_thumbnail_support_items(){
        return array(
            'post',
            PostType::ARTICLE,
            PostType::MORTGAGE_ARTICLE,
            'page',
            'player'
        );
    }
endif;

if ( function_exists( 'add_image_size' ) ) {
    add_image_size( ThumbnailSize::CARD, 265, 155, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::CARD2X, 530, 310, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::CARD3X, 795, 465, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::CARD4X, 1060, 620, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::SQUARE, 155, 155, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::SQUARE2X, 310, 310, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::SQUARE3X, 465, 465, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::SQUARE4X, 620, 620, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::RELATED, 344, 150, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::RELATED2X, 688, 300, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::RELATED3X, 1032, 450, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::RELATED4X, 1376, 600, array( 'center', 'center')  );

    add_image_size( ThumbnailSize::SEGMENTCARD, 351, 215, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::SEGMENTCARD2X, 702, 430, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::SEGMENTCARD3X, 1404, 860, array( 'center', 'center')  );
    add_image_size( ThumbnailSize::SEGMENTCARD4X, 2808, 1720, array( 'center', 'center')  );



}

/**
 * Disable the WPSEO v3.1+ Primary Category feature.
 */
add_filter( 'wpseo_primary_term_taxonomies', '__return_empty_array' );

/**
 * register custom taxonomies with json api
 */
function sb_add_taxes_to_api() {
    $taxonomies = get_taxonomies( '', array(Taxonomy::AGE_PROFILE, Taxonomy::ARTICLE_CATEGORY, Taxonomy::EMPLOYMENT_STATUS, Taxonomy::TOPIC) );

    foreach( $taxonomies as $taxonomy ) {
        $taxonomy->show_in_rest = true;
    }
}
add_action( 'init', 'sb_add_taxes_to_api', 30 );



/*===================================================
=            Registering Pensions Article Sidebar            =
===================================================*/

// Register Sidebars
function articles_sidebar() {

    $args = array(
        'id'            => 'articles_sidebar',
        'name'          => __( 'Pensions articles sidebar', 'Pensions articles sidebar' ),
        'before_title'  => ' <h3>',
        'after_title'   => '</h3>',
        'before_widget' => ' <div class="s-article-widget %s">',
        'after_widget'  => '</div>',
    );
    register_sidebar( $args );

}
add_action( 'widgets_init', 'articles_sidebar' );


/*=====  End of Registering Pensions Article Sidebar  ======*/

/*===================================================
=            Registering Mortgages Article Sidebar            =
===================================================*/

// Register Sidebars
function mortgages_articles_sidebar() {

    $args = array(
        'id'            => 'mortgages_articles_sidebar',
        'name'          => __( 'Mortgages articles sidebar', 'Mortgages articles sidebar' ),
        'before_title'  => ' <h3>',
        'after_title'   => '</h3>',
        'before_widget' => ' <div class="s-article-widget %s">',
        'after_widget'  => '</div>',
    );
    register_sidebar( $args );

}
add_action( 'widgets_init', 'mortgages_articles_sidebar' );


/*=====  End of Registering Mortgages Article Sidebar  ======*/

/*===================================================
=            Registering Pensions Article Footer Sidebar            =
===================================================*/

// Register Sidebar
// This should be removed once the widgets are set through ACF
function pensions_articles_footer_sidebar() {

    $args = array(
        'id'            => 'pensions_articles_footer_sidebar',
        'name'          => __( 'Pensions Articles Footer sidebar', 'Pensions Articles Footer sidebar' ),
        'before_title'  => ' <h3>',
        'after_title'   => '</h3>',
        'before_widget' => ' ',
        'after_widget'  => '',
    );
    register_sidebar( $args );

}
add_action( 'widgets_init', 'pensions_articles_footer_sidebar' );


/*=====  End of Registering Pensions Article Footer Sidebar  ======*/


/*===================================================
=      Registering protection Article Footer Sidebar            =
===================================================*/

// Register Sidebar
// This should be removed once the widgets are set through ACF

function protection_articles_footer_sidebar() {

    $args = array(
        'id'            => 'protection_articles_footer_sidebar',
        'name'          => __( 'Protection footer widgets', 'Protection footer widgets' ),
        'before_title'  => ' <h3>',
        'after_title'   => '</h3>',
        'before_widget' => ' ',
        'after_widget'  => '',
    );
    register_sidebar( $args );

}
add_action( 'widgets_init', 'protection_articles_footer_sidebar' );


/*=====  End of Registering protection Article Footer Sidebar  ======*/



/*===================================================
=        Registering Active widgets area            =
===================================================*/

// Register Sidebar
function active_widgets() {

    $active_widgets = array(
        'id'            => 'active_widgets',
        'name'          => __( 'Active widgets', 'Active widgets' ),
        'before_title'  => ' <h3>',
        'after_title'   => '</h3>',
        'before_widget' => ' <div class="active-widgets s-article-widget %s">',
        'after_widget'  => '</div>',
    );
    register_sidebar( $active_widgets );

    $mortgage_calculator_centre_widgets = array(
        'id'            => 'mortgage_calculator_centre_widgets',
        'name'          => __( 'Mortgage calculator centre widgets', 'Mortgage calculator centre widgets' ),
        'before_title'  => ' <h3>',
        'after_title'   => '</h3>',
        'before_widget' => ' <div class="mortgage-calculator-centre-widgets s-article-widget %s">',
        'after_widget'  => '</div>',
    );
    register_sidebar( $mortgage_calculator_centre_widgets );

}
add_action( 'widgets_init', 'active_widgets' );


/*=====  End of Registering Active widgets area  ======*/



/*=============================================================
=            Custom class from ACF to text widgets            =
=============================================================*/


add_filter('dynamic_sidebar_params', 'adding_custom_class_to_text_widgets_from_acf');

function adding_custom_class_to_text_widgets_from_acf( $params ) {

    // get widget vars
    $widget_name = $params[0]['widget_name'];
    $widget_id = $params[0]['widget_id'];


    // bail early if this widget is not a Text widget
    if( $widget_name != 'Text' ) {

        return $params;

    }

    // add color style to before_widget
    $css_classes = get_field('css_classes', 'widget_' . $widget_id);

    if( $css_classes ) {

        $params[0]['before_widget'] .= '<div class="' .  $css_classes . '">';
        $params[0]['after_widget'] .=  '</div>';

    }

    // return
    return $params;

}

/*=====  End of Custom class from ACF to text widgets  ======*/


/*=====================================================================
=            Adding support for shortcodes on text widgets            =
=====================================================================*/

add_filter('widget_text', 'do_shortcode');


/*=====  End of Adding support for shortcodes on text widgets  ======*/


/*=====================================================
=            Options page for BIL settings            =
=====================================================*/

if( function_exists('acf_add_options_page') ) {
    $args = array(
        'page_title' => 'BIL settings',
        'capability' => 'activate_plugins',
        'menu_title' => 'BIL settings',
        'parent_slug' => 'options-general.php',

        );
    acf_add_options_page($args);
}

/*=====  End of Options page for Mortgage settings  ======*/

/*=====================================================
=            Options page for Mortgage settings            =
=====================================================*/

if( function_exists('acf_add_options_page') ) {
    $args = array(
        'page_title' => 'Mortgage settings',
        'capability' => 'activate_plugins',
        'menu_title' => 'Mortgage settings',
        'parent_slug' => 'options-general.php',

    );
    acf_add_options_page($args);
}

/*=====  End of Options page for Mortgage settings  ======*/


/**
 * Adding filter to replace home_url() with site_url() in custom permalinks created by WP Category Permalink
 */
add_filter('site_url', function ($url) {
    if (strpos($url, '/'.PostType::ARTICLE.'/%'.Taxonomy::ARTICLE_CATEGORY.'%/') !== FALSE) {
        $url = str_replace(site_url(), home_url(), $url);
    }
    return $url;
}, 1);


add_filter('post_link', 'article_category_permalink', 10, 3);
add_filter('post_type_link', 'article_category_permalink', 10, 3);

/**
 * get the article category permalink based on the primary article category selected with the article
 * @param $permalink
 * @param $post_id
 * @param $leavename
 * @return mixed
 */


/*========================================
=            is_tax shortcode            =
========================================*/

// Add Shortcode
function show_for_tax_only_shortcode( $atts , $content = null ) {

    // Attributes
    $atts = shortcode_atts(
        array(
            'taxonomy-name' => '',
            'terms' => '',
        ),
        $atts,
        'show-for-term-only'
    );

    $terms = explode(',', $atts[terms]);
    if (
        (is_tax($atts['taxonomy-name'], $terms) || has_term($terms, $atts['taxonomy-name']))
        && $content != null ){

        return $content;
    };

}
add_shortcode( 'show-for-term-only', 'show_for_tax_only_shortcode' );

/*=====  End of is_tax shortcode  ======*/

function article_category_permalink($permalink, $post_id, $leavename) {
    if (strpos($permalink, '%article_category%') === FALSE) return $permalink;

    // Get post
    $post = get_post($post_id);
    if (!$post) return $permalink;

    return str_replace('%article_category%', $post->primary_article_category, $permalink);
}

/**
 * auto load the article categories for an acf select field named primary_article_category
 * @param $field
 * @return mixed
 */
function acf_load_article_category_field_choices( $field ) {

    $field['choices'] = array();
    $categories = Timber::get_terms(Taxonomy::ARTICLE_CATEGORY);
    if( is_array($categories) ) {
        foreach( $categories as $category ) {
            $key = $category->slug;
            $label = $category->name;
            $field['choices'][ $key ] = $label;
        }
    }
    return $field;

}

add_filter('acf/load_field/name=primary_article_category', 'acf_load_article_category_field_choices');


/*******************************************************************
 * Hooking into save post action
 * we want to assign parent mortgage segment/stage taxonomy terms
 * for selected child terms of mortgage articles
 *******************************************************************/
add_action('save_post', 'assign_parent_terms', 10, 2);

function assign_parent_terms($post_id, $post){

    if($post->post_type != PostType::MORTGAGE_ARTICLE)
        return $post_id;

    // get all assigned terms
    $terms = wp_get_post_terms($post_id, Taxonomy::MORTGAGE_SEGMENT );
    foreach($terms as $term){
        while($term->parent != 0 && !has_term( $term->parent, Taxonomy::MORTGAGE_SEGMENT, $post )){
            // move upward until we get to 0 level terms
            wp_set_post_terms($post_id, array($term->parent), Taxonomy::MORTGAGE_SEGMENT, true);
            $term = get_term($term->parent, Taxonomy::MORTGAGE_SEGMENT);
        }
    }
}

/*******************************
* Add custom class to popular posts widget
*******************************/
add_filter('dynamic_sidebar_params', 'add_classes_to__widget');
function add_classes_to__widget($params){

    // Create an patter using part of the class views
    $pattern = '/views-(\d)/';
    // Match pattern with parameter 'widget_id' which gives us matches
    preg_match_all($pattern, $params[0]['widget_id'] , $matches);
    // if we have a match add custom clacc before_widget
    if(isset($matches[1][0])){

        $classe_to_add = 's-article__popular-posts '; // make sure you leave a space at the end
        $classe_to_add = 'class=" '.$classe_to_add;
        $params[0]['before_widget'] = str_replace('class="',$classe_to_add,$params[0]['before_widget']);

    }
    return $params;
}

