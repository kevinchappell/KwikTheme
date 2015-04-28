<form id="grid_filter" action="javascript:void(0)" class="clear">
  <?php
  $order = (isset($_GET['order']) ? $_GET['order'] : 'ASC');
  $orderby = (isset($_GET['orderby']) ? $_GET['orderby'] : 'date');
  $category = (isset($_GET['category']) ? $_GET['category'] : 'all');
  ?>
  <input type="radio" value="ASC" id="order_asc" <?php echo ($order == 'ASC' ? 'checked="checked"': ''); ?> name="grid_order" />
  <input type="radio" value="DESC" id="order_desc" <?php echo ($order == 'DESC' ? 'checked="checked"': ''); ?> name="grid_order" />
  <input type="radio" value="title" id="grid_orderby_title" <?php echo ($order == 'title' ? 'checked="checked"': ''); ?> name="grid_orderby" />
  <input type="radio" value="date" id="grid_orderby_date" <?php echo ($order == 'date' ? 'checked="checked"': ''); ?> name="grid_orderby" />
  <input type="hidden" value="<?php echo $orderby; ?>" name="orderby" />
  <input type="hidden" value="<?php echo $category; ?>" name="category" />
  <label for="order_asc" class="order_btns order_asc">ASC</label><label for="order_desc" class="order_btns order_desc">DESC</label>
  <div id="grid_sort" style="float:right;" class="custom_dropdown">Order by
    <ul class="dropdown">
      <li class="category-option" title="title"><label for="grid_orderby_title"><?php _e('Title','kwik')?></label></li>
      <li class="category-option" title="date"><label for="grid_orderby_date"><?php _e('Date','kwik')?></label></li>
    </ul>
  </div>
  <input type="text" placeholder="<?php _e('Search','kwik'); ?>" name="s_term" id="s_term" class="input">
  <?php $categories = get_categories('hide_empty=1'); ?>
  <div id="grid_category" class="custom_dropdown" tabindex="1">Category
    <ul class="dropdown">
      <?php
      echo '<li class="category-option"><label for="grid_category-all">' . __('All','kwik') . '<input type="radio" value="all" id="grid_category-all" '.($category == 'all' ? 'checked="checked"': '').' name="grid_category" /></label></li>';
      foreach($categories as $cat){
        echo '<li class="category-option"><label for="grid_category-'.$cat->slug.'">' . $cat->name . '<input type="radio" value="'.$cat->slug.'" id="grid_category-'.$cat->slug.'" '.($category == $cat->slug ? 'checked="checked"': '').' name="grid_category" /></label></li>';
      }
      ?>
    </ul>
  </div>
</form>
