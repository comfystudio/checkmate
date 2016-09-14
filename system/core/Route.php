<?php
/** Route */
class Route{
	/**
	 * FUNCTION: addRoute
	 * @param string $RouteName          The name of the route
	 * @param string $RouteUrl           The url of the route
	 * @param string $RouteArea		 	 The required area
	 * @param string $RouteController    The required controller
	 * @param string $RouteAction        The required action
	 */
	public static function addRoute($RouteName, $RouteUrl, $RouteArea, $RouteController, $RouteAction){
		$routeName = isset($RouteName) ? $RouteName : '';
		$routeUrl = isset($RouteUrl) ? trim($RouteUrl, '/') : '';
		$routeController = isset($RouteController) ? $RouteController : '';
		$routeAction = isset($RouteAction) ? $RouteAction : '';
		$routeArea = isset($RouteArea) ? $RouteArea : '';
		
		$routeArray = (object) array(
            "routeUrl" => $routeUrl,
			"routeArea" => $routeArea,
			"routeController" => $routeController,
			"routeAction" => $routeAction
		);
		
		return $routeArray;
	}
}
?>