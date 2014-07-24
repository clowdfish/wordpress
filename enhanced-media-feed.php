<?php
/*
 * Plugin Name:   Enhanced Media Feed
 * Plugin URI:    http://www.clowdfish.com
 * Description:   A plugin to enhance the default RSS Feed to include media and filter options.
 * Version:       1.0
 * Author:        Sascha Gros
 * Author URI:    http://www.clowdfish.com
 */
 
 /* Copyright 2014 Sascha Gros (email : s.gros@clowdfish.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	defined('ABSPATH') or die("No script kiddies please!");
	
	// register scripts for the plugin
	function emf_enqueue($hook) {
		wp_enqueue_script( 'emf_custom_script', plugin_dir_url( __FILE__ ) . '/js/enhanced-media-feed.js' );
	}
	add_action( 'admin_enqueue_scripts', 'emf_enqueue' );

	// add Media RSS extension to enhance the publishing of multimedia files in RSS feeds.
	add_action('rss2_ns', 'add_media_namespace');
	
	// add featured image in the media:content section
	add_action('rss2_item', 'add_featured_image');
	
	function add_media_namespace() {
		echo 'xmlns:media="http://search.yahoo.com/mrss/"';
	}
	
	function add_featured_image() {
		global $post;
		$emf_options = get_option( 'emf_settings' );
		$emf_image_width = $emf_options['emf_text_field_0'];
		
		if(has_post_thumbnail()) :
			$full_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			$custom_image_width;
			$custom_image_height;
			
			if('0' == $emf_image_width || '' == $emf_image_width) {
				$custom_image_width = $full_image_url[1];
				$custom_image_height = $full_image_url[2];
			}
			else {
				$custom_image_width = $emf_image_width;
				$custom_image_height = round($full_image_url[2] * ($custom_image_width/$full_image_url[1]), 0);
			}
			?>
			<media:content url="<?php echo $full_image_url[0]; ?>" medium="image" width="<?php echo $custom_image_width; ?>" height="<?php echo $custom_image_height; ?>">
			<media:description type="plain"><![CDATA[<?php echo $post->post_title; ?>]]></media:description>
			</media:content>
	<?php endif; 
	} 
?>
<?php
/* Admin settings page */
add_action( 'admin_menu', 'emf_add_admin_menu' );
add_action( 'admin_init', 'emf_settings_init' );

function emf_add_admin_menu(  ) { 
	add_options_page( 'Enhanced Media Feed', 'Enhanced Media Feed', 'manage_options', 'enhanced_media_feed', 'enhanced_media_feed_options_page' );
}

function emf_settings_exist(  ) { 
	if( false == get_option( 'enhanced_media_feed_settings' ) ) { 
		add_option( 'enhanced_media_feed_settings' );
	}
}

function emf_settings_init(  ) { 
	register_setting( 'pluginPage', 'emf_settings' );

	add_settings_section(
		'emf_pluginPage_section', 
		__( 'Enhanced Media Feed Settings', 'wordpress' ), 
		'emf_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'emf_text_field_0', 
		__( 'Image width in pixels:', 'wordpress' ), 
		'emf_text_field_0_render', 
		'pluginPage', 
		'emf_pluginPage_section' 
	);
}

function emf_text_field_0_render(  ) { 
	$options = get_option( 'emf_settings' );
	?>
	<input type='text' id='emf_width_field' name='emf_settings[emf_text_field_0]' value='<?php echo $options['emf_text_field_0']; ?>'>
	<?php
}

function emf_settings_section_callback(  ) { 
	echo __( 'You can enter a pre-defined image width here. This might be necessary, if your connected RSS client cannot resize images from a RSS feed.<br />
			  Leave the field blank or put a zero, if Wordpress should use the image size of your featured images.<br /><br />
			  Please note, that the changes will only take effect as soon as the next post is published or as soon as a published post is updated.', 
			  'wordpress' );
}

function enhanced_media_feed_options_page(  ) { 
	?>
	<form id='emf_settings_form' action='options.php' method='post'>
		<h2>Enhanced Media Feed</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
	</form>
	<?php
}
?>