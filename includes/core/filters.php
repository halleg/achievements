<?php
/**
 * Core filters
 *
 * This file contains the filters that are used throughout Achievements. They are
 * consolidated here to make searching for them easier, and to help developers
 * understand at a glance the order in which things occur.
 *
 * @package Achievements
 * @subpackage CoreFilters
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Attach Achievements to WordPress
 *
 * Achievements uses its own internal actions to help aid in third-party plugin
 * development, and to limit the amount of potential future code changes when
 * updates to WordPress core occur.
 *
 * These actions exist to create the concept of 'plugin dependencies'. They
 * provide a safe way for plugins to execute code *only* when Achievements is
 * installed and activated, without needing to do complicated guesswork.
 */
add_filter( 'request',                 'dpa_request',            10    );
add_filter( 'template_include',        'dpa_template_include',   10    );
add_filter( 'wp_title',                'dpa_title',              10, 3 );
add_filter( 'body_class',              'dpa_body_class',         10, 2 );
add_filter( 'map_meta_cap',            'dpa_map_meta_caps',      10, 4 );
add_filter( 'allowed_themes',          'dpa_allowed_themes',     10    );
add_filter( 'redirect_canonical',      'dpa_redirect_canonical', 10    );
add_filter( 'plugin_locale',           'dpa_plugin_locale',      10, 2 );

// Add post_parent__in to posts_where
add_filter( 'posts_where', 'dpa_query_post_parent__in', 10, 2 );

// Run filters on achievement->post_content
add_filter( 'dpa_get_achievement_content', 'wptexturize',       10 );
add_filter( 'dpa_get_achievement_content', 'capital_P_dangit',  12 );
add_filter( 'dpa_get_achievement_content', 'convert_smilies',   14 );
add_filter( 'dpa_get_achievement_content', 'convert_chars',     16 );
add_filter( 'dpa_get_achievement_content', 'wpautop',           18 );
add_filter( 'dpa_get_achievement_content', 'shortcode_unautop', 20 );

// Run filters on achievement->post_excerpt
add_filter( 'dpa_get_achievement_excerpt', 'wptexturize',       10 );
add_filter( 'dpa_get_achievement_excerpt', 'capital_P_dangit',  12 );
add_filter( 'dpa_get_achievement_excerpt', 'convert_smilies',   14 );
add_filter( 'dpa_get_achievement_excerpt', 'convert_chars',     16 );
add_filter( 'dpa_get_achievement_excerpt', 'wpautop',           18 );
add_filter( 'dpa_get_achievement_excerpt', 'shortcode_unautop', 20 );

/**
 * Template Compatibility
 *
 * If you want to completely bypass this and manage your own custom Achievements
 * template hierarchy, start here by removing these filter and then look at how
 * dpa_template_include() works and do something similar. :)
 */
add_filter( 'dpa_template_include', 'dpa_template_include_theme_supports', 2, 1 );
add_filter( 'dpa_template_include', 'dpa_template_include_theme_compat',   4, 2 );

// Run all template parts through additional template locations
//add_filter( 'dpa_locate_template',   'dpa_add_template_locations' );
add_filter( 'dpa_get_template_part', 'dpa_add_template_locations' );
