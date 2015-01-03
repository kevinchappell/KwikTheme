<?php

/**
 * The sidebar containing the front page widget areas.
 *
 * If no active widgets in either sidebar, they will be hidden completely.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

$utils = new KwikUtils();
if ( ! is_active_sidebar( 'sidebar-horz-1' ) && ! is_active_sidebar( 'sidebar-horz-2' ) ) return;

// If we get this far, we have widgets. Let do this.
 if ( is_active_sidebar( 'sidebar-horz-1' ) ) : ?>
	<div class="first front-widgets clear <?php echo $utils->number_to_class($utils->widget_count( 'sidebar-horz-1', false )); ?>">
		<?php dynamic_sidebar( 'sidebar-horz-1' ); ?>
	</div><!-- .first -->
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'sidebar-horz-2' ) ) : ?>
	<div class="second front-widgets <?php echo $utils->number_to_class($utils->widget_count( 'sidebar-horz-2', false )); ?>">
		<?php dynamic_sidebar( 'sidebar-horz-2' ); ?>
	</div><!-- .second -->
<?php endif; ?>
