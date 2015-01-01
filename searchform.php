<?php

/**
 * The template for displaying search forms in KwikTheme
 *
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */
?>

	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <span class="icon-search"></span>
	<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'kwik' ); ?>" />
		<label for="s" class="assistive-text"><?php _e( 'Search', 'kwik' ); ?></label>
		<input type="text" class="field" name="s" id="s" value="" placeholder="<?php _e('Search','kwik') ?>" />
	</form>