<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
    <link href="/assets/images/favicon.ico" rel="shortcut icon" />

    <!-- Css -->
    <?php renderAdminCssBundle(); ?>
     <?php renderAdminHeadJSBundle(); ?>
    <?php echo isset($this->pageCss) ? Page::getPageCss($this->pageCss) : ''; ?>

    <title><?php echo isset($this->pageTitle) ? Page::getPageTitle($this->pageTitle) : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo isset($this->pageDescription) ? Page::getPageDescription($this->pageDescription) : ''; ?>" />

</head>
<body>
	<div id="page-wrapper">
    	<div id="page-container" class="header-fixed-top sidebar-visible-lg-full">
	    	<!-- Sidebar -->
	    	<?php $this->renderPartial('shared/_sidebar', 'backoffice');?>
			<div id="main-container">
				<?php $this->renderPartial('shared/_header', 'backoffice');?>
				<?php require $pathToViewsFolder . $renderBody . '.php'; ?>
				<?php $this->renderPartial('shared/_footer', 'backoffice');?>
			</div>
    	</div>
	</div>

    <?php renderAdminJSBundle(); ?>
	<?php echo isset($this->pageJs) ? Page::getPageJs($this->pageJs) : ''; ?>
</body>
</html>