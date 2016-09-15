<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0">  
    <!-- <link href="/assets/images/favicon.ico" rel="shortcut icon" />  -->   
    <title><?php echo isset($this->pageTitle) ? Page::getPageTitle($this->pageTitle) : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo isset($this->pageDescription) ? Page::getPageDescription($this->pageDescription) : ''; ?>" />
    <meta name="author" content="WebsiteNI">
    
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <!-- Css -->
    <?php 
	    renderDefaultCssBundle();
        renderDefaultHeadJSBundle();
	?>
    <?php echo isset($this->pageCss) ? Page::getPageCss($this->pageCss) : ''; ?>
</head>
<body class="cbp-spmenu-push">
    <?php $this->renderPartial('shared/_header');?>
        <div id="container">

            <!--IF Flash Message then display it-->
            <?php if (!empty($this->flash)) { ?>
                <div class="alert-<?php echo $this->flash[1];?> alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><strong><?php echo ucfirst($this->flash[1]);?></strong></h4>
                    <?php echo Html::formatSuccess($this->flash[0]); ?>
                </div>
            <?php } ?>

            <!--IF Error Message then display it-->
<!--             <?php if (!empty($this->error)) { ?>
                <div class="alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><strong>Error</strong></h4>
                    <?php
                        echo Html::formatErrors($this->error);
                    ?>
                </div>
            <?php } ?> -->

            <?php require $pathToViewsFolder . $renderBody . '.php'; ?>
        </div>
    <?php if (isset($this->pageSection) && $this->pageSection != 'Holding'){?>
        <?php $this->renderPartial('shared/_why-choose');?>
    <?php } ?>
    <?php $this->renderPartial('shared/_footer');?>
    <?php 
	    renderDefaultJSBundle();
	?>
	<?php echo isset($this->pageJs) ? Page::getPageJs($this->pageJs) : ''; ?>
</body>
</html>