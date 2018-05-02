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

        $elements = $this->getMyInfo();

        $title = __('To access the website', 'adback-solution-to-adblock-lite');
        $deactivate = __('Deactivate your adblock<br/>then reload the page.', 'adback-solution-to-adblock-lite');
        $click = __('Or click on the banner<br/>nearby', 'adback-solution-to-adblock-lite');
        $close = __('Close', 'adback-solution-to-adblock-lite');
        $why_title = __('Why do I see this message ?', 'adback-solution-to-adblock-lite');
        $why_description = __('Your browser blocks the ads on this website. To discover how to access it, find the help down here.', 'adback-solution-to-adblock-lite');
        $why_link = __('Access help page', 'adback-solution-to-adblock-lite');
        $issue_title = __('An issue ?', 'adback-solution-to-adblock-lite');
        $issue_description = __('If you have clicked on the banner or deactivate your adblock and you still see this message, report it to us below.', 'adback-solution-to-adblock-lite');
        $issue_link = __('Report a bug', 'adback-solution-to-adblock-lite');
        $issue_test = __('Verification in progress', 'adback-solution-to-adblock-lite');
        $issue_thank_you = __('Thank you for your help', 'adback-solution-to-adblock-lite');
        $who_title = __('Who are we ?', 'adback-solution-to-adblock-lite');
        $who_description = __('Thanks to AdBack, you can monetize your adblocked audience by displaying users friendly banners.', 'adback-solution-to-adblock-lite');
        $who_link = __('Discover AdBack', 'adback-solution-to-adblock-lite');

        echo <<<JS
        <script type='text/javascript'>
            window.adback_wording = {};
            window.adback_wording['title'] = "{$title}";
            window.adback_wording['deactivate'] = "{$deactivate}";
            window.adback_wording['click'] = "{$click}";
            window.adback_wording['close'] = "{$close}";
            window.adback_wording['why_title'] = "{$why_title}";
            window.adback_wording['why_description'] = "{$why_description}";
            window.adback_wording['why_link'] = "{$why_link}";
            window.adback_wording['issue_title'] = "{$issue_title}";
            window.adback_wording['issue_description'] = "{$issue_description}";
            window.adback_wording['issue_link'] = "{$issue_link}";
            window.adback_wording['issue_test'] = "{$issue_test}";
            window.adback_wording['issue_thank_you'] = "{$issue_thank_you}";
            window.adback_wording['who_title'] = "{$who_title}";
            window.adback_wording['who_description'] = "{$who_description}";
            window.adback_wording['who_link'] = "{$who_link}";
        </script>
JS;

        if (is_array($elements)) {
            foreach ($elements as $type => $element) {
                if ('product' != $type) {
                    echo "<script type='text/javascript'>\n$element\n</script>\n";
                }
            }
        }

    }
}
