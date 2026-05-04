<?php

/**
 *
 * Uninstall script
 *
 * This file contains all the logic required to uninstall the plugin
 *
 *
 * @package 	Easy Popular Posts
 * @copyright	Copyright (c) 2008, Chrsitopher Ross
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * @since 		Easy Popular Posts 15.01
 *
 *
 */


if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit;
	
global $wpdb;

$wpdb->query( 
	"DELETE FROM $wpdb->postmeta  WHERE meta_key LIKE `_easy-popular-posts%`"
);	