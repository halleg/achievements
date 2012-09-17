<?php
/**
 * Achievement Progress post type template tags
 *
 * If you try to use an Progress post type template loops outside of the Achievement
 * post type template loop, you will need to implement your own swtich_to_blog and
 * wp_reset_postdata() handling if running in a multisite and in a
 * dpa_is_running_networkwide() configuration. Otherwise the data won't be fetched
 * from the appropriate site.
 *
 * @package Achievements
 * @subpackage ProgressTemplate
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The Progress post type loop.
 *
 * Only for use inside a dpa_has_achievements() template loop.
 *
 * @param array|string $args All the arguments supported by {@link WP_Query}, and some more.
 * @return bool Returns true if the query has any results to loop over
 * @since 3.0
 */
function dpa_has_progress( $args = array() ) {
	$defaults = array(
		'max_num_pages'  => false,                         // Maximum number of pages to show
		'order'          => 'DESC',                        // 'ASC', 'DESC
		'orderby'        => 'date',                        // 'meta_value', 'author', 'date', 'title', 'modified', 'parent', rand'
		'paged'          => dpa_get_paged(),               // Page number
		'post_status'    => dpa_get_unlocked_status_id(),  // Get posts in the unlocked status by default.
		'post_type'      => dpa_get_progress_post_type(),  // Only retrieve progress posts
		's'              => '',                            // No search

		// Conditional defaults
		'author'         => is_author()                 ? get_the_author_ID()      : null,  // If on author archive page, use that author's user ID.
		'post_parent'    => dpa_is_single_achievement() ? dpa_get_achievement_id() : null,  // If on single achievement page, use that post's ID. 
		'posts_per_page' => dpa_is_single_achievement() ? -1                       : dpa_get_progresses_per_page(), // If on a single achievement page, don't paginate progresses.
	);
	$args = dpa_parse_args( $args, $defaults, 'has_progress' );

	// Run the query
	achievements()->progress_query = new WP_Query( $args );

	return apply_filters( 'dpa_has_progress', achievements()->progress_query->have_posts() );
}

/**
 * Whether there are more achievement progresses available in the loop. Is progresses a word?
 *
 * @since 3.0
 * @return bool True if posts are in the loop
 */
function dpa_progress() {
	return achievements()->progress_query->have_posts();
}

/**
 * Iterate the post index in the loop. Retrieves the next post, sets up the post, sets the 'in the loop' property to true.
 *
 * @since 3.0
 */
function dpa_the_progress() {
	return achievements()->progress_query->the_post();
}

/**
 * Output the achievement progress ID
 *
 * @param int $progress_id Optional
 * @see dpa_get_achievement_id()
 * @since 3.0
 */
function dpa_progress_id( $progress_id = 0 ) {
	echo dpa_get_progress_id( $progress_id );
}
	/**
	 * Return the achievement progress ID
	 *
	 * @param int $progress_id Optional
	 * @return int The achievement progress ID
	 * @since 3.0
	 */
	function dpa_get_progress_id( $progress_id = 0 ) {
		// Easy empty checking
		if ( ! empty( $progress_id ) && is_numeric( $progress_id ) )
			$the_progress_id = $progress_id;

		// Currently inside an achievement loop
		elseif ( ! empty( achievements()->progress_query->in_the_loop ) && isset( achievements()->progress_query->post->ID ) )
			$the_progress_id = achievements()->progress_query->post->ID;

		else
			$the_progress_id = 0;

		return (int) apply_filters( 'dpa_get_progress_id', (int) $the_progress_id, $progress_id );
	}

/**
 * Output the user ID of the person who made this achievement progress
 *
 * @param int $progress_id Optional. Progress ID
 * @since 3.0
 */
function dpa_progress_author_id( $progress_id = 0 ) {
	echo dpa_get_progress_author_id( $progress_id );
}
	/**
	 * Return the user ID of the person who made this achievement progress
	 *
	 * @param int $progress_id Optional. Progress ID
	 * @return int User ID
	 * @since 3.0
	 */
	function dpa_get_progress_author_id( $progress_id = 0 ) {
		$progress_id = dpa_get_progress_id( $progress_id );
		$author_id   = get_post_field( 'post_author', $progress_id );

		return (int) apply_filters( 'dpa_get_progress_author_id', (int) $author_id, $progress_id );
	}

/**
 * Output the post date and time that a user made progress on an achievement
 *
 * @param int $progress_id Optional. Progress ID.
 * @param bool $humanise Optional. Humanise output using time_since. Defaults to true.
 * @param bool $gmt Optional. Use GMT.
 * @since 3.0
 */
function dpa_progress_date( $progress_id = 0, $humanise = true, $gmt = false ) {
	echo dpa_get_progress_date( $progress_id, $humanise, $gmt );
}
	/**
	 * Return the post date and time that a user made progress on an achievement
	 *
	 * @param int $progress_id Optional. Progress ID.
	 * @param bool $humanise Optional. Humanise output using time_since. Defaults to true.
	 * @param bool $gmt Optional. Use GMT.
	 * @return string
	 * @since 3.0
	 */
	function dpa_get_progress_date( $progress_id = 0, $humanise = true, $gmt = false ) {
		$progress_id = dpa_get_progress_id( $progress_id );
		
		// 4 days, 4 hours ago
		if ( $humanise ) {
			$gmt    = ! empty( $gmt ) ? 'G' : 'U';
			$date   = get_post_time( $gmt, $progress_id );
			$time   = false; // For filter below
			$result = dpa_time_since( $date );

		// August 22, 2012 at 5:55 pm
		} else {
			$date   = get_post_time( get_option( 'date_format' ), $gmt, $progress_id );
			$time   = get_post_time( get_option( 'time_format' ), $gmt, $progress_id );
			$result = sprintf( _x( '%1$s at %2$s', '[date] at [time]', 'dpa' ), $date, $time );
		}

		return apply_filters( 'dpa_get_progress_date', $result, $progress_id, $humanise, $gmt, $date, $time );
	}

/**
 * Output the avatar link of the user who the achievement progress belongs to.
 *
 * @param array $args See dpa_get_user_avatar_link() documentation.
 * @since 3.0
 */
function dpa_progress_user_avatar( $args = array() ) {
	echo dpa_get_progress_user_avatar( $args );
}
	/**
	 * Return the avatar link of the user who the achievement progress belongs to.
	 *
	 * @param array $args See dpa_get_user_avatar_link() documentation.
	 * @return string
	 * @since 3.0
	 */
	function dpa_get_progress_user_avatar( $args = array() ) {
		$defaults = array(
			'type'    => 'avatar',
			'user_id' => dpa_get_progress_author_id(),
		);
		$r = dpa_parse_args( $args, $defaults, 'get_progress_user_avatar' );
		extract( $r );

		// Get the user's avatar link
		$avatar = dpa_user_avatar_link( array(
			'type'    => $type,
			'user_id' => $user_id,
		) );

		return apply_filters( 'dpa_get_progress_user_avatar', $avatar, $args );
	}

/**
 * Output a link to the profile of the user who the achievement progress belongs to.
 *
 * @param array $args See dpa_get_user_avatar_link() documentation.
 * @since 3.0
 */
function dpa_progress_user_link( $args = array() ) {
	echo dpa_get_progress_user_link( $args );
}
	/**
	 * Return a link to the profile of the user who the achievement progress belongs to.
	 *
	 * @param array $args See dpa_get_user_avatar_link() documentation.
	 * @return string
	 * @since 3.0
	 */
	function dpa_get_progress_user_link( $args = array() ) {
		$defaults = array(
			'type'    => 'name',
			'user_id' => dpa_get_progress_author_id(),
		);
		$r = dpa_parse_args( $args, $defaults, 'get_progress_user_link' );
		extract( $r );

		// Get the user's avatar link
		$link = dpa_user_avatar_link( array(
			'type'    => $type,
			'user_id' => $user_id,
		) );

		return apply_filters( 'dpa_get_progress_user_link', $link, $args );
	}

/**
 * Output the row class of an achievement progress object
 *
 * @param int $progress_id Optional. Progress ID
 * @since 3.0
 */
function dpa_progress_class( $progress_id = 0 ) {
	echo dpa_get_progress_class( $progress_id );
}
	/**
	 * Return the row class of an achievement progress object
	 *
	 * @param int $progress_id Optional. Progress ID
	 * @return string Row class of an achievement progress object
	 * @since 3.0
	 */
	function dpa_get_progress_class( $progress_id = 0 ) {
		$progress_id = dpa_get_progress_id( $progress_id );
		$classes     = array();
		$count       = isset( achievements()->progress_query->current_post ) ? achievements()->progress_query->current_post : 1;

		// If we've only one post in the loop, don't both with odd and even.
		if ( $count > 1 )
			$classes[] = ( (int) $count % 2 ) ? 'even' : 'odd';
		else
			$classes[] = 'dpa-single-progress';

		$classes[] = 'user-id-' . dpa_get_progress_author_id( $progress_id );
		$classes   = get_post_class( array_filter( $classes ), $progress_id );
		$classes   = apply_filters( 'dpa_get_progress_class', $classes, $progress_id );

		// Remove hentry as Achievements isn't hAtom compliant.
		foreach ( $classes as &$class ) {
			if ( 'hentry' == $class )
				$class = '';
		}
		$classes = array_merge( $classes, array() );

		$retval = 'class="' . join( ' ', $classes ) . '"';
		return $retval;
	}


/**
 * Achievement Progress pagination
 */

/**
 * Output the pagination count
 *
 * @since 3.0
 */
function dpa_progress_pagination_count() {
	echo dpa_get_progress_pagination_count();
}
	/**
	 * Return the pagination count
	 *
	 * @return string Progress pagination count
	 * @since 3.0
	 */
	function dpa_get_progress_pagination_count() {
		if ( empty( achievements()->progress_query ) )
			return;

		// Set pagination values
		$start_num = intval( ( achievements()->progress_query->paged - 1 ) * achievements()->progress_query->posts_per_page ) + 1;
		$from_num  = number_format_i18n( $start_num );
		$to_num    = number_format_i18n( ( $start_num + ( achievements()->progress_query->posts_per_page - 1 ) > achievements()->progress_query->found_posts ) ? achievements()->progress_query->found_posts : $start_num + ( achievements()->progress_query->posts_per_page - 1 ) );
		$total_int = (int) ! empty( achievements()->progress_query->found_posts ) ? achievements()->progress_query->found_posts : achievements()->progress_query->post_count;
		$total     = number_format_i18n( $total_int );

		// Several achievements within a single page
		if ( empty( $to_num ) ) {
			$retstr = sprintf( _n( 'Viewing %1$s achievement progress', "Viewing %1$s achievements&rsquo; progress", $total_int, 'dpa' ), $total );

		// Several achievements with several pages
		} else {
			$retstr = sprintf( _n( 'Viewing achievement progress %2$s (of %4$s total)', 'Viewing %1$s achievements&rsquo; progress - %2$s through %3$s (of %4$s total)', $total_int, 'dpa' ), achievements()->progress_query->post_count, $from_num, $to_num, $total );
		}

		return apply_filters( 'dpa_get_progress_pagination_count', $retstr );
	}
