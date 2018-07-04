<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    One_Click_Adblock_Monetisation
 * @subpackage One_Click_Adblock_Monetisation/public
 */

include_once(plugin_dir_path( __FILE__ ) . '../class-ocam-generic.php');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class Ocam_Public extends Ocam_Generic
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
         * defined in Ocam_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ocam_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (is_plugin_active('one-click-adblock-monetisation/ad-back.php')) {
            return;
        }

        $elements = $this->getMyInfo();

        $title = __('To access the website', 'one-click-adblock-monetisation');
        $deactivate = __('Deactivate your adblock<br/>then reload the page.', 'one-click-adblock-monetisation');
        $click = __('Or click on the banner<br/>nearby', 'one-click-adblock-monetisation');
        $close = __('Close', 'one-click-adblock-monetisation');
        $why_title = __('Why do I see this message ?', 'one-click-adblock-monetisation');
        $why_description = __('Your browser blocks the ads on this website. To discover how to access it, find the help down here.', 'one-click-adblock-monetisation');
        $why_link = __('Access help page', 'one-click-adblock-monetisation');
        $issue_title = __('An issue ?', 'one-click-adblock-monetisation');
        $issue_description = __('If you have clicked on the banner or deactivate your adblock and you still see this message, report it to us below.', 'one-click-adblock-monetisation');
        $issue_link = __('Report a bug', 'one-click-adblock-monetisation');
        $issue_test = __('Verification in progress', 'one-click-adblock-monetisation');
        $issue_thank_you = __('Thank you for your help', 'one-click-adblock-monetisation');
        $who_title = __('Who are we ?', 'one-click-adblock-monetisation');
        $who_description = __('Thanks to AdBack, you can monetize your adblocked audience by displaying users friendly banners.', 'one-click-adblock-monetisation');
        $who_link = __('Discover AdBack', 'one-click-adblock-monetisation');

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
