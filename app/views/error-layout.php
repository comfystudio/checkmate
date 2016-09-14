<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0">    
    <link href="/assets/images/favicon.ico" rel="shortcut icon" />   
    <title><?php echo isset($this->pageTitle) ? Page::getPageTitle($this->pageTitle) : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo isset($this->pageDescription) ? Page::getPageDescription($this->pageDescription) : ''; ?>" />
    <meta name="author" content="Output Digital">

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Css -->
    <?php 
	    renderDefaultCssBundle();
	?>
    <?php echo isset($this->pageCss) ? Page::getPageCss($this->pageCss) : ''; ?>
</head>
<body class="cbp-spmenu-push">
	<?php $this->renderPartial('shared/_header');?>
        <div id = "container">
            <?php require $pathToViewsFolder . $renderBody . '.php'; ?>
        </div>
    <?php $this->renderPartial('shared/_footer');?>
    <?php 
	    renderDefaultJSBundle();
	?>
	<?php echo isset($this->pageJs) ? Page::getPageJs($this->pageJs) : ''; ?>
</body>
</html>