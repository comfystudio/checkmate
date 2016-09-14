<!-- Page content -->
<div id="page-content">
	<!-- Table Styles Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Properties</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li><a href="/backoffice/properties/">Properties</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Table Styles Header -->
    <!-- Table Styles Block -->
    <div class="block full">
    	<div class="block-title">
            <!-- <h2>View Details for Booking Ref: <?php //echo $this->getBookingDetails['code']; ?></h2> -->
    	</div>
        <div id="order_intro" class="col-sm-12 col-xs-12">

	        <div class="order_row">
                <p class="lead"><strong>Property Details</strong></p>
            </div><!-- end order_row -->

            <table class="table table-bordered table-striped table-vcenter table-hover">
                <tr>
	                <th>Property Image</th>
	                <td><?php if(isset($this->getAllData[0]['image']) && !empty($this->getAllData[0]['image'])){ ?>
                            <img src="/image.php?width=240&height=240&image=/assets/uploads/<?php echo $this->getAllData[0]['image']?>" alt="<?php echo $this->getAllData[0]['image']?>">                           
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>Property Created By</th>
                    <td><?php echo $this->getAllData[0]['firstname'].' '.$this->getAllData[0]['surname']; ?></td>
                </tr>
                <tr>
	                <th>Property Title</th>
	                <td><?php echo $this->getAllData[0]['title']; ?></td>
                </tr>
                <tr>
	                <th>Property Address</th>
	                <td>
		            <?php
                        if(!empty($this->getAllData[0]['house_number'])){echo $this->getAllData[0]['house_number'].', <br/>';}
				        if(!empty($this->getAllData[0]['address_1'])){echo $this->getAllData[0]['address_1'].', <br/>';}
				        if(!empty($this->getAllData[0]['address_2'])){echo $this->getAllData[0]['address_2'].', <br/>';}
				        if(!empty($this->getAllData[0]['address_3'])){echo $this->getAllData[0]['address_3'].', <br/>';}
                        if(!empty($this->getAllData[0]['address_4'])){echo $this->getAllData[0]['address_4'].', <br/>';}
                        if(!empty($this->getAllData[0]['postcode'])){echo $this->getAllData[0]['postcode'];}
				    ?>
			    	</td>
                </tr>
            </table>

            <?php if(isset($this->getAllData[0]['rooms']) && !empty($this->getAllData[0]['rooms'])){?>
                <div class="order_row">
                    <p class="lead"><strong>Property Layout</strong></p>           
                </div><!-- end order_row -->

                <table class="table table-bordered table-striped table-vcenter table-hover">
                    <tr>
    	                <th>Template Name</th>
    	                <td><?php echo $this->getAllData[0]['template_title']; ?></td>
                    </tr>

                    <tr>
                        <th>Template Description</th>
                        <td><?php echo $this->getAllData[0]['description']; ?></td>
                    </tr>

                    <!-- ROOMS -->
                    <?php foreach($this->getAllData[0]['rooms'] as $key => $rooms){?>
                        <tr>
                            <th><?php echo $rooms['name'];?></th>
                            <td>
                                <?php 
                                    if(isset($rooms['items_array']) && !empty($rooms['items_array'])){
                                        foreach($rooms['items_array'] as $key2 => $items){
                                            echo $items['name'].'<br/>';
                                        }
                                    } 
                                ?>
                            </td>
                        </tr>
                    <?php } ?>

                
                </table>

            <?php }else{?>
                <div class="order_row">
                    <p class="lead"><strong>No Layout added yet</strong></p>           
                </div><!-- end order_row -->
            <?php }?>
        </div>

            <div id="back-btn" class="block-section">
                <a href="/backoffice/properties/" class="btn btn-effect-ripple btn-primary" style="overflow: hidden; position: relative;"><i class="fa fa-chevron-circle-left"></i> Back</a>
            </div>
        <!-- </div> -->
    </div>
    <!-- END Table Styles Block -->
</div>
<!-- END Page Content -->