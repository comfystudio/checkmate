<div class="greyback">
    <div class ="container">
        <div class="formintro">
            <div class="single_notification">
                <?php if (!empty($this->error)) { ?>
                    <div class="alert alert-info alert-labeled formerror">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                        </button>
                        <div class="alert-labeled-row">
                            <span class="alert-label alert-label-left alert-labelled-cell">
                                <i class="glyphicon glyphicon-info-sign"></i>
                            </span>
                            <h4>
                                <strong>Failure</strong>
                            </h4>
                            <p class="alert-body alert-body-right alert-labelled-cell">
                                <?php
                                foreach($this->error as $error){
                                    echo $error.'<br/>';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class = "row front-content">
                <div class = "col-md-offset-4 col-md-4 ">
                    <img src="/assets/images/logo-small.png" alt ="Check mate small logo" class = "logo-small">
                </div>
                <div class = "col-md-offset-4">
                </div>
            </div>

            <div class = "row">
                <div class = "col-md-offset-4 col-md-4 strapline-header">
                    Completed Reports
                </div>
            </div>
            <div class = "row">
                <div class ="col-xs-12 welcome-message">
                    These are reports that have been completed their check in / check out process
                </div>
            </div>
        </div>

        <div class = "row">
            <table class="table table-bordered table-striped table-vcenter table-hover no-margin">
                <thead>
                <tr>
                    <th>Property Image</th>
                    <th>Property</th>
                    <th>LandLord / Letting Agent</th>
                    <th>Lead Tenant</th>
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
                        <td><img src = "/assets/uploads/<?php echo $data['image']?>" width="160px"></td>
                        <td><?php echo $data['property_title']?></td>
                        <td><?php echo $data['lord_firstname'].' '.$data['lord_surname']?></td>
                        <td><?php echo $data['tenant_firstname'].' '.$data['tenant_surname']?></td>
                        <td>
                            <?php
                            if(isset($data['tenant_approved_check_in']) && $data['tenant_approved_check_in'] == 1 && isset($data['lord_approved_check_in']) && $data['lord_approved_check_in'] == 1){
                                echo 'Both Tenant and LandLord / Agent have approved';
                            }elseif(isset($data['tenant_approved_check_in']) && $data['tenant_approved_check_in'] == 1){
                                echo 'Only Tenant has approved';
                            }elseif(isset($data['lord_approved_check_in']) && $data['lord_approved_check_in'] == 1){
                                echo 'Only Landlord / Agent has approved';
                            }else{
                                echo 'Neither the Landlord / Agent nor Tenant has approved';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if(isset($data['tenant_approved_check_out']) && $data['tenant_approved_check_out'] == 1 && isset($data['lord_approved_check_out']) && $data['lord_approved_check_out'] == 1){
                                echo 'Both Tenant and LandLord / Agent have approved';
                            }elseif(isset($data['tenant_approved_check_out']) && $data['tenant_approved_check_out'] == 1){
                                echo 'Only Tenant / Agent has approved';
                            }elseif(isset($data['lord_approved_check_out']) && $data['lord_approved_check_out'] == 1){
                                echo 'Only Landlord / Agent has approved';
                            }else{
                                echo 'Neither the Landlord / Agent nor Tenant has approved';
                            }
                            ?>
                        </td>
                        <td><?php echo date("F j, Y", strtotime($data['check_in'])) ?></td>
                        <td><?php echo date("F j, Y", strtotime($data['check_out'])) ?></td>
                        <td class="text-left">
                            <img src="/assets/images/download.png">
                            <a href="/reports/report-download/<?php echo $data['id']; ?>/" data-toggle="tooltip" title="Download PDF" class="">Download PDF</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>