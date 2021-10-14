
<div id="profile" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header ">
        <h5 class="modal-title">My Profile</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="code.php" method="POST">

      <div class="modal-body">
          <section>
            <div class="container">
              <div class="row">
                <?php
                      $auth_id = $_SESSION['auth']['user_id'];
                      $q = "SELECT * FROM users WHERE id='$auth_id'";
                      $q_run = mysqli_query($con, $q);
                      foreach($q_run as $usdata)
                      { 
                    ?>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="fname" value="<?php echo $usdata['fname'] ?>" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="lname" value="<?php echo $usdata['lname'] ?>" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" readonly value="<?php echo $usdata['email'] ?>" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo $usdata['phone'] ?>" class="form-control">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="uname" value="<?php echo $usdata['username'] ?>" class="form-control username">
                    <small class="user-avail"></small>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Status</label>
                    <input type="text" name="about" value="<?php echo $usdata['about'] ?>" class="form-control">
                  </div>
                </div>
                <div class="ml-auto">
                </div>
                <?php
                      } 
                      ?>
              </div>
            </div>
          </section>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="update_profile" class="btn  btn-primary">Update</button>
      </div>
      </form>

    </div>
  </div>
</div>

<div id="mySidenav" class="sidenav">

    <a class="navbar-brand text-white" href="index.php">
        <img src="images/logo.png" alt="img" class="sidebar-logo">
        <span class="mb-0">Pigeon</span>
    </a>

    <a href="javascript:void(0)" class="closebtn closeNav">&times;</a>

    <a href="#">
        <h5 class="my-3"><?php if(isset($_SESSION['login'])){ echo $_SESSION['auth']['user_name']; }else{ echo "User Login";} ?> </h5>
    </a>
    <a href="index.php">Home</a>
    <a href="#" data-toggle="modal" data-target="#frndlst">Your Friends</a>
    <a href="friends.php">Search Friends</a>
    <?php if(isset($_SESSION['login'])) {?>
    <a href="#" data-toggle="modal" data-target="#profile">My Profile</a>
    <a href="logout.php">Logout</a>
    <?php } else { ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php }?>

</div>