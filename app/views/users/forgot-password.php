<div class="greyback">
    <div class ="container">
        <div class="formintro">
            <?php if (!empty($this->error)) { ?>
                <div class="alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><strong>Error</strong></h4>
                    <?php
                        echo Html::formatErrors($this->error);
                    ?>
                </div>
            <?php } ?>

            <div class = "row front-content">
                <div class = "col-md-offset-4 col-md-4 ">
                    <img src="/assets/images/logo-small.png" alt ="Check mate small logo" class = "logo-small">
                </div>
                <div class = "col-md-offset-4">
                </div>
            </div>

            <div class = "row">
                <div class = "col-md-offset-4 col-md-4 strapline-header">
                    Forgot Password
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    Please enter your email address to receive reset email.
                </div>
            </div>
        </div>

        <form class="full" action="" method="post" id="LoginForm">
            <div class="col-sm-offset-4">
                <div class="form-group col-sm-5 <?php if ((!empty($this->missing)) && in_array('email', $this->missing)) { echo 'error'; }?>">
                    <input type="email" class="form-control" id="email" placeholder="Email Address" name="email">
                </div>
            </div>
             <div class="col-sm-12 form-spacing" style="text-align:center">
                <button type="submit" class="formbtn btn-default">Reset Password</button>  
            </div>
        </form>
    </div>
</div>
