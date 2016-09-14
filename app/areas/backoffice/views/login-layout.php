<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
    <link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon" />

    <!-- Css -->
    <?php renderAdminCssBundle(); ?>
    <?php renderAdminHeadJSBundle(); ?>
    <?php echo isset($this->pageCss) ? Page::getPageCss($this->pageCss) : ''; ?>

    <title><?php echo isset($this->pageTitle) ? Page::getPageTitle($this->pageTitle) : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo isset($this->pageDescription) ? Page::getPageDescription($this->pageDescription) : ''; ?>" />

</head>
<body>

    <?php require $pathToViewsFolder . $renderBody . '.php'; ?>
    <?php renderAdminJSBundle(); ?>
	<?php echo isset($this->pageJs) ? Page::getPageJs($this->pageJs) : ''; ?>

</body>
</html>