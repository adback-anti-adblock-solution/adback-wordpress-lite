<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    Ad_Back_Lite
 * @subpackage Ad_Back_Lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ad_Back_Lite
 * @subpackage Ad_Back_Lite/admin
 * @author     AdBack <contact@adback.co>
 */

include_once(plugin_dir_path(__FILE__) . '../class-ad-back.php');

class Ad_Back_Lite_Admin extends Ad_Back_Lite_Generic
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
         * defined in Ad_Back_Lite_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ad_Back_Lite_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (!$this->shouldPageHaveLib()) {
            return;
        }

        wp_enqueue_style('vex-css', plugin_dir_url(__FILE__) . 'css/vex.css', array(), $this->version, 'all');
        wp_enqueue_style('vex-theme-css', plugin_dir_url(__FILE__) . 'css/vex-theme-default.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ab-admin.css', array(), $this->version, 'all');
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
         * defined in Ad_Back_Lite_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ad_Back_Lite_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (!$this->shouldPageHaveLib()) {
            return;
        }

        $translation_array = array(
            'bounce' => __('Bounce rate of adblocker users', 'ad-back'),
            'ad_blocker' => __('Adblocker activation / deactivation', 'adback-solution-to-adblock-lite'),
            'ad_blocker_percent' => __('Ad blocker percent', 'adback-solution-to-adblock-lite'),
            'blocked_page_view' => __('Blocked page views', 'adback-solution-to-adblock-lite'),
            'browser' => __('Browser', 'adback-solution-to-adblock-lite'),
            'os' => __('OS', 'adback-solution-to-adblock-lite'),
            'percent_adblock_users' => __('Percent adblock users', 'adback-solution-to-adblock-lite'),
            'percent_bounce_adblock_users' => __('Percent bounce adblock users', 'adback-solution-to-adblock-lite'),
            'percent_bounce_all_users' => __('Percent bounce all users', 'adback-solution-to-adblock-lite'),
            'oops' => __('Oops...', 'adback-solution-to-adblock-lite'),
            'invalid_email_or_password' => __('Invalid email or password', 'adback-solution-to-adblock-lite'),
            'the_key_email_and_domain_fields_should_be_fill' => __('The key, email and domain fields should be filled', 'adback-solution-to-adblock-lite'),
            'the_email_and_password_fields_should_be_fill' => __('The email and password fields should be filled', 'adback-solution-to-adblock-lite'),
            'there_is_an_error_in_the_registration' => __('There is an error in the registration: {0}', 'adback-solution-to-adblock-lite'),
            'users_having_ad_blocker' => __('Users having ad blocker', 'adback-solution-to-adblock-lite'),
            'users_who_have_disabled_an_ad_blocker' => __('Users who have disabled an ad blocker', 'adback-solution-to-adblock-lite'),
            'percent_page_view_with_ad_block' => __('Percent page view with AdBlock', 'adback-solution-to-adblock-lite'),
            'percent_page_view' => __('Percent page view', 'adback-solution-to-adblock-lite'),
            'days' => __('days', 'adback-solution-to-adblock-lite'),
            'loading' => __('Loading ...', 'adback-solution-to-adblock-lite'),
            'no_data' => __('No Data', 'adback-solution-to-adblock-lite'),
            'error' => __('Something went wrong', 'adback-solution-to-adblock-lite'),
        );

        if ($this->isConnected()) {
            if ($this->getDomain() == '') {
                $this->askDomain();
            }
            // Loading AdBack library
            wp_enqueue_script('adback', 'https://' . $this->getDomain() . '/lib/ab.min.js', array(), $this->version, true);
        }

        wp_enqueue_script('vex-js', plugin_dir_url(__FILE__) . 'js/vex.combined.min.js', array(), $this->version, true);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ab-admin.js', array('jquery'), $this->version, true);
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

            if (isset($_GET['page']) && ($_GET['page'] == 'ab-lite' || $_GET['page'] == 'ab-lite-settings' ||
                    $_GET['page'] == 'ab-lite-message' || $_GET['page'] == 'ab-lite-diagnostic') || $_GET['page'] == 'ab-lite-placements') {
                return true;
            }

        }


        return false;
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
            include_once('partials/ad-back-admin-widget.php');
        } else {
            printf(__('You must be log in to see stats. Go to <a href="%s">Log in page</a>', 'ad-back'), get_admin_url(get_current_blog_id(), 'admin.php?page=ab'));
        }
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
            include_once('partials/ad-back-admin-display.php');
        } else {
            if (isset($_GET['access_token'])) {
                $this->saveToken(array(
                    'access_token' => $_GET['access_token'],
                    'refresh_token' => '',
                ));
                include_once('partials/ad-back-admin-redirect.php');
            } else {
                include_once('partials/ad-back-admin-login-display.php');
            }
        }
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function displayPluginSettingsPage()
    {
        if ($this->isConnected()) {
            if ($this->getDomain() == '') {
                $this->askDomain();
            }
            include_once('partials/ad-back-admin-settings-display.php');
        } else {
            if (isset($_GET['access_token'])) {
                $this->saveToken(array(
                    'access_token' => $_GET['access_token'],
                    'refresh_token' => '',
                ));
                include_once('partials/ad-back-admin-redirect.php');
            } else {
                include_once('partials/ad-back-admin-login-display.php');
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
            $adback = new Ad_Back_Lite_Public($this->plugin_name, $this->version);
            $adback->enqueueScripts();
            $token = $this->getToken();
            $script = $this->askScripts();
            $table_name_end_point = $wpdb->prefix . 'adback_end_point';
            $endPoints = $wpdb->get_row("SELECT * FROM " . $table_name_end_point . " WHERE id = " . get_current_blog_id());

            $rules = get_option('rewrite_rules', array());

            include_once('partials/ad-back-admin-diagnostic.php');
        } else {
            if (isset($_GET['access_token'])) {
                $this->saveToken(array(
                    'access_token' => $_GET['access_token'],
                    'refresh_token' => '',
                ));
                include_once('partials/ad-back-admin-redirect.php');
            } else {
                include_once('partials/ad-back-admin-login-display.php');
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
            include_once('partials/ad-back-admin-refresh-domain.php');
        } else {
            include_once('partials/ad-back-admin-login-display.php');
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

        add_menu_page('AdBack (Lite)', 'AdBack (Lite)', 'manage_options', 'ab-lite', '', plugin_dir_url(__FILE__) . '/partials/images/_dback_blanc_logo.png', $_wp_last_object_menu);

        add_submenu_page('ab-lite', 'AdBack Statistiques', __('Statistics', 'adback-solution-to-adblock-lite'), 'manage_options', 'ab-lite', array($this, 'displayPluginStatsPage'));
        add_submenu_page('ab-lite', 'AdBack Settings', __('Settings', 'adback-solution-to-adblock-lite'), 'manage_options', 'ab-lite-settings', array($this, 'displayPluginSettingsPage'));
        add_submenu_page('ab-lite', 'AdBack Diagnostic', __('Diagnostic', 'adback-solution-to-adblock-lite'), 'manage_options', 'ab-lite-diagnostic', array($this, 'displayPluginDiagnosticPage'));

        add_plugins_page('ab-lite', '', 'manage_options', 'ab-lite-refresh-domain', array($this, 'displayPluginRefreshDomainPage'));
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

            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ab-admin.css', array(), $this->version, 'all');

            if (!$this->isConnected()) {
                echo '<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
		                <div class="adback-incentive">
		                <form name="adback-incentive" action="' . esc_url(get_admin_url(get_current_blog_id(), 'admin.php?page=ab-lite-settings')) . '" method="POST">
                        <div class="adback-incentive-button-container">
                            <div class="adback-incentive-button-border">
                                <input type="submit" class="adback-incentive-button" value="' . __("Activate my AdBack plugin", 'adback-solution-to-adblock-lite') . '">
                            </div>
                        </div>
                        <div class="adback-incentive-description">
                            ' . __("It's time to analyze your adblock users, set up your AdBack account!", 'adback-solution-to-adblock-lite') . '
                        </div>
                    </div>
                    </form>
                </div>';
            }
            require_once plugin_dir_path(__FILE__) . '../includes/class-ad-back-external-checker.php';
            Ad_Back_Lite_External_Checker::check();
        }
    }
}
