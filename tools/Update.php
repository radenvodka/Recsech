<?php
require_once("sdata-modules.php");
/**
 * @Author: Nokia 1337
 * @Date:   2019-06-01 09:58:00
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-05 03:13:29
*/
class Recsech
{
	function __construct()
	{
		$this->sdata  	= new Sdata;
		$this->version 	= '1.6.0';
	}
	function Required(){
		echo color("yellow", "[+] Check required : \r\n");
		echo '    +  ', function_exists('curl_version') ? color("green",'Curl Enabled') : color("red",'Curl Disabled');
		echo "\r\n";
		echo '    +  ', phpversion() > '7' ? color("green",'PHP 7+ OK') : color("red",'PHP 7+ :( ');
		echo "\r\n";
	}
	function Update(){
		$this->Required();
		$url[] 		= array('url' => 'https://api.github.com/repos/radenvodka/Recsech/releases/latest');
		$head[] 	=  array(
			'falsehead' => true,
		);
		$respons 	= $this->sdata->sdata($url,$head);unset($url);unset($head);
		foreach ($respons as $key => $value) {
			$json = json_decode($value['respons'],true);
			if( $this->version < $json['tag_name']){
				echo color("yellow", "[+] ").color("red", "You are using the old version (".$this->version.") , update it to version ".$json['tag_name']."\r\n\n");
				echo color("nevy", "    + Version Name  : ".color("green",$json['name']." [NEW]") )."\r\n";
				echo color("nevy", "    + Author        : ".color("green",$json['author'][login]) )."\r\n";
				echo color("nevy", "    + Link Download : ".color("green",$json['zipball_url']) )."\r\n";
				die();
			}
		}
	}
	function version(){
		return $this->version;
	}
}