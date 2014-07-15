<?php
/*
 * Plugin Name:   Enhanced Media RSS Feed
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
	
	// add Media RSS extension to enhance the publishing of multimedia files in RSS feeds.
	add_action('rss2_ns', 'add_media_namespace');
	
	// add featured image in the media:content section
	add_action('rss2_item', 'add_featured_image');
	
	function add_media_namespace() {
		echo 'xmlns:media="http://search.yahoo.com/mrss/"';
	}
	
	function add_featured_image() {
		global $post;
		if(has_post_thumbnail()) :
			$full_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			
			$custom_image_width = 565; // TODO: set specific image width with settings
			$custom_image_height = round($full_image_url[2] * ($custom_image_width/$full_image_url[1]), 0);
			
			?>
			<media:content url="<?php echo $full_image_url[0]; ?>" medium="image" width="<?php echo $custom_image_width; ?>" height="<?php echo $custom_image_height; ?>">
			<media:description type="plain"><![CDATA[<?php echo $post->post_title; ?>]]></media:description>
			</media:content>
	<?php endif; 
	} 
?>
