<?php
function renderDefaultCssBundle(){
	echo '<link rel="stylesheet" href="/assets/css/reset.css?v=1.0">';
	echo '<link rel="stylesheet" href="/assets/css/main.css?v=1.0">';
    echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">';
	echo '<link rel="stylesheet" href="/assets/css/bootstrap.min.css?v=1.0">';

}

function renderDefaultJSBundle(){
	echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>';
    echo '<script src="/assets/js/general.js"></script>';
    echo '<script src="/assets/js/bootstrap.min.js"></script>';
}

function renderAdminCssBundle(){
	echo '<link rel="stylesheet" href="/app/areas/backoffice/assets/css/bootstrap.min.css?v=1.0">';
	echo '<link rel="stylesheet" href="/app/areas/backoffice/assets/css/plugins.css?v=1.0">';
	echo '<link rel="stylesheet" href="/app/areas/backoffice/assets/css/main.css?v=1.0">';
	echo '<link rel="stylesheet" href="/app/areas/backoffice/assets/css/themes.css?v=1.0">';
	echo '<link rel="stylesheet" href="/app/areas/backoffice/assets/css/custom.css?v=1.0">';
}
function renderAdminHeadJSBundle(){
    echo '<script src="/app/areas/backoffice/assets/js/modernizr-2.8.3.min.js"></script>';
}
function renderAdminJSBundle(){
    echo '<script src="/app/areas/backoffice/assets/js/jquery-2.1.4.min.js"></script>';
    echo '<script src="/app/areas/backoffice/assets/js/bootstrap.min.js"></script>';
    echo '<script src="/app/areas/backoffice/assets/js/plugins.js"></script>';
    echo '<script src="/app/areas/backoffice/assets/js/app.js"></script>';
    echo '<script src="/app/areas/backoffice/assets/js/general.js"></script>';
    echo '<script src="/app/areas/backoffice/assets/js/ckeditor/ckeditor.js"></script>';

}
?>