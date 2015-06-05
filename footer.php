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
        <?php if ( is_active_sidebar( 'footer_widgets' ) ) : ?>
        	<div class="inner clear">
        	<?php dynamic_sidebar( 'footer_widgets' ); ?>
        	</div>
	    <?php endif; ?>
        <div id="copyright" class="clear inner">&copy; Copyright <?php echo date('Y') ?> - <?php bloginfo( 'name' ); ?>. All rights reserved.</div>
      </footer>
      <!-- #footer -->
    </div>
    <!-- #page -->
    <?php wp_footer(); ?>
    <script type="text/javascript">document.getElementById('main').style.paddingBottom = document.getElementById('footer').offsetHeight + 20 + 'px';</script>
  </body>
</html>
