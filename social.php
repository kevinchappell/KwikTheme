 <?php 
 $options = KwikThemeOptions::kt_get_options(); 

 ?>
       <ul id="social_links">
          <li class="blog_link"><a href="<?php bloginfo('url'); ?>/category/blog/"><?php _e('BLOG','kwik');?></a></li>
          <?php	
		  $social_networks = array();

		  if($options['social_networks'][0] != '') $social_networks[] = '<li class="fb"><a href="https://www.facebook.com/Beatsolorg" target="_blank" title="'.__('Visit our Facebook page','kwik').'">'.__('facebook','kwik').'</a></li>';
		  if($options['social_networks'][1] != '') $social_networks[] = '<li class="twit"><a href="https://twitter.com/beatsolorg" target="_blank" title="'.__('Tweet This','kwik').'">'.__('twitter','kwik').'</a></li>';
          if($options['social_networks'][2] != '') $social_networks[] = '<li class="linkedin"><a href="https://www.linkedin.com/company/'.$options['social_networks'][2].'" target="_blank" title="'.__('Visit our LinkedIn page','kwik').'">'.__('linkedin','kwik').'</a></li>';
		  if($options['social_networks'][3] != '') $social_networks[] = '<li class="youtube"><a href="http://www.youtube.com/'.$options['social_networks'][3].'" target="_blank" title="'.__('Watch our videos','kwik').'">'.__('Watch our videos','kwik').'</a></li>';
		  $social_networks[] = '<li class="rss"><a href="http://beatsol.org/feed" target="_blank" title="'.__('RSS Feed','kwik').'">'.__('RSS','kwik').'</a></li>';

		  foreach($social_networks as $social_network){			  
			  echo $social_network;			  
		}
		
		  
          ?>
      </ul>   
