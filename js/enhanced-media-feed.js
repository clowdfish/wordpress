/*
	 enhanced-media-feed.js
	 Copyright 2014 Sascha Gros (clowdfish)

	 This program is free software; you can redistribute it and/or modify
	 it under the terms of the GNU General Public License, version 2, as
	 published by the Free Software Foundation.

	 This program is distributed in the hope that it will be useful,
	 but WITHOUT ANY WARRANTY; without even the implied warranty of
	 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 GNU General Public License for more details.
*/
(function ($, window, document, undefined) {
	$(document).ready(function() {
		$('#emf_settings_form').on('submit', function() {
			// validation
			var width_value = $("#emf_width_field").val();
			
			if(Math.floor(width_value) == width_value && $.isNumeric(width_value) && width_value > 0 || 0 == width_value) {
				return true;
			} else {
				alert("The value must be a positive integer or zero!");
				return false;
			}
		});
	});
	/* ready */
})(jQuery, window, document);