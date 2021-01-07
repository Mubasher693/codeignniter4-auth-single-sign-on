<?= $this->extend('includes/index') ?>
<?= $this->section('content') ?>
        <div class="row">
            <div class="col-sm-3"><!--left col-->
                <div class="text-center">
                    <?php $image_path = ($info['thumbnail_path'] != '' && $info['profile_image']!= '' ? $info['thumbnail_path'].$info['profile_image'] : 'avatar_2x.png'); ?>
                    <img src="<?= base_url().'/'.$image_path ?>" class="avatar img-circle img-thumbnail" alt="avatar">
                    <!--<img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" class="avatar img-circle img-thumbnail" alt="avatar">-->
                </div>
                </hr><br>
                <div class="panel panel-default">
                    <div class="panel-heading">Website <i class="fa fa-link fa-1x"></i></div>
                    <div class="panel-body"><a href="http://bootnipets.com">bootnipets.com</a></div>
                </div>
                <ul class="list-group">
                    <li class="list-group-item text-muted">Activity <i class="fa fa-dashboard fa-1x"></i></li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Shares</strong></span> 125</li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Likes</strong></span> 13</li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Posts</strong></span> 37</li>
                    <li class="list-group-item text-right"><span class="pull-left"><strong>Followers</strong></span> 78</li>
                </ul>
                <div class="panel panel-default">
                    <div class="panel-heading">Social Media</div>
                    <div class="panel-body">
                        <i class="fa fa-facebook fa-2x"></i> <i class="fa fa-github fa-2x"></i> <i class="fa fa-twitter fa-2x"></i> <i class="fa fa-pinterest fa-2x"></i> <i class="fa fa-google-plus fa-2x"></i>
                    </div>
                </div>
            </div><!--/col-3-->
            <div class="col-sm-9">
                <div class="tab-content">
                    <form class="form" action="<?= base_url('users/get/'.$id);?>" method="post" id="profileForm" enctype="multipart/form-data">
                        <!--Image-->
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="first_name"><h4>Upload a photo</h4></label>
                                <input type="file" name="image" class="text-center center-block file-upload <?= ($validation->hasError('image')) ? 'is-invalid' : ''; ?>" />
                                <span class="help-block">(Leave blank if you don't want to change, Valid formats are JPEG & JPG)</span>
                                <div class="invalid-feedback"> <?= $validation->getError('image'); ?> </div>
                            </div>
                        </div>
                        <!--User name-->
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="first_name"><h4>Username</h4></label>
                                <input type="text" class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : ''; ?>" name="username" id="username" placeholder="username"
                                       title="enter username." value="<?= (old('username') ? old('username')  : $info['username']); ?>">
                                <div class="invalid-feedback"> <?= $validation->getError('username'); ?> </div>
                            </div>
                        </div>
                        <!--first name-->
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="first_name"><h4>First name</h4></label>
                                <input type="text" class="form-control <?= ($validation->hasError('first_name')) ? 'is-invalid' : ''; ?>" name="first_name" id="first_name" placeholder="first name"
                                       title="enter your first name if any." value="<?= (old('first_name') ? old('first_name')  : $info['first_name']); ?>">
                                <div class="invalid-feedback"> <?= $validation->getError('first_name'); ?> </div>
                            </div>
                        </div>
                        <!--last name-->
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="last_name"><h4>Last name</h4></label>
                                <input type="text" class="form-control <?= ($validation->hasError('last_name')) ? 'is-invalid' : ''; ?>" name="last_name" id="last_name" placeholder="last name"
                                       title="enter your last name if any." value="<?= (old('last_name') ? old('last_name')  : $info['last_name']); ?>">
                                <div class="invalid-feedback"> <?= $validation->getError('last_name'); ?> </div>
                            </div>
                        </div>
                        <!--phone-->
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="phone"><h4>Phone</h4></label>
                                <input type="text" class="form-control <?= ($validation->hasError('phone')) ? 'is-invalid' : ''; ?>" name="phone" id="phone" placeholder="enter phone"
                                       title="enter your phone number if any." value="<?= (old('phone') ? old('phone')  : $info['phone']); ?>">
                                <div class="invalid-feedback"> <?= $validation->getError('phone'); ?> </div>
                            </div>
                        </div>
                        <!--mobile-->
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="mobile"><h4>Mobile</h4></label>
                                <input type="text" class="form-control <?= ($validation->hasError('mobile')) ? 'is-invalid' : ''; ?>" name="mobile" id="mobile" placeholder="enter mobile number"
                                       title="enter your mobile number if any." value="<?= (old('mobile') ? old('mobile')  : $info['mobile']); ?>">
                                <div class="invalid-feedback"> <?= $validation->getError('mobile'); ?> </div>
                            </div>
                        </div>
                        <!--email-->
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="email"><h4>Email</h4></label>
                                <input type="email" class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : ''; ?>" placeholder="Email"
                                       name="email" value="<?= (old('email') ? old('email')  : $info['email']); ?>">
                                <div class="invalid-feedback"> <?= $validation->getError('email'); ?> </div>
                            </div>
                        </div>
                        <!--location-->
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="email"><h4>Address</h4></label>
                                <textarea type="text" class="form-control <?= ($validation->hasError('address')) ? 'is-invalid' : ''; ?>"
                                          id="location" placeholder="somewhere" name="address"
                                          title="enter a location">
                                    <?= (old('address') ? trim(old('address'))  : trim($info['address'])); ?>
                                </textarea>
                                <div class="invalid-feedback"> <?= $validation->getError('address'); ?> </div>
                            </div>
                        </div>
                        <!--password-->
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="password"><h4>Password</h4></label>
                                <input type="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : ''; ?>" name="password" id="password" placeholder="password" title="enter your password.">
                                <div class="invalid-feedback"> <?= $validation->getError('password'); ?> </div>
                            </div>
                        </div>
                        <!--confirm password-->
                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="password2"><h4>Verify</h4></label>
                                <input type="password" class="form-control <?= ($validation->hasError('confirm_password')) ? 'is-invalid' : ''; ?>" name="confirm_password" id="confirm_password" placeholder="Retype password" >
                                <div class="invalid-feedback"> <?= $validation->getError('confirm_password'); ?> </div>
                            </div>
                        </div>
                        <!--buttons-->
                        <div class="form-group">
                            <div class="col-xs-12">
                                <br>
                                <button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                            </div>
                        </div>
                    </form>
                </div><!--/tab-pane-->
            </div><!--/tab-content-->

        </div><!--/col-9-->
<?= $this->endSection() ?>