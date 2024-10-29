<?php
/*
Plugin Name: AJS Footnotes
Plugin URI: http://www.ajseidl.com/
Version: 1.2
Description: Allows a writer to easily add attractive footnotes to a post, and control their presentation.
Author: Adam J. Seidl
Author URI: http://www.ajseidl.com/
*/

/*
 * The plugin was inspirted by WP Footnotes (http://elvery.net/drzax/wordpress-footnotes-plugin)
 * by Simon Elvery (http://www.elvery.net/drzax/)
 */

/*
 * This file is part of AJS-Footnotes a plugin for WordPress
 * Copyright (C) 2013 Adam J. Seidl
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * TODO: Add page specific options (whether to run or not) (next version)
 * TODO: Add options to adjust positioning (next version)
 */

/*Constants*/
define('AJS_FOOTNOTES_VERSION','1.7');

/*Generate Instance*/
$ajs_footnotes = new ajs_footnotes();

/*Grab the options page...*/
include_once(dirname(__FILE__).'/options.php');

class ajs_footnotes {
	private $options, $default_options, $match_count, $footnotes;
	
	function __construct() {
		$this->match_count = 0;
		$this->footnotes = array();
		$this->errors = array();
		//setup default options array
		$this->default_options = array(
			//Plugin functions
			'number_system' => 'lower-alpha',
			'cite_number_location' => 'sup',
			'encode_html_specials' => 'false',
			'display_notes_list' => 'true',
			'display_popups' => 'true',
			'wrote_js' => 'false',
			'wrote_css' => 'false',
			'js_version' => '1',
			'css_version' => '1',
			//Cite pre-post text
			'cite_number_pre' => '',
			'cite_number_post' => '',
				
			//Run statuses
			'run_status_post' => 'true',
			'run_status_page' => 'true',
			'run_status_home' => 'true',
			'run_status_archive' => 'true',
			'run_status_feed' => 'strip',
			'run_status_search' => 'strip',
			
			//CSS Classes & JS IDs
			'cite_link_class' => 'ajs-footnote',
			'note_list_class' => 'ajs-fn',
			'backlink_text' =>  '(back)',
			'id_lead' => 'ajs-fn-id',
			//Popups
		/**
		 * TODO: Add popup_note_position logic
		 */
			'popup_note_position' => 'top left',
			
			'popup_container_element' => 'div',
			'popup_display_effect_in' => 'fadeIn',
			'popup_display_effect_out' => 'fadeOut',
			'popup_duration_in' => 200,
			'popup_duration_out' => 200,
			'popup_border_color' => '#888888',
			'popup_border_style' => 'solid',
			'popup_border_width' => 1,
			'popup_border_width_unit' => 'px',
			'footnotes_open' => '((',
			'footnotes_close' => '))',			
			
			//Styling
			//footnote list
			'footnote_list_color' => '#666666',
			'footnote_list_size' => '0.8',
			'footnote_list_size_unit' => 'em',
			//popups
			'popup_z_index' => '999',
			'popup_adjustment_top' => '0',
			'popup_adjustment_left' => '0',
			'popup_text_size' => 1,
			'popup_text_size_unit' => 'em',
			'popup_min_width' => '60',
			'popup_min_width_unit' => 'px',
			'popup_max_width' => '300',
			'popup_max_width_unit' => 'px',
			'popup_bgcolor' => '#f8f8f8',
			'popup_fgcolor' => '000',
			'popup_padding_right' => '1',
			'popup_padding_right_unit' => 'em',				
			'popup_padding_left' => '1',
			'popup_padding_left_unit' => 'em',
			'popup_padding_top' => 0.5,
			'popup_padding_top_unit'=> 'em',
			'popup_padding_bottom' => 0.5,
			'popup_padding_bottom_unit' => 'em',
			'popup_shadow_hval' => 0.3,
			'popup_shadow_h_unit' => 'em',
			'popup_shadow_vval' => 0.3,
			'popup_shadow_v_unit' => 'em',
			'popup_shadow_blur' => 0.3,
			'popup_shadow_blur_unit' => 'em',
			'popup_shadow_spread' => 0,
			'popup_shadow_spread_unit' => 'px',
			'popup_shadow_color' => '#ebebeb',
			'popup_top_left_corner' => '4',
			'popup_top_left_corner_unit' => 'px',
			'popup_top_right_corner' => '4',
			'popup_top_right_corner_unit' => 'px',
			'popup_bottom_left_corner' => '4',
			'popup_bottom_left_corner_unit' => 'px',
			'popup_bottom_right_corner' => '4',
			'popup_bottom_right_corner_unit' => 'px',
			'version' => AJS_FOOTNOTES_VERSION	
		);

		//get options from the DB if they're there
		if (!$this->options = get_option('ajs_footnote_options')){
			//set the default options as the options...
			$this->options = $this->default_options;
			update_option('ajs_footnote_options', $this->options);
		} else {
			//Set any option that has no value
			if ($this->options['version'] != AJS_FOOTNOTES_VERSION) {
				$this->options = $this->options + $this->default_options;
			}
			
			$this->options['version'] = AJS_FOOTNOTES_VERSION;
			update_option('ajs_footnote_options', $this->options);
		}

		
		if( !empty($_POST['save_options'])) {
			$this->errors = $this->checkPostValues($_POST);
			//no errors, then we merge with the default options and save...
			if( count($this->errors) == 0 ) {
				$this->options = $_POST + $this->default_options;
				update_option('ajs_footnote_options', $this->options);
			} 
		} elseif (!empty($_POST['reset_options'])) {
			update_option('ajs_footnote_options', '');
			update_option('ajs_footnote_options', $this->default_options);
		}
		
		//Hooks
		add_action('the_content', array($this, 'make_notes')); //process the content
		//assign the js and css to run
		add_action('wp_footer', array($this, 'q_scripts'));
		add_action('wp_head', array($this, 'insertCSS')); //insert proper CSS styling
		//if($this->options['display_popups']) {
		//	add_action('wp_enqueue_scripts', array($this, 'q_scripts'));
			//add_action('wp_footer', array($this, 'insertJavaScript'));
		//}
		add_action('admin_enqueue_scripts', array($this, 'enqueueAdminScripts'));
		add_action('admin_menu', array($this, 'addOptionsPage')); //so you can edit opts
	}
	
	/**
	 * Looks through the content and greps footnotes.
	 * @param $content string The post content via WP
	 * @return string The new content with footnotes inserted
	 */
	public function make_notes($content) {
		global $post;
		if ( $this->getRunStatus() != 'false') {
			//have to empty array for Blog roll
			$this->footnotes = array();
			$this->match_count = 0;
			$notated_content = ''; //holds the content
			$note_list = '';
			$popup_list = '';
			/**
			 * TODO: Deal with pagination nightmare (maybe next version)
			 */
			//Retrieve the footnotes from the text and replace them with the appropriate reference using a callback
			$notated_content=  preg_replace_callback("/(".preg_quote($this->options['footnotes_open'])."|<footnote>)(.*)(".preg_quote($this->options['footnotes_close'])."|<\/footnote>)/Us", array(&$this, 'remove_note_content'), $content);
			if ( $this->match_count == 0 ) {
				return $content;
			} else {
				
				
				if( $this->getRunStatus() == 'true' ) {
					//Populate the notes list (if such a thing exists)
					if ($this->options['display_notes_list']) {
						$note_list = '<ol class="'.$this->options['note_list_class'].'">'.PHP_EOL;
						for($ii = 0; $ii < count($this->footnotes); $ii++) {
							$note_list .= '<li><a id="link_'.$this->options['id_lead'].'_'.($ii+1).'-'.$post->ID.'"></a>'.$this->footnotes[$ii].'&nbsp;&nbsp;<a class="ajs-back-link" href="#back_'.$this->options['id_lead'].'_'.($ii + 1).'-'.$post->ID.'">'.$this->options['backlink_text'].'</a></li>'.PHP_EOL;
						}
						$note_list .= '</ol>'.PHP_EOL;
					}
					
					if($this->options['display_popups']) {
						for($ii = 0; $ii < count($this->footnotes); $ii++) {
							$popup_list .= $this->create_popups($ii);
						}
					}
					
					$notated_content .= $note_list.$popup_list;
				}
				return $notated_content;
			}
		} else { //don't run the plugin here
				return $content;
		}
	}
	/**
	 * This function creates the containers that hold the popup notes.
	 * @param string $number The index of this note in the $this->footnotes array
	 * @return string The HTML to produce the popup.
	 */
	private function create_popups($number) {
		global $post;
		$note_content = ($this->options['encode_html_specials'] == 'true')? htmlentities($this->footnotes[$number]) : $this->footnotes[$number];
		$container_open = '<'.$this->options['popup_container_element'].' id="'.$this->options['id_lead'].'_'.($number+1).'-'.$post->ID.'" style="display:none; position:absolute;" class="ajs-footnote-popup">';
		$container_open .= '<'.$this->options['popup_container_element'].'>';
		$container_close = str_repeat('</'.$this->options['popup_container_element'].'>', 2);
		
		return $container_open.$note_content.$container_close;
	}
	
	/**
	 * This function logs the matched footnote and returns the formated identifier to preg_replace
	 * @param array $matches
	 * @return string The formatted footnote identifier
	 */
	private function remove_note_content($matches) {
		global $post;
		$this->match_count++;
		if($this->getRunStatus() == 'true') {
			//slice off the identifying strings
			$matched_string = array_slice($matches, 2, -1);
			$this->footnotes[] = implode('', $matched_string);
			//Convert (if needed)
			$display_value = $this->options['cite_number_pre'].$this->convert_numeral($this->match_count).$this->options['cite_number_post'];
			
			//Return the numbered reference
			$numbered_link = '<a href="#link_'.$this->options['id_lead'].'_'.$this->match_count.'-'.$post->ID.'" id="back_'.$this->options['id_lead'].'_'.$this->match_count.'-'.$post->ID.'">'.$display_value.'</a>';
			//Format the reference character(s)
			if ($this->options['cite_number_location'] == 'sup') {
				$output = '<sup class="'.$this->options['cite_link_class'].'">'.$numbered_link.'</sup>';
			} else if($this->options['cite_number_location'] == 'sub') {
				$output = '<sub class="'.$this->options['cite_link_class'].'">'.$numbered_link.'</sub>';
			} else { //inline
				$output = '<span class="'.$this->options['cite_link_class'].'">'.$numbered_link.'</span>';	
			}
					
			return $output;
		} else {
			return '';
		}
	}
	/**
	 * This function enqueues the JS used by thte plugins
	 */
	public function q_scripts() {
		//echo 'HI HO'.$this->match_count;
		//stops the javascript from showing up on pages where settings say it's not neccessary
		if ( 'true' != $this->getRunStatus() ||  0 == $this->match_count ) {
			echo $this->getRunStatus();
			return;
		}
		wp_enqueue_script(
			'hoverIntent',
			plugins_url('js/hoverIntent.js', __FILE__),
			array('jquery'),
			null,
			true
		);
		wp_enqueue_script(
			'ajs-footnotes',
			plugins_url('js/ajs-footnotes.js', __FILE__),
			array('jquery'),
			null,
			true
		);
	}
	/**
	 * This function inserts the jQuery required to make the popups work. (It never runs if popups are disabled.
	 */
	public function insertJavaScript() {
		/**
		 * TODO: Make sure popups will fit on screen (requires jqueryui; postponed until next version.)
		 */
	?>
	<script type="text/javascript">
	jQuery(function($){
		var doNothing = false;
		/* OLD CODE*/
		<?php 
		/**
		* TODO: remove
		*/
		?>
		$('.ajs-footnote>a').hoverIntent({
			over: function(event) {
				//console.log($(event.target).offset());
				//if there's a popup showing, make it go away...
				$('.ajs-footnote-popup').hide();
				var noteNumber = ($(event.target).attr('href')).substring(($(event.target).attr('href')).lastIndexOf('_')+1);
				var docWidth = $(document).width();
				var offset = $(event.target).offset();
				var noteWidth = $('#<?php echo $this->options['id_lead'];?>_'+noteNumber).css({display:'none', visibility:'hidden'}).width());

				//$('#<?php echo $this->options['id_lead'];?>_'+noteNumber).css({position:'absolute', top: (offset.top - $('#<?php echo $this->options['id_lead'];?>_'+noteNumber).height() )+'px', left:offset.left+'px'}).<?php echo $this->options['popup_display_effect_in'];?>(<?php echo $this->options['popup_display_effect_in']!='show'? $this->options['popup_duration_in'] :'';?>);
				//$('#<?php echo $this->options['id_lead'];?>_'+noteNumber).
				
			}, 
			out: function(event) {
				if(!doNothing){
					var noteNumber = ($(event.target).attr('href')).substring(($(event.target).attr('href')).lastIndexOf('_')+1);
					$('#<?php echo $this->options['id_lead'];?>_'+noteNumber).<?php echo $this->options['popup_display_effect_out'];?>(<?php echo $this->options['popup_display_effect_out']!='hide'? $this->options['popup_duration_out'] :'';?>);
				}
			},
			timeout: 500,
		});

		
		$('.ajs-footnote-popup').hoverIntent({
			over: function(event) {
				doNothing = true;
			},
			out: function(event) {
				doNothing = false;
				$(this).<?php echo $this->options['popup_display_effect_out'];?>(<?php echo $this->options['popup_display_effect_out']!='hide'? $this->options['popup_duration_out'] :'';?>);
			},
			timeout:500,
		});
	});
	</script>
	<?php 		
	}
	
	public function insertCSS() {
		?>
		<style type="text/css" media="all">
		<?php 
		if( $this->options['display_notes_list']) { ?>
			ol.<?php echo $this->options['note_list_class'];?> {
				list-style-type: <?php echo $this->options['number_system']?>;
				font-size: <?php echo $this->options['footnote_list_size'].$this->options['footnote_list_size_unit'];?>;
				color: <?php echo $this->options['footnote_list_color'];?>
			}
		<?php 
		} //end if(display_notes_list)
			
		if( $this->options['display_popups']) { ?>
			<?php echo $this->options['popup_container_element'];?>.ajs-footnote-popup {
				position:relative;
				z-index: <?php echo $this->options['popup_z_index'];?>
				
			}
			<?php echo $this->options['popup_container_element'];?>.ajs-footnote-popup <?php echo $this->options['popup_container_element'];?> {
				color: <?php echo $this->options['popup_fgcolor'];?>;
				background-color: <?php echo $this->options['popup_bgcolor'];?>;
				min-width: <?php echo $this->options['popup_min_width'].$this->options['popup_min_width_unit'];?>;
				max-width: <?php echo $this->options['popup_max_width'].$this->options['popup_max_width_unit']?>;
				padding: <?php echo $this->options['popup_padding_top'].$this->options['popup_padding_top_unit'].' '.$this->options['popup_padding_right'].$this->options['popup_padding_right_unit'].' '.$this->options['popup_padding_bottom'].$this->options['popup_padding_bottom_unit'].' '.$this->options['popup_padding_left'].$this->options['popup_padding_left_unit'].';'?>
				border: <?php echo $this->options['popup_border_width'].$this->options['popup_border_width_unit'];?> <?php echo $this->options['popup_border_color'];?> <?php echo $this->options['popup_border_style'];?>;
				box-shadow: <?php echo $this->options['popup_shadow_hval'].$this->options['popup_shadow_h_unit'].' '.$this->options['popup_shadow_vval'].$this->options['popup_shadow_v_unit'].' '.$this->options['popup_shadow_blur'].$this->options['popup_shadow_blur_unit'].' '.$this->options['popup_shadow_spread'].$this->options['popup_shadow_spread_unit'].' '.$this->options['popup_shadow_color'];?>;
				border-radius: <?php echo $this->options['popup_top_left_corner'].$this->options['popup_top_left_corner_unit'];?> <?php echo $this->options['popup_top_right_corner'].$this->options['popup_top_right_corner_unit'];?> <?php echo $this->options['popup_bottom_right_corner'].$this->options['popup_bottom_right_corner_unit'];?> <?php echo $this->options['popup_bottom_left_corner'].$this->options['popup_bottom_left_corner_unit'];?>;
			}
			.ajs-footnote {line-height:1; display:inline-block;}
			.ajs-footnote a {display:block; width:100%;}
		<?php 
		}// end if(display_popups)

		?>
		</style>
		<?php
	}//ends insertCSS()
	
	public function enqueueAdminScripts() {
		wp_register_script('colorPicker', plugins_url('js/spectrum.js', __FILE__), 'jquery');
		wp_register_script('dd', plugins_url('js/jquery.dd.min.js', __FILE__), 'jquery');
		wp_enqueue_script('colorPicker');
		wp_enqueue_script('dd');
		wp_enqueue_script('jquery-ui-button');
	}
	
	/**
	 * Sets up the options page using the options.php file
	 */
	public function addOptionsPage() {
		add_options_page('AJS Footnotes', 'AJS Footnotes', 'manage_options', __FILE__, array($this, 'createOptionsPage'));
	}
	
	private function generateJS() {
		$notePosition = explode($this->options['popup_note_position'], ' ');
		$output = <<<EOJS
/**
 * This file is part of the AJS Footnotes Wordpress Plugin, by Adam J. Seidl.
 * See http://www.ajseidl.com/projects/ajs-footnotes/ for details & support.
 * @version 2.0
 */
jQuery(function($){
	var docDim, noteVPos, noteHPos, doNothing, adjustments;
	noteVPos = '$notePosition[0]';
	noteHPos = '$notePosition[1]';
	docDim = { 'width': $(document).width(), 'height': $(document).height() };
	doNothing = false;
	//top is an ABSOLUTE measure of distance FROM the note link
	adjustments = { top: {$this->options['popup_adjustment_top']}, left: {$this->options['popup_adjustment_left']} };

	$('.ajs-footnote-popup').css({});
	//get the link containers
	$('.ajs-footnote>a').hoverIntent({
			over: function( event ) {
				var noteNumber, noteWidth, thisNote, noteVdelta, noteHdelta, eOffset;
				//get note number
				noteNumber = ($(event.target).attr('href')).substring(($(event.target).attr('href')).lastIndexOf('_')+1);
				thisNote = '#ajs-fn-id'+'_'+noteNumber;
				noteWidth = $(thisNote)
					.css({display:'none', visibility:'hidden'})
					.outerWidth();
				noteHeight = $(thisNote).outerHeight();
				
				//Math time...
				/**
				 * TODO: Adjust V & H for document fit
				 */
				switch ( noteVPos ) {
				case 'top':
					noteVdelta = -1* (noteHeight + adjustments.top);
					$(thisNote).css({paddingBottom: $(event.target).height()+adjustments.top});
					break;
				case 'bottom':
					noteVdelta = 0;
					$(thisNote).css({paddingTop: $(event.target).height()+adjustments.top})
					break;
				default:
					noteVdelta = -1*Math.round(noteHeight/2 -  $(event.target).height()/2);
				} //end noteVPos switch
				
				switch ( noteHPos ) {
				case 'right':
					noteHdelta = -1*(noteWidth - adjustments.left);
					( adjustments.left <= 0 ) ? $(thisNote).css({paddingRight: $(event.target).width() + -1*(adjustments.left) }) : noteHdelta += adjustments.left;
					break;
				case 'center':
					noteHdelta = -1*Math.round(noteWidth/2);
					break;
				default:
					noteHdelta = 0;
					( adjustments.left >= 0 ) ? $(thisNote).css({ paddingLeft: $(event.target).width()+adjustments.left }) : noteHdelta = adjustments.left;
				} //end noteHPos switch
				
				//get the event offset
				eOffset = $(event.target).offset();
				console.log(eOffset.top);
				
				$(thisNote).css({position:'absolute', top: eOffset.top+noteVdelta+'px', left: eOffset.left+noteHdelta+'px', display: 'block', visibility:'visible'});
				console.log(noteWidth+ ' X '+noteHeight);
			}, 
			out: function( event ) {
				var noteNumber, thisNote;
				
				noteNumber = ($(event.target).attr('href')).substring(($(event.target).attr('href')).lastIndexOf('_')+1);
				thisNote = '#ajs-fn-id'+'_'+noteNumber;
				if( !doNothing ) {
					$(thisNote).css({display:'none'});
				}
			},
			timeout: 500
	});//end .ajs-footnote hoverIntent
	$('.ajs-footnote-popup').hoverIntent({
		over: function(event) {
			doNothing = true;
		},
		out: function(event) {
			doNothing = false;
			$(this).hide();
		},
		timeout:500,
	});
	
});	
EOJS;
	}
	
	/**
	 * Actually creates the HTML, JS, and CSS for the options page
	 */
	public function createOptionsPage() {
		$this->options = get_option('ajs_footnote_options');
		foreach($this->options as $key=>$val) {
			if($key != 'backlink_text') {
				$new_opts[$key] = htmlentities2($val);
			} else {
				$new_opts[$key] = $val;
			}
		}
		$this->options = $new_opts;
		unset($new_opts);
		if(count($this->errors > 0)) {
			$optionsPage = new optionsPage($this->options, $this->errors);
		} else {
			$optionsPage = new optionsPage($this->options);
		}
	}
	
	/**
	 * A convience method that returns the run status option for the current content type
	 * @return string true, false, or strip
	 */
	private function getRunStatus() {
		if ( is_single() ) {
			return $this->options['run_status_post'];
		}
		if ( is_page() ) {
			return $this->options['run_status_page'];
		}
		if ( is_home() ) {
			return $this->options['run_status_home'];
		}
		if ( is_feed() ) {
			return $this->options['run_status_feed'];
		}
		if ( is_search() ) {
			return $this->options['run_status_search'];
		}
		if ( is_archive() ) {
			return $this->options['run_status_archive'];
		}
		return "true";
	}
	
	/**
	 * This function converts a decimal numeral into the requested format
	 * @param int $n The decimal number
	 * @return string Representing the formated number
	 */
	private function convert_numeral($n) {
		if($this->options['number_system'] == 'decimal') {
			return $n;
		}
		$converted_number = null;
		switch($this->options['number_system']) {
			case 'decimal-leading-zero':
				$w = max(2, strlen($n));
				$converted_number = sprintf("%0{$w}d", $n);
				break;
			case 'lower-roman':
				$converted_number = $this->convertDecToRoman($n, true);
				break;
			case 'upper-roman':
				$converted_number = $this->convertDecToRoman($n);
				break;
			case 'lower-alpha':
				$converted_number = $this->convertDecToAlpha($n, true);
				break;
			case 'upper-alpha':
				$converted_number = $this->convertDecToAlpha($n);
				break;
			default:
				$converted_number = $n;
		}
		return $converted_number;
	}
	
	/**
	 * This function turns a decimal value into its Roman Numeral equivalent
	 * @param int $n The decimal number we're converting
	 * @param bool $mode If mode is true, the value is returned in lower case
	 * @return string The Roman Numeral representation of the decimal $n
	 */
	private function convertDecToRoman($n, $mode = false) {
		//Alogrithm by pradeep @ http://www.go4expert.com/articles/roman-php-t4948/
		$lookup = array(
				'M' => 1000,
				'CM' => 900, 
				'D' => 500, 
				'CD' => 400,
				'C' => 100,
				'XC' => 90,
				'L' => 50,
				'XL' => 40,
				'X' => 10,
				'IX' => 9,
				'V' => 5,
				'IV'=> 4,
				'I' => 1
				);
		$result = '';
		foreach ($lookup as $roman=>$decimal) {
			$matches = intval($n / $decimal);
			$result .= str_repeat($roman, $matches);
			$n = $n % $decimal;
		}
		return ($mode)? strtolower($result) : $result;
	}
	/**
	 * This function turns a decimal value into a corresponding letter (or repeated letter) so 27 = AA.
	 * @param int $n The decimal value to convert
	 * @param bool $mode If true, lower case letter(s) returned
	 * @return string The decimal as an alphabetic representation
	 */
	private function convertDecToAlpha($n, $mode = false) {
		$letter = chr(($n % 26)+96);
		$letter .= (floor($n/26))? str_repeat($letter, floor($n/26)) : '';
		return ($mode)? $letter : strtoupper($letter);
	}
	/**
	 * This function checks to see if the submitted values are valid.
	 * @param array $_POST values passed from Options
	 */
	private function checkPostValues($post) {
		
		$errors = array();
		/*Popup & Footnote Values*/
		$numeric = array(
			'popup_duration_in',
			'popup_duration_out',
			'popup_border_width',
			'popup_min_width',
			'popup_max_width',
			'popup_text_size',
			'popup_padding_right',
			'popup_padding_left',
			'popup_padding_top',
			'popup_padding_bottom',
			'popup_shadow_hval',
			'popup_shadow_vval',
			'popup_shadow_blur',
			'popup_shadow_spread',
			'popup_top_left_corner',
			'popup_top_right_corner',
			'popup_bottom_left_corner',
			'popup_bottom_right_corner',
			'footnote_list_size'
		);

		$hexColor = array(
			'popup_bgcolor',
			'popup_fgcolor',
			'popup_shadow_color',
			'popup_border_color',
			'footnote_list_color'			
		);

		//Merge Color Checks
		foreach ( $numeric as $value ) {
			
			if (!isset($post[$value])) {
				$errors[$value] = 'You must enter a value here.';
			} else {
				if (!is_numeric($post[$value])) {
					$errors[$value] = 'This value must be a number.';
				}
			}
		}
		
		/*foreach ( $hexColor as $value ) {
			if (!isset($post[$value])) {
				$errors[$value] = 'You must enter a value here.';
			} else {
				if (!preg_match('/^#[a-f0-9]{6}$/i', $post[$value])) {
					$errors[$value] = 'You must enter a 6 digit hexadecimal number with the hash (#).';
				}
			}
		}*/

		
		return $errors;
	}


}
?>