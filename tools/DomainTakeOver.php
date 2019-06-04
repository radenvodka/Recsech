<?php
require_once("sdata-modules.php");
/**
 * @Author: Nokia 1337
 * @Date:   2019-06-01 09:58:00
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-03 23:44:26
*/
class DomainTakeOver
{
	function __construct()
	{
		$this->sdata  = new Sdata;
	}
	function Fingerprint(){
		$array  = array(
			'AWS/S3' 			=> 'The specified bucket does not exist', 
			'Bitbucket' 		=> 'Repository not found', 
			'Fastly' 			=> 'Fastly error: unknown domain:', 
			'Feedpress' 		=> 'The feed has not been found.', 
			'Ghost' 			=> 'The thing you were looking for is no longer here, or never was', 
			'Github' 			=> "There isn't a Github Pages site here.", 
			'Help Juice' 		=> "We could not find what you're looking for.", 
			'Help Scout' 		=> 'No settings were found for this company:	', 
			'Heroku' 			=> 'No such app', 
			'Intercom' 			=> "Uh oh. That page doesn't exist.", 
			'JetBrains' 		=> 'is not a registered InCloud YouTrack', 
			'Kinsta' 			=> 'No Site For Domain', 
			'LaunchRock' 		=> "It looks like you may have taken a wrong turn somewhere. Don't worry", 
			'Mashery' 			=> 'Unrecognized domain', 
			'Pantheon' 			=> '404 error unknown site!	', 
			'Readme.io	' 		=> 'Project doesnt exist', 
			'Shopify' 			=> 'Sorry, this shop is currently unavailable.', 
			'Tumblr' 			=> "Whatever you were looking for doesn't currently exist at this address", 
			'Tilda' 			=> 'Please renew your subscription', 
			'UserVoice' 		=> 'This UserVoice subdomain is currently available!', 
			'Wordpress' 		=> 'Do you want to register *.wordpress.com?', 
		);
		return $array;
	}
	function Domain($Domain){
		$url[] 		= array('url' => $Domain);
		$respons 	= $this->sdata->sdata($url);unset($url);
		foreach ($respons as $key => $value) {
			foreach ($this->Fingerprint() as $name => $preg) {
				if(preg_match("/".$preg."/", $value['respons'])){
					$match[] = $name;
				}
			}
		}
		if(count($match) >= 1){
			foreach ($match as $key => $name) {
				$info[] = array(
					'domain' 	=> $Domain,
					'status' 	=> color("yellow", "Possibility of takeover (".$name.")"), 
				);
			}
		}else{
			$info[] = array(
				'domain' 	=> $Domain,
				'status' 	=> color("grey","Cannot be taken over"), 
			);
		}
		return $info;
	}
}