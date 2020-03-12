<?php
/**
 * @Author: Nokia 1337
 * @Date:   2019-06-01 09:58:00
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2020-03-12 19:17:36
*/
class SSLcheck
{
	
	function __construct($Domain)
	{
		$orignal_parse 	= parse_url($Domain, PHP_URL_HOST);
		$get 			= stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
		$read 			= stream_socket_client("ssl://".$orignal_parse.":443", $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);
		$cert 			= stream_context_get_params($read);
		$certinfo 		= openssl_x509_parse($cert['options']['ssl']['peer_certificate']);
		unset($certinfo['purposes']);
		return $certinfo;
	}
}