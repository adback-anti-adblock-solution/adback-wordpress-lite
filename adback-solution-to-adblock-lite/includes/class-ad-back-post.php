<?php

/**
 * Used to post content to the adback api
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    Ad_Back
 * @subpackage Ad_Back/includes
 */

/**
 * Used to post content to the adback api
 *
 * This class defines all code to send some data
 *
 * @since      1.0.0
 * @package    Ad_Back
 * @subpackage Ad_Back/includes
 * @author     AdBack <contact@adback.co>
 */
class Ad_Back_Post
{
    public static function execute($url, $fields, $header = array())
    {
        $header[] = 'Content-Type: application/json';

        if (function_exists('curl_version')) {
            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            if (is_array($fields)) {
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            } else {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            //execute post
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);
        } else {
            $options = array(
                'http' => array(
                    'header' => implode("\r\n", $header),
                    'method' => 'POST',
                    'content' => is_array($fields) ? json_encode($fields) : $fields
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
        }

        return $result;
    }
}
