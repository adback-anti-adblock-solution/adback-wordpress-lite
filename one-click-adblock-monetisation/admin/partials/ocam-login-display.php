<?php

/**
 * @since      1.0.0
 *
 * @package    One_Click_Adblock_Monetisation
 * @subpackage One_Click_Adblock_Monetisation/admin/partials
 */

?>
<h1><?php _e( 'AdBack', 'ad-back' ); ?></h1>

<div id="ocam-login">
    <div class="ocam-col-md-12">
            <div class="ocam-login-box">
                <center><a href="https://www.adback.co" target="_blank"><div class="ocam-login-logo" style="background-image:url('<?php echo plugin_dir_url( __FILE__ ); ?>images/_dback.png');"></div></a></center>
                <center>
                    <?php if (get_option('adback_registration_error', false) == 'adback_oauth.registration.existing_user') { ?>
                        <span class="ocam-registration-error">
                            <?php esc_html_e('You already have an AdBack account. Connect to your account by clicking on the "Log in" link below the button :', 'one-click-adblock-monetisation'); ?>
                        </span>
                    <?php } elseif (get_option('adback_registration_error', false)) { ?>
                        <span class="ocam-registration-error">
                            <?php esc_html_e('An error occured during your registration. Click on the "Create my AdBack account" button to try again.', 'one-click-adblock-monetisation'); ?>
                        </span>
                      <div class="ocam-registration-advantages-box">
                        <h4 class="ocam-title"><?php esc_html_e('Why create an AdBack account ?', 'one-click-adblock-monetisation'); ?></h4>
                        <p class="ocam-registration-advantages-intro"><?php esc_html_e('AdBack is an analytics and monetization tool of your adblock audience. It is 100&#37; free and without obligation. By authorizing AdBack, you will access:', 'one-click-adblock-monetisation'); ?></p>
                        <ul>
                            <li><?php esc_html_e('A unique and unblockable technology', 'one-click-adblock-monetisation'); ?></li>
                            <li><?php esc_html_e('Detailed statistics directly on your WordPress and AdBack interface', 'one-click-adblock-monetisation'); ?></li>
                            <li><?php esc_html_e('Unique and user-friendly monetization solutions', 'one-click-adblock-monetisation'); ?></li>
                        </ul>
                    </div>
                    <div class="ocam-registration-advantages-box">
                        <p class="ocam-registration-advantages-intro"><?php esc_html_e('By activating the plugin:', 'one-click-adblock-monetisation'); ?></p>
                        <ul>
                            <li><?php esc_html_e('You accept the AdBack Terms of Service', 'one-click-adblock-monetisation'); ?></li>
                            <li><?php _e('The application will collect automatically the name of your website and the associated email address.<br>&ensp; That address will be used to give you the information related to your account and to the AdBack news and products', 'one-click-adblock-monetisation'); ?></li>
                            <li><?php esc_html_e('The application will install the AdBack script, necessary to display the analytics and monetization solutions', 'one-click-adblock-monetisation'); ?></li>
                        </ul>
                        <center>
                            <p><?php _e('<a href="https://landing.adback.co/en/legal-notice/">Terms of Service</a>', 'one-click-adblock-monetisation'); ?> - <?php _e('<a href="https://landing.adback.co/en/privacy-policy/">Privacy Policy</a>', 'one-click-adblock-monetisation'); ?></p>
                        </center>
                    </div>
                <?php } else { ?>
                    <div class="ocam-registration-advantages-box">
                        <p class="ocam-registration-advantages-intro"><?php esc_html_e('AdBack is an analytics and monetization tool of your adblock audience. It is 100&#37; free and without obligation. By authorizing AdBack, you will access:', 'one-click-adblock-monetisation'); ?></p>
                        <ul>
                            <li><?php esc_html_e('A unique and unblockable technology', 'one-click-adblock-monetisation'); ?></li>
                            <li><?php esc_html_e('Detailed statistics directly on your WordPress and AdBack interface', 'one-click-adblock-monetisation'); ?></li>
                            <li><?php esc_html_e('Unique and user-friendly monetization solutions', 'one-click-adblock-monetisation'); ?></li>
                        </ul>
                    </div>
                    <div class="ocam-registration-advantages-box">
                        <p class="ocam-registration-advantages-intro"><?php esc_html_e('By activating the plugin:', 'one-click-adblock-monetisation'); ?></p>
                        <ul>
                            <li><?php esc_html_e('You accept the AdBack Terms of Service', 'one-click-adblock-monetisation'); ?></li>
                            <li><?php _e('The application will collect automatically the name of your website and the associated email address.<br>&ensp; That address will be used to give you the information related to your account and to the AdBack news and products', 'one-click-adblock-monetisation'); ?></li>
                            <li><?php esc_html_e('The application will install the AdBack script, necessary to display the analytics and monetization solutions', 'one-click-adblock-monetisation'); ?></li>
                        </ul>
                        <center>
                            <p><?php _e('<a href="https://landing.adback.co/en/legal-notice/">Terms of Service</a>', 'one-click-adblock-monetisation'); ?> - <?php _e('<a href="https://landing.adback.co/en/privacy-policy/">Privacy Policy</a>', 'one-click-adblock-monetisation'); ?></p>
                        </center>
                    </div>
                <?php } ?>
            </center>
            <center>
                <button
                        class="ocam-button ocam-button-primary"
                        id="ocam-register-adback"
                        style="margin-top: 30px;"
                        data-site-url="<?php echo get_site_url(get_current_blog_id()) ?>"
                        data-email="<?php echo get_bloginfo('admin_email') ?>"
                        data-local="<?php echo (get_locale() === 'fr_FR') ? 'fr':'en'; ?>"
                >
                    <?php esc_html_e('Create my AdBack account', 'one-click-adblock-monetisation'); ?>
                </button>
            </center>
            <br/>
            <center>
                <a href="#" id="ocam-login-adback" style="width:100%;margin-top: 30px;"><?php esc_html_e('Log in', 'adback-solution-to-adblock-lite'); ?></a>
            </center>
            <br/>
            <center>
                <a href="/wp-admin/plugins.php" class="ocam-refuse-adback"><?php esc_html_e('Refuse (you won’t be able to use AdBack solutions)', 'adback-solution-to-adblock-lite'); ?></a>
            </center>
        </div>
    </div>
</div>
