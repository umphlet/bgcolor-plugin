<?php
/*
Plugin Name: BG Color Plugin
Plugin URI:  https://github.com/umphlet/bgcolor-plugin
Description: A simple plugin to change the background color of the site.
Version:     1.0
Author:      Sean Umphlet
Author URI:  http://umphlet.github.io
License:     GPL2
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Copyright 2019 Sean Umphlet (sean.umphlet@gmail.com)
BG Color Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
BG Color Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with BG Color Plugin . If not, see https://www.gnu.org/licenses/old-licenses/gpl-2.0.html.
*/

/** Initialiuze bgcolor option */
add_option( 'bgcolor_color', '#FFFFFF', '', 'yes' );

/** Add Admin Menu */
add_action( 'admin_menu', 'bgcolor_menu' );

/** Add Menu Options */
function bgcolor_menu() {
  add_menu_page( 'BG Color Options', 'BG Color', 'administrator', __FILE__, 'bgcolor_settings_page' );
  add_action( 'admin_init', 'bgcolor_register_mysettings' );
}

/** Register Color Option */
function bgcolor_register_mysettings() { 
  register_setting( 'bgcolor_settings', 'bgcolor_color' );
}
  
/** BGColor Form */
function bgcolor_settings_page() {
?>
<div class="wrap">
<h2>BG Color Options</h2>
<p>You can select a color from the color picker below to change the background color of your site.</p>
<form action="options.php" method="post">
<?php
  settings_fields( 'bgcolor_settings' );
  do_settings_sections( 'bgcolor_settings' );
?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">BG Color</th>
        <td><input type="text" id="picker" name="bgcolor_color" value="<?php echo esc_attr( get_option('bgcolor_color') ); ?>" /></td>
        </tr>
    </table>
    <!-- Semicolon needed to be able to execute custom jQuery code for color picker -->
    <script>
    ;(function($){
      $("#picker").spectrum({
      showInput: true,
      showInitial: true,
      preferredFormat: "hex"
    });
    })(jQuery);
    </script>
<?php submit_button(); ?>
</form>
</div>
<?php } 

/** Place inline styles on site */
function bgcolor_styles_method() {
  wp_enqueue_style(
		'custom-style',
		get_template_directory_uri() . '/css/custom_script.css'
	);
  $color = get_option( 'bgcolor_color' );
  $custom_css = "
    body {
      background-color: {$color} !important;
    }";
  wp_add_inline_style( 'custom-style', $custom_css );
  }
  
add_action( 'wp_enqueue_scripts', 'bgcolor_styles_method' );

/** Styles/js for color picker for Spectrum jS color picker https://bgrins.github.io/spectrum/ */
function bgcolor_admin_scripts( $hook ) {
  if ( $hook == 'toplevel_page_bgcolor-plugin/bgcolor-plugin') {
  wp_register_script( 'spectrum-js', plugins_url() . '/bgcolor-plugin/spectrum/spectrum.js', array('jquery') );
  wp_register_style( 'spectrum-css', plugins_url() . '/bgcolor-plugin/spectrum/spectrum.css' );
  wp_enqueue_style( 'spectrum-css' );
  wp_enqueue_script( 'spectrum-js' );
  }
  
}

add_action( 'admin_enqueue_scripts', 'bgcolor_admin_scripts' );

?>
