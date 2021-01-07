<?= $this->extend('includes/index') ?>
<?= $this->section('content') ?>
<?php if(isset($info)){ ?>
    <table id="example1" class="table table-bordered table-striped">
        <thead>
        <tr>
            <th class="text-center">Image</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created Date Time</th>
            <th>Last Update Date Time</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($info as $item):?>
            <tr>
                <?php $thumbnail_url = (isset($item['thumbnail_path']) && !empty($info))?  base_url().'/'.$item['thumbnail_path'].$item['profile_image'] : base_url().'/dist/img/user2-160x160.jpg' ?>
                <td class="text-center"><img src="<?= $thumbnail_url ?>" class="user-image" alt="User Image" width="70"></td>
                <td><?=  $item['first_name'] ?></td>
                <td><?=  $item['last_name'] ?></td>
                <td><?=  $item['username'] ?></td>
                <td><?=  $item['email'] ?></td>
                <td><?=  $item['created_date_time'] ?></td>
                <td><?=  $item['updated_date_time'] ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <th class="text-center">Image</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created Date Time</th>
            <th>Last Update Date Time</th>
        </tr>
        </tfoot>
    </table>
<?php } ?>
<?= $this->endSection() ?>

