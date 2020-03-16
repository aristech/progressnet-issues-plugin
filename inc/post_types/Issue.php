<?php

/**
 * @package Progressnet
 *
 */

namespace Inc\post_types;

/* ================================
 * Enqueue Admin Configuration Page
 * =================================
 */

class Issue
{
	function issue_init()
	{
		register_post_type('issue', array(
			'labels'                => array(
				'name'                  => __('Issues', 'progressnet'),
				'singular_name'         => __('Issue', 'progressnet'),
				'all_items'             => __('All Issues', 'progressnet'),
				'archives'              => __('Issue Archives', 'progressnet'),
				'attributes'            => __('Issue Attributes', 'progressnet'),
				'insert_into_item'      => __('Insert into Issue', 'progressnet'),
				'uploaded_to_this_item' => __('Uploaded to this Issue', 'progressnet'),
				'featured_image'        => _x('Featured Image', 'issue', 'progressnet'),
				'set_featured_image'    => _x('Set featured image', 'issue', 'progressnet'),
				'remove_featured_image' => _x('Remove featured image', 'issue', 'progressnet'),
				'use_featured_image'    => _x('Use as featured image', 'issue', 'progressnet'),
				'filter_items_list'     => __('Filter Issues list', 'progressnet'),
				'items_list_navigation' => __('Issues list navigation', 'progressnet'),
				'items_list'            => __('Issues list', 'progressnet'),
				'new_item'              => __('New Issue', 'progressnet'),
				'add_new'               => __('Add New', 'progressnet'),
				'add_new_item'          => __('Add New Issue', 'progressnet'),
				'edit_item'             => __('Edit Issue', 'progressnet'),
				'view_item'             => __('View Issue', 'progressnet'),
				'view_items'            => __('View Issues', 'progressnet'),
				'search_items'          => __('Search Issues', 'progressnet'),
				'not_found'             => __('No Issues found', 'progressnet'),
				'not_found_in_trash'    => __('No Issues found in trash', 'progressnet'),
				'parent_item_colon'     => __('Parent Issue:', 'progressnet'),
				'menu_name'             => __('Issues', 'progressnet'),
			),
			'public'                => false,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => array('title', 'editor'),
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => null,
			// 'taxonomies'            => array('opened', 'category'),
			'menu_icon'             => 'dashicons-menu-alt3',
			'show_in_rest'          => true,
			'rest_base'             => 'issue',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		));
	}


	/**
	 * Sets the post updated messages for the `issue` post type.
	 *
	 * @param  array $messages Post updated messages.
	 * @return array Messages for the `issue` post type.
	 */
	function issue_updated_messages($messages)
	{
		global $post;

		$permalink = get_permalink($post);

		$messages['issue'] = array(
			0  => '', // Unused. Messages start at index 1.
			/* translators: %s: post permalink */
			1  => sprintf(__('Issue updated. <a target="_blank" href="%s">View Issue</a>', 'progressnet'), esc_url($permalink)),
			2  => __('Custom field updated.', 'progressnet'),
			3  => __('Custom field deleted.', 'progressnet'),
			4  => __('Issue updated.', 'progressnet'),
			/* translators: %s: date and time of the revision */
			5  => isset($_GET['revision']) ? sprintf(__('Issue restored to revision from %s', 'progressnet'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
			/* translators: %s: post permalink */
			6  => sprintf(__('Issue published. <a href="%s">View Issue</a>', 'progressnet'), esc_url($permalink)),
			7  => __('Issue saved.', 'progressnet'),
			/* translators: %s: post permalink */
			8  => sprintf(__('Issue submitted. <a target="_blank" href="%s">Preview Issue</a>', 'progressnet'), esc_url(add_query_arg('preview', 'true', $permalink))),
			/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
			9  => sprintf(
				__('Issue scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Issue</a>', 'progressnet'),
				date_i18n(__('M j, Y @ G:i', 'progressnet'), strtotime($post->post_date)),
				esc_url($permalink)
			),
			/* translators: %s: post permalink */
			10 => sprintf(__('Issue draft updated. <a target="_blank" href="%s">Preview Issue</a>', 'progressnet'), esc_url(add_query_arg('preview', 'true', $permalink))),
		);

		return $messages;
	}



	/**
	 * Registers the `open` taxonomy,
	 * for use with 'issue'.
	 */
	function open_init()
	{
		register_taxonomy('open', array('issue'), array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'rewrite'           => true,
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts',
			),
			'labels'            => array(
				'name'                       => __('Opened', 'progressnet'),
				'singular_name'              => _x('Open', 'taxonomy general name', 'progressnet'),
				'search_items'               => __('Search Opened', 'progressnet'),
				'popular_items'              => __('Popular Opened', 'progressnet'),
				'all_items'                  => __('All Opened', 'progressnet'),
				'parent_item'                => __('Parent Open', 'progressnet'),
				'parent_item_colon'          => __('Parent Open:', 'progressnet'),
				'edit_item'                  => __('Edit Open', 'progressnet'),
				'update_item'                => __('Update Open', 'progressnet'),
				'view_item'                  => __('View Open', 'progressnet'),
				'add_new_item'               => __('Add New Open', 'progressnet'),
				'new_item_name'              => __('New Open', 'progressnet'),
				'separate_items_with_commas' => __('Separate Opened with commas', 'progressnet'),
				'add_or_remove_items'        => __('Add or remove Opened', 'progressnet'),
				'choose_from_most_used'      => __('Choose from the most used Opened', 'progressnet'),
				'not_found'                  => __('No Opened found.', 'progressnet'),
				'no_terms'                   => __('No Opened', 'progressnet'),
				'menu_name'                  => __('Opened', 'progressnet'),
				'items_list_navigation'      => __('Opened list navigation', 'progressnet'),
				'items_list'                 => __('Opened list', 'progressnet'),
				'most_used'                  => _x('Most Used', 'open', 'progressnet'),
				'back_to_items'              => __('&larr; Back to Opened', 'progressnet'),
			),
			'show_in_rest'      => true,
			'rest_base'         => 'open',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
		));
	}


	/**
	 * Sets the post updated messages for the `open` taxonomy.
	 *
	 * @param  array $messages Post updated messages.
	 * @return array Messages for the `open` taxonomy.
	 */
	function open_updated_messages($messages)
	{

		$messages['open'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __('Open added.', 'progressnet'),
			2 => __('Open deleted.', 'progressnet'),
			3 => __('Open updated.', 'progressnet'),
			4 => __('Open not added.', 'progressnet'),
			5 => __('Open not updated.', 'progressnet'),
			6 => __('Opened deleted.', 'progressnet'),
		);

		return $messages;
	}

	/**
	 * Registers the `closed` taxonomy,
	 * for use with 'issue'.
	 */
	function closed_init()
	{
		register_taxonomy('closed', array('issue'), array(
			'hierarchical'      => false,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'rewrite'           => true,
			'capabilities'      => array(
				'manage_terms'  => 'edit_posts',
				'edit_terms'    => 'edit_posts',
				'delete_terms'  => 'edit_posts',
				'assign_terms'  => 'edit_posts',
			),
			'labels'            => array(
				'name'                       => __('Closed', 'progressnet'),
				'singular_name'              => _x('Closed', 'taxonomy general name', 'progressnet'),
				'search_items'               => __('Search Closed', 'progressnet'),
				'popular_items'              => __('Popular Closed', 'progressnet'),
				'all_items'                  => __('All Closed', 'progressnet'),
				'parent_item'                => __('Parent Closed', 'progressnet'),
				'parent_item_colon'          => __('Parent Closed:', 'progressnet'),
				'edit_item'                  => __('Edit Closed', 'progressnet'),
				'update_item'                => __('Update Closed', 'progressnet'),
				'view_item'                  => __('View Closed', 'progressnet'),
				'add_new_item'               => __('Add New Closed', 'progressnet'),
				'new_item_name'              => __('New Closed', 'progressnet'),
				'separate_items_with_commas' => __('Separate Closed with commas', 'progressnet'),
				'add_or_remove_items'        => __('Add or remove Closed', 'progressnet'),
				'choose_from_most_used'      => __('Choose from the most used Closed', 'progressnet'),
				'not_found'                  => __('No Closed found.', 'progressnet'),
				'no_terms'                   => __('No Closed', 'progressnet'),
				'menu_name'                  => __('Closed', 'progressnet'),
				'items_list_navigation'      => __('Closed list navigation', 'progressnet'),
				'items_list'                 => __('Closed list', 'progressnet'),
				'most_used'                  => _x('Most Used', 'closed', 'progressnet'),
				'back_to_items'              => __('&larr; Back to Closed', 'progressnet'),
			),
			'show_in_rest'      => true,
			'rest_base'         => 'closed',
			'rest_controller_class' => 'WP_REST_Terms_Controller',
		));
	}


	/**
	 * Sets the post updated messages for the `closed` taxonomy.
	 *
	 * @param  array $messages Post updated messages.
	 * @return array Messages for the `closed` taxonomy.
	 */
	function closed_updated_messages($messages)
	{

		$messages['closed'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __('Closed added.', 'progressnet'),
			2 => __('Closed deleted.', 'progressnet'),
			3 => __('Closed updated.', 'progressnet'),
			4 => __('Closed not added.', 'progressnet'),
			5 => __('Closed not updated.', 'progressnet'),
			6 => __('Closed deleted.', 'progressnet'),
		);

		return $messages;
	}
}
