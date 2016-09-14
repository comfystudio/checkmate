<div id="login-container">
    <!-- Login Header -->
    <h1 class="h2 text-light text-center push-top-bottom animation-slideDown">
        <strong>Welcome to <?php echo SITE_NAME; ?></strong>
    </h1>
    <!-- END Login Header -->

    <!-- Login Block -->
    <div class="block animation-fadeInQuickInv">
        <!-- Login Title -->
        <div class="block-title">
            <div class="block-options pull-right">
<!--                <a href="page_ready_reminder.php" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="Forgot your password?"><i class="fa fa-exclamation-circle"></i></a>-->
            </div>
            <h2>Please Login</h2>
        </div>
        <!-- END Login Title -->

        <?php
            if (!empty($this->error)) {
                echo Html::formatErrors($this->error);
            }
            if(!empty($_GET['logout'])){
                $this->success[] = 'You have successfully logged out.';
                echo Html::formatSuccess($this->success);
            }
        ?>

        <!-- Login Form -->
        <form id="form-login" action="/backoffice/login/" method="post" class="form-horizontal">
            <div class="form-group <?php if ((!empty($this->missing)) && in_array('username', $this->missing)) { echo 'error'; }?>">
                <div class="col-xs-12">
                    <input type="text" id="login-email" name="username" class="form-control" placeholder="Username">
                </div>
            </div>
            <div class="form-group <?php if ((!empty($this->missing)) && in_array('password', $this->missing)) { echo 'error'; }?>">
                <div class="col-xs-12">
                    <input type="password" id="login-password" name="password" class="form-control" placeholder="Password">
                </div>
            </div>
            <div class="form-group form-actions">
                <div class="col-xs-4 text-left">
                    <button type="submit" class="btn btn-effect-ripple btn-sm btn-primary"><i class="fa fa-check"></i> Login</button>
                </div>
            </div>
        </form>
        <!-- END Login Form -->
    </div>
    <!-- END Login Block -->
</div>