<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           One_Click_Adblock_Monetisation
 *
 * @wordpress-plugin
 * Plugin Name:       One Click Adblock Monetization
 * Plugin URI:        https://landing.adback.co
 * Description:       Automatically display a footer message offering your users the possibility to turn off their adblocker or to click on an ad to access the website.
 * Version:           1.0.0
 * Author:            AdBack
 * Author URI:        https://www.adback.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       one-click-adblock-monetisation
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'ocam_action_links' );

function ocam_action_links( $links ) {
    return $links;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ocam-activator.php
 */
function activate_ad_back_lite($networkwide) {
    if (!current_user_can( 'activate_plugins' ) )
        return;

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ocam-activator.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ocam-updator.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ocam-get.php';
    Ocam_Activator::activate($networkwide);
    Ocam_Updator::update();

    adback_lite_plugin_rules();
    flush_rewrite_rules();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ocam-deactivator.php
 */
function deactivate_ad_back_lite() {
    if (!current_user_can( 'activate_plugins' ) )
        return;

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ocam-deactivator.php';
    Ocam_Deactivator::deactivate();
}

function adback_lite_admin_notices() {
    if ($notices= get_option('adback_lite_deferred_admin_notices')) {
        foreach ($notices as $notice) {
            echo "<div class='error notice is-dismissible'><p>" . $notice . "</p></div>";
        }
        delete_option('adback_lite_deferred_admin_notices');
    }
}

function adback_lite_plugins_loaded() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ocam-updator.php';
    Ocam_Updator::update();
}

function adback_lite_plugin_rules() {
    global $wpdb;
    $table_name_end_point = $wpdb->prefix . 'adback_end_point';
    $endPoints = $wpdb->get_row("SELECT * FROM " . $table_name_end_point . " WHERE id = ".get_current_blog_id());
    if (null !== $endPoints) {
        if ('' != $endPoints->old_end_point) {
            add_rewrite_rule($endPoints->old_end_point . '/?(.*)', 'index.php?pagename=adback_proxy&adback_request=$matches[1]', 'top');
        }
        if ('' != $endPoints->end_point) {
            add_rewrite_rule($endPoints->end_point . '/?(.*)', 'index.php?pagename=adback_proxy&adback_request=$matches[1]', 'top');
        }
        if ('' != $endPoints->next_end_point) {
            add_rewrite_rule($endPoints->next_end_point . '/?(.*)', 'index.php?pagename=adback_proxy&adback_request=$matches[1]', 'top');
        }
    }

    $table_name_token = $wpdb->prefix . 'adback_token';
    $token = $wpdb->get_row("SELECT * FROM " . $table_name_token . " WHERE id = ".get_current_blog_id());

    if (is_array($token)) {
        $token = (object)$token;
    }

    add_rewrite_rule($token->access_token.'/update', 'index.php?pagename=adback_update', 'top');
}

function adback_lite_plugin_query_vars($vars) {
    $vars[] = 'adback_lite_request';

    return $vars;
}

function adback_lite_plugin_display() {
    $adback_page_name = get_query_var('pagename');
    if ('adback_proxy' == $adback_page_name):
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-ocam-proxy.php';
        $adback_request = get_query_var('adback_request');
        Ocam_Proxy::execute($adback_request);
        exit;
    endif;
    if ('adback_update' == $adback_page_name):
        global $wpdb;

        $table_name = $wpdb->prefix . 'adback_full_tag';
        $blogId = get_current_blog_id();
        $wpdb->query('DELETE FROM ' . $table_name . " WHERE blog_id = ". $blogId);

        echo "Refreshed";
        exit;
    endif;
}

function adback_lite_new_blog($blogId) {
    if (is_plugin_active_for_network( 'one-click-adblock-monetisation/ad-back.php') ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-ocam-activator.php';

        switch_to_blog($blogId);
        Ocam_Activator::initializeBlog();
        restore_current_blog();
    }
}

function adback_lite_delete_blog($tables) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ocam-deactivator.php';
    Ocam_Deactivator::deleteBlog($tables);
}

add_action('admin_notices', 'adback_lite_admin_notices');
add_action('wpmu_new_blog', 'adback_lite_new_blog');
add_action('plugins_loaded', 'adback_lite_plugins_loaded');
add_filter('wpmu_drop_tables', 'adback_lite_delete_blog' );
register_activation_hook( __FILE__, 'activate_ad_back_lite' );
register_deactivation_hook( __FILE__, 'deactivate_ad_back_lite' );
//add rewrite rules in case another plugin flushes rules
add_action('init', 'adback_lite_plugin_rules');
//add plugin query vars (product_id) to wordpress
add_filter('query_vars', 'adback_lite_plugin_query_vars');
add_filter('template_redirect', 'adback_lite_plugin_display');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ocam.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-ocam-get.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-ocam-post.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ad_back_lite() {

    $plugin = new One_Click_Adblock_Monetisation();
    $plugin->run();

}
run_ad_back_lite();
