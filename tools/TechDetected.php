<?php
require_once("sdata-modules.php");
/**
 * @Author: Nokia 1337
 * @Date:   2019-06-01 09:58:00
 * @Last Modified by:   Nokia 1337
 * @Last Modified time: 2019-06-01 21:16:08
*/
class TechDetected
{
	function __construct()
	{
		$this->sdata  	= new Sdata;
		$this->proxy 	= null;
		$this->count 	= 0;
	}
	function Domain($Domain){
		while (TRUE) {
			if($this->proxy){
				$url[] 		= array('url' => 'https://api.wappalyzer.com/lookup-basic/beta/?url=https://'.$Domain,'note' => $Domain);
				$head[]     = array('proxy' => $this->proxy , 'falsehead' => true,);
				$respons 	= $this->sdata->sdata($url,$head);unset($url);unset($head);
			}else{
				$url[] 		= array('url' => 'https://api.wappalyzer.com/lookup-basic/beta/?url=https://'.$Domain,'note' => $Domain);
				$head[]     = array('falsehead' => true);
				$respons 	= $this->sdata->sdata($url , $head);unset($url);unset($head);
			}
			foreach ($respons as $key => $value) {
				$json = json_decode($value[respons],true);
				if(empty($json[message])  ){
					foreach ($json as $key => $value) {
						$tech[] = $value;
					}
					break;
				}else{
					if($this->count == 2){
						$tech[] = array('name' => color("red",'Request Time Out') );
						$ok = true;
						break;
					}
					if($value[info][http_code] == 429 || $value[info][http_code] == 0){
						$this->proxy = $this->sdata->useProxy();
						$this->count++;
					}else{
						$ok = true;
						break;
					}
				}
			}
			if($tech || $ok == true){
				$this->count = 0;
				break;
			}
		}
		return $tech;

	}
}