<?php   $this->load->view('templates/header');  ?>
<?php   $this->load->view('templates/navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

    <section class="container login-register-form p-5">
        <form action="<?=base_url('register')?>" method="POST">
            <h1 class="mb-3">Registration Form</h1>
            <div class="row mb-3">
                <div class="col">
                    <label for="first_name">First Name</label>
                </div>
                <div class="col">
                    <input type="text" name="first_name" class="form-control" value="<?=(!empty($first_name) ? $first_name : '')?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="last_name">Last Name</label>
                </div>
                <div class="col">
                    <input type="text" name="last_name" class="form-control" value="<?=(!empty($last_name) ? $last_name : '')?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="email_address">Email Address</label>
                </div>
                <div class="col">
                    <input type="text" name="email_address" class="form-control" value="<?=(!empty($email_address) ? $email_address : '')?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="password">Password</label>
                </div>
                <div class="col">
                    <input type="password" name="password" class="form-control" value="<?=(!empty($password) ? $password : '')?>">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="confirm_password">Confirm Password</label>
                </div>
                <div class="col">
                    <input type="password" name="confirm_password" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <input type="submit" class="btn btn-success form-control" value="Register Account">
                </div>
            </div>
        </form>
    </section>
    
<?php   $this->load->view('templates/footer');  ?>