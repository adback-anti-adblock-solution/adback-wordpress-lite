<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    Ad_Back_Lite
 * @subpackage Ad_Back_Lite/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ad_Back_Lite
 * @subpackage Ad_Back_Lite/public
 * @author     AdBack
 */

include_once(plugin_dir_path( __FILE__ ) . '../class-ad-back.php');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class Ad_Back_Lite_Public extends Ad_Back_Lite_Generic
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueScripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ad_Back_Lite_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ad_Back_Lite_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (is_plugin_active('adback-solution-to-adblock/ad-back.php')) {
            return;
        }

        $title = __('Click the ad', 'adback-solution-to-adblock-lite');
        $message = __('Disable your adblocker and reload the page to access this website', 'adback-solution-to-adblock-lite');
        $button = __('Access this website without ads', 'adback-solution-to-adblock-lite');

        echo <<<JS
        <script type='text/javascript'>
            window.adback_wording = {};
            window.adback_wording['title'] = "{$title}";
            window.adback_wording['message'] = "{$message}";
            window.adback_wording['button'] = "{$button}";
        </script>
JS;

        $elements = $this->getMyInfo();

        if (is_array($elements)) {
            foreach ($elements as $type => $element) {
                if ('product' != $type) {
                    echo "<script type='text/javascript'>\n$element\n</script>\n";
                }
            }
        }

    }
}
