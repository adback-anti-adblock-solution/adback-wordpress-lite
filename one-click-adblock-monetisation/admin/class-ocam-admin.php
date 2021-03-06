<?php

/**
 * @since      1.0.0
 *
 * @package    One_Click_Adblock_Monetisation
 * @subpackage One_Click_Adblock_Monetisation/admin
 * @author     AdBack <contact@adback.co>
 */

include_once plugin_dir_path(__FILE__) . '../class-ocam-generic.php';

class Ocam_Admin extends Ocam_Generic
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueueStyles()
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

        if (!$this->shouldPageHaveLib()) {
            return;
        }

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ocam-theme.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
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

        if (!$this->shouldPageHaveLib()) {
            return;
        }

        $translation_array = array(
            'bounce' => __('Bounce rate of adblocker users', 'ad-back'),
            'ad_blocker' => __('Adblocker activation / deactivation', 'one-click-adblock-monetisation'),
            'ad_blocker_percent' => __('Ad blocker percent', 'one-click-adblock-monetisation'),
            'blocked_page_view' => __('Blocked page views', 'one-click-adblock-monetisation'),
            'browser' => __('Browser', 'one-click-adblock-monetisation'),
            'os' => __('OS', 'one-click-adblock-monetisation'),
            'percent_adblock_users' => __('Percent adblock users', 'one-click-adblock-monetisation'),
            'percent_bounce_adblock_users' => __('Percent bounce adblock users', 'one-click-adblock-monetisation'),
            'percent_bounce_all_users' => __('Percent bounce all users', 'one-click-adblock-monetisation'),
            'oops' => __('Oops...', 'one-click-adblock-monetisation'),
            'invalid_email_or_password' => __('Invalid email or password', 'one-click-adblock-monetisation'),
            'the_key_email_and_domain_fields_should_be_fill' => __('The key, email and domain fields should be filled', 'one-click-adblock-monetisation'),
            'the_email_and_password_fields_should_be_fill' => __('The email and password fields should be filled', 'one-click-adblock-monetisation'),
            'there_is_an_error_in_the_registration' => __('There is an error in the registration: {0}', 'one-click-adblock-monetisation'),
            'users_having_ad_blocker' => __('Users having ad blocker', 'one-click-adblock-monetisation'),
            'users_who_have_disabled_an_ad_blocker' => __('Users who have disabled an ad blocker', 'one-click-adblock-monetisation'),
            'percent_page_view_with_ad_block' => __('Percent page view with AdBlock', 'one-click-adblock-monetisation'),
            'percent_page_view' => __('Percent page view', 'one-click-adblock-monetisation'),
            'days' => __('days', 'one-click-adblock-monetisation'),
            'loading' => __('Loading ...', 'one-click-adblock-monetisation'),
            'no_data' => __('No Data', 'one-click-adblock-monetisation'),
            'error' => __('Something went wrong', 'one-click-adblock-monetisation'),
        );

        if ($this->isConnected()) {
            if ($this->getDomain() == '') {
                $this->askDomain();
            }
            // Loading AdBack library
            wp_enqueue_script('adback', 'https://' . $this->getDomain() . '/lib/ab.min.js', array(), $this->version, true);
        }

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ocam.js', array('jquery'), $this->version, true);
        wp_localize_script($this->plugin_name, 'trans_arr', $translation_array);
    }

    /**
     * Return if the current page is plugin page
     *
     * @return bool
     */
    public function shouldPageHaveLib()
    {
        if (is_admin()) {
            $screen = get_current_screen();
            if ($screen->id == "dashboard") {
                return true;
            }

            if ((
                isset($_GET['page']) &&
                ($_GET['page'] === 'ocam-lite' || $_GET['page'] === 'ocam-lite-settings' ||
                $_GET['page'] === 'ocam-lite-message' || $_GET['page'] === 'ocam-lite-diagnostic')) ||
                $_GET['page'] === 'ocam-lite-placements'
            ) {
                return true;
            }

        }


        return false;
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function displayPluginStatsPage()
    {
        if ($this->isConnected()) {
            if ($this->getDomain() == '') {
                $this->askDomain();
            }
            include_once('partials/ocam-display.php');
        } else {
            if (isset($_GET['access_token'])) {
                $this->saveToken(array(
                    'access_token' => $_GET['access_token'],
                    'refresh_token' => '',
                ));
                include_once('partials/ocam-redirect.php');
            } else {
                include_once('partials/ocam-login-display.php');
            }
        }
    }

    /**
     * Render the message page for this plugin.
     *
     * @since    1.0.0
     */
    public function displayPluginDiagnosticPage()
    {
        global $wpdb;
        if ($this->isConnected()) {
            if ($this->getDomain() === '') {
                $this->askDomain();
            }
            $adback = new Ocam_Public($this->plugin_name, $this->version);
            $adback->enqueueScripts();
            $token = $this->getToken();
            $script = $this->askScripts();
            $table_name_end_point = $wpdb->prefix . 'adback_end_point';
            $endPoints = $wpdb->get_row("SELECT * FROM " . $table_name_end_point . " WHERE id = " . get_current_blog_id());

            $rules = get_option('rewrite_rules', array());

            include_once('partials/ocam-diagnostic.php');
        } else {
            if (isset($_GET['access_token'])) {
                $this->saveToken(array(
                    'access_token' => $_GET['access_token'],
                    'refresh_token' => '',
                ));
                include_once('partials/ocam-redirect.php');
            } else {
                include_once('partials/ocam-login-display.php');
            }
        }
    }

    /**
     * Render the refresh domain page for this plugin.
     *
     * @since    1.0.0
     */
    public function displayPluginRefreshDomainPage()
    {
        if ($this->isConnected()) {
            $this->askDomain();
            include_once('partials/ocam-refresh-domain.php');
        } else {
            include_once('partials/ocam-login-display.php');
        }
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function addPluginAdminMenu()
    {
        global $_wp_last_object_menu;

        $_wp_last_object_menu++;

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */

        add_menu_page('One Click Adblock Monetization', 'One Click Adblock Monetization', 'manage_options', 'ocam-lite', '', plugin_dir_url(__FILE__) . '/partials/images/_dback_blanc_logo.png', $_wp_last_object_menu);

        add_submenu_page('ocam-lite', 'AdBack Statistiques', __('Statistics', 'one-click-adblock-monetisation'), 'manage_options', 'ocam-lite', array($this, 'displayPluginStatsPage'));
        add_submenu_page('ocam-lite', 'AdBack Diagnostic', __('Diagnostic', 'one-click-adblock-monetisation'), 'manage_options', 'ocam-lite-diagnostic', array($this, 'displayPluginDiagnosticPage'));

        add_plugins_page('ocam-lite', '', 'manage_options', 'ocam-lite-refresh-domain', array($this, 'displayPluginRefreshDomainPage'));
    }

    public function saveMessageCallback()
    {
        update_option('adback_admin_hide_message', $_POST['hide-admin'] == 'true' ? '1' : '0');

        $this->saveMessage($_POST['display']);

        echo "{\"done\":true}";
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    public function saveGoMessageCallback()
    {
        $this->saveMessage($_POST['display']);

        echo "{\"done\":true}";
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    public function logoutCallback()
    {
        global $wpdb; // this is how you get access to the database

        $table_name = $wpdb->prefix . 'adback_account';
        $wpdb->update(
            $table_name,
            array(
                "id" => get_current_blog_id(),
                "username" => "",
                "key" => "",
                "secret" => ""
            ),
            array("id" => get_current_blog_id())
        );

        //create token table
        $table_name = $wpdb->prefix . 'adback_token';
        $wpdb->update(
            $table_name,
            array(
                "id" => get_current_blog_id(),
                "access_token" => "",
                "refresh_token" => ""
            ),
            array("id" => get_current_blog_id())
        );

        //create myinfo table
        $table_name = $wpdb->prefix . 'adback_myinfo';
        $wpdb->update(
            $table_name,
            array(
                "id" => get_current_blog_id(),
                "myinfo" => "",
                "domain" => "",
                "update_time" => current_time('mysql', 1)
            ),
            array("id" => get_current_blog_id())
        );

        echo "{\"done\":true}";
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    public function addConfigNotice()
    {
        if (current_user_can('manage_options')) {

            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ocam-theme.css', array(), $this->version, 'all');

            if (!$this->isConnected()) {
                echo '<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
                        <div class="adback-incentive">
                        <form name="adback-incentive" action="' . esc_url(get_admin_url(get_current_blog_id(), 'admin.php?page=ocam-lite')) . '" method="POST">
                        <div class="adback-incentive-button-container">
                            <div class="adback-incentive-button-border">
                                <input type="submit" class="adback-incentive-button" value="' . __("Activate my AdBack plugin", 'one-click-adblock-monetisation') . '">
                            </div>
                        </div>
                        <div class="adback-incentive-description">
                            ' . __("It's time to analyze your adblock users, set up your AdBack account!", 'one-click-adblock-monetisation') . '
                        </div>
                    </div>
                    </form>
                </div>';
            }
            require_once plugin_dir_path(__FILE__) . '../includes/class-ocam-external-checker.php';
            Ocam_External_Checker::check();
        }
    }

    public function dashboardWidget()
    {
        wp_add_dashboard_widget(
            'adback',
            'Adback',
            array($this, 'dashboardWidgetContent')
        );
    }

    public function dashboardWidgetContent()
    {

        if ($this->isConnected()) {
            if ($this->getDomain() == '') {
                $this->askDomain();
            }
            include_once('partials/ocam-widget.php');
        } else {
            printf(__('You must be log in to see stats. Go to <a href="%s">Log in page</a>', 'ad-back'), get_admin_url(get_current_blog_id(), 'admin.php?page=ab'));
        }
    }
}
