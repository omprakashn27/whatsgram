<?php include('authentication.php');  ?>
<?php include('includes/header.php') ?>

<div id="sentreqs" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content b-white">
      <div class="modal-header">
        <h4 class="modal-title">Pending requests</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php
        $auth_id = $_SESSION['auth']['user_id'];
        $sent_query = "SELECT r.sender_id, r.receiver_id, u.id as userid, u.username FROM requests r, users u WHERE sender_id='$auth_id' AND u.id=r.receiver_id AND status='0' "; 
        $sent_query_run = mysqli_query($con,$sent_query);
        if(mysqli_num_rows($sent_query_run) > 0)
        {
          foreach($sent_query_run as $namess )
          {
            $req_receiver_id = $namess['receiver_id'];
            $check_duplicate_query = "SELECT * FROM friends WHERE users_id='$auth_id' AND friend_id='$req_receiver_id' AND status='0' "; 
            $check_duplicate_query_run = mysqli_query($con,$check_duplicate_query);
            if(mysqli_num_rows($check_duplicate_query_run) == 0)
            {
              ?>
                <div class="userlist-row alldata bg-f5f5f5">
                  <div class="userlist-img">
                      <img src="images/user.jpg" alt="Image">
                  </div>
                  <div class="userlist-info">
                      <h5> <?=  $namess['username'] ?>
                        <button type="button" value="<?= $namess['receiver_id']; ?>" class="float-right btn bg-info text-white btn-sm px-2 f-12 unSendReq">Unsend</button>
                      </h5>
                      <h6>Pigeon user</h6>
                  </div>
                </div>
              <?php
            }
          }
        }
        else{
            ?>
             <p clasa=""> You Dont have any of your requests pending!" </p>
            <?php
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="allfrnds" class="modal fade " role="dialog">
  <div class="modal-dialog">
    <div class="modal-content b-white">
      <div class="modal-header">
        <h4 class="modal-title">All Friends</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php
        $auth_id = $_SESSION['auth']['user_id'];
        $alfrnd_query = "SELECT f.friend_id,u.id,u.username, u.token FROM friends f,users u WHERE users_id='$auth_id' AND u.id=f.friend_id "; 
        $alfrnd_query_run = mysqli_query($con,$alfrnd_query);
        if(mysqli_num_rows($alfrnd_query_run) > 0)
        {
            foreach($alfrnd_query_run as $nassmess )
            {
                ?>
                    <div class="laptop-view d-none d-sm-block ">
                        <a href="index.php?frusrkaid=<?php echo $nassmess['token'];?>" class="userlistname">
                          <div class="userlist-row alldata bg-f5f5f5">
                            <div class="userlist-img">
                                <img src="images/user.jpg" alt="Image">
                            </div>
                            <div class="userlist-info">
                                <h5> <?=  $nassmess['username'] ?></h5>
                                <h6>Pigeon user</h6>
                            </div>
                          </div>
                        </a>
                    </div>
                    <div class="mobile-view d-block d-sm-none">
                        <a href="chat.php?frusrkaid=<?php echo $nassmess['token'];?>" class="userlistname">
                          <div class="userlist-row alldata bg-f5f5f5">
                            <div class="userlist-img">
                                <img src="images/user.jpg" alt="Image">
                            </div>
                            <div class="userlist-info">
                                <h5> <?=  $nassmess['username'] ?></h5>
                                <h6>Pigeon user</h6>
                            </div>
                          </div>
                        </a>
                    </div>
                <?php
            }
        }
        else{
            echo "You Have no friends! Send them a request now to start chatting";
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="frndprofile" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content b-white">
      <div class="modal-header">
        <h4 class="modal-title"><h2 class="viewname"></h2> <h2>'s profile</h2> </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
       <h2 class="viewname"></h2>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="Invite" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Invite your friends</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
          <div class="container">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <a href="whatsapp://send?text= Join me on pigeon, a light and fast messenger app. Register now %0A *Click here*%0A👇🏻👇👇 %0A https://localhost/om/pigeon/index.php  %0A">
                        <button class="btn btn-block btn-success"><i class="fa fa-whatsapp mr-2 w-icon"></i>Whatsapp</button>
                        </a>
                    </div>
                </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<div id="friendRequests" class="modal frreqs fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header ">
        <h4 class="modal-title">Friend Requests</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php
            $authid = $_SESSION['auth']['user_id'];
            $query_freq = "SELECT * FROM requests WHERE receiver_id='$authid' AND status='0' ";
            $query_run = mysqli_query($con, $query_freq);
            if(mysqli_num_rows($query_run) > 0)
            {
              if(mysqli_fetch_array($query_run) > 0)
              {
                foreach($query_run as $usr)
                {
                  $requests_id = $usr['sender_id'];
                  $check_duplicate_query = "SELECT * FROM friends WHERE users_id='$auth_id' AND friend_id='$requests_id' AND status='0' "; 
                  $check_duplicate_query_run = mysqli_query($con, $check_duplicate_query);
                  if(mysqli_num_rows($check_duplicate_query_run) == 0)
                  {
                    ?>
                      <div class="alldata">
                            <?php
                              $s_id = $usr['sender_id'];
                              $query_req_name = "SELECT * FROM users WHERE id='$s_id' ";
                              $get_req_name = mysqli_query($con,$query_req_name);
                              if(mysqli_num_rows($get_req_name) > 0)
                              {
                                if(mysqli_fetch_array($get_req_name) > 0)
                                {
                                  foreach($get_req_name as $rname)
                                  {
                                      if($rname['username'] != NULL)
                                      {
                                        ?>
                                            <div class="userlist-row alldata bg-f5f5f5">
                                              <div class="userlist-img">
                                                  <img src="images/user.jpg" alt="Image">
                                              </div>
                                              <div class="userlist-info">
                                                  <h5> <?=  $rname['username'] ?>
                                                  <button value="<?php echo $usr['sender_id']; ?>" class="delbtn btn del-btn btn-danger btn-sm float-right mr-1">Delete</button>
                                                    <button value="<?php echo $usr['sender_id']; ?>" class="approvbtn btn acc-btn btn-success btn-sm float-right mr-1">Accept</button>
                                                  </h5>
                                              </div>
                                            </div>
                                          <?php
                                      }
                                      else{
                                          echo "User";
                                      }
                                  }
                                }
                              }
                          ?>
                          
                      </div>
                    <?php
                  }
                }    
              }
            }
            else{
                ?>
                    <h6 class="font-weight-bold">You Dont have any Friend Requests</h6>
                
                <?php
            }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div class="friend-top-heading">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <a class="btn float-left mr-2 text-white" href="index.php"><i class="fa fa-angle-left mr-1"></i> BACK</a>

        <button type="button" class="btn btn-secondary dropdown-toggle float-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-ellipsis-v ml-1"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right user-menubar-dropdowm">
          <a href="javascript:void(0)" data-toggle="modal" data-target="#friendRequests" class="dropdown-item">All Requests</a>
          <a href="javascript:void(0)" data-toggle="modal" data-target="#allfrnds" class="dropdown-item">All Friends</a>
          <a href="javascript:void(0)" data-toggle="modal" data-target="#Invite" class="dropdown-item">Invite</a>
          <a href="javascript:void(0)" data-toggle="modal" data-target="#sentreqs" class="dropdown-item">Sent Requests</a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="friend-section">
  <div class="container">

    <div class="row">
      <div class="col-md-8 mt-2">
        <div class="search-sec">

          <div class="p-md-4 p-2 bg-white">
              <div class="form-group ">
                <label class="font-weight-bold mb-1">Search Friends</label>
                <input type="search" required class="form-control" placeholder="Search..." id="search_user">
              </div>
              <div id="searchResults" class="searchResult-container"></div>
          </div>

        </div>
      </div>
      <div class="col-md-4 mt-2">
        <div class="p-md-4 border-left">

            <label class="font-weight-bold mb-1">Recent Friends</label><br/>
            <div class="fnam">
                <?php    
                    $frnd_q = "SELECT f.friend_id,u.id,u.username,u.token FROM friends f,users u  WHERE users_id='$authid' AND u.id=f.friend_id  ORDER BY id DESC LIMIT 5";
                    $frnd_q_run = mysqli_query($con,$frnd_q);
                    if(mysqli_num_rows($frnd_q_run) > 0)
                    {
                        foreach($frnd_q_run as $frs)   
                        {
                            ?>
                              <div class="border-top bg-f2f2f2 my-2 getproclos">
                                <div class="laptop-view d-none d-sm-block">
                                  <a class="btn btn-block text-left py-2" href="index.php?frusrkaid=<?php echo $frs['token']; ?>">
                                    <i class="fa fa-user"></i>
                                    <?php echo $frs['username']; ?>
                                  </a>
                                </div>
                                <div class="mobile-view d-block d-sm-none">
                                  <a class="btn btn-block text-left py-2" href="chat.php?frusrkaid=<?php echo $frs['token']; ?>">
                                    <i class="fa fa-user"></i>
                                    <?php echo $frs['username']; ?>
                                  </a>
                                </div>
                               
                            </div>
                            <?php
                        }
                    }
                    else{
                        echo "You have no friends";
                    }
                ?>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include('includes/footer.php') ?>
