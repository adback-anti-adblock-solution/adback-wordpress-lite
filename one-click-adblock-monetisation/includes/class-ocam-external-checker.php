<?php

/**
 * Fired during plugin activation and upgrade
 *
 * @link       https://www.adback.co
 * @since      2.4.0
 *
 * @package    One_Click_Adblock_Monetisation
 * @subpackage One_Click_Adblock_Monetisation/includes
 */

/**
 * Fired during plugin activation and upgrade
 *
 * This class defines all code necessary to run during the plugin's upgrade
 *
 * @since      2.7.0
 * @package    One_Click_Adblock_Monetisation
 * @subpackage One_Click_Adblock_Monetisation/includes
 * @author     AdBack <contact@adback.co>
 */
class Ocam_External_Checker
{
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    2.7.0
     */
    public static function check()
    {
        $wpRocketSettings = get_option("wp_rocket_settings");

        if (is_array($wpRocketSettings) && in_array($wpRocketSettings['lazyload_iframes'], array(1, true, '1'))) {
            $notices = get_option('adback_lite_deferred_admin_notices', array());
            $notices[] = __('There is an incompatibility between the WP Rocket plugin and AdBack. Please uncheck the box "Enable for iframes and videos" in Settings > WP Rocket > Basic > LazyLoad to continue using AdBack. This change won\'t interfere with the operation of your website.', 'one-click-adblock-monetisation');
            update_option('adback_lite_deferred_admin_notices', $notices);
        }

        $wpRocketLazyLoadSettings = get_option("rocket_lazyload_options");

        if (is_array($wpRocketLazyLoadSettings) && in_array($wpRocketLazyLoadSettings['iframes'], array(1, true, '1'))) {
            $notices = get_option('adback_lite_deferred_admin_notices', array());
            $notices[] = __('There is an incompatibility between the LazyLoad plugin and AdBack. Please uncheck the box "iframes and videos" in Settings > LazyLoad to continue using AdBack. This change won\'t interfere with the operation of your website.', 'one-click-adblock-monetisation');
            update_option('adback_lite_deferred_admin_notices', $notices);
        }
    }
}
