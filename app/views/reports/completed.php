<table class="table table-bordered table-striped table-vcenter table-hover no-margin">
    <thead>
    <tr>
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
            <td><?php echo $data['property_title']?></td>
            <td><?php echo $data['lord_firstname'].' '.$data['lord_surname']?></td>
            <td><?php echo $data['tenant_firstname'].' '.$data['tenant_surname']?></td>
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
                <a href="/reports/report-download/<?php echo $data['id']; ?>/" data-toggle="tooltip" title="Download PDF" class="btn btn-effect-ripple btn-sm btn-primary"><i class="fa fa-cloud-download"></i></a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>