<?php
/*
* This file is part of AJS Footnotes a plugin for WordPress
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

class optionsPage {
	private $options = array(), $errors = array();
	public function __construct($opts, $errs = null) {
		/*Setup the options*/
		$this->options = $opts;
		$this->errors = (is_null( $errs ))? $this->errors : $errs;
				
		wp_enqueue_style('ajs-admin', plugins_url('/css/admin.css', __FILE__));
		wp_enqueue_style('jqueryui', plugins_url('/css/wp-admin/jquery-ui-1.10.2.custom.min.css', __FILE__));
		wp_enqueue_style('colorPicker', plugins_url('/css/spectrum.css', __FILE__));
		wp_enqueue_style('dd', plugins_url('/css/dd.css', __FILE__));
		
		?>
		
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2>AJS Footnotes</h2>
			<?php if(count($this->errors) > 0): ?>
			<p class="error">There were errors processing this form.</p>
			<ul>
			<?php 
				foreach( $this->errors as $key=>$value ) {
					echo '<li>'.$key.' '.$value.'</li><br>';
				}
			?>
			</ul>
				
			<?php endif; ?>
			<form id="ajs-footnote-options" method="post">
		  <h3>Basic Options</h3>
		  <table class="form-table" style="width:500px">
		    <tr>
		      <th scope="row"><label for="number_system">Footnote Numbering Style</label></th>
		      <?php $select_vals = array('decimal'=>'','lower-roman'=>'','upper-roman'=>'','lower-alpha'=>'','upper-alpha'=>'');
		      	foreach($select_vals as $key=>$value) {
					if($key == $this->options['number_system']) {
						$select_vals[$key] = ' selected="selected"';
					}
				}
		      ?>
		      <td><select name="number_system">
		        <option value="decimal"<?php echo $select_vals['decimal'];?>>1, 2, 3...10...</option>
		        <option value="lower-roman"<?php echo $select_vals['lower-roman'];?>>i, ii, iii...x...</option>
		        <option value="upper-roman"<?php echo $select_vals['upper-roman'];?>>I, II, III...X...</option>
		        <option value="lower-alpha"<?php echo $select_vals['lower-alpha'];?>>a, b, c...j...</option>
		        <option value="upper-alpha"<?php echo $select_vals['upper-alpha'];?>>A, B, C...J...</option>
			  </select></td>
		    </tr>
		    <tr>
		      <th height="38" scope="row" style="vertical-align:middle">Footnote Link Location</th>
		      <td class="radio">
		      	<?php 
		      		$inline = '';
		      		$sup = '';
		      		$sub = '';
		      		switch ($this->options['cite_number_location']) {
						case 'inline':
							$inline=' checked="checked"';
							break;
						case 'sub':
							$sub = ' checked="checked"';
							break;
						default:
							$sup = 'checked="checked"';
					}
		      	?>
		      	<input type="radio" name="cite_number_location" id="sup" value="sup" class="link-location"<?php echo $sup;?>/>
		      	<label for="sup" style="font-weight:normal">Super<sup><span class="link-location" style="color:#000;">script</span></sup></label>
		        
		        <input type="radio" name="cite_number_location" id="sub" value="sub"  class="link-location"<?php echo $sub;?> />
		      	<label for="sub" style="font-weight:normal">Sub<sub><span style="color:#000;">script</span></sub></label>
		      	
		      	<input name="cite_number_location" type="radio" id="inline" value="inline"  class="link-location"<?php echo $inline;?> />
		      	<label for="inline" style="font-weight:normal">In<span style="color:#000;">line</span><sup>&nbsp;</sup></label>
		      </td>
		    </tr>
		    <tr>
				<th scope="row">Footnote Identifier Lead Text</th>
				<td>
			    	<input type="text" name="cite_number_pre" id="cite_number_pre" value="<?php echo $this->options['cite_number_pre']; ?>" />
			    </td>
			</tr>
			<tr>
				<th scope="row">Footnote Identifier Post Text</th>
				<td>
			    	<input type="text" name="cite_number_post" id="cite_number_post" value="<?php echo $this->options['cite_number_post']; ?>" />
		    	</td>
		    </tr>
		    <tr>
		    	<th scope="row">Backlink Text</th>
		    	<td>
		    		<input type="text" value="<?php echo $this->options['backlink_text'];?>" name="backlink_text" id="backlink_text" />
		    	</td>
		    </tr>
		    <tr>
		      <th scope="row">Show Popups</th>
		      <td class="radio">
		      	<input type="radio" name="display_popups" id="yes_popups" value="true" <?php echo ($this->options['display_popups'] == 'true')? ' checked="checked"':'';?> />
		      	<label for="yes_popups">Yes</label>
		      	<input type="radio" name="display_popups" id="no_popups" value="false" <?php echo ($this->options['display_popups'] == 'false')? ' checked="checked"':'';?> />
		      	<label for="no_popups">No</label>
		      </td>
		    </tr>
		    <tr>
		      <th scope="row">Show Footnote List</th>
		      <td class="radio">
		      	<input type="radio" name="display_notes_list" id="show_notes" value="true" <?php echo ($this->options['display_notes_list'] == 'true')? ' checked="checked"':'';?> />
		      	<label for="show_notes">Yes</label>
		      	<input type="radio" name="display_notes_list" id="no_notelist" value="false" <?php echo ($this->options['display_notes_list'] == 'false')? ' checked="checked"':'';?> />
		      	<label for="no_notelist">No</label>
		      </td>
		    </tr>
		  </table>
		   <hr style="border:none; border-top:1px solid #ebebeb; color:#ebebeb; width:450px;margin:2em;" />
		 <h3>Footnotes&apos; Look &amp; Feel</h3>
		  <table class="form-table">
		    <tr>
		      <th scope="row"><label for="footnote_list_size">Footnote Text Size</label></th>
		      <td><input type="text" name="footnote_list_size" id="footnote_list_size" value="<?php echo $this->options['footnote_list_size'];?>" />
		      <?php echo $this->generateUnitsDropdown('footnote_list_size_unit', $this->options['footnote_list_size_unit']) ?>
		      </td>
		    </tr>
		    <tr>
		      <th scope="row"><label for="footnote_list_color">Footnote Text Color</label></th>
		      <td>
		      	<input type="text" name="footnote_list_color" id="footnote_list_color" maxlength="6" class="colorPicker" style="height:22px;" value="<?php echo $this->options['footnote_list_color']; ?>" />
		    </td>
		  	</tr>
		  </table>
		  <hr style="border:none; border-top:1px solid #ebebeb; color:#ebebeb; width:450px;margin:2em;" />
		 <!-- POPUPS -->
		 <h3>Popups&apos; Positioning</h3>
		 <table class="form-table">
		 	<tr>
		 		<th scope="row">Placement</th>
		 		<td>
		 			<select name="popup_note_position" id="popup_note_position" style="width:200px;">
		 				<option class="topLeft" value="top left"<?php echo $this->options['popup_note_position'] == 'top left' ? ' selected="selected"' : ''?>>Top Left</option>
		 				<option class="topCenter" value="top center"<?php echo $this->options['popup_note_position'] == 'top center' ? ' selected="selected"' : ''?>>Top Center</option>
		 				<option class="topRight" value="top right"<?php echo $this->options['popup_note_position'] == 'top right' ? ' selected="selected"' : ''?>>Top Right</option>
		 				<option class="right" value="center right"<?php echo $this->options['popup_note_position'] == 'right' ? ' selected="selected"' : ''?>>Right</option>
		 				<option class="left" value="center left"<?php echo $this->options['popup_note_position'] == 'left' ? ' selected="selected"' : ''?>>Left</option>
		 				<option class="bottomLeft" value="bottom left"<?php echo $this->options['popup_note_position'] == 'bottom left' ? ' selected="selected"' : ''?>>Bottom Left</option>
		 				<option class="bottomCenter" value="bottom center"<?php echo $this->options['popup_note_position'] == 'bottom center' ? ' selected="selected"' : ''?>>Bottom Center</option>
		 				<option class="bottomRight" value="bottom right"<?php echo $this->options['popup_note_position'] == 'bottom right' ? ' selected="selected"' : ''?>>Bottom Right</option>
		 			</select>
		 		</td>
		 	</tr>
		 </table>
		  <h3>Popups&apos; Look &amp; Feel</h3>
		  <table class="form-table">
		  	<tr>
		      <th scope="row"><label for="popup_bgcolor">Popup Background Color</label></th>
		      <td>
		        <input type="text" name="popup_bgcolor" id="popup_bgcolor" maxlength="6" style="height:22px;" class="colorPickerA" value="<?php echo $this->options['popup_bgcolor']; ?>" /></td>
		    </tr>
		    <tr>
		      <th scope="row">Popup Text Color</th>
		      <td>
		        <input type="text" style="height:22px;" name="popup_fgcolor" id="popup_fgcolor" maxlength="6" class="colorPicker" value="<?php echo $this->options['popup_fgcolor']; ?>" /></td>
		    </tr>
		    <tr>
		      <th scope="row"><label for="popup_text_size">Popup Text Size</label></th>
		      <td><input type="text" name="popup_text_size" id="popup_text_size" value="<?php echo $this->options['popup_text_size'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_text_size_unit', $this->options['popup_text_size_unit']) ?>
		      </td>
		    </tr>
		   	<tr>
		      <th scope="row"><label for="popup_min_width">Popup Minimum Width</label></th>
		      <td><input type="text" name="popup_min_width" id="popup_min_width" value="<?php echo $this->options['popup_min_width'];?>" />
		        <?php echo $this->generateUnitsDropdown('popup_min_width_unit', $this->options['popup_min_width_unit']) ?>
		        </td>
		    </tr>
		    <tr>
		      <th scope="row"><label for="popup_max_width">Popup Maximum Width</label></th>
		      <td><input type="text" name="popup_max_width" id="popup_max_width" value="<?php echo $this->options['popup_max_width'];?>" />
		        <?php echo $this->generateUnitsDropdown('popup_max_width_unit', $this->options['popup_max_width_unit']) ?>
		        </td>
		    </tr>
		    </table>
		     <hr style="border:none; border-top:1px solid #ebebeb; color:#ebebeb; width:450px;margin:2em;" />
		    <h4>Popup Border</h4>
		    <table class="form-table">
		    <tr>
		      <th scope="row"><label for="popup_border_width">Border Width</label></th>
		      <td><input type="text" name="popup_border_width" id="popup_border_width" value="<?php echo $this->options['popup_border_width'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_border_width_unit', $this->options['popup_border_width_unit']) ?>  
		      </td>
		    </tr>
		    <tr>
		      <th scope="row"><label for="popup_border_color">Border Color</label></th>
		      <td>
		        <input type="text" name="popup_border_color" id="popup_border_color" maxlength="7" class="colorPickerA" style="height:22px;" value="<?php echo $this->options['popup_border_color'];?>"/></td>
		    </tr>
		    <tr>
		      <th scope="row"><label for="popup_border_style">Border Style</label></th>
				<?php $selected_style = array('none'=>'', 'hidden'=>'', 'dotted'=>'', 'dashed'=>'', 'solid'=>'', 'double'=>'', 'groove'=>'', 'inset'=>'', 'outset'=>'', 'ridge'=>'ridge');
		      	foreach( $selected_style as $key=>$value) {
					if( $this->options['popup_border_style'] == $key ) {
						$selected_style[$key] = ' selected="selected"';
					}
				}
		      ?>
		      <td><select name="popup_border_style" id="popup_border_style">
		        <option value="none"<?php echo $selected_style['none'];?>>None</option>
		        <option value="hidden"<?php echo $selected_style['hidden'];?>>Hidden</option>
		        <option value="dotted"<?php echo $selected_style['dotted'];?>>Dotted</option>
		        <option value="dashed"<?php echo $selected_style['dashed'];?>>Dashed</option>
		        <option value="solid"<?php echo $selected_style['solid'];?>>Solid</option>
		        <option value="double"<?php echo $selected_style['double'];?>>double</option>
		        <option value="groove"<?php echo $selected_style['groove'];?>>Groove</option>
		        <option value="ridge"<?php echo $selected_style['ridge'];?>>Ridge</option>
		        <option value="inset"<?php echo $selected_style['inset'];?>>Inset</option>
		        <option value="outset"<?php echo $selected_style['outset'];?>>Outset</option>
		      </select>
		      </td>
		    </tr>
		    </table>
		     <hr style="border:none; border-top:1px solid #ebebeb; color:#ebebeb; width:450px;margin:2em;" />
		     
		    <h4>Popup Corner Rounding</h4>
		    <table class="form-table">
		    <tr>
		      <th scope="row"><label for="popup_padding">Corner Radius</label></th>
		      <td><input type="text" name="popup_corners" id="popup_corners" value="<?php echo $this->options['popup_top_left_corner'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_corner_unit', $this->options['popup_top_left_corner_unit']) ?>
		        <a href="#" title="More corner options" id="cornerMore" style="display:inline-block; padding-left:15px;">More...</a>
		      </td>
		    </tr>
		    <tr class="corner-details">
		      <th scope="row"><label for="popup_top_left_corner">Top Left Radius</label></th>
		      <td><input type="text" name="popup_top_left_corner" id="popup_top_left_corner" value="<?php echo $this->options['popup_top_left_corner'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_top_left_corner_unit', $this->options['popup_top_left_corner_unit']) ?>
		      </td>
		    </tr>
			<tr class="corner-details">
		      <th scope="row"><label for="popup_top_right_corner">Top Right Radius</label></th>
		      <td><input type="text" name="popup_top_right_corner" id="popup_top_right_corner" value="<?php echo $this->options['popup_top_right_corner'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_top_right_corner_unit', $this->options['popup_top_right_corner_unit']) ?>
		      </td>
		    </tr>
		    <tr class="corner-details">
		      <th scope="row"><label for="popup_bottom_right_corner">Bottom Right Radius</label></th>
		      <td><input type="text" name="popup_bottom_right_corner" id="popup_bottom_right_corner" value="<?php echo $this->options['popup_bottom_right_corner'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_bottom_right_corner_unit', $this->options['popup_bottom_right_corner_unit']) ?>
		      </td>
		    </tr>
		    <tr class="corner-details">
		      <th scope="row"><label for="popup_bottom_left_corner">Bottom Left Radius</label></th>
		      <td><input type="text" name="popup_bottom_left_corner" id="popup_bottom_left_corner" value="<?php echo $this->options['popup_bottom_left_corner'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_bottom_left_corner_unit', $this->options['popup_bottom_left_corner_unit']) ?>
		      </td>
		    </tr>
		  </table>
		  <hr style="border:none; border-top:1px solid #ebebeb; color:#ebebeb; width:450px;margin:2em;" />
		    <h4>Popup Shadow</h4>
		    <table class="form-table">
		    <tr>
		      <th scope="row"><label for="popup_shadow_color">Shadow Color</label></th>
		      <td>
		        <input type="text" name="popup_shadow_color" id="popup_shadow_color" maxlength="6" style="height:22px;" class="colorPickerA" value="<?php echo $this->options['popup_shadow_color']; ?>" /></td>
		    </tr>
		    <tr>
		      <th scope="row"><label for="popup_shadow_hval">Horizontal Value</label></th>
		      <td><input type="text" name="popup_shadow_hval" id="popup_shadow_hval" value="<?php echo $this->options['popup_shadow_hval'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_shadow_h_unit', $this->options['popup_shadow_h_unit']) ?>  
		      </td>
		    </tr>
		    <tr>
		      <th scope="row"><label for="popup_shadow_vval">Verical Value</label></th>
		      <td><input type="text" name="popup_shadow_vval" id="popup_shadow_vval" value="<?php echo $this->options['popup_shadow_vval'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_shadow_v_unit', $this->options['popup_shadow_v_unit']) ?>  
		      </td>
		    </tr>
		        <tr>
		      <th scope="row"><label for="popup_shadow_blur">Blur</label></th>
		      <td><input type="text" name="popup_shadow_blur" id="popup_shadow_blur" value="<?php echo $this->options['popup_shadow_blur'];?>" />
		        <?php echo $this->generateUnitsDropdown('popup_shadow_blur_unit', $this->options['popup_shadow_blur_unit']) ?>
		      </td>
		    </tr>
		        <tr>
		      <th scope="row"><label for="popup_shadow_spread">Spread</label></th>
		      <td><input type="text" name="popup_shadow_spread" id="popup_shadow_spread" value="<?php echo $this->options['popup_shadow_spread'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_shadow_spread_unit', $this->options['popup_shadow_spread_unit']) ?>  
		      </td>
		    </tr>
		    </table>
		     <hr style="border:none; border-top:1px solid #ebebeb; color:#ebebeb; width:450px;margin:2em;" />
		    <h4>Popup Padding</h4>
		    <table class="form-table">
		    <tr>
		      <th scope="row"><label for="popup_padding">Padding</label></th>
		      <td><input type="text" name="popup_padding" id="popup_padding" value="<?php echo $this->options['popup_padding_top'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_padding_unit', $this->options['popup_padding_top_unit']) ?>
		        <a href="#" title="More padding options" id="paddingMore" style="display:inline-block; padding-left:15px;">More...</a>
		      </td>
		    </tr>
		    <tr class="padding-details">
		      <th scope="row"><label for="popup_padding_top">Popup Padding (Top)</label></th>
		      <td><input type="text" name="popup_padding_top" id="popup_padding_top" value="<?php echo $this->options['popup_padding_top'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_padding_top_unit', $this->options['popup_padding_top_unit']) ?>
		      </td>
		    </tr>
			<tr class="padding-details">
		      <th scope="row"><label for="popup_padding_right">Popup Padding (Right)</label></th>
		      <td><input type="text" name="popup_padding_right" id="popup_padding_right" value="<?php echo $this->options['popup_padding_right'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_padding_right_unit', $this->options['popup_padding_right_unit']) ?>
		      </td>
		    </tr>
		    <tr class="padding-details">
		      <th scope="row"><label for="popup_padding_bottom">Popup Padding (Bottom)</label></th>
		      <td><input type="text" name="popup_padding_bottom" id="popup_padding_bottom" value="<?php echo $this->options['popup_padding_bottom'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_padding_bottom_unit', $this->options['popup_padding_bottom_unit']) ?>
		      </td>
		    </tr>
		    <tr class="padding-details">
		      <th scope="row"><label for="popup_padding_left">Popup Padding (Left)</label></th>
		      <td><input type="text" name="popup_padding_left" id="popup_padding_left" value="<?php echo $this->options['popup_padding_left'];?>" />
		      <?php echo $this->generateUnitsDropdown('popup_padding_left_unit', $this->options['popup_padding_left_unit']) ?>
		      </td>
		    </tr>
		  </table>
		   <hr style="border:none; border-top:1px solid #ebebeb; color:#ebebeb; width:450px;margin:2em;" />
		  <h3>Popup Behavior</h3>
		  <table class="form-table">
		    <tr>
		      <th scope="row"><label for="popup_display_effect_in">Entrance Effect</label></th>
		      <?php $inEffect = array('fadeIn'=>'', 'slideDown'=>'','show'=>'');
		      	foreach($inEffect as $key=>$value) {
					if($this->options['popup_display_effect_in'] == $key) {
						$inEffect[$key] = ' selected="selected"';
					}
				}
		      ?>
		      <td>
		        <select name="popup_display_effect_in" id="popup_display_effect_in">
		          <option value="fadeIn"<?php echo $inEffect['fadeIn'];?>>Fade In</option>
		          <option value="slideDown"<?php echo $inEffect['slideDown'];?>>Slide Down</option>
		          <option value="show"<?php echo $inEffect['show'];?>>None</option>
		      </select></td>
		    </tr>
		    <tr>
		      <th scope="row"><label for="popup_duration_in">Entracne Duration (ms)</label></th>
		      <td>
		      <input type="text" name="popup_duration_in" id="popup_duration_in" maxlength="5" value="<?php echo $this->options['popup_duration_in']; ?>" /></td>
		    </tr>
		    <tr>
		      <th scope="row"><label for="popup_display_effect_out">Exit Effect</label></th>
		      <?php $outEffect = array('fadeOut'=>'', 'slideUp'=>'','hide'=>'');
		      	foreach($outEffect as $key=>$value) {
					if($this->options['popup_display_effect_out'] == $key) {
						$outEffect[$key] = ' selected="selected"';
					}
				}
		      ?>
		      <td><select name="popup_display_effect_out" id="popup_display_effect_out">
		          <option value="fadeOut"<?php echo $outEffect['fadeOut'];?>>Fade Out</option>
		          <option value="slideUp"<?php echo $outEffect['slideUp'];?>>Slide Up</option>
		          <option value="hide"<?php echo $outEffect['hide'];?>>None</option>
		      </select></td>
		    </tr>
		    <tr>
		      <th scope="row"><label for="popup_duration_out">Exit Duration (ms)</label></th>
		      <td><input type="text" name="popup_duration_out" id="popup_duration_out" maxlength="5" value="<?php echo $this->options['popup_duration_out']; ?>" /></td>
		    </tr>
		  </table>
		   <hr style="border:none; border-top:1px solid #ebebeb; color:#ebebeb; width:450px;margin:2em;" />
		  <h3>Advanced Options</h3>
		  <table class="form-table">
		    <tr>
		      <th scope="row"><label for="encode_html_specials">Escape HTML Entities</label></th>
		      <td class="radio">
		      	<input type="radio" name="encode_html_specials" id="encode" value="true" <?php echo ($this->options['encode_html_specials'] == 'true')? ' checked="checked"':'';?> />
		      	<label for="encode">Yes</label>
		      	<input type="radio" name="encode_html_specials" id="no_encode" value="false" <?php echo ($this->options['encode_html_specials'] == 'false')? ' checked="checked"' : '';?> />
		      	<label for="no_encode">No</label>
		      </td>
		    </tr>
		    <tr>
		    	<th scope="row"><h4>Plugin Running Options</h4></th>
		    </tr>
		    <tr>
    			<td>
    					Homepage
    			</td>
    			<td class="radio">
    				<input type="radio" name="run_status_home" id="run_home" value="true" <?php echo ($this->options['run_status_home'] == 'true')? ' checked="checked"':'';?> />
    				<label for="run_home">Run</label>
    				<input type="radio" name="run_status_home" id="strip_home" value="strip" <?php echo ($this->options['run_status_home'] == 'strip')? ' checked="checked"':'';?> />
    				<label for="strip_home">Strip Notes</label>
    				<input type="radio" name="run_status_home" id="norun_home" value="false" <?php echo ($this->options['run_status_home'] == 'false')? ' checked="checked"':'';?> />
    				<label for="norun_home">Do Nothing</label>
    			</td>
		    </tr>
		    <tr>
    			<td>
    					Posts
    			</td>
    			<td class="radio">
    				<input type="radio" name="run_status_post" id="run_post" value="true" <?php echo ($this->options['run_status_post'] == 'true')? ' checked="checked"':'';?> />
    				<label for="run_post">Run</label>
    				<input type="radio" name="run_status_post" id="strip_post" value="strip" <?php echo ($this->options['run_status_post'] == 'strip')? ' checked="checked"':'';?> />
    				<label for="strip_post">Strip Notes</label>
    				<input type="radio" name="run_status_post" id="norun_post" value="false" <?php echo ($this->options['run_status_post'] == 'false')? ' checked="checked"':'';?> />
    				<label for="norun_post">Do Nothing</label>
    			</td>
		    </tr>
		    <tr>
    			<td>
    					Pages
    			</td>
    			<td class="radio">
    				<input type="radio" name="run_status_page" id="run_page" value="true" <?php echo ($this->options['run_status_page'] == 'true')? ' checked="checked"':'';?> />
    				<label for="run_page">Run</label>
    				<input type="radio" name="run_status_page" id="strip_page" value="strip" <?php echo ($this->options['run_status_page'] == 'strip')? ' checked="checked"':'';?> />
    				<label for="strip_page">Strip Notes</label>
    				<input type="radio" name="run_status_page" id="norun_page" value="false" <?php echo ($this->options['run_status_page'] == 'false')? ' checked="checked"':'';?> />
    				<label for="norun_page">Do Nothing</label>
    			</td>
		    </tr>
		    <tr>
    			<td>
    					Search
    			</td>
    			<td class="radio">
    				<input type="radio" name="run_status_search" id="run_search" value="true" <?php echo ($this->options['run_status_search'] == 'true')? ' checked="checked"':'';?> />
    				<label for="run_search">Run</label>
    				<input type="radio" name="run_status_search" id="strip_search" value="strip" <?php echo ($this->options['run_status_search'] == 'strip')? ' checked="checked"':'';?> />
    				<label for="strip_search">Strip Notes</label>
    				<input type="radio" name="run_status_search" id="norun_search" value="false" <?php echo ($this->options['run_status_search'] == 'false')? ' checked="checked"':'';?> />
    				<label for="norun_search">Do Nothing</label>
    			</td>
		    </tr>
		    <tr>
    			<td>
    					Archives
    			</td>
    			<td class="radio">
    				<input type="radio" name="run_status_archive" id="run_archive" value="true" <?php echo ($this->options['run_status_archive'] == 'true')? ' checked="checked"':'';?> />
    				<label for="run_archive">Run</label>
    				<input type="radio" name="run_status_archive" id="strip_archive" value="strip" <?php echo ($this->options['run_status_archive'] == 'strip')? ' checked="checked"':'';?> />
    				<label for="strip_archive">Strip Notes</label>
    				<input type="radio" name="run_status_archive" id="norun_archive" value="false" <?php echo ($this->options['run_status_archive'] == 'false')? ' checked="checked"':'';?> />
    				<label for="norun_archive">Do Nothing</label>
    			</td>
		    </tr>
		    <tr>
    			<td>
    					Feeds
    			</td>
    			<td class="radio">
    				<input type="radio" name="run_status_feed" id="run_feed" value="true" <?php echo ($this->options['run_status_feed'] == 'true')? ' checked="checked"':'';?> />
    				<label for="run_feed">Run</label>
    				<input type="radio" name="run_status_feed" id="strip_feed" value="strip" <?php echo ($this->options['run_status_feed'] == 'strip')? ' checked="checked"':'';?> />
    				<label for="strip_feed">Strip Notes</label>
    				<input type="radio" name="run_status_feed" id="norun_feed" value="false" <?php echo ($this->options['run_status_feed'] == 'false')? ' checked="checked"':'';?> />
    				<label for="norun_feed">Do Nothing</label>
    			</td>
		    </tr>
		    
		  </table> 
		  <p class="submit">
		  	<input type="submit" value="Reset Options" name="reset_options" id="reset_options" class="button button-primary" style="margin-right:30px;"/> <input type="submit" value="Save Changes" name="save_options" id="save_options" class="button button-primary" />
		  </p>
		</form>
		<hr style="border:none; border-top:1px solid #ebebeb; color:#ebebeb; width:450px;margin:2em;" />
		<div class="info">
			<h3>Need Help?</h3>
			<p>Check out the homepage at <a href="http://www.ajseidl.com/projects/ajs-footnotes/" title="AJS Footnotes">AjSeidl.com</a>.</p>
			<p>Bug reports and feature requests should be made in the Wordpress Tracking and Wordpress forum systems respectively (you&apos;ll need a Wordpress.org login) or send them to me directly via the <a href="http://www.ajseidl.com/contact/" title="Contact - AjSeidl.com">Contact Page</a></p>
			<p>If you really love this plugin and want to show it, please rate it on Wordpress.org. If you really can&apos;t believe you used to live without it, <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=99KA7XRMC99Q6" title="Contribute to this plugin">buy me a beer via PayPal</a>.</p>
		</div>
		</div>
		<script type="text/javascript">
			jQuery(function($){
				$('.radio').buttonset();
				$('.colorPickerA').spectrum({showAlpha: true, showInput:true, preferredFormat: 'rgb', showInitial: true });
				$('.colorPicker').spectrum({showAlpha: false, showInput:true, preferredFormat: 'rgb', showInitial: true });
				$('#paddingMore').click(function(event){
					event.preventDefault();
					if($(event.target).text() == 'More...') {
						$(event.target).text('Less...');
					} else {
						$(event.target).text('More...');
					}
					$('.padding-details').toggle();
				});
				$('#cornerMore').click(function(event){
					event.preventDefault();
					if($(event.target).text() == 'More...') {
						$(event.target).text('Less...');
					} else {
						$(event.target).text('More...');
					}
					$('.corner-details').toggle();
				});
				$('#popup_padding, #popup_padding_unit').change(function(event){
					$('.padding-details input[type=text]').val($('#popup_padding').val());
					$('.padding-details select').val($('#popup_padding_unit :selected').val());
				});
				$('#popup_corners, #popup_corner_unit').change(function(event){
					$('.corner-details input[type=text]').val($('#popup_corners').val());
					$('.corner-details select').val($('#popup_corner_unit :selected').val());
				});
				$('#popup_note_position').msDropDown({useSprite: 'notePos'});
			});
		</script>
<?php 
	} //end constructor
	
	private function generateUnitsDropdown( $id, $v ) {
		$select = '';
		$selected_unit = array('px'=>'','em'=>'','ex'=>'', '%'=>'', 'pt'=>'','pc'=>'','mm'=>'','cm'=>'','in'=>'');
		
		foreach($selected_unit as $key=>$value){
			if( $v == $key) {
				$selected_unit[$key] = ' selected="selected"';
			}
		}
		
		$select = '<select name="'.$id.'" id="'.$id.'">'.PHP_EOL;
		$select .= '	<option value="px"'.$selected_unit['px'].'>px</option>'.PHP_EOL;
		$select .= '	<option value="em"'.$selected_unit['em'].'>em</option>'.PHP_EOL;
		$select .= '	<option value="ex"'.$selected_unit['ex'].'>ex</option>'.PHP_EOL;
		$select .= '	<option value="%"'.$selected_unit['%'].'>%</option>'.PHP_EOL;
		$select .= '	<option value="pt"'.$selected_unit['pt'].'>pt</option>'.PHP_EOL;
		$select .= '	<option value="pc"'.$selected_unit['pc'].'>pc</option>'.PHP_EOL;
		$select .= '	<option value="mm"'.$selected_unit['mm'].'>mm</option>'.PHP_EOL;
		$select .= '	<option value="cm"'.$selected_unit['cm'].'>cm</option>'.PHP_EOL;
		$select .= '	<option value="in"'.$selected_unit['in'].'>in</option>'.PHP_EOL;
		$select .= '</select>'.PHP_EOL;
		
		return $select;
	}
}//end class
?>
