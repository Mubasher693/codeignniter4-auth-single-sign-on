<?= $this->extend('auth/index') ?>
<?= $this->section('content') ?>
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?= route_to('login') ?>" class="h1"><b>XY</b>Z</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="<?= base_url('auth/login');?>" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control <?= ($validation->hasError('email')) ? 'is-invalid' : ''; ?>" placeholder="Email" name="email" value="<?= old('email'); ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback"> <?= $validation->getError('email'); ?> </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control <?= ($validation->hasError('password') || $validation->hasError('credentials')) ? 'is-invalid' : ''; ?>" placeholder="Password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback"> <?= $validation->getError('password'); ?> </div>
                        <div class="invalid-feedback"> <?= $validation->getError('credentials'); ?> </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="credentials">
                                <label for="remember">Remember Me</label>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <div class="social-auth-links text-center mt-2 mb-3">
                    <!--Facebook-->
                    <?php if($fb_login_button != ''){
                        echo $fb_login_button;
                    } else { ?>
                        <a href="#" class="btn btn-block btn-primary">
                            <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
                        </a>
                    <?php } ?>
                    <!--Gmail-->
                    <?php if($gm_login_button != '') {
                        echo $gm_login_button;
                    } else { ?>
                        <a href="#" class="btn btn-block btn-danger">
                            <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                        </a>
                    <?php } ?>
                </div>
                <!-- /.social-auth-links -->

                <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
                <p class="mb-0">
                    <a href="<?= route_to('register') ?>" class="text-center">Register a new membership</a>
                </p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
<?= $this->endSection() ?>