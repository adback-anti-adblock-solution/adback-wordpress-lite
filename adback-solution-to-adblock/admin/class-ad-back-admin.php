<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    Ad_Back
 * @subpackage Ad_Back/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ad_Back
 * @subpackage Ad_Back/admin
 * @author     Antoine Ferrier <contact@adback.co>
 */

include_once( plugin_dir_path( __FILE__ ) . '../class-ad-back.php');

class Ad_Back_Admin extends Ad_Back_Generic
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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
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
         * defined in Ad_Back_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ad_Back_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (!$this->isPluginPage()) {
            return;
        }

        wp_enqueue_style('sweetalert-css', plugin_dir_url( __FILE__ ) . 'css/sweetalert2.min.css', array(), $this->version, 'all' );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ab-admin.css', array(), $this->version, 'all' );
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
         * defined in Ad_Back_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ad_Back_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (!$this->isPluginPage()) {
            return;
        }

        $translation_array = array(
            'bounce' => __( 'Bounce', 'ad-back' ),
            'ad_blocker' => __('Ad Blocker', 'ad-back'),
            'ad_blocker_percent' => __('Ad blocker percent', 'ad-back'),
            'blocked_page_view' => __('Blocked page view', 'ad-back'),
            'browser' => __('Browser', 'ad-back'),
            'os' => __('OS', 'ad-back'),
            'percent_adblock_users' => __('Percent adblock users', 'ad-back'),
            'percent_bounce_adblock_users' => __('Percent bounce adblock users', 'ad-back'),
            'percent_bounce_all_users' => __('Percent bounce all users', 'ad-back'),
            'oops' => __('Oops...', 'ad-back'),
            'invalid_email_or_password' => __('Invalid email or password', 'ad-back'),
            'the_key_email_and_domain_fields_should_be_fill' => __('The key, email and domain fields should be fill', 'ad-back'),
            'the_email_and_password_fields_should_be_fill' => __('The email and password fields should be fill', 'ad-back'),
            'there_is_an_error_in_the_registration' => __('There is an error in the registration : {0}', 'ad-back'),
            'users_having_ad_blocker' => __('Users having ad blocker', 'ad-back'),
            'users_who_have_disabled_an_ad_blocker' => __('Users who have disabled an ad blocker', 'ad-back'),
            'percent_page_view_with_ad_block' => __('Percent page view with AdBlock', 'ad-back'),
            'percent_page_view' => __('Percent page view', 'ad-back'),
            'days' => __('days', 'ad-back'),
            'loading' => __('Loading ...', 'ad-back'),
            'no_data' => __('No Data', 'ad-back'),
        );

        if($this->isConnected()) {
            if($this->getDomain() == '') {
                $this->askDomain();
            }
            // Loading AdBack library
            wp_enqueue_script('adback', 'https://'. $this->getDomain() .'/lib/ab.min.js', $this->version, true );
        }

        wp_enqueue_script('sweetalert-js', plugin_dir_url( __FILE__ ) . 'js/sweetalert2.min.js', $this->version, false );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ab-admin.js', array( 'jquery' ), $this->version, false );
        wp_localize_script( $this->plugin_name, 'trans_arr', $translation_array );
    }

    /**
     * Return if the current page is plugin page
     *
     * @return bool
     */
    public function isPluginPage()
    {
        if(isset($_GET['page']) && ($_GET['page'] == 'ab' || $_GET['page'] == 'ab-settings')) {
            return true;
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
        if($this->isConnected()) {
            if($this->getDomain() == '') {
                $this->askDomain();
            }
            include_once( 'partials/ad-back-admin-display.php' );
        } else {
            if(isset($_GET['access_token'])) {
                $this->saveToken([
                    'access_token' => $_GET['access_token'],
                    'refresh_token' => '',
                    ]);
                include_once( 'partials/ad-back-admin-redirect.php');
            } else {
                include_once( 'partials/ad-back-admin-login-display.php');
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
        if($this->isConnected()) {
            if($this->getDomain() == '') {
                $this->askDomain();
            }
            $messages = $this->getMessages();
            include_once( 'partials/ad-back-admin-settings-display.php' );
        } else {
            if(isset($_GET['access_token'])) {
                $this->saveToken([
                    'access_token' => $_GET['access_token'],
                    'refresh_token' => '',
                    ]);
                include_once( 'partials/ad-back-admin-redirect.php');
            } else {
                include_once( 'partials/ad-back-admin-login-display.php');
            }
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

        add_menu_page( 'AdBack', 'AdBack', 'manage_options', 'ab', '', 'dashicons-chart-bar', $_wp_last_object_menu );

        add_submenu_page('ab', 'AdBack Statistiques', __('Statistics', 'ad-back'), 'manage_options', 'ab', array($this, 'displayPluginStatsPage'));
        add_submenu_page('ab', 'AdBack Settings', __('Settings', 'ad-back'), 'manage_options', 'ab-settings', array($this, 'displayPluginSettingsPage'));
    }

    public function registerWithAbBackAccountCallback()
    {
        $url = 'https://www.adback.co/oauth/access_token?grant_type=password';
        $fields = array(
            'username' => $_POST['username'],
            'password' => $_POST['password']
        );

        $headers = array(
            'Authorization: Basic YzAxMDY1ZWQ5ZWZlZDI1ZDQ1ZTlmN2ZmYmQ3NDFiYmFkZTRiODljY2Q0ZDJiOGE4NDQ1Mjc5MWYxMmNkZjNhOTozOGNmM2JjNjQ0OGMzYjYyODBiODI5OGFmNzBhNzU1MDEyNDY2NTE4YTllODEyZmRkZGU2YWVmN2JjNmM3YjQ1'
        );

        $result = $this->postContents($url, $fields, $headers);
        $token = json_decode($result, true);

        if(array_key_exists("error", $token) == false) {
            $this->saveToken($token);

            if($this->isConnected($token)) {
                echo "{\"done\":true}";
                wp_die();
            }
        }

        echo "{\"done\":false}";
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    public function saveMessageCallback()
    {
        $this->saveMessage($_POST['display'], $_POST['message'], $_POST['header-text'], $_POST['close-text']);

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
                "id" => "1",
                "username" => "",
                "key" => "",
                "secret" => ""
            ),
            array("id"=>1)
        );

        //create token table
        $table_name = $wpdb->prefix . 'adback_token';
        $wpdb->update(
            $table_name,
            array(
                "id" => "1",
                "access_token" => "",
                "refresh_token" => ""
            ),
            array("id"=>1)
        );

        //create myinfo table
        $table_name = $wpdb->prefix . 'adback_myinfo';
        $wpdb->update(
            $table_name,
            array(
                "id" => "1",
                "myinfo" => "",
                "domain" => "",
                "update_time" => current_time('mysql', 1)
            ),
            array("id"=>1)
        );

        $table_name = $wpdb->prefix . 'adback_message';
        $wpdb->update(
            $table_name,
            array(
                "id" => "1",
                "message" => "",
                "header_text" => "",
                "close_text" => "",
                "display" => "0",
                "update_time" => current_time('mysql', 1)
            ),
            array("id"=>1)
        );

        echo "{\"done\":true}";
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    public function addConfigNotice()
    {
        if (current_user_can('manage_options')) {
            if (!$this->isConnected()) {
                echo '<div class="error"><p>'.__('Warning: Your AdBack plugin is not enabled while you have not configured your AdBack account.', 'ad-back').' <a href="'. esc_url( get_admin_url(null, 'admin.php?page=ab-settings') ) .'">'.__('Settings').'</a></p></div>';
            }
        }
    }
}
