<?php
// vim: set et sw=4 ts=4 sts=4 fdm=marker ff=unix fenc=utf8
/**
 * Wiki Parser
 *
 * @author feelinglucky<i.feelinglucky[at]gmail.com>
 *   @link http://www.gracecode.com/
 *   @date 2008-08-20
 */

/**
 * 安全获取 GET/POST 的参数
 *
 * @param  String $request_name
 * @param  Mixed  $default_value
 * @param  String $method 'post', 'get', 'all' default is 'all'
 * @return String
 */
function getRequest($request_name, $default_value = null, $method = "all")
{
    $magic_quotes = ini_get("magic_quotes_gpc") ? true : false;
    $method = strtolower($method);

    switch (strtolower($method)) {
    default:
    case "all":
        if (isset($_POST[$request_name])) {
            return $magic_quotes ? stripslashes($_POST[$request_name]) : $_POST[$request_name];
        } else if (isset($_GET[$request_name])) {
            return $magic_quotes ? stripslashes($_GET[$request_name]) : $_GET[$request_name];
        } else {
            return $default_value;
        }
        break;

    case "get":
        if (isset($_GET[$request_name])) {
            return $magic_quotes ? stripslashes($_GET[$request_name]) : $_GET[$request_name];
        } else {
            return $default_value;
        }
        break;

    case "post":
        if (isset($_POST[$request_name])) {
            return $magic_quotes ? stripslashes($_POST[$request_name]) : $_POST[$request_name];
        } else {
            return $default_value;
        }
        break;

    default:
        return $default_value;
        break;
    }
}

header('Content-type: text/javascript');

$result = array(
    'error'    => '',
    'response' => ''
);

$wiki = getRequest('wiki', null, 'post');
if (!$wiki || !is_string($wiki)) {
    $result['error'] = 'request is empty or format error.';
    die(json_encode($result));
}

require_once 'Creole_Wiki.php';
$parser = new Creole_Wiki;
$result['response'] = $parser->transform(trim($wiki));
die(json_encode($result));
?>
