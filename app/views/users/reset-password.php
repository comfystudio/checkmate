<div class="greyback">
    <div class ="container">
        <div class="formintro">
            <div class="single_notification">
                <?php if (!empty($this->error)) { ?>
                    <div class="alert alert-info alert-labeled formerror">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
                        <div class="alert-labeled-row">
                            <span class="alert-label alert-label-left alert-labelled-cell">
                                <i class="glyphicon glyphicon-info-sign"></i>
                            </span>
                            <h4>
                                <strong>Failure</strong>
                            </h4>
                            <p class="alert-body alert-body-right alert-labelled-cell">
                                <?php
                                foreach($this->error as $error){
                                    echo $error.'<br/>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </div>

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
                    Please enter your new password.
                </div>
            </div>
        </div>

        <?php if ($this->keysMatch == TRUE){?>
			<form class="full" action="" method="post" id="LoginForm">
	            <div class="form-wrapper">
                    <div class = "row">
                        <div class="form-group col-sm-6  right-border <?php if ((!empty($this->missing)) && in_array('password', $this->missing)) { echo 'error'; }?>">
                            <input name="password" class="with_icon form-control" type="password" placeholder="new password" />
                        </div>
                        <!-- <span class = "help-block">Note: Password must contain at least one number, one uppercase letter and at least 8 characters.</span> -->

                        <!-- <p>Please confirm your new password.</p> -->
                        <div class="form-group col-sm-6  <?php if ((!empty($this->missing)) && in_array('confirm_password', $this->missing)) { echo 'error'; }?>">
                            <input name="confirm_password" class="with_icon form-control" type="password" placeholder="confirm password" />
                        </div>
                    </div>
	            </div>

	            <div class="col-sm-12 form-spacing" style="text-align:center">
                	<button type="submit" class="formbtn btn-default center-button" value="Reset Password">Reset Password</button>
            	</div>
			</form>
		<?php }else{ ?>
			<h1>There was a problem!</h1>
		    <p>Unfortunately some things don't match up here.</p>
		    <p>Please <?php echo Html::actionLink('reset your password again', 'forgot-password', 'login', 'HTTPS'); ?> and follow the supplied link.</p>
		    <p>If you have tried this and are still having problems, please let us know by using our <a href="/contact">contact form</a>
		<?php } ?>
    </div>
</div>
