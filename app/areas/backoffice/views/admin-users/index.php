<!-- Page content -->
<div id="page-content">
	<!-- Table Styles Header -->
    <div class="content-header">
        <div class="row">
            <div class="col-sm-6">
                <div class="header-section">
                    <h1>Admin Users</h1>
                </div>
            </div>
            <div class="col-sm-6 hidden-xs">
                <div class="header-section">
                    <ul class="breadcrumb breadcrumb-top">
                        <li>Admin Users</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Table Styles Header -->
    <!-- Table Styles Block -->
    <div class="block full">
    	<div class="block-title">
            <h2>Manage Admin Users</h2>
            <div class="block-options pull-right">
	    		 <div id="esearch" class="dataTables_filter">
		    		<form class="form-wrap" method='get' action='/backoffice/admin-users/index'>
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
                            <?php if(Session::get('AdminIsSuper')) { ?>
                                    <a href="/backoffice/admin-users/add" class="btn btn-success"><i class="fa fa-plus"></i> Add Admin User</a>
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
                                    <th>Username</th>
                                    <th>Display Name</th>
                                    <th>Email</th>
	                                <th>Super User?</th>
	                                <th>Created</th>
                                    <th style="width: 90px; min-width:90px;" class="text-center"><i class="fa fa-flash"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($this->getAllData as $data) {?>
                                <tr>
                                    <td><strong><?php echo $data['id']; ?></strong></td>
                                    <td><strong><?php echo $data['user_name']; ?></strong></td>
                                    <td><strong><?php echo $data['display_name']; ?></strong></td>
                                    <td><?php echo $data['user_email']; ?></td>
                                    <td><?php if($data['is_super'] == 1) {echo "Yes";}else{ echo "No";} ?></td>
                                    <td><?php echo date("F j, Y, g:i a", strtotime($data['created'])) ?></td>
                                    <td class="text-left">
                                        <?php if(Session::get('AdminIsSuper')) { ?>
                                            <a href="/backoffice/admin-users/edit/<?php echo $data['id']; ?>/" data-toggle="tooltip" title="Edit Admin User" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-pencil"></i></a>
                                            <?php if(Session::get('AdminCurrentUserID') != $data['id']) { ?>
                                                <a href="/backoffice/admin-users/delete/<?php echo $data['id']; ?>/" data-toggle="tooltip" title="Delete Admin User" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-times"></i></a>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
	            <?php }else{ ?>
		             <div class="row no-result">
			            <div class="col-xs-12">
							<p>There are no users to display.</p>
			            </div>
		            </div>
			    <?php } ?>
                <div class="pagination-wrap row">
                    <div class="pull-right">
                        <?php if(Session::get('AdminIsSuper')) { ?>
                            <a href="/backoffice/admin-users/add" class="btn btn-success"><i class="fa fa-plus"></i> Add Admin User</a>
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