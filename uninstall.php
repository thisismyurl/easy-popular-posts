<?php
/**
 * Uninstall routine for Easy Popular Posts.
 *
 * Deletes all post-meta keys created by the plugin.
 *
 * @package Easy_Popular_Posts
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

// Remove all post-meta rows created by the plugin.
$wpdb->query( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	"DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '_easy-popular-posts-%'"
);
