<?php
/**
 * Kwik Framework
 * Description: Reusable utilities and inputs to aid in WordPress theme and plugin creation
 * Author: Kevin Chappell
 * Author URI: http://kevin-chappell.com/
 */

if (!class_exists('KwikUtils')) {
	Class KwikUtils {

		/* returns a result form url */
		private function curl_get_result($url) {
			$ch = curl_init();
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		/**
		 * fetch a resource using cURL then cache for next use.
		 * @param  [String] $url  	- url of the resource to be fetched
		 * @param  [String] $type 	- type of resource to be fetched (fonts, tweets, etc)
		 * @return [JSON]
		 */
		private function fetchCachedResource($url, $type, $expire) {
			$cache_file = dirname(__FILE__) . '/cache/' . $type;

			$last = file_exists($cache_file) ? filemtime($cache_file) : false;
			$now = time();

			// check the cache file
			if (!$last || (($now - $last) || !file_exists($cache_file) > $expire)) {

				$cache_rss = $this->curl_get_result($url);

				if ($cache_rss) {
					$cache_static = fopen($cache_file, 'wb');
					fwrite($cache_static, $cache_rss);
					fclose($cache_static);
				}
			}

			return file_get_contents($cache_file);
		}

		public function get_google_fonts($api_key) {

			$feed = "https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&fields=items(category%2Cfamily%2Cvariants)&key=" . $api_key;

			$fonts = json_decode($this->fetchCachedResource($feed, 'fonts', 1200));

			if ($fonts) {// are there any results?
				return $fonts->items;
			} else {// There are no fonts... somehow
				return false;
			}
		}

		public function get_all_post_types() {
			$all_post_types = array();
			$args = array(
				'public' => true,
				'_builtin' => true
			);
			$output = 'objects';// names or objects, note names is the default
			$operator = 'and';// 'and' or 'or'

			$default_post_types = get_post_types($args, $output, $operator);

			foreach ($default_post_types as $k => $v) {
				$all_post_types[$k]['label'] = $v->labels->name;
				$all_post_types[$k]['name'] = $v->name;
			}

			$args = array(
				'public' => true,
				'_builtin' => false
			);

			$custom_post_types = get_post_types($args, $output, $operator);

			foreach ($custom_post_types as $k => $v) {
				$all_post_types[$k]['label'] = $v->labels->name;
				$all_post_types[$k]['name'] = $v->name;
			}

			array_push($all_post_types, array('name' => '404', 'label' => __('404 Not Found', 'kwik')));

			return $all_post_types;
		}

	}//---------/ Class KwikUtils

	Class KwikInputs {

		public function positions() {
			$positions = array(
				'0 0' => 'Top Left',
				'0 50%' => 'Top Center',
				'0 100%' => 'Top Right',
				'50% 0' => 'Middle Left',
				'50% 50%' => 'Middle Center',
				'50% 100%' => 'Middle Right',
				'100% 0' => 'Bottom Left',
				'100% 50%' => 'Bottom Center',
				'100% 100%' => 'Bottom Right',
			);
			return $positions;
		}

		public function repeat() {
			$repeat = array(
				'no-repeat' => 'No Repeat',
				'repeat' => 'Repeat',
				'repeat-x' => 'Repeat-X',
				'repeat-y' => 'Repeat-Y',
			);
			return $repeat;
		}

		public function bgSize() {
			$bgSize = array(
				'auto' => 'Default',
				'100% 100%' => 'Stretch',
				'cover' => 'Cover',
			);
			return $bgSize;
		}

		public function bgAttachment() {
			$bgAttachment = array(
				'scroll' => 'Scroll',
				'fixed' => 'Fixed',
			);
			return $bgAttachment;
		}

		public function fontWeights() {
			$fontWeights = array(
				'normal' => 'Normal',
				'bold' => 'Bold',
				'bolder' => 'Bolder',
				'lighter' => 'Lighter',
			);
			return $fontWeights;
		}

		private function attrs($attrs) {
			$output = '';
			if (is_object($attrs)) {
				foreach ($attrs as $key => $val) {
					if (is_array($val)) {$val = implode(" ", $val);
					}

					$output .= $key . '="' . esc_attr($val) . '"';
				}
			}
			return $output;
		}

		/**
		 * Generate markup for input field
		 * @param  [Object] $attrs Object with properties for field attributes
		 * @return [String]        markup for desired input field
		 */
		private function input($attrs) {
			$input = '<input ' . $this->attrs($attrs) . ' />';
			return $input;
		}

		public function imgInput($name, $val) {
			$thumb = wp_get_attachment_image_src($val, 'thumbnail');
			$thumb = $thumb['0'];
			$attrs = (object) array(
				'type' => 'hidden',
				'name' => $name,
				'class' => 'img_id',
				'value' => $val,
			);
			$img_input = $this->input($attrs);
			$img_input .= '<img src="' . $thumb . '" class="img_prev" width="23" height="23" title="' . get_the_title($val) . '"/><span id="site_bg_img_ttl" class="img_title">' . get_the_title($val) . (!empty($val) ? '<span title="' . __('Remove Image', 'kwik') . '" class="clear_img tooltip"></span>' : '') . '</span><input type="button" class="upload_img" id="upload_img" value="+ ' . __('IMG', 'kwik') . '" />';
			return $img_input;
		}

		public function textInput($name, $val) {
			$attrs = (object) array(
				'type' => 'text',
				'name' => $name,
				'class' => 'op_text',
				'value' => $val,
			);
			return $this->input($attrs);
		}

		public function spinner($name, $val) {
			$attrs = (object) array(
				'type' => 'number',
				'name' => $name,
				'class' => 'kf_spinner',
				'max' => '50',
				'min' => '1',
				'value' => $val,
			);
			return $this->input($attrs);
		}

		public function colorInput($name, $val) {
			$attrs = (object) array(
				'type' => 'text',
				'name' => $name,
				'class' => 'cpicker',
				'value' => $val,
			);
			$color_input = $this->input($attrs);
			if (!empty($val)) {$color_input .= '<span class="clear_color tooltip" title="' . __('Remove Color', 'kwik') . '"></span>';
			}

			return $color_input;
		}

		public function selectInput($name, $val, $options) {
			$select_input = '<select name="' . $name . '">';
			foreach ($options as $k => $v) {
				$select_input .= '<option ' . selected($k, $val, false) . ' value="' . $k . '">' . $v . '</option>';
			}

			$select_input .= '</select>';
			return $select_input;
		}

		public function fontFamilyInput($name, $cur_val) {
			$utils = new KwikUtils();
			$fonts = $utils->get_google_fonts('AIzaSyDTUcM9QmvxwUdg2cAJQArNWPaAjAnP--E');
			$options = array();
			foreach ($fonts as $font) {
				$options[str_replace(' ', '+', $font->family)] = $font->family;
			}
			return $this->selectInput($name, $cur_val, $options);
		}

	}//---------/ Class KwikInputs

	Class Validate extends KwikInputs {

		public function validateFont($val) {
			$font = array(
				'color' => $this->color($val['color']),
				'weight' => wp_filter_nohtml_kses($val['weight']),
				'size' => wp_filter_nohtml_kses($val['size']),
				'line-height' => wp_filter_nohtml_kses($val['line-height']),
				'font-family' => wp_filter_nohtml_kses($val['font-family'])
			);
			return $font;
		}

		public function linkColor($val) {
			$link_color = array(
				'default' => $this->color($val['default']),
				'visited' => $this->color($val['visited']),
				'hover' => $this->color($val['hover']),
				'active' => $this->color($val['active'])
			);

			return $link_color;
		}

		public function color($val) {
			$color = (isset($val) && preg_match('/^#?([a-f0-9]{3}){1,2}$/i', $val)) ? '#' . strtolower(ltrim($val, '#')) : '';
			return $color;
		}

		public function validateHeaders($val) {
			$headers = array();
			$utils = new KwikUtils();
			$post_types = $utils->get_all_post_types();

			foreach ($post_types as $type) {
				$headers[$type['name']] = array(
					'color' => $this->color($val[$type['name']]['color']),
					'weight' => wp_filter_nohtml_kses($val[$type['name']]['weight']),
					'size' => wp_filter_nohtml_kses($val[$type['name']]['size']),
					'line-height' => wp_filter_nohtml_kses($val[$type['name']]['line-height']),
					'font-family' => wp_filter_nohtml_kses($val[$type['name']]['font-family']),
					'bg_color' => $this->color($val[$type['name']]['bg_color']),
					'img' => wp_filter_nohtml_kses($val[$type['name']]['img']),
					'position' => wp_filter_nohtml_kses($val[$type['name']]['position']),
					'repeat' => wp_filter_nohtml_kses($val[$type['name']]['repeat']),
					'bg_size' => wp_filter_nohtml_kses($val[$type['name']]['bg_size']),
					'attachment' => wp_filter_nohtml_kses($val[$type['name']]['img']),
					'text' => wp_filter_nohtml_kses($val[$type['name']]['text'])
				);
			}

			return $headers;
		}

	}
}
