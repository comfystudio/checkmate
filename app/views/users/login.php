<div id="login_page" class="popup_form clearfix">
	<div class="container clearfix">
		<div class="inner clearfix">
			<h2>Login</h2>
			<div class="login_form full">
            	<?php
				if (!empty($this->error)) {
					echo Html::formatErrors($this->error);
				}
				if(!empty($_GET['logout'])){
					$this->success[] = 'You have successfully logged out.';
					echo Html::formatSuccess($this->success);
				}
				?>
				<form class="full" action="/login" method="post" id="LoginForm">
					<div class="full email_outer <?php if ((!empty($this->missing)) && in_array('email', $this->missing)) { echo 'error'; }?>">
						<input name="email" class="with_icon" type="text" placeholder="Email Address"/>
					</div>
					<div class="full pw_outer <?php if ((!empty($this->missing)) && in_array('password', $this->missing)) { echo 'error'; }?>">
						<input name="password" type="password" class="with_icon" placeholder="Password"/>
					</div>
					<input type="submit" class="btn duckegg solid full" value="Login">
				</form>
			</div>
			<div class="full already_have_account">
				<a href="/users/forgot-password/">Forgot password?</a>
			</div>
		</div>
	</div>
</div>