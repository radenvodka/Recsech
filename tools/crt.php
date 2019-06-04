<?php
require_once("sdata-modules.php");
/**
 * @Author: Nokia 1337
 * @Date:   2019-06-01 09:58:00
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-05 02:38:46
*/
class Cert
{
	function __construct($domain)
	{
		$this->sdata  = new Sdata;
		$this->domain = $domain;
	}
	function check($filterDomain , $domainInFilter){
		echo "    >>> Find with crt ... ";
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
				if(strtolower($filterDomain) == 'y'){
					if(preg_match("/".$domainInFilter."/", $domain)){
						$domains[] = str_replace('*.', '', $domain);
					}
				}else{
					$domains[] = str_replace('*.', '', $domain);
				}
			}
		}
		echo "Done !\n";
		echo "    >>> Find with findsubdomains ... ";
		$url[] = array(
			'url' => 'https://findsubdomains.com/subdomains-of/'.$this->domain, 
		);
		$result = $this->sdata->sdata($url);unset($url);
		foreach ($result as $key => $respons) {
			preg_match_all('/<a href="javascript:void\(0\);" class="desktop-hidden">(.*?)<\/a>/m', $respons['respons'], $matches);
			foreach ($matches[1] as $key => $domain) {
				if($domain != '{{:domain}}'){
					if(strtolower($filterDomain) == 'y'){
						if(preg_match("/".$domainInFilter."/", $domain)){
							$domains[] = str_replace('*.', '', $domain);
						}
					}else{
						$domains[] = str_replace('*.', '', $domain);
					}
				}
			}
		}
		$domains[] = str_replace('*.', '', $this->domain);
		echo "Done !\n";
		return array_unique($domains);
	}
}