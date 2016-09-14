<div id="login_page" class="popup_form forgot_password clearfix">
	<div class="container clearfix">
		<div class="inner clearfix">
			<h2>Forgot Password</h2>
			<div class="login_form full">
            	<?php
				if (!empty($this->error)) {
					echo Html::formatErrors($this->error);
				}elseif (!empty($this->success)) {
					echo Html::formatSuccess($this->success);
				}
				?>
				<p>Please enter your new password.</p>
				<?php if ($this->keysMatch == TRUE){?>
					<form class="full" action="" method="post" id="LoginForm">
						<div class="full pw_outer  <?php if ((!empty($this->missing)) && in_array('password', $this->missing)) { echo 'error'; }?>">
							<input name="password" class="with_icon" type="password" placeholder="new password" />
						</div>
                        <span class = "help-block">Note: Password must contain at least one number, one uppercase letter and at least 8 characters.</span>
                        <br/>
                        <br/>

                        <p>Please confirm your new password.</p>
                        <div class="full pw_outer  <?php if ((!empty($this->missing)) && in_array('confirm_password', $this->missing)) { echo 'error'; }?>">
                            <input name="confirm_password" class="with_icon" type="password" placeholder="confirm password" />
                        </div>
                        <input type="submit" class="btn duckegg solid full" value="Reset Password">
					</form>
				<?php }else{ ?>
					<h1>There was a problem!</h1>
				    <p>Unfortunately some things don't match up here.</p>
				    <p>Please <?php echo Html::actionLink('reset your password again', 'forgot-password', 'login', 'HTTPS'); ?> and follow the supplied link.</p>
				    <p>If you have tried this and are still having problems, please let us know by using our <a href="/contact">contact form</a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>