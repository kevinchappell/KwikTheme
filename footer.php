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
  <div class="inner">
  <?php if ( is_active_sidebar( 'footer_widgets' ) ) : dynamic_sidebar( 'footer_widgets' ); ?><?php endif; ?>
    <?php wp_nav_menu( array( 'menu' => 'Footer Menu', 'container' => false, 'menu_class' => 'menu clear')); ?>   
    <div id="copyright" class="clear">&copy; Copyright by KwikTheme Foundation. All rights reserved.</div> 
  </div>
</footer>
<!-- #footer -->
</div>
<!-- #page -->
<?php wp_footer(); ?>
</body>
</html>