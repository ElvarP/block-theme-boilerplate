<?php
function custom_block_category($categories, $post)
{
    return array_merge(

        array(
            array(
                'slug' => 'theme-blocks',
                'title' => __('Theme Blocks', 'textdomain'),
            ),
        ),
        $categories
    );
}
add_filter('block_categories_all', 'custom_block_category', 10, 2);

function register_acf_blocks()
{
    foreach (glob(get_stylesheet_directory() . '/build/blocks/*/') as $path) {

        register_block_type($path . 'block.json');
    }
}
add_action('init', 'register_acf_blocks', 5);

function custom_scripts_and_styles()
{
    $ASSET_INFO = include get_stylesheet_directory() . '/build/theme/index.asset.php';
    wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/build/theme/index.css', array(), $ASSET_INFO['version']);
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/build/theme/index.js', $ASSET_INFO['dependencies'], $ASSET_INFO['version']);
}
add_action('wp_enqueue_scripts', 'custom_scripts_and_styles');

function add_editor_styles()
{
    add_theme_support('editor-styles');
    add_editor_style(get_stylesheet_directory_uri() . '/build/theme/index.css');
}
add_action('admin_init', 'add_editor_styles');


// Define path and URL to the ACF plugin.
define('MY_ACF_PATH', get_stylesheet_directory() . '/inc/acf/');
define('MY_ACF_URL', get_stylesheet_directory_uri() . '/inc/acf/');

// Include the ACF plugin.
include_once(MY_ACF_PATH . 'acf.php');

// Customize the url setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url($url)
{
    return MY_ACF_URL;
}
switch (wp_get_environment_type()) {
    case 'local':
    case 'development':
        add_filter('acf/settings/show_admin', '__return_true');
        break;

    case 'production':
    default:
        add_filter('acf/settings/show_admin', '__return_false');
        add_filter('acf/settings/show_updates', '__return_false', 100);
        break;
}
