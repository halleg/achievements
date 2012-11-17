<?php
/**
 * Single Achievement content part
 *
 * @package Achievements
 * @subpackage ThemeCompatibility
 */
?>

<div id="dpa-achievements">

	<?php dpa_breadcrumb(); ?>

	<?php do_action( 'dpa_template_before_single_achievement' ); ?>

	<div id="dpa-achievement-<?php dpa_achievement_id(); ?>" <?php dpa_achievement_class(); ?>>

		<?php do_action( 'dpa_template_before_achievement_thumbnail' ); ?>

			<?php if ( has_post_thumbnail() ) : ?>

				<?php the_post_thumbnail( 'medium', array( 'class' => 'attachment-medium dpa-single-achievement-thumbnail' ) ); ?>

			<?php endif; ?>

		<?php do_action( 'dpa_template_after_achievement_thumbnail' ); ?>


		<?php do_action( 'dpa_template_before_achievement_content' ); ?>

		<?php dpa_achievement_content(); ?>

		<?php do_action( 'dpa_template_after_achievement_content' ); ?>


		<?php if ( dpa_has_progress() ) : ?>

			<?php dpa_get_template_part( 'loop-progresses' ); ?>

		<?php else : ?>

			<?php dpa_get_template_part( 'feedback-no-progresses' ); ?>

		<?php endif; ?>

	</div><!-- #dpa-achievement-<?php dpa_achievement_id(); ?> -->

	<?php do_action( 'dpa_template_after_single_achievement' ); ?>

</div>
