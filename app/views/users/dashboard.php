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
				<p>Membership Type: <?php echo $this->paymentTypes[$this->user[0]['payment_type']]?>
					<?php if($this->user[0]['payment_type'] < 5){?>
						- <a href = "/payments/upgrade/">Upgrade Membership</a>
					<?php } ?>
				</p>
				<p>Remaining Property Credits: <?php echo $this->user[0]['remaining_credits'] - $this->propertyCount?></p>
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
				<?php $sevenDay = 60*60*24*7;?>
				<?php foreach($this->properties as $property){?>
					<li>
						<img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $property['image'];?>" alt="<?php echo $property['image'];?>">
						<?php echo $property['title']?>
						<?php if(isset($property['check_out']) && !empty($property['check_out'])){?>
							<?php $checkOutTime = strtotime($property['check_out'])?>
							<?php $checkInTime = strtotime($property['check_in'])?>
							<?php $difference = abs($checkOutTime - $timeInSeconds)?>
							<?php $differenceCheckIn = abs($checkInTime - $timeInSeconds)?>
							<?php if($difference <= $fourDay){?>
								<a href = "/reports/checkout/<?php echo $property['id']?>">Begin Check Out</a>
							<?php }elseif($differenceCheckIn <= $sevenDay){ ?>
								<a href = "/reports/checkin/<?php echo $property['id']?>">Amend Check in</a>
							<?php } ?>
						<?php } else {?>
							<a href = "/reports/checkin/<?php echo $property['id']?>">Begin Check in</a>
						<?php } ?>
						<br/>
						<a href = "/properties/edit/<?php echo $property['id']?>">Edit Property</a>
						<br/>
						<a href = "/properties/delete/<?php echo $property['id']?>">Delete Property</a>

					</li>
				<?php } ?>
			</ul>

			<h1>Ongoing Check In / Check Out</h1>
			<ul>
				<?php foreach($this->reports as $report){?>
					<li>
						<img src="/image.php?width=120&height=120&image=/assets/uploads/<?php echo $report['image'];?>" alt="<?php echo $report['image'];?>">
						<?php echo $report['title']?>

						<?php $checkOutTime = strtotime($report['check_out'])?>
						<?php $checkInTime = strtotime($report['check_in'])?>
						<?php $difference = abs($checkOutTime - $timeInSeconds)?>
						<?php $differenceCheckIn = abs($checkInTime - $timeInSeconds)?>
						<?php if($difference <= $fourDay){?>
							<a href = "/reports/checkout/<?php echo $property['id']?>">Begin Check Out</a>
						<?php }elseif($differenceCheckIn <= $sevenDay){ ?>
							<a href = "/reports/checkin/<?php echo $property['id']?>">Begin Check in</a>
						<?php } ?>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>