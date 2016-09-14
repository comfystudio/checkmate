<?php
/** Page */
class Page {
	/**
	 * Function: getPageTitle
	 * $pageTitle - An array of the Page Title
	 */
	public static function getPageTitle($pageTitle){
		$theTitle = '';

		// Loop through the array
    	foreach(array_filter($pageTitle) as $value){
  			$theTitle .= $value . ' - ';
		}
		
		// Add the Site Name 
    	$theTitle .= SITE_NAME;
		
		// Return the Page Title
		return $theTitle;
	}	
	
	/**
	 * Function: getPageDescription
	 * $pageTitle - An array of the Page Description
	 */
	public static function getPageDescription($pageDescription){
		$theDescription = $pageDescription;
		
		// Return the Page Description
		return $theDescription;
	}	
	
	/**
	 * Function: getPageCss
	 * $pageCss - An array of the Page Css
	 */
	public static function getPageCss($pageCss){
		$theCss = '';
		
		// Loop through the array
    	foreach($pageCss as $value){
			$theCss .= '<link rel="stylesheet" type="text/css" href="/';
			$theCss .= !empty($value->theArea) ? 'app/areas/' . $value->theArea . '/' : '';
			$theCss .= 'assets/css/' . $value->theFile . '.css"';
			$theCss .= ' media="';
			$theCss .= !empty($value->theMedia) ? $value->theMedia  : '';
			$theCss .= '">';
		}
		
		// Return the Page Css
		return $theCss;
	}	

	/**
	 * Function: getPageJs
	 * $pageJs - An array of the Page Js
	 */
	public static function getPageJs($pageJs){
		$theJs = '';
			
		// Loop through the array
    	foreach($pageJs as $value){
			$theJs .= '<script src="/';
			$theJs .= !empty($value->theArea) ? 'app/areas/' . $value->theArea . '/' : '';
			$theJs .= 'assets/js/' . $value->theFile . '.js"></script>';
		}
		
		// Return the Page Js
		return $theJs;
	}	
}
?>