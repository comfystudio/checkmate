<!-- Page content -->
<div id="page-content">
	<!-- Table Styles Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Contact</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li>Contact</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Table Styles Header -->
    <!-- Table Styles Block -->
    <div class="block full">
    	<div class="block-title">
            <h2>Manage Contact</h2>
            <div class="block-options pull-right">
	    		 <div id="esearch" class="dataTables_filter">
		    		<form class="form-wrap" method='get' action='/backoffice/contact-us/index'>
                        <div class="input-group pull-right">
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
                            <?php if($this->countData[0]['total'] == 0) {?>
                                <a href="/backoffice/contact-us/add" class="btn btn-success"><i class="fa fa-plus"></i> Add Contact</a>
                            <?php } ?>
                        </div>
					</div>
				</div>
				<?php if(!empty($this->getAllData)) {?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-vcenter table-hover no-margin">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Facebook Link</th>
                                    <th>Instagram Link</th>
                                    <th>Twitter Link</th>
                                    <th style="width: 90px; min-width:90px;" class="text-center"><i class="fa fa-flash"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($this->getAllData as $data) {?>
                                    <tr>
                                        <td><?php echo $data['id']?></td>
                                        <td><?php echo $data['facebook']?></td>
                                        <td><?php echo $data['instagram']?></td>
                                        <td><?php echo $data['twitter']?></td>
                                        <td class="text-left">
                                            <a href="/backoffice/contact-us/edit/<?php echo $data['id']; ?>/" data-toggle="tooltip" title="Edit Contact" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                                            <a href="/backoffice/contact-us/delete/<?php echo $data['id']; ?>/" data-toggle="tooltip" title="Delete Contact" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
	            <?php }else{ ?>
		             <div class="row no-result">
			            <div class="col-xs-12">
							<p>There are no contact-us to display.</p>
			            </div>
		            </div>
			    <?php } ?>
                <div class="pagination-wrap row">
                    <div class="pull-right">
                        <?php if($this->countData[0]['total'] == 0) {?>
                            <a href="/backoffice/contact-us/add" class="btn btn-success"><i class="fa fa-plus"></i> Add Contact</a>
                        <?php } ?>
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