<!-- Page content -->
<div id="page-content">
	<!-- Table Styles Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Reports</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li>Reports</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Table Styles Header -->
    <!-- Table Styles Block -->
    <div class="block full">
    	<div class="block-title">
            <h2>Manage Reports</h2>
            <div class="block-options pull-right">
	    		 <div id="esearch" class="dataTables_filter">
		    		<form class="form-wrap" method='get' action='/backoffice/reports/index'>
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
                                    <th>Property</th>
                                    <th>LandLord / Letting Agent</th>
                                    <th>Lead Tenant</th>
                                    <th>Current Status</th>
                                    <th>Check In Approval</th>
                                    <th>Check Out Approval</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th style="width: 130px; min-width:130px;" class="text-center"><i class="fa fa-flash"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($this->getAllData as $data) {?>
                                    <tr>
                                        <td><a href = "/backoffice/properties/view/<?php echo $data['property_id']?>"><?php echo $data['property_title']?></td>
                                        <td><a href = "/backoffice/users/edit/<?php echo $data['lord_id']?>"><?php echo $data['lord_firstname'].' '.$data['lord_surname']?></td>
                                        <td><a href = "/backoffice/users/edit/<?php echo $data['tenant_id']?>"><?php echo $data['tenant_firstname'].' '.$data['tenant_surname']?></td>
                                        <td><?php echo $this->status[$data['status']]?></td>
                                        <td>
                                            <?php
                                                if(isset($data['tenant_approved_check_in']) && $data['tenant_approved_check_in'] == 1 && isset($data['lord_approved_check_in']) && $data['lord_approved_check_in'] == 1){
                                                    echo 'Both Tenant and LandLord have approved';
                                                }elseif(isset($data['tenant_approved_check_in']) && $data['tenant_approved_check_in'] == 1){
                                                    echo 'Only Tenant has approved';
                                                }elseif(isset($data['lord_approved_check_in']) && $data['lord_approved_check_in'] == 1){
                                                    echo 'Only Landlord has approved';
                                                }else{
                                                    echo 'Neither the Landlord nor Tenant has approved';
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if(isset($data['tenant_approved_check_out']) && $data['tenant_approved_check_out'] == 1 && isset($data['lord_approved_check_out']) && $data['lord_approved_check_out'] == 1){
                                                    echo 'Both Tenant and LandLord have approved';
                                                }elseif(isset($data['tenant_approved_check_out']) && $data['tenant_approved_check_out'] == 1){
                                                    echo 'Only Tenant has approved';
                                                }elseif(isset($data['lord_approved_check_out']) && $data['lord_approved_check_out'] == 1){
                                                    echo 'Only Landlord has approved';
                                                }else{
                                                    echo 'Neither the Landlord nor Tenant has approved';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo date("F j, Y", strtotime($data['check_in'])) ?></td>
                                        <td><?php echo date("F j, Y", strtotime($data['check_out'])) ?></td>
                                        <td class="text-left">
                                            <a href="/backoffice/reports/report-download/<?php echo $data['id']; ?>/" target="_blank" data-toggle="tooltip" title="Download PDF" class="btn btn-effect-ripple btn-sm btn-primary"><i class="fa fa-cloud-download"></i></a>
                                            <a href="/backoffice/reports/edit/<?php echo $data['id']; ?>/" data-toggle="tooltip" title="Edit Report" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                                            <a href="/backoffice/reports/delete/<?php echo $data['id']; ?>/" data-toggle="tooltip" title="Delete Report" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
	            <?php }else{ ?>
		             <div class="row no-result">
			            <div class="col-xs-12">
							<p>There are no items to display.</p
>			            </div>
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