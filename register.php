<?php
session_start();

if(isset($_SESSION['login']) && $_SESSION['login'] == true)
{
    $_SESSION['status'] = "You are logged in";
    header('location: index.php');
    exit(0);
}

include('includes/header.php'); 

?>

<section class="section my-overflow">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="login-form shadow border p-md-5 p-3">
                    <form action="code.php" method="POST">
                        <a class="float-right mr-2 text-dark d-block d-sm-none" href="login.php"><i class="fa fa-angle-left mr-1"></i> BACK</a>
                        <h4 class="font-weight-bold text-md-center">Register</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" required name="fname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control"  required name="lname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" onblur="PhoneNumvalidate()" id="mobilenumber" maxlength="10" minlength="10" class="form-control" required name="phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email address</label>
                                    <input type="email" required class="form-control" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" required class="form-control" name="password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" required class="form-control" name="cpassword">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" required class="form-control username" name="username">
                                </div>
                            </div>
                            <div class="col-md-4 mt-md-4 mb-3">
                                <small class="user-avail"></small>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" name="reg_btn" class="btn btn-primary px-4">Register</button>
                            <div class="mt-3">
                                Already have an account ? <a href="login.php" class=" rem-ul">Login Now</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
 function PhoneNumvalidate()
  {
    var filter = /^[0-9][0-9]{9}$/;
    
    var a = $("#mobilenumber").val();     
    if (!(filter.test(a))) {
        swal("","Enter valid mobile number","warning");
        $("#mobilenumber").val('');
    }
  }
</script>
<?php include('includes/footer.php') ?>