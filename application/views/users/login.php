<?php   $this->load->view('templates/header');  ?>
<?php   $this->load->view('templates/navbar');  ?>
<?php   $this->load->view('templates/flashdata');  ?>

    <section class="container login-register-form p-5">
        <form action="<?=base_url('login')?>" method="POST">
            <h1 class="mb-3">Login Form</h1>
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
            <div class="row">
                <div class="col">
                    <input type="submit" class="btn btn-success form-control" value="Login Account">
                </div>
            </div>
        </form>
    </section>
    
<?php   $this->load->view('templates/footer');  ?>