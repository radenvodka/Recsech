<?php
require_once("sdata-modules.php");
/**
 * @Author: Nokia 1337
 * @Date:   2019-06-01 09:58:00
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-01 10:29:22
*/
class Cert
{
	function __construct($domain)
	{
		$this->sdata  = new Sdata;
		$this->domain = $domain;
	}
	function check(){
		
		echo color("yellow","[+] Search for all (sub) domains : ");

		$url[] 		= array('url' => 'https://crt.sh/?q='.$this->domain);
		$respons 	= $this->sdata->sdata($url);unset($url);
		foreach ($respons as $key => $value) {
			preg_match_all('/<TD style="text-align:center"><A href="(.*?)">(.*?)<\/A><\/TD>/m', $value['respons'], $matches);
			foreach ($matches[1] as $key => $ids) {
				$url[] = array(
					'url' => 'https://crt.sh/'.$ids.'&opt=cablint', 
				);
			}
		}
		$respons 	= $this->sdata->sdata($url);unset($url);
		foreach ($respons as $key => $value) {
			preg_match_all('/DNS:(.*?)<BR>/m', $value['respons'], $matches);
			foreach ($matches[1] as $key => $domain) {
				$domains[] = $domain;
			}
		}
		
		echo color("green","Done!")."\r\n";

		return array_unique($domains);
	}
}