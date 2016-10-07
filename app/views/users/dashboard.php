<div class = "container dashboard">
    <div class = "row">
        <div class = "col-md-5">
            <h1 class = "dashboard-name"><?php echo $this->user[0]['firstname'].' '.$this->user[0]['surname']?></h1><br/>
            <h3 class = "dashboard-type"><?php echo $this->userTypes[$this->user[0]['type']]?></h3>
        </div>

        <div class = "col-md-7">
            <h1 class = "notification-h1">Notifications</h1>
        </div>
    </div>

    <div class = "row dashboard-user-info">
        <div class = "col-md-5">
            <?php if(isset($this->user[0]['logo_image']) && !empty($this->user[0]['logo_image'])){?>
                <img src="/image.php?width=250&image=/assets/uploads/<?php echo $this->user[0]['logo_image'];?>" alt="<?php echo $this->user[0]['logo_image'];?>">
            <?php }else{?>
                <img src="/image.php?width=250&image=/assets/images/logo-small.png">
            <?php } ?>

            <p><a href = "/users/edit/<?php echo $this->user[0]['id']?>" class = "formbtn btn-default">Edit Profile</a></p>

            <div class = "dashboard-membership">
                <?php if(isset($this->user[0]['payment_type']) && !empty($this->user[0]['payment_type'])){?>
                    <p>Membership Type: <?php echo $this->paymentTypes[$this->user[0]['payment_type']]?>
                        <?php if($this->user[0]['payment_type'] < 5){?>
                            - <a href = "/payments/upgrade/" class = "red">Upgrade Membership</a>
                        <?php } ?>
                    </p>
                    <?php if($this->user[0]['payment_type'] == 5){?>
                        <p>Remaining Property Credits: Unlimited - <a href = "/payments/cancel" class = "red">Cancel Subscription</a></p>
                    <?php }else{ ?>
                        <p>Property Credits: <?php echo $this->user[0]['remaining_credits'] - $this->propertyCount?> - <a href = "/payments/cancel" class = "red">Cancel Membership</a></p>
                    <?php } ?>
                <?php }else{?>
                    <p><a href = "/payments/create/" class = 'red'>Become a Member</a></p>
                <?php } ?>
            </div>


        </div>

        <div class = "col-md-7">
            <ul class = "dashboard-notifications">
                <?php foreach($this->notifications as $notification){?>
                    <li>
                        <?php echo substr($notification['text'],0,50).'...';?>
                        <a href = "/notifications/delete/<?php echo $notification['id']?>" class = "delete">
                            <img src="/assets/images/delete.png" alt="delete notification">Delete
                        </a>
                        <a href = "/notifications/view/<?php echo $notification['id']?>"  class = "view">
                            <img src="/assets/images/eye.png" alt="View notification">View
                        </a>
                    </li>
                <?php } ?>
            <ul>
        </div>
    </div>

    <div class = "row dashboard-options">
        <div class = "col-md-2 option">
            <a href = "/properties/add/">
                <img src="/assets/images/home.png" alt="Create New Property">
                <p>Create a New Property</p>
            </a>
        </div>

        <div class = "col-md-2 option">
            <a href = "/templates/add/">
                <img src="/assets/images/layout.png" alt="Create Property Layout">
                <p>Create a New Property Layout</p>
            </a>
        </div>

        <div class = "col-md-2 option">
            <a href = "/reports/completed">
                <img src="/assets/images/completed.png" alt="View Completed Reports">
                <p>View Completed Reports</p>
            </a>
        </div>
    </div>

    <div class = "row current-properties">
        <div class="col-md-12">
            <h1>Current Properties</h1>
            <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi quis magna ut justo mollis consequat eu non massa.
                Morbi malesuada mollis ligula, in consectetur dui porta a.</h3>
        </div>
    </div>

    <div class = "row dashboard-properties">
        <?php $date = date('Y-m-d'); ?>
        <?php $timeInSeconds  = strtotime($date);?>
        <?php $fourDay = 60*60*24*4;?>
        <?php $sevenDay = 60*60*24*7;?>
        <?php foreach($this->properties as $property){?>
            <div class = "col-md-12 property-row">
                <img src="/image.php?width=160&image=/assets/uploads/<?php echo $property['image'];?>" alt="<?php echo $property['image'];?>" class = "property-image">
                <p><?php echo $property['title'].', '.$property['address_1'].', '.$property['address_2']?></p>
                <p>
                    <?php if(isset($property['check_out']) && !empty($property['check_out'])){?>
                        <?php $checkOutTime = strtotime($property['check_out'])?>
                        <?php $checkInTime = strtotime($property['check_in'])?>
                        <?php $difference = abs($checkOutTime - $timeInSeconds)?>
                        <?php $differenceCheckIn = abs($checkInTime - $timeInSeconds)?>
                        <?php if($difference <= $fourDay){?>
                            <img src="/assets/images/check-out.png">
                            <a href = "/reports/checkout/<?php echo $property['id']?>">Check Out</a>
                        <?php }elseif($differenceCheckIn <= $sevenDay){ ?>
                            <img src="/assets/images/blue-map.png">
                            <a href = "/reports/checkin/<?php echo $property['id']?>">Check in</a>
                        <?php } ?>
                    <?php } else {?>
                        <img src="/assets/images/blue-map.png">
                        <a href = "/reports/start/<?php echo $property['id']?>">Start Check in</a>
                    <?php } ?>
                    <img src="/assets/images/edit.png">
                    <a href = "/properties/edit/<?php echo $property['id']?>">Edit Property</a>
                    <img src="/assets/images/blue-delete.png">
                    <a href = "/properties/delete/<?php echo $property['id']?>">Delete Property</a>
                </p>
            </div>
        <?php } ?>
    </div>


    <div class = "row current-properties">
        <div class="col-md-12">
            <h1>Ongoing Check In / Check Out</h1>
            <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi quis magna ut justo mollis consequat eu non massa.
                Morbi malesuada mollis ligula, in consectetur dui porta a.</h3>
        </div>
    </div>

    <div class = "row dashboard-properties">
        <?php foreach($this->reports as $report){?>
            <div class = "col-md-12 property-row">
                <img src="/image.php?width=160&image=/assets/uploads/<?php echo $report['image'];?>" alt="<?php echo $report['image'];?>" class = "property-image">
                <p><?php echo $report['title'].', '.$report['address_1'].', '.$report['address_2']?></p>
                <p>
                    <?php $checkOutTime = strtotime($report['check_out'])?>
                    <?php $checkInTime = strtotime($report['check_in'])?>
                    <?php $difference = abs($checkOutTime - $timeInSeconds)?>
                    <?php $differenceCheckIn = abs($checkInTime - $timeInSeconds)?>
                    <?php if($difference <= $fourDay){?>
                        <img src="/assets/images/check-out.png">
                        <a href = "/reports/checkout/<?php echo $report['property_id']?>">Check Out</a>
                    <?php }elseif($differenceCheckIn <= $sevenDay){ ?>
                        <img src="/assets/images/blue-map.png">
                        <a href = "/reports/checkin/<?php echo $report['property_id']?>">Check in</a>
                    <?php } ?>
                    <img src="/assets/images/download.png">
                    <a href = "/reports/report-download/<?php echo $report['id']?>">Download PDF</a>
                </p>
            </div>
        <?php } ?>
    </div>
</div>