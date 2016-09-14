<?php
/** View */
class View{
	/** __construct */
	function __construct(){
	}

    /**
     * FUNCTION: render
	 * @param string $renderBody
	 * @param string $layout
	 * @param string $area
     */
	public function render($renderBody, $layout = false, $area = false, $data = false, $require = false){
		// Build the Path to the Views Folder
		$pathToViewsFolder = 'app/';
		if($area){
			$pathToViewsFolder .= 'areas/' . $area . '/';
		}
		$pathToViewsFolder .= 'views/';
		
		
		// Check if the View Exists
		if (!file_exists($pathToViewsFolder . $renderBody . '.php')) {
			Url::redirect('error');
		}
		
		// Check if alternative Layout requested
	    if($layout){
            if($require == 'require') {
                require $pathToViewsFolder . $layout . '.php';
            }else{
                require_once $pathToViewsFolder . $layout . '.php';
            }
		}
		else{
             if($require == 'require') {
                 require $pathToViewsFolder . 'layout.php';
             }else{
                 require_once $pathToViewsFolder . 'layout.php';
             }
		}
	}
	
	
    /**
     * FUNCTION: renderPartial
	 * @param string $renderPartial
	 * @param string $area
     */
	public function renderPartial($renderPartial, $area = false, $data = false){
		// Build the Path to the Views Folder
		$pathToViewsFolder = 'app/';
		if($area){
			$pathToViewsFolder .= 'areas/' . $area . '/';
		}
		$pathToViewsFolder .= 'views/';
		require $pathToViewsFolder . $renderPartial . '.php';
	}

    /**
     * FUNCTION: renderToString
     * This function will return a rendered view as a string...mainly intended for emails.
	 * @param string $renderPartial
     * @param string $layout
	 * @param string $area
     */
    public function renderToString($renderBody, $layout = false, $area = false, $data = false) {
        ob_start(function(){});
        $this->render($renderBody,$layout,$area,$data, 'require');
        $message = ob_get_contents();
        ob_end_flush();
        return $message;
    }
}
?>