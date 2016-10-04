<!-- <script type="text/javascript" src="https://js.stripe.com/v2/"></script> -->

<section class="dash full">
	<div class="container clearfix">
		<div id="dash_sidebar" class="col first span_3_of_12">

		</div><!-- end dash_sidebar -->

		<div id="dash_main" class="col span_9_of_12">

			<div class="main_module">

				<div class="title clearfix">
					<h1>Payment</h1>

					<div class="title_right">
						<p><img src="/assets/images/stripe-solid.png" alt="Powered By Stripe" /></p>
					</div><!-- end title_right -->
				</div><!-- end title -->

				<div id="full_form" class="clearfix">
					<form id="payment-form" method="post">

						<!-- For failed payment errors -->
						<?php
						if(!empty($this->error)){
							echo Html::formatErrors($this->error);
						}?>

						<?php if(!isset($this->user[0]['payment_type']) || $this->user[0]['payment_type'] < 1){?>
							<input type="radio" name="type" value="1" <?php if(isset($this->user[0]['payment_type']) && $this->user[0]['payment_type'] == 1){echo 'checked="checked"';}?> > Pay & Go - £20 a year for one property<br>
						<?php } ?>

						<?php if(!isset($this->user[0]['payment_type']) || $this->user[0]['payment_type'] < 2){?>
							<input type="radio" name="type" value="2" <?php if(isset($this->user[0]['payment_type']) && $this->user[0]['payment_type'] == 2){echo 'checked="checked"';}else{echo 'checked="checked"';}?>> Basic - £25 a month for 50 properties<br>
						<?php } ?>

						<?php if(!isset($this->user[0]['payment_type']) || $this->user[0]['payment_type'] < 3){?>
							<input type="radio" name="type" value="3" <?php if(isset($this->user[0]['payment_type']) && $this->user[0]['payment_type'] == 3){echo 'checked="checked"';}?> > Medium - £45 a month for 100 properties<br/>
						<?php } ?>

						<?php if(!isset($this->user[0]['payment_type']) || $this->user[0]['payment_type'] < 4){?>
							<input type="radio" name="type" value="4" <?php if(isset($this->user[0]['payment_type']) && $this->user[0]['payment_type'] == 4){echo 'checked="checked"';}?> > Heavy - £60 a month for 200 properties<br/>
						<?php } ?>					

						<?php if(!isset($this->user[0]['payment_type']) || $this->user[0]['payment_type'] < 5){?>
							<input type="radio" name="type" value="5" <?php if(isset($this->user[0]['payment_type']) && $this->user[0]['payment_type'] == 5){echo 'checked="checked"';}?> > Unlimited - £80 a month for unlimited amount of properties<br/>
						<?php } ?>
						<!-- Separate because Stripe validation appends directly to .payment-errors -->
						<div id="payment_errors" class="alert" style="display: none;">
				            <ul><li><span class="payment-errors"></span></li></ul>
				        </div>

			        	<input type = "hidden" data-stripe="email" name = "stripeEmail" value = "<?php echo $this->user[0]['email']?>">

						<div class="form_item">
							<label for="cardholder"><span class="required">*</span> Cardholder's Name:</label>
							<input type="text" data-stripe="name" id="cardholder" placeholder="Cardholder's Name" value=""  />
						</div><!-- end form_item -->

						<div class="form_item">
							<label for="card_number"><span class="required">*</span> Card Number:</label>
							<input type="text" size="20" data-stripe="number" id="card_number" placeholder="Card Number" value=""  />
						</div><!-- end form_item -->

						<div class="form_item">
							<label for="expiry"><span class="required">*</span> Expiry:</label>
							<input size="2" data-stripe="exp-month" id="expiry_month" class="month" placeholder="MM" value=""  />
							<input size="4" data-stripe="exp-year" id="expiry_year" class="year" placeholder="YYYY" value=""  />
						</div><!-- end form_item -->

						<div class="form_item">
							<label for="cvc"><span class="required">*</span> CVC:</label>
							<div id="cvc_tooltip">?</div>
							<input type="text" size="4" data-stripe="cvc" id="cvc" placeholder="CVC" value=""  />
						</div><!-- end form_item -->

						<div class="card_icons">
							<ul>
								<li class="first"><img src="/assets/images/mastercard.png" alt="Mastercard" /></li>
								<li><img src="/assets/images/visa.png" alt="Visa" /></li>
								<li><img src="/assets/images/amex.png" alt="American Express" /></li>
							</ul>
						</div>

						<div class="proceed_btn">
							<button type="submit" class="btn duckegg solid loader">Pay Now</button>
						</div>

					</form>
				</div><!-- end property_information_form -->

			</div><!-- end main_module -->

	</div><!-- end container -->
</section>
