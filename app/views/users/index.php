 <div class="post_item">
    <div class="white_area">
        <h2 class="floatleft">Manage Users</h2>
        <a href="/users/add" class="btn floatright">Add new user</a>
        <form class="form-wrap" method='get' action=''>
            <div class="searcharea">
                <input class="search" placeholder="Search users" name="keywords" id="search_term" <?php if (isset($_GET["keywords"])) {echo 'value="'.htmlentities($_GET["keywords"]).'"';}?>>
                <button class="search"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </div><!--white_area-->
</div><!--post_item-->

<table class = "user_list">
    <thead>
        <th>Name</th>
        <th>Email</th>
        <th>Active?</th>
        <th style ="text-align: center;"><i class="fa fa-flash"></i></th>
    </thead>
    <?php foreach($this->getAllData as $user){ ?>
        <tr>
            <td><?php echo $user['firstname'].' '.$user['surname']?></td>
            <td><?php echo $user['email'];?></td>
            <td><?php if($user['is_active'] == 1){echo 'Yes';}else{echo 'No';}?></td>
            <td>
                <div class="button_options full">
                    <a href="/users/edit/<?php echo $user['id']?>" title="Edit"><button><i class="fa fa-pencil-square-o"></i></button></a>
                    <a href="/users/delete/<?php echo $user['id']?>" title="Delete"><button><i class="fa fa-trash"></i></button></a>
                </div>
            </td>
        </tr>
    <?php } ?>
</table>

<div class="pagination">
    <?php if(!empty($this->page_links)){ ?>
        <div class="dataTables_paginate paging_bootstrap">
            <?php echo $this->page_links; ?>
        </div>
    <?php } ?>
</div>