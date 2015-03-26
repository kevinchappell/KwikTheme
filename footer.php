<?php

/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

?>

        </div><!-- .inner -->
      </div><!-- #main -->

      <footer id="footer" role="contentinfo">
        <div class="inner clear">
        <?php
        if ( is_active_sidebar( 'footer_widgets' ) ) :
          dynamic_sidebar( 'footer_widgets' );
        endif;
        ?>
          <?php //wp_nav_menu( array( 'theme_location' => 'footer', 'container' => FALSE, 'menu_class' => 'menu clear', 'fallback_cb' => FALSE)); ?>
        </div>
        <div id="copyright" class="clear inner">&copy; Copyright <?php echo date('Y') ?> - <?php bloginfo( 'name' ); ?>. All rights reserved.</div>
      </footer>
      <!-- #footer -->
    </div>
    <!-- #page -->
    <?php wp_footer(); ?>
  </body>
</html>
