<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    Ad_Back
 * @subpackage Ad_Back/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ad_Back
 * @subpackage Ad_Back/includes
 * @author     AdBack <contact@adback.co>
 */
class Ad_Back_Deactivator
{
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate()
    {
        global $wpdb;

        $wpdb->query('delete from ' . $wpdb->prefix . 'adback_full_tag');
        $wpdb->query('delete from ' . $wpdb->prefix . 'adback_end_point');
        delete_option("adback_solution_to_adblock_db_version");
    }

    public static function deleteBlog($tables)
    {
        global $wpdb;

        $tables[] = $wpdb->prefix . 'adback_account';
        $tables[] = $wpdb->prefix . 'adback_token';
        $tables[] = $wpdb->prefix . 'adback_myinfo';
        $tables[] = $wpdb->prefix . 'adback_full_tag';
        $tables[] = $wpdb->prefix . 'adback_end_point';

        return $tables;
    }
}
