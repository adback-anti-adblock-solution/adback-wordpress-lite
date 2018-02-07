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
<?php include "ad-back-admin-header.php" ?>
<div id="ab-settings">
    <div id="ab-full-app">
        <grid>
            <div col="5/6">

                <div id="ab-configuration-form"></div>
                <section style="background-color:transparent;">
                    <h4 class="header-section"><?php esc_html_e('Get more statistics', 'adback-solution-to-adblock-lite'); ?></h4>
                    <hr/>
                    <div class="section-content">
                        <button id="ab-website" class="btn-generic-save" primary
                                m-full><?php esc_html_e('Redirect me to my AdBack dashboard', 'adback-solution-to-adblock-lite'); ?></button>
                    </div>
                </section>
                <section style="background-color:transparent;">
                    <h4 class="header-section"><?php esc_html_e('Adback Account', 'adback-solution-to-adblock-lite'); ?></h4>
                    <hr/>
                    <div class="section-content">
                        <button id="ab-logout" class="btn-generic-save" primary
                                m-full><?php esc_html_e('Log out', 'adback-solution-to-adblock-lite'); ?></button>
                    </div>
                </section>
            </div>
            <div col="1/6">
                <div id="adb-sidebar-standalone"
                     data-reviewlink="https://wordpress.org/support/plugin/adback-solution-to-adblock-lite/reviews/"
                     data-supportlink="https://wordpress.org/support/plugin/adback-solution-to-adblock-lite">
                </div>
            </div>
        </grid>
    </div>
</div>

<script type="text/javascript">
    window.onload = function () {
        if (typeof adbackjs === 'object') {
            adbackjs.init({
                token: '<?php echo $this->getToken()->access_token; ?>',
                url: 'https://<?php echo $this->getDomain(); ?>/api/',
                language: '<?php echo str_replace('_', '-', get_locale()); ?>',
                version: 2
            });
        }
    };

    (function ($) {
        $(document).ready(function () {
            $("#ab-website").on('click', function () {
                var locale = "<?php echo explode('_', get_locale())[0]; ?>";
                var email = "<?php echo get_bloginfo('admin_email') ?>";
                window.location.href = 'https://www.adback.co/' + locale + '/login?_login_email=' + email;
            });
        });
    })(jQuery);
</script>
