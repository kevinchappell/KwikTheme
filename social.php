 <?php 
 $options = op_get_theme_options(); 

 ?>
       <ul id="social_links">
          <li class="blog_link"><a href="<?php bloginfo('url'); ?>/category/blog/"><?php _e('BLOG','op');?></a></li>
          <?php	
		  $social_networks = array();

		  if($options['social_networks'][0] != '') $social_networks[] = '<li class="fb"><a href="https://www.facebook.com/Beatsolorg" target="_blank" title="'.__('Visit our Facebook page','op').'">'.__('facebook','op').'</a></li>';
		  if($options['social_networks'][1] != '') $social_networks[] = '<li class="twit"><a href="https://twitter.com/beatsolorg" target="_blank" title="'.__('Tweet This','op').'">'.__('twitter','op').'</a></li>';
          if($options['social_networks'][2] != '') $social_networks[] = '<li class="linkedin"><a href="https://www.linkedin.com/company/'.$options['social_networks'][2].'" target="_blank" title="'.__('Visit our LinkedIn page','op').'">'.__('linkedin','op').'</a></li>';
		  if($options['social_networks'][3] != '') $social_networks[] = '<li class="youtube"><a href="http://www.youtube.com/'.$options['social_networks'][3].'" target="_blank" title="'.__('Watch our videos','op').'">'.__('Watch our videos','op').'</a></li>';
		  $social_networks[] = '<li class="rss"><a href="http://beatsol.org/feed" target="_blank" title="'.__('RSS Feed','op').'">'.__('RSS','op').'</a></li>';

		  foreach($social_networks as $social_network){			  
			  echo $social_network;			  
		}
		
		  
          ?>
      </ul>   
