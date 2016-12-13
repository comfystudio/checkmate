<!-- Page content -->
<div id="page-content">
	<!-- Table Styles Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Payments</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li>Payments</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Table Styles Header -->
    <!-- Table Styles Block -->
    <div class="block full">
    	<div class="block-title">
            <h2>Manage Payments</h2>
            <div class="block-options pull-right">
	    		 <div id="esearch" class="dataTables_filter">
		    		<form class="form-wrap" method='get' action='/backoffice/payments/index'>
                        <div class="input-group pull-right">
					        <input type="text" class="form-control" placeholder="Search" name="keywords" id="search_term" <?php if (isset($_GET["keywords"])) {echo 'value="'.htmlentities($_GET["keywords"]).'"';}?>><span class="search-btn"><button type="submit" class="btn btn-effect-ripple btn-sm"><i class="fa fa-search"></i></button></span>
					    </div>
					</form>
				</div>
    		</div>
    	</div>
		<?php if (!empty($this->flash)) { ?>
			<div class="alert alert-<?php echo $this->flash[1];?> alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><strong><?php echo ucfirst($this->flash[1]);?></strong></h4>
                <?php echo Html::formatBackofficeSuccess($this->flash[0]); ?>
            </div>
        <?php } ?>
        <!-- Table Styles Content -->
	        <div class="dataTables_wrapper form-inline no-footer">
		        <div class="row">
			       <div class="col-xs-12">
				        <div class="pull-right">
                        </div>
					</div>
				</div>
				<?php if(!empty($this->getAllData)) {?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter table-hover no-margin">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Payment Type</th>
                                    <th>Last Payment</th>
                                    <th>Remaining Credits</th>
                                    <th>Bonus Credits</th>
                                    <!-- <th style="width: 90px; min-width:90px;" class="text-center"><i class="fa fa-flash"></i></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($this->getAllData as $data) {?>
                                    <tr>
                                        <td><?php echo $data['id']?></td>
                                        <td><?php echo $data['firstname'].' '.$data['surname']?></td>
                                        <td><?php echo $data['email']?></td>
                                        <td><?php echo $this->paymentTypes[$data['type']]?></td>
                                        <td><?php echo date("F j, Y, g:i a", strtotime($data['last_payment'])) ?></td>
                                        <td><?php echo $data['remaining_credits']?></td>
                                        <td><?php echo $data['bonus_credits']?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
	            <?php }else{ ?>
		             <div class="row no-result">
			            <div class="col-xs-12">
							<p>There are no items to display.</p>
			            </div>
		            </div>
			    <?php } ?>
                <div class="pagination-wrap row">
                    <div class="pull-right">
                    </div>
                    <?php if(!empty($this->page_links)){ ?>
                        <div class="dataTables_paginate paging_bootstrap">
                            <?php echo $this->page_links; ?>
                        </div>
                    <?php } ?>
                </div>
	        </div>
        <!-- END Table Styles Content -->
    </div>
    <!-- END Table Styles Block -->
</div>
<!-- END Page Content -->