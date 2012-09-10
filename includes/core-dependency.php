<?php
/**
 * Plugin Dependency
 *
 * The purpose of the following actions is to mimic the behaviour of something
 * called 'plugin dependency' which enables a plugin to have plugins of their
 * own in a safe and reliable way.
 *
 * This is done in BuddyPress and bbPress. We do this by mirroring existing
 * WordPress actions in many places allowing dependant plugins to hook into
 * the Achievements specific ones, thus guaranteeing proper code execution.
 *
 * @package Achievements
 * @subpackage CoreDependency
 */

/**
 * Activation Actions
 */

/**
 * Runs on plugin activation
 *
 * @since 3.0
 */
function dpa_activation() {
	do_action( 'dpa_activation' );
}

/**
 * Runs on plugin deactivation
 *
 * @since 3.0
 */
function dpa_deactivation() {
	do_action( 'dpa_deactivation' );
}

/**
 * Runs when uninstalling the plugin
 *
 * @since 3.0
 */
function dpa_uninstall() {
	do_action( 'dpa_uninstall' );
}


/**
 * Main Actions
 */

/**
 * Main action responsible for constants, globals, and includes
 *
 * @since 3.0
 */
function dpa_loaded() {
	do_action( 'dpa_loaded' );
}

/**
 * Set up constants
 *
 * @since 3.0
 */
function dpa_constants() {
	do_action( 'dpa_constants' );
}

/**
 * Set up globals BEFORE includes
 *
 * @since 3.0
 */
function dpa_bootstrap_globals() {
	do_action( 'dpa_bootstrap_globals' );
}

/**
 * Include files
 *
 * @since 3.0
 */
function dpa_includes() {
	do_action( 'dpa_includes' );
}

/**
 * Set up globals AFTER includes
 *
 * @since 3.0
 */
function dpa_setup_globals() {
	do_action( 'dpa_setup_globals' );
}

/**
 * Initialise any code after everything has been loaded
 *
 * @since 3.0
 */
function dpa_init() {
	do_action( 'dpa_init' );
}

/** 
 * Register any objects before anything is initialised.
 * 
 * @since 3.0
 */ 
function dpa_register() { 
	do_action( 'dpa_register' );
}

/**
 * Initialise widgets
 *
 * @since 3.0
 */
function dpa_widgets_init() {
	do_action( 'dpa_widgets_init' );
}

/**
 * Setup the currently logged-in user
 *
 * @since 3.0
 */
function dpa_setup_current_user() {
	do_action( 'dpa_setup_current_user' );
}

/**
 * Supplemental Actions
 */

/**
 * Load translations for current language
 *
 * @since 3.0
 */
function dpa_load_textdomain() {
	do_action( 'dpa_load_textdomain' );
}

/**
 * Sets up the theme directory
 *
 * @since 3.0
 */
function dpa_register_theme_directory() {
	do_action( 'dpa_register_theme_directory' );
}

/**
 * Set up the post types
 *
 * @since 3.0
 */
function dpa_register_post_types() {
	do_action( 'dpa_register_post_types' );
}

/**
 * Set up the post statuses
 *
 * @since 3.0
 */
function dpa_register_post_statuses() {
	do_action( 'dpa_register_post_statuses' );
}

/**
 * Register the built-in taxonomies
 *
 * @since 3.0
 */
function dpa_register_taxonomies() {
	do_action( 'dpa_register_taxonomies' );
}

/**
 * Register custom endpoints
 *
 * @since 3.0
 */
function dpa_register_endpoints() {
	do_action( 'dpa_register_endpoints' );
}
/**
 * Enqueue CSS and JS
 *
 * @since 3.0
 */
function dpa_enqueue_scripts() {
	do_action( 'dpa_enqueue_scripts' );
}

/**
 * Everything's loaded and ready to go!
 *
 * @since 3.0
 */
function dpa_ready() {
	do_action( 'dpa_ready' );
}


/**
 * Theme Permissions
 */

/**
 * The main action used for redirecting Achievements theme actions that are not
 * permitted by the current_user.
 *
 * @since 3.0
 */
function dpa_template_redirect() {
	do_action( 'dpa_template_redirect' );
}


/**
 * Theme Helpers
 */

/**
 * The main action used for executing code before the theme has been setup
 *
 * @since 3.0
 */
function dpa_register_theme_packages() {
	do_action( 'dpa_register_theme_packages' );
}

/**
 * The main action used for executing code before the theme has been setup
 *
 * @since 3.0
 */
function dpa_setup_theme() {
	do_action( 'dpa_setup_theme' );
}

/**
 * The main action used for executing code after the theme has been setup
 *
 * @since 3.0
 */
function dpa_after_setup_theme() {
	do_action( 'dpa_after_setup_theme' );
}


/**
 * Filters
 */

/**
 * Piggy back filter for WordPress' "request" filter
 *
 * @since 3.0
 * @param array $query_vars Optional
 * @return array
 */
function dpa_request( $query_vars = array() ) {
	return apply_filters( 'dpa_request', $query_vars );
}

/**
 * The main filter used for theme compatibility and displaying custom Achievements theme files.
 *
 * @since 3.0
 * @param string $template
 * @return string Template file to use
 */
function dpa_template_include( $template = '' ) {
	return apply_filters( 'dpa_template_include', $template );
}

/**
 * Generate Achievements-specific rewrite rules
 *
 * @since 3.0
 * @param WP_Rewrite $wp_rewrite
 */
function dpa_generate_rewrite_rules( $wp_rewrite ) {
	do_action_ref_array( 'dpa_generate_rewrite_rules', array( &$wp_rewrite ) );
}

/**
 * Filter the allowed themes list for Achievements-specific themes
 *
 * @since 3.0
 */
function dpa_allowed_themes( $themes ) {
	return apply_filters( 'dpa_allowed_themes', $themes );
}