<?php
class BackofficeArea{
	public static function registerArea(){    
		$areaArray = array(
			"areaName" => "backoffice",
			"areaUrl" => "backoffice/",
			"areaController" => "adminUsers",
			"areaAction" => "index"
		);
		return $areaArray;
	}
	
}