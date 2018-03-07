<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.adback.co
 * @since             1.0.0
 * @package           Ad_Back_Lite
 *
 * @wordpress-plugin
 * Plugin Name:       Adblock Monetization
 * Plugin URI:        adback.co
 * Description:       With AdBack, access analytics about adblocker users, address them personalized messages, propose alternative solutions to advertising (video, survey).
 * Version:           0.1.0
 * Author:            AdBack
 * Author URI:        https://www.adback.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       adback-solution-to-adblock-lite
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'ad_back_lite_action_links' );

function ad_back_lite_action_links( $links ) {
    return $links;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ad-back-activator.php
 */
function activate_ad_back_lite($networkwide) {
    if (!current_user_can( 'activate_plugins' ) )
        return;

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-back-activator.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-back-updator.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-back-get.php';
    Ad_Back_Lite_Activator::activate($networkwide);
    Ad_Back_Lite_Updator::update();

    adback_lite_plugin_rules();
    flush_rewrite_rules();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ad-back-deactivator.php
 */
function deactivate_ad_back_lite() {
    if (!current_user_can( 'activate_plugins' ) )
        return;

    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-back-deactivator.php';
    Ad_Back_Lite_Deactivator::deactivate();
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
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-back-updator.php';
    Ad_Back_Lite_Updator::update();
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
}

function adback_lite_plugin_query_vars($vars) {
    $vars[] = 'adback_lite_request';

    return $vars;
}

function adback_lite_plugin_display() {
    $adback_proxy_page = get_query_var('pagename');
    if ('adback_proxy' == $adback_proxy_page):
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-back-proxy.php';
        $adback_request = get_query_var('adback_request');
        Ad_Back_Lite_Proxy::execute($adback_request);
        exit;
    endif;
}

function adback_lite_new_blog($blogId) {
    if (is_plugin_active_for_network( 'adback-solution-to-adblock-lite/ad-back.php') ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-back-activator.php';

        switch_to_blog($blogId);
        Ad_Back_Lite_Activator::initializeBlog();
        restore_current_blog();
    }
}

function adback_lite_delete_blog($tables) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ad-back-deactivator.php';
    Ad_Back_Lite_Deactivator::deleteBlog($tables);
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
require plugin_dir_path( __FILE__ ) . 'includes/class-ad-back.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-ad-back-get.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-ad-back-post.php';

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

    $plugin = new Ad_Back_Lite();
    $plugin->run();

}
run_ad_back_lite();
