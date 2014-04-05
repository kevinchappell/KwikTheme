<?php

/**
 * The template for displaying search forms in OpenPower
 *
 * @subpackage OpenPower
 * @since OpenPower 1.0
 */
?>

	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">   
	<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'op' ); ?>" /> 
		<label for="s" class="assistive-text"><?php _e( 'Search', 'op' ); ?></label>
		<input type="text" class="field" name="s" id="s" value="" placeholder="<?php _e('Search','op') ?>" />
	</form>