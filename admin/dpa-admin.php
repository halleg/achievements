<?php
/**
 * Main Achievements Admin Class
 *
 * @package Achievements
 * @subpackage Administration
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Loads Achievements admin area thing
 *
 * @since 3.0
 */
class DPA_Admin {
	// Paths

	/**
	 * Path to the Achievements admin directory
	 */
	public $admin_dir = '';

	/**
	 * URL to the Achievements admin directory
	 */
	public $admin_url = '';

	/**
	 * URL to the Achievements image directory
	 */
	public $images_url = '';


	/**
	 * The main Achievements admin loader
	 *
	 * @since 3.0
	 */
	public function __construct() {
		$this->setup_globals();
		$this->includes();
		$this->setup_actions();
	}

	/**
	 * Set up the admin hooks, actions and filters
	 *
	 * @since 3.0
	 */
	private function setup_actions() {
		// Attach Achievements' admin_init action to the WordPress admin_init action.
		add_action( 'admin_init',         array( $this, 'admin_init'                 ) );

		// Add menu item to settings menu
		add_action( 'admin_menu',         array( $this, 'admin_menus'                ) );

		// Add notice if not using an Achievements theme
		add_action( 'admin_notices',      array( $this, 'activation_notice'          ) );

		// Add settings
		add_action( 'dpa_admin_init',     array( $this, 'register_admin_settings'    ) );

		// Add link to settings page
		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 2 );

		// Add sample permalink filter
		add_filter( 'post_type_link',     'dpa_filter_sample_permalink',        10, 4 );
	}

	/**
	 * Include required files
	 *
	 * @since 3.0
	 */
	private function includes() {
		require( $this->admin_dir . 'dpa-admin-functions.php' );
		require( $this->admin_dir . 'dpa-supported-plugins.php' );  // Supported plugins screen
	}

	/**
	 * Set admin globals
	 *
	 * @global achievements $achievements Main Achievements object
	 * @since 3.0
	 */
	private function setup_globals() {
		global $achievements;

		// Admin dir
		$this->admin_dir  = trailingslashit( $achievements->plugin_dir . 'admin' );

		// Admin url
		$this->admin_url  = trailingslashit( $achievements->plugin_url . 'admin' );

		// Admin images URL
		$this->images_url = trailingslashit( $this->admin_url . 'images' );
	}

	/**
	 * Add wp-admin menus
	 *
	 * @since 3.0
	 */
	public function admin_menus() {
		// "Supported Plugins" menu
		$hook = add_submenu_page( 'edit.php?post_type=dpa_achievements', __( 'Achievements &mdash; Supported Plugins', 'dpa' ), __( 'Supported Plugins', 'dpa' ), 'manage_options', 'achievements-plugins', 'dpa_supported_plugins' );

		// Hook into early actions to register custom CSS and JS
		add_action( "admin_print_styles-$hook",  array( $this, 'enqueue_styles'  ) );
		add_action( "admin_print_scripts-$hook", array( $this, 'enqueue_scripts' ) );

		// Hook into early actions to register contextual help and screen options
		add_action( "load-$hook",                array( $this, 'screen_options'  ) );
	}

	/**
	 * Hook into early actions to register contextual help and screen options
	 *
	 * @since 3.0
	 */
	public function screen_options() {
		// Only load up styles if we're on an Achievements admin screen
		if ( ! DPA_Admin::is_admin_screen() )
			return;

		// "Supported Plugins" screen
		if ( 'achievements-plugins' == $_GET['page'] )
			dpa_supported_plugins_load();
	}

	/**
	 * Enqueue CSS for our custom admin screens
	 *
	 * @global achievements $achievements Main Achievements object
	 * @since 3.0
	 */
	public function enqueue_styles() {
		global $achievements;

		// Only load up styles if we're on an Achievements admin screen
		if ( ! DPA_Admin::is_admin_screen() )
			return;

		// "Supported Plugins" screen
		if ( 'achievements-plugins' == $_GET['page'] )
			wp_enqueue_style( 'dpa_admin_css', trailingslashit( $achievements->plugin_url ) . 'css/supportedplugins.css', array(), '20120209' );
	}

	/**
	 * Enqueue JS for our custom admin screens
	 *
	 * jQuery Cookie plugin taken from BuddyPress. Original source unknown.
	 *
	 * @global achievements $achievements Main Achievements object
	 * @since 3.0
	 */
	public function enqueue_scripts() {
		global $achievements;

		// Only load up scripts if we're on an Achievements admin screen
		if ( ! DPA_Admin::is_admin_screen() )
			return;

		// "Supported Plugins" screen
		if ( 'achievements-plugins' == $_GET['page'] ) {
			wp_enqueue_script( 'dpa_socialite',   trailingslashit( $achievements->plugin_url ) . 'js/socialite-min.js',          array(),                                             '20120413', true );
			wp_enqueue_script( 'dpa_cookie_js',   trailingslashit( $achievements->plugin_url ) . 'js/jquery-cookie-min.js',      array( 'jquery' ),                                   '20120413', true );
			wp_enqueue_script( 'tablesorter_js',  trailingslashit( $achievements->plugin_url ) . 'js/jquery-tablesorter-min.js', array( 'jquery' ),                                   '20120413', true );
			wp_enqueue_script( 'dpa_sp_admin_js', trailingslashit( $achievements->plugin_url ) . 'js/supportedplugins-min.js',   array( 'jquery', 'dpa_cookie_js', 'dpa_socialite' ), '20120413', true );

			// Add thickbox for the 'not installed' links on the List view
			add_thickbox();
		}
	}

	/**
	 * Register the settings
	 *
	 * @since 3.0
	 */
	public function register_admin_settings() {
		// Only do stuff if we're on an Achievements admin screen
		if ( ! DPA_Admin::is_admin_screen() )
			return;

		// Fire an action for Achievements plugins to register their custom settings
		do_action( 'dpa_register_admin_settings' );
	}

	/**
	 * Admin area activation notice
	 *
	 * Shows a nag message in admin area about the theme not supporting Achievements
	 *
	 * @global achievements $achievements Main Achievements object
	 * @global string $pagenow
	 * @since 3.0
	 */
	public function activation_notice() {
		global $achievements, $pagenow;

		// Bail if not on admin theme page
		if ( 'themes.php' != $pagenow )
			return;

		// Bail if user cannot change the theme
		if ( ! current_user_can( 'switch_themes' ) )
			return;

		// Set $achievements->theme_compat to true to supress this nag
		if ( ! empty( $achievements->theme_compat->theme ) && ! current_theme_supports( 'dpa_achievements' ) ) : ?>

			<div id="message" class="updated fade">
				<p style="line-height: 150%"><?php _e( 'Your active theme does not include template files for Achievements. Your achievement pages are using the default styling included with Achievements.', 'dpa' ); ?></p>
			</div>

		<?php endif;
	}

	/**
	 * Add Settings link to plugins area
	 *
	 * @global achievements $achievements Main Achievements object
	 * @param array $links Links array in which we would prepend our link
	 * @param string $file Current plugin basename
	 * @return array Processed links
	 * @since 3.0
	 */
	public function add_settings_link( $links, $file ) {
		global $achievements;

		if ( plugin_basename( $achievements->file ) == $file ) {
			$settings_link = '<a href="' . esc_attr( admin_url( 'options-general.php?page=achievements' ) ) . '">' . __( 'Settings', 'dpa' ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

	/**
	 * Dedicated admin init action for Achievements
	 *
	 * @since 3.0
	 */
	public function admin_init() {
		do_action( 'dpa_admin_init' );
	}

	/**
	 * Is the current screen part of Achievements? e.g. a post type screen.
	 *
	 * @return bool True if this is an Achievements admin screen
	 * @since 3.0
	 */
	public static function is_admin_screen() {
		$result = false;

		if ( ! empty( $_GET['post_type'] ) && 'dpa_achievements' == $_GET['post_type'] )
			$result = true;

		return true;
	}
}

/**
 * Set up Achievements' Admin
 *
 * @since 3.0
 */
function dpa_admin() {
	global $achievements;
	$achievements->admin = new DPA_Admin();
}
?>