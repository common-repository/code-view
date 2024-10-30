<?php
/**
 * @package Code View
 * @version 0.0.1
 */

/*
Plugin Name: Code View
Plugin URI: http://wordpress.org/plugins/code-view/
Description: This is a basic plugin that uses highlight js to render code examples in blog post, example usage [cv php] print 'welcome'; [/cv] or [cv css] .html{ background: black; } [/cv]
Author: Turtlebytes LLC
Version: 0.0.1
Author URI: http://turtlebytes.com/
*/

Class CodeView
{
    public $short_codes = array();
    public static $version = '1.0.1';

    public static $css_dependencies = array();

    public static $js_dependencies = array(
        'shortcode',
        'jquery'
    );

    /**
     * render and return the template so wordpress can do its thing
     *
     * @return mixed
     */
    function cv()
    {
        $args = func_get_args();
        $lang = 'http';

        // This is crazy but catch all undefined errors
        if(isset($args['0'])) {
            if (isset($args['0']['lang'])) {
                $lang = $args['0']['lang'];
            } elseif (isset($args['0']['0'])) {
                $lang = $args['0']['0'];
            }
        }

        $code = $args[1];

        return include('templates/code.php');
    }
}

// Register the shortcode
add_shortcode("cv", ['CodeView', 'cv']);

//Load the styles and javascript
wp_register_style('highlight-default.css', plugin_dir_url( __FILE__ ) . 'assets/highlight/styles/default.css', CodeView::$css_dependencies, CodeView::$version);
wp_enqueue_style( 'highlight-default.css');

wp_register_style('code-view.css', plugin_dir_url( __FILE__ ) . 'assets/css/code-view.css', CodeView::$css_dependencies, CodeView::$version);
wp_enqueue_style( 'code-view.css');

wp_register_style('hightlight-style.css', plugin_dir_url( __FILE__ ) . 'assets/highlight/styles/atom-one-light.css', CodeView::$css_dependencies, CodeView::$version);
wp_enqueue_style( 'hightlight-style.css');

wp_register_script('highlight.js', plugins_url('assets/highlight/highlight.pack.js', __FILE__), CodeView::$js_dependencies, CodeView::$version, true);
wp_register_script('line-numbers.min.js', plugins_url('assets/js/line-numbers.min.js', __FILE__), CodeView::$js_dependencies, CodeView::$version, true);
wp_register_script('code-view.init', plugins_url('assets/js/register.js', __FILE__), CodeView::$js_dependencies, CodeView::$version, true);

wp_enqueue_script('highlight.js');
wp_enqueue_script('line-numbers.min.js');
wp_enqueue_script('code-view.init');