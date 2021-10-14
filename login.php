<?php session_start(); 

if(isset($_SESSION['login']) && $_SESSION['login'] == true)
{
    $_SESSION['status'] = "You are already logged in";
    header('location: index.php');
    exit(0);
}

include('includes/header.php'); 

?>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 my-auto">
                <div class="login-form shadow border p-md-5 p-2">
                    <h4 class="login-header">Login</h4>
                    <hr>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <div class="form-group">
                                <label>Email address</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="text-center">
                                <button type="submit" name="login_btn" class="btn btn-primary px-3">Login</button>
                                <p class="text-dark mt-3">  
                                    Dont have an Account? <a href="register.php" class="rem-ul"> Sign up </a>
                                </p>  
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include('includes/footer.php') ?>