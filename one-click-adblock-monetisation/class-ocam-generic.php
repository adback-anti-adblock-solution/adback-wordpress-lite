<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    One_Click_Adblock_Monetisation
 * @subpackage One_Click_Adblock_Monetisation
 */
class Ocam_Generic
{
    private $connected = null;

    public function getMyInfo()
    {
        global $wpdb; // this is how you get access to the database
        require_once(dirname(__FILE__) . '/includes/class-ocam-rewrite-rule-validator.php');

        $table_name = $wpdb->prefix . 'adback_full_tag';
        $blogId = get_current_blog_id();
        $myinfo = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE blog_id = ". $blogId);
        $scriptData = array();

        foreach ($myinfo as $scriptInfo) {
            if (strtotime($scriptInfo->update_time) > (time() - 10800)) {
                $scriptData[$scriptInfo->type] = $scriptInfo->value;
            }
        }

        if (empty($scriptData)) {
            if ($this->isRewriteRouteEnabled()) {
                $endPoints = $this->askEndPoints();
                $this->saveEndPointsAndUpdateRoutes($endPoints, $blogId);
            }

            $fullScripts = $this->askFullScripts();
            $genericScript = $this->askGenericScripts();

            $fullScripts['script_codes']['generic'] = $genericScript;

            $types = array(
                'analytics',
                'generic',
                'iab_banner',
            );
            foreach ($types as $key => $type) {
                if (
                    is_array($fullScripts['script_codes'])
                    && array_key_exists($type, $fullScripts['script_codes'])
                    && '' !== $fullScripts['script_codes'][$type]['code']
                ) {
                    $sql = <<<SQL
INSERT INTO $table_name
  (id,blog_id,type,value,update_time) VALUES (%d,%d,%s,%s,%s)
  ON DUPLICATE KEY UPDATE value = %s, update_time = %s;
SQL;
                    $sql = $wpdb->prepare(
                        $sql,
                        $key,
                        $blogId,
                        $type,
                        $fullScripts['script_codes'][$type]['code'],
                        current_time('mysql', 1),
                        $fullScripts['script_codes'][$type]['code'],
                        current_time('mysql', 1)
                    );
                    $wpdb->query($sql);
                    $scriptData[$type] = $fullScripts['script_codes'][$type]['code'];
                }
            }
        }

        return $scriptData;
    }

    public function saveMessage($display)
    {
        $url = 'https://www.adback.co/api/custom-message/update-status?_format=json&access_token=' . $this->getToken()->access_token;
        $displayAsBoolean = 'true' === $display ? true : false;
        $fields = array('display' => $displayAsBoolean);
        Ocam_Post::execute($url, $fields);

        return true;
    }

    public function isConnected($token = null)
    {
        if ($this->connected !== null) {
            return $this->connected;
        }

        if ($token == null) {
            $token = $this->getToken();
        }

        if (is_array($token)) {
            $token = (object)$token;
        }

        if (isset($token->access_token) && $token->access_token !== '') {
            $domain = 'www.adback.co';
            $url = "https://".$domain."/api/test/normal?access_token=" . $token->access_token;

            $result = json_decode(Ocam_Get::execute($url), true);
            return $this->connected = is_array($result) && array_key_exists("name", $result);
        } else {
            return $this->connected = false;
        }
    }

    public function getToken()
    {
        global $wpdb; // this is how you get access to the database

        $table_name = $wpdb->prefix . 'adback_token';
        $token = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE id = ".get_current_blog_id());

        return $token;
    }

    public function saveToken($token)
    {
        global $wpdb; // this is how you get access to the database

        if ($token == null || array_key_exists("error", $token)) {
            return;
        }

        $table_name = $wpdb->prefix . 'adback_token';
        $blogId = get_current_blog_id();
        $savedToken = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE id = ".$blogId);

        if (null === $savedToken) {
            $sql = <<<SQL
INSERT INTO $table_name
  (id,access_token,refresh_token) values (%d,%s,%s)
  ON DUPLICATE KEY UPDATE access_token = %s, refresh_token = %s;
SQL;
            $sql = $wpdb->prepare(
                $sql,
                $blogId,
                $token["access_token"],
                $token["refresh_token"],
                $token["access_token"],
                $token["refresh_token"]
            );
            $wpdb->query($sql);
        } else {
            $wpdb->update(
                $table_name,
                array(
                    "access_token" => $token["access_token"],
                    "refresh_token" => $token["refresh_token"]
                ),
                array("id" => $blogId)
            );
        }

        $this->notifyInstallation($token["access_token"]);
    }

    public function notifyInstallation($accessToken)
    {
        $notifyUrl = 'https://www.adback.co/api/plugin-activate/wordpressLite?access_token=' . $accessToken;

        Ocam_Get::execute($notifyUrl);
    }

    public function askDomain()
    {
        if (null === $this->getToken() || '' === $this->getToken()->access_token) {
            return null;
        }

        $jsonDomain = Ocam_Get::execute("https://www.adback.co/api/script/me?access_token=" . $this->getToken()->access_token);
        $result = json_decode($jsonDomain, true);
        if (isset($result['analytics_domain'])) {
            $this->saveDomain($result['analytics_domain']);
        }
    }

    public function askScripts()
    {
        if (null === $this->getToken() || '' === $this->getToken()->access_token) {
            return null;
        }

        $jsonScripts = Ocam_Get::execute("https://www.adback.co/api/script/me?access_token=" . $this->getToken()->access_token);
        $result = json_decode($jsonScripts, true);

        return $result;
    }

    public function askFullScripts()
    {
        if (null === $this->getToken() || '' === $this->getToken()->access_token) {
            return null;
        }

        $jsonScripts = Ocam_Get::execute("https://www.adback.co/api/script/me/full?access_token=" . $this->getToken()->access_token);
        $result = json_decode($jsonScripts, true);

        return $result;
    }

    public function askGenericScripts()
    {
        if (null === $this->getToken() || '' === $this->getToken()->access_token) {
            return null;
        }

        $jsonScripts = Ocam_Get::execute("https://www.adback.co/api/script/me/generic?access_token=" . $this->getToken()->access_token);
        $result = json_decode($jsonScripts, true);

        return $result;
    }

    public function isRewriteRouteEnabled()
    {
        return (bool) get_option('permalink_structure');
    }

    public function askEndPoints()
    {
        if (null === $this->getToken() || '' === $this->getToken()->access_token) {
            return null;
        }

        $jsonEndPoints = Ocam_Get::execute("https://www.adback.co/api/end-point/me?access_token=" . $this->getToken()->access_token);
        $result = json_decode($jsonEndPoints, true);

        return $result;
    }

    public function refreshEndPoints()
    {
        if (null === $this->getToken() || '' === $this->getToken()->access_token) {
            return null;
        }

        $jsonEndPoints = Ocam_Get::execute("https://www.adback.co/api/end-point/refresh?access_token=" . $this->getToken()->access_token);
        $result = json_decode($jsonEndPoints, true);

        return $result;
    }

    public function saveEndPointsAndUpdateRoutes($endPoints, $blogId)
    {
        global $wpdb;

        $table_name_end_point = $wpdb->prefix . 'adback_end_point';

        // loop while endpoints (next) conflict with rewrite rules, if not, insert all endpoint data
        for ($i = 0; $i < 5; $i++) {
            if (!Ocam_Rewrite_Rule_Validator::validate($endPoints['next_end_point'])) {
                $sql = <<<SQL
INSERT INTO $table_name_end_point
  (id,old_end_point,end_point,next_end_point) VALUES (%d,%s,%s,%s)
  ON DUPLICATE KEY UPDATE old_end_point = %s, end_point = %s, next_end_point = %s;
SQL;
                $sql = $wpdb->prepare(
                    $sql,
                    $blogId,
                    $endPoints['old_end_point'],
                    $endPoints['end_point'],
                    $endPoints['next_end_point'],
                    $endPoints['old_end_point'],
                    $endPoints['end_point'],
                    $endPoints['next_end_point']
                );
                $wpdb->query($sql);
                break;
            }
            $endPoints = $this->refreshEndPoints();
        }

        adback_lite_plugin_rules();
        flush_rewrite_rules();
    }

    public function saveDomain($domain)
    {
        global $wpdb; // this is how you get access to the database

        $table_name = $wpdb->prefix . 'adback_myinfo';
        $wpdb->update(
            $table_name,
            array(
                'domain' => $domain,
                'update_time' => current_time('mysql', 1)
            ),
            array("id" => get_current_blog_id())
        );
    }

    public function getDomain()
    {
        global $wpdb; // this is how you get access to the database

        $table_name = $wpdb->prefix . 'adback_myinfo';
        $myinfo = $wpdb->get_row("SELECT domain FROM " . $table_name . " WHERE id = ".get_current_blog_id()." AND update_time >= DATE_SUB(NOW(),INTERVAL 3 HOUR);");

        return $myinfo ? $myinfo->domain : '';
    }

    public function askSubscription()
    {
        if (null === $this->getToken() || '' === $this->getToken()->access_token) {
            return null;
        }

        $jsonDomain = Ocam_Get::execute("https://www.adback.co/api/subscription/me?access_token=" . $this->getToken()->access_token);
        $result = json_decode($jsonDomain, true);

        return $result;
    }
}
