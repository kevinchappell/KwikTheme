<?php

function remove_existing_editor_style() {
  global $editor_styles;
  if (is_array($editor_styles)) {
    foreach ($editor_styles as $e) {
      $e = 'editor-style.css' == $e ? '' : $e;
    }
  }
}

add_action('init', 'remove_existing_editor_style');

function kt_editor_style() {
  add_editor_style('css/editor-style.css');
}
add_action('after_setup_theme', 'kt_editor_style');

//Add button to the button array.
function register_button($buttons) {
  //Use PHP 'array_push' function to add the columnThird button to the $buttons array
  array_push($buttons, "addservicebox");
  array_push($buttons, "addpullquote");
  //Return buttons array to TinyMCE
  return $buttons;
}

//Add custom plugin to TinyMCE - returns associative array which contains link to JS file. The JS file will contain your plugin when created in the following step.
function add_custom($plugin_array) {
  $plugin_array['addservicebox'] = get_bloginfo('template_url') . '/js/editor_plugin.js';
  $plugin_array['addpullquote'] = get_bloginfo('template_url') . '/js/editor_plugin_pullquote.js';
  return $plugin_array;
}

add_action('wp_ajax_add_service_box_function_callback', 'add_service_box_function_callback');
function add_service_box_function_callback() {?>
<!DOCTYPE html>
<head>
    <title>Create a Service Box</title>
<script type="text/javascript" src="<?php bloginfo('url')?>/wp-includes/js/tinymce/tiny_mce_popup.js?ver=358-20121205"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory')?>/js/service_box_dialog.js"></script>
</head>
<body>
<form onsubmit="ServiceBox.update();return false;" action="#">
  <table border="0" cellpadding="4" cellspacing="0" role="presentation">
    <tr>
      <td colspan="2" class="title" id="app_title">Add Service Box</td>
    </tr>
    <tr>
      <td class="nowrap"><label for="anchorName">Title:</label></td>
      <td><input name="anchorName" type="text" class="mceFocus" id="anchorName" value="" style="width: 200px" aria-required="true" /></td>
    </tr>
    <tr>
      <td class="nowrap"><label for="anchorName">Content:</label></td>
      <td><textarea name="service_box_content" class="mceFocus" cols="37" id="service_box_content"></textarea></td>
    </tr>
  </table>

  <div class="mceActionPanel">
    <input type="submit" id="insert" name="insert" value="{#update}" />
    <input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
  </div>
</form>
</html>
<?php
die;
};

add_action('wp_ajax_add_pull_quote_function_callback', 'add_pull_quote_function_callback');
function add_pull_quote_function_callback() {?>
<!DOCTYPE html>
<head>
    <title>Create a Pull-Quote</title>
<script type="text/javascript" src="<?php bloginfo('url')?>/wp-includes/js/tinymce/tiny_mce_popup.js?ver=358-20121205"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory')?>/js/pull_quote_dialog.js"></script>
</head>
<body>
<form onsubmit="PullQuote.update();return false;" action="#">
  <table border="0" cellpadding="4" cellspacing="0" role="presentation">
    <tr>
      <td colspan="2" class="title" id="app_title">Add a Pull Quote</td>
    </tr>
    <tr>
      <td class="nowrap"><label for="pq_title">Title:</label></td>
      <td><input name="pq_title" type="text" class="mceFocus" id="pq_title" value="" style="width: 200px" aria-required="true" /></td>
    </tr>
    <tr>
      <td class="nowrap"><label for="pull_quote_content">Content:</label></td>
      <td><textarea name="pull_quote_content" class="mceFocus" cols="37" id="pull_quote_content"></textarea></td>
    </tr>
    <tr>
      <td class="nowrap"><label for="pull_quote_align">Alignment:</label></td>
      <td>
            <select name="pull_quote_align" class="mceFocus" id="pull_quote_align">
                <option value="block">None</option>
                <option value="alignleft">Left</option>
                <option value="aligncenter">Center</option>
                <option value="alignright">Right</option>
            </select>
            </td>
    </tr>
    <tr>
      <td class="nowrap"><label for="pull_quote_width">Width:</label></td>
      <td>
            <select name="pull_quote_width" class="mceFocus" id="pull_quote_width">
                <option value="width_10">10%</option>
                <option value="width_20">20%</option>
                <option value="width_30">30%</option>
                <option value="width_40">40%</option>
                <option value="width_60">60%</option>
                <option value="width_90">90%</option>
                <option value="">Block display</option>
            </select>
            </td>
    </tr>
  </table>

  <div class="mceActionPanel">
    <input type="submit" id="insert" name="insert" value="{#update}" />
    <input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
  </div>
</form>
</html>
<?php
die;
};