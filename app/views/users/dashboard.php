<div class = "container">
	<div class = "row" >
		<div class = "col-md-6" style = "border:1px solid black">
			<h1>User Details</h1>
			<?php if(isset($this->user[0]['logo_image']) && !empty($this->user[0]['logo_image'])){?>
				<img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $this->user[0]['logo_image'];?>" alt="<?php echo $this->user[0]['logo_image'];?>">
			<?php } ?>
			<p><?php echo $this->user[0]['firstname'].' '.$this->user[0]['surname']?> - <?php echo $this->userTypes[$this->user[0]['type']]?></p>
			<p><a href = "/users/edit/<?php echo $this->user[0]['id']?>">Edit Profile</a></p>
			<br/>
			<?php if(isset($this->user[0]['payment_type']) && !empty($this->user[0]['payment_type'])){?>
				<p>Membership Type: <?php echo $this->paymentTypes[$this->user[0]['payment_type']]?> - <a href = "/payments/upgrade/<??>">Upgrade Membership</a></p>
				<p>Remaining Property Credits: <?php echo $this->user[0]['remaining_credits']?></p>
			<?php }?>
		</div>

		<div class = "col-md-6" style = "border:1px solid black">
			<h1>Notifications</h1>
			<ul>
				<?php foreach($this->notifications as $notification){?>
					<li>
						<?php echo substr($notification['text'],0,50).'...';?>
						<a href = "/notifications/view/<?php echo $notification['id']?>">Read</a>
					</li>
				<?php } ?>
			<ul>
		</div>
	</div>

	<div class = "row">
		<div class = "col-md-6" style = "border:1px solid black">
			<h1>Main Functions</h1>
			<a href = "/properties/add/">Create New Property</a>
			<br/>
			<a href = "/templates/add/">Create New Property Layout</a>
			<br/>
			<a href = "/reports/completed">View Completed Reports</a>
		</div>

		<div class = "col-md-6" style = "border:1px solid black">
			<h1>Current Properties</h1>
			<ul>
				<?php $date = date('Y-m-d'); ?>
				<?php $timeInSeconds  = strtotime($date);?>
				<?php $fourDay = 60*60*24*4;?>
				<?php Debug::printr($date);?>
				<?php Debug::printr($timeInSeconds);?>
				<?php Debug::printr($fourDay);?>
				<?php foreach($this->properties as $property){?>
					<li>
						<img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $property['image'];?>" alt="<?php echo $property['image'];?>">
						<?php echo $property['title']?>
						<?php if(isset($property['check_out']) && !empty($property['check_out'])){?>
							<?php $checkOutTime = strtotime($property['check_out'])?>
							<?php Debug::printr($checkOutTime);?>
							<?php $difference = abs($checkOutTime - $timeInSeconds)?>
							<?php Debug::printr($difference);?>
							<?php if($difference <= $fourDay){?>
								<a href = "/reports/checkout/<?php echo $property['id']?>">Begin Check out</a>
							<?php } ?>
						<?php } ?>
						<a href = "/reports/checkin/<?php echo $property['id']?>">Begin Check in</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>