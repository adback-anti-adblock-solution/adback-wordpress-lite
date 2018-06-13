<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    Ad_Back_Lite
 * @subpackage Ad_Back_Lite/admin/partials
 */

?>
<h1><?php _e( 'AdBack', 'ad-back' ); ?></h1>

<div id="ab-login">
    <div class="ab-col-md-12">
            <div class="ab-login-box">
                <center><a href="https://www.adback.co" target="_blank"><div class="ab-login-logo" style="background-image:url('<?php echo plugin_dir_url( __FILE__ ); ?>images/_dback.png');"></div></a></center>
                <center>
                    <?php if (get_option('adback_registration_error', false) != 'adback_oauth.registration.existing_user') { ?>
                        <span class="ab-registration-error">
                            <?php esc_html_e('You already have an AdBack account. Connect to your account by clicking on the "Log in" link below the button :', 'adback-solution-to-adblock-lite'); ?>
                        </span>
                    <?php } elseif (get_option('adback_registration_error', false)) { ?>
                        <span class="ab-registration-error">
                            <?php esc_html_e('An error occured during your registration. Click on the "Create my AdBack account" button to try again.', 'adback-solution-to-adblock-lite'); ?>
                        </span>
                      <div class="ab-registration-advantages-box">
                        <h4 class="ab-title"><?php esc_html_e('Why create an AdBack account ?', 'adback-solution-to-adblock-lite'); ?></h4>
                        <p class="ab-registration-advantages-intro"><?php esc_html_e('AdBack is an analytics and monetization tool of your adblock audience. It is 100&#37; free and without obligation. By authorizing AdBack, you will access:', 'adback-solution-to-adblock-lite'); ?></p>
                        <ul>
                            <li><?php esc_html_e('A unique and unblockable technology', 'adback-solution-to-adblock-lite'); ?></li>
                            <li><?php esc_html_e('Detailed statistics directly on your WordPress and AdBack interface', 'adback-solution-to-adblock-lite'); ?></li>
                            <li><?php esc_html_e('Unique and user-friendly monetization solutions', 'adback-solution-to-adblock-lite'); ?></li>
                        </ul>
                    </div>
                    <div class="ab-registration-advantages-box">
                        <p class="ab-registration-advantages-intro"><?php esc_html_e('By activating the plugin:', 'adback-solution-to-adblock-lite'); ?></p>
                        <ul>
                            <li><?php esc_html_e('You accept the AdBack Terms of Service', 'adback-solution-to-adblock-lite'); ?></li>
                            <li><?php _e('The application will collect automatically the name of your website and the associated email address.<br>&ensp; That address will be used to give you the information related to your account and to the AdBack news and products', 'adback-solution-to-adblock-lite'); ?></li>
                            <li><?php esc_html_e('The application will install the AdBack script, necessary to display the analytics and monetization solutions', 'adback-solution-to-adblock-lite'); ?></li>
                        </ul>
                        <center>
                            <p><?php _e('<a href="https://landing.adback.co/en/legal-notice/">Terms of Service</a>', 'adback-solution-to-adblock-lite'); ?> - <?php _e('<a href="https://landing.adback.co/en/privacy-policy/">Privacy Policy</a>', 'adback-solution-to-adblock-lite'); ?></p>
                        </center>
                    </div>
                <?php } else { ?>
                    <div class="ab-registration-advantages-box">
                        <p class="ab-registration-advantages-intro"><?php esc_html_e('AdBack is an analytics and monetization tool of your adblock audience. It is 100&#37; free and without obligation. By authorizing AdBack, you will access:', 'adback-solution-to-adblock-lite'); ?></p>
                        <ul>
                            <li><?php esc_html_e('A unique and unblockable technology', 'adback-solution-to-adblock-lite'); ?></li>
                            <li><?php esc_html_e('Detailed statistics directly on your WordPress and AdBack interface', 'adback-solution-to-adblock-lite'); ?></li>
                            <li><?php esc_html_e('Unique and user-friendly monetization solutions', 'adback-solution-to-adblock-lite'); ?></li>
                        </ul>
                    </div>
                    <div class="ab-registration-advantages-box">
                        <p class="ab-registration-advantages-intro"><?php esc_html_e('By activating the plugin:', 'adback-solution-to-adblock-lite'); ?></p>
                        <ul>
                            <li><?php esc_html_e('You accept the AdBack Terms of Service', 'adback-solution-to-adblock-lite'); ?></li>
                            <li><?php _e('The application will collect automatically the name of your website and the associated email address.<br>&ensp; That address will be used to give you the information related to your account and to the AdBack news and products', 'adback-solution-to-adblock-lite'); ?></li>
                            <li><?php esc_html_e('The application will install the AdBack script, necessary to display the analytics and monetization solutions', 'adback-solution-to-adblock-lite'); ?></li>
                        </ul>
                        <center>
                            <p><?php _e('<a href="https://landing.adback.co/en/legal-notice/">Terms of Service</a>', 'adback-solution-to-adblock-lite'); ?> - <?php _e('<a href="https://landing.adback.co/en/privacy-policy/">Privacy Policy</a>', 'adback-solution-to-adblock-lite'); ?></p>
                        </center>
                    </div>
                <?php } ?>
            </center>
            <center>
                <button
                        class="ab-button ab-button-primary"
                        id="ab-register-adback"
                        style="margin-top: 30px;"
                        data-site-url="<?php echo get_site_url(get_current_blog_id()) ?>"
                        data-email="<?php echo get_bloginfo('admin_email') ?>"
                        data-local="<?php echo (get_locale() === 'fr_FR') ? 'fr':'en'; ?>"
                >
                    <?php esc_html_e('Create my AdBack account', 'adback-solution-to-adblock-lite'); ?>
                </button>
            </center>
            <br/>
            <center>
                <a href="#" id="ab-login-adback" style="width:100%;margin-top: 30px;"><?php esc_html_e('Log in', 'adback-solution-to-adblock-lite'); ?></a>
            </center>
            <br/>
            <center>
                <a href="/wp-admin/plugins.php" class="ab-refuse-adback"><?php esc_html_e('Refuse (you won’t be able to use AdBack solutions)', 'adback-solution-to-adblock-lite'); ?></a>
            </center>
        </div>
    </div>
</div>
