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
				<form class="full" action="" method="post" id="LoginForm">
					<div class="full email_outer <?php if ((!empty($this->missing)) && in_array('email', $this->missing)) { echo 'error'; }?>">
						<input name="email" class="with_icon" type="text" placeholder="Email Address"/>
					</div>
					<input type="submit" class="btn duckegg solid full" value="Reset Password">
				</form>
			</div>
		</div>
	</div>
</div>