<?php

/**
 * Used to get content from the adback api
 *
 * @link       https://www.adback.co
 * @since      1.0.0
 *
 * @package    One_Click_Adblock_Monetisation
 * @subpackage One_Click_Adblock_Monetisation/includes
 */

/**
 * Used to get content from the adback api
 *
 * This class defines all code to get some data
 *
 * @since      1.0.0
 * @package    One_Click_Adblock_Monetisation
 * @subpackage One_Click_Adblock_Monetisation/includes
 * @author     AdBack <contact@adback.co>
 */
class Ocam_Get
{
    static public function execute($url)
    {
        if (function_exists('curl_version')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            $data = curl_exec($curl);
            curl_close($curl);
            return $data;
        } else {
            return @file_get_contents($url);
        }
    }
}
