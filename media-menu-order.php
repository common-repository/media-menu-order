<?php
/*
Plugin Name: Media Menu Order
Plugin URI: http://room34.com
Description: A very simple plugin that adds a Menu Order field to Media Library editing interfaces, allowing you to assign menu order to images and attachments.
Version: 1.1.0
Author: Room 34 Creative Services, LLC
Author URI: http://room34.com
License: GPL2
*/

/*  Copyright 2016-2020 Room 34 Creative Services, LLC (email: info@room34.com)

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

// Don't load directly
if (!defined('ABSPATH')) { exit; }

function media_menu_order_field($form_fields, $post) {
	if ($post->post_type == 'attachment') {
		$field_value = $post->menu_order;
		$form_fields['menu_order2'] = array(
			'value' => $field_value ? $field_value : 0,
			'label' => __('Menu Order'),
			'helps' => null,
		);
	}
	return $form_fields;
}
add_filter('attachment_fields_to_edit', 'media_menu_order_field', 10, 2);

function media_menu_order_save($attachment_id) {
	// Recursion prevention
	// Based on: https://tommcfarlin.com/update-post-in-save-post-action/
	remove_action('edit_attachment', 'media_menu_order_save', 10);
	if (isset($_POST['attachments'][$attachment_id]['menu_order2'])) {
		$menu_order = $_POST['attachments'][$attachment_id]['menu_order2'];
		update_post_meta($attachment_id, 'menu_order2', $menu_order);
		wp_update_post(array(
			'ID' => $attachment_id,
			'menu_order' => $menu_order,
		));
	}
	// Recursion prevention
	add_action('edit_attachment', 'media_menu_order_save', 10, 1);
}
add_action('edit_attachment', 'media_menu_order_save', 10, 1);
