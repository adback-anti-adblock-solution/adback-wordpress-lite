<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    Ad_Back
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

function adback_delete_tables()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'adback_account';
    $sql = "DROP TABLE IF EXISTS ".$table_name;
    $wpdb->query($sql);

    $table_name = $wpdb->prefix . 'adback_token';
    $sql = "DROP TABLE IF EXISTS ".$table_name;
    $wpdb->query($sql);

    $table_name = $wpdb->prefix . 'adback_myinfo';
    $sql = "DROP TABLE IF EXISTS ".$table_name;
    $wpdb->query($sql);

    $table_name = $wpdb->prefix . 'adback_message';
    $sql = "DROP TABLE IF EXISTS ".$table_name;
    $wpdb->query($sql);
}

if (is_multisite()) {
    global $wpdb;

    $sites = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

    foreach ($sites as $blogId) {
        switch_to_blog($blogId);
        adback_delete_tables();
        restore_current_blog();
    }
} else {
    adback_delete_tables();
}
