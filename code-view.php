<?php
/**
 * @package Code View
 * @version 0.0.5
 */

/*
Plugin Name: Code View
Plugin URI: http://wordpress.org/plugins/code-view/
Description: This is a basic plugin that uses highlight js to render code examples in blog post, example usage [cv php] print 'welcome'; [/cv] or [cv css] .html{ background: black; } [/cv]
Author: Turtlebytes LLC
Version: 0.0.5
Author URI: http://turtlebytes.com/
*/

Class CodeView {
	public static $version = '0.0.5';

	public static $settings = [];

	public static $css_dependencies = [];

	public static $js_dependencies = [
		'shortcode',
		'jquery'
	];

	// Default settings
	public static $defaults = [
		'cv_theme'       => 'atom-one-light',
		'line_numbers'   => true,
		'include_emblem' => true,
	];

	// Usable themes from this plugin
	public static $themes = [
		'agate',
		'androidstudio',
		'arduino-light',
		'arta',
		'ascetic',
		'atelier-cave-dark',
		'atelier-cave-light',
		'atelier-dune-dark',
		'atelier-dune-light',
		'atelier-estuary-dark',
		'atelier-estuary-light',
		'atelier-forest-dark',
		'atelier-forest-light',
		'atelier-heath-dark',
		'atelier-heath-light',
		'atelier-lakeside-dark',
		'atelier-lakeside-light',
		'atelier-plateau-dark',
		'atelier-plateau-light',
		'atelier-savanna-dark',
		'atelier-savanna-light',
		'atelier-seaside-dark',
		'atelier-seaside-light',
		'atelier-sulphurpool-dark',
		'atelier-sulphurpool-light',
		'atom-one-dark',
		'atom-one-light',
		'brown-paper',
		'brown-papersq',
		'codepen-embed',
		'color-brewer',
		'darcula',
		'dark',
		'darkula',
		'docco',
		'dracula',
		'far',
		'foundation',
		'github',
		'github-gist',
		'googlecode',
		'grayscale',
		'gruvbox-dark',
		'gruvbox-light',
		'hopscotch',
		'hybrid',
		'idea',
		'ir-black',
		'kimbie.dark',
		'kimbie.light',
		'magula',
		'mono-blue',
		'monokai',
		'monokai-sublime',
		'obsidian',
		'ocean',
		'paraiso-dark',
		'paraiso-light',
		'pojoaque',
		'pojoaque',
		'purebasic',
		'qtcreator_dark',
		'qtcreator_light',
		'railscasts',
		'rainbow',
		'routeros',
		'school-book',
		'school-book',
		'solarized-dark',
		'solarized-light',
		'sunburst',
		'tomorrow',
		'tomorrow-night',
		'tomorrow-night-blue',
		'tomorrow-night-bright',
		'tomorrow-night-eighties',
	];

	/**
	 * CodeView constructor.
	 */
	public function __construct() {

		// Register admin components
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'register_settings_page' ] );

		// Basic setup
		$this->load_settings();
		$this->register_short_codes();
		$this->register_assets();

	}

	/**
	 *
	 */
	private function load_settings() {
		self::$settings = (array) get_option( 'codeview_settings', [] );
	}

	/**
	 * Do my best to get a settings value
	 *
	 * @param $key
	 * @param bool $fallback
	 *
	 * @return bool|mixed
	 */
	public static function getSetting( $key, $fallback = false ) {

		// Get user setting
		if ( isset( self::$settings[ $key ] ) ) {
			return self::$settings[ $key ];
		}

		// Get Default
		if ( isset( self::$defaults[ $key ] ) ) {
			return self::$defaults[ $key ];
		}

		// No user setting or default somehow. Use fallback
		return $fallback;
	}

	/**
	 * @return string
	 */
	public function base_file() {
		return __FILE__; // Add single point of failure
	}

	/**
	 * render and return the template so wordpress can do its thing
	 *
	 * @return string
	 */
	function cv() {
		$args = func_get_args();
		$lang = 'http';

		// This is crazy but catch all undefined errors when trying to get the included language
		if ( isset( $args['0'] ) ) {
			if ( isset( $args['0']['lang'] ) ) {
				$lang = $args['0']['lang'];
			} elseif ( isset( $args['0']['0'] ) ) {
				$lang = $args['0']['0'];
			}
		}

		// Wordpress escapes html characters and because its used in a short code its surrounded with \n characters, trim removes them
		$code = trim( strip_tags( $args[1] ) );

		return include( 'pages/code.php' );
	}

	/**
	 *
	 */
	public function register_settings() {
		register_setting( 'codeview_settings', 'codeview_settings' );
	}

	/**
	 * Register the settings page
	 */
	function register_settings_page() {
		add_options_page( __( 'CodeView Settings', 'codeview' ), __( 'CodeView Settings', 'codeview' ), 'manage_options', 'codeview', [ $this, 'settings_page' ] );
	}

	/**
	 * Register the shortcode
	 */
	private function register_short_codes() {
		add_shortcode( "cv", [ 'CodeView', 'cv' ] );
	}

	/**
	 * Load the styles and javascript
	 */
	private function register_assets() {
		wp_register_style( 'highlight-default.css', plugin_dir_url( self::base_file() ) . 'assets/highlight/styles/default.css', CodeView::$css_dependencies, CodeView::$version );
		wp_enqueue_style( 'highlight-default.css' );

		wp_register_style( 'code-view.css', plugin_dir_url( self::base_file() ) . 'assets/css/code-view.css', CodeView::$css_dependencies, CodeView::$version );
		wp_enqueue_style( 'code-view.css' );

		wp_register_style( 'hightlight-style.css', plugin_dir_url( self::base_file() ) . 'assets/highlight/styles/' . self::getSetting( 'cv_theme' ) . '.css', CodeView::$css_dependencies, CodeView::$version );
		wp_enqueue_style( 'hightlight-style.css' );

		wp_register_script( 'highlight.js', plugins_url( 'assets/highlight/highlight.pack.js', self::base_file() ), CodeView::$js_dependencies, CodeView::$version, true );
		wp_register_script( 'line-numbers.min.js', plugins_url( 'assets/js/line-numbers.min.js', self::base_file() ), CodeView::$js_dependencies, CodeView::$version, true );
		wp_register_script( 'code-view.init', plugins_url( 'assets/js/register.js', self::base_file() ), CodeView::$js_dependencies, CodeView::$version, true );

		wp_enqueue_script( 'highlight.js' );
		wp_enqueue_script( 'line-numbers.min.js' );
		wp_enqueue_script( 'code-view.init' );

	}

	/**
	 * Render the settings page
	 * @return mixed
	 */
	function settings_page() {
		return require 'pages/settings-page.php';
	}

}

/**
 * Initialize CodeView
 */
add_action( 'init', 'CodeView', 5 );
if ( ! function_exists( 'CodeView' ) ) {
	function CodeView() {
		global $CodeView;
		$CodeView = new CodeView();
	}
}
