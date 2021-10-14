<div class="msg-header-section">
    
    <div class="msg-header-container">
        <div class="profile-img">
            <img src="images/user.jpg" alt="Profile Image">
        </div>
        <div class="profile-data">
            <h5><?php if(isset($_SESSION['login'])){ echo $_SESSION['auth']['user_name']; }else{ echo "User Login";} ?>
                <span class="openNav sidemenu-icon">&#9776;</span>
            </h5>
        </div>
    </div>
    <div class="msg-header-search">
            <div class="input-group">
                <input type="text" class="form-control searchFriend"  placeholder="Search..." >
            </div>
    </div>
    <div id="userlist">
        <div id="userlist-inner">
            <div class="msg-header-userlist lllload" id="load-laptop-sidebar">
                <?php
                    $auth_token = $_SESSION['auth']['user_token'];
                    $frnd_cht_hstry = "SELECT m.sender_id, m.receiver_id, m.user_message,m.sent_time, m.status,m.notify_status, u.id,u.token, u.username FROM messages m,users u 
                    WHERE ((m.sender_id='$auth_token' AND sender_delete='0') OR (m.receiver_id='$auth_token' AND m.receiver_delete='0')) AND (u.token=m.receiver_id OR u.token=m.sender_id ) ORDER BY m.sent_time DESC";
                    $frnd_cht_hstry_run = mysqli_query($con,$frnd_cht_hstry);
                    $shown_users = [];
                    
                    if(mysqli_num_rows($frnd_cht_hstry_run) > 0)
                    {
                        foreach($frnd_cht_hstry_run as $frschat)   
                        {
                            if($frschat['id'] == $authid)
                            {
                                    
                            }
                            else
                            {
                                if(!in_array($frschat['token'], $shown_users))
                                {
                                    array_push($shown_users, $frschat['token']);
                                    ?>
                                        <div class="laptop-view d-none d-sm-block parentToken">
                                            <a href="index.php?frusrkaid=<?php echo $frschat['token'];?>" class="userlistname">
                                                <input type="hidden" value="<?php echo $frschat['token'];?>" class="utokenid">
                                                <div class="<?php if($_GET['frusrkaid'] == $frschat['token'] ) {echo "active";} ?> ">
                                                    <div class="userlist-row">
                                                        <div class="userlist-img">
                                                            <img src="images/user.jpg" alt="Image">
                                                        </div>
                                                        <?php 
                                                            if($frschat['notify_status'] == '1' && $frschat['receiver_id'] == $auth_token) 
                                                            { 
                                                                $sender_token = $frschat['sender_id'];
                                                                $unread_msgs = "SELECT id FROM messages WHERE receiver_id='$auth_token' AND sender_id='$sender_token' AND notify_status='1' ";
                                                                $unread_msgs_run = mysqli_query($con, $unread_msgs);
                                                                $total_no = mysqli_num_rows($unread_msgs_run);
                                                                ?>
                                                                    <span id="unread-msg"> <?= $total_no; ?> </span>
                                                                <?php
                                                            }
                                                        ?>
                                                        <div class="userlist-info">
                                                            <h5><?php echo $frschat['username'];?></h5>
                                                            <h6>
                                                                <?php 
                                                                    if($frschat['sender_id'] == $auth_token)
                                                                    {
                                                                        echo "You: ";
                                                                    }
                                                                    echo $frschat['user_message'];
                                                                ?>
                                                                <span class="float-right msgTime">
                                                                    <?php echo substr($frschat['sent_time'],11,-3); ?> 
                                                                </span>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="mobile-view d-block d-sm-none">
                                            <a href="chat.php?frusrkaid=<?php echo $frschat['token'];?>" >
                                                <input type="hidden" value="<?php echo $frschat['token'];?>" name="utokenid">
                                                <div class="<?php if($_GET['frusrkaid'] == $frschat['token'] ) {echo "active";} ?> ">
                                                    <div class="userlist-row">
                                                        <div class="userlist-img">
                                                            <img src="images/user.jpg" alt="Image">
                                                        </div>
                                                        <?php 
                                                            if($frschat['notify_status'] == '1' && $frschat['receiver_id'] == $auth_token) 
                                                            { 
                                                                $sender_token = $frschat['sender_id'];
                                                                $unread_msgs = "SELECT id FROM messages WHERE receiver_id='$auth_token' AND sender_id='$sender_token' AND notify_status='1' ";
                                                                $unread_msgs_run = mysqli_query($con, $unread_msgs);
                                                                $total_no = mysqli_num_rows($unread_msgs_run);
                                                                ?>
                                                                    <span id="unread-msg"> <?= $total_no; ?> </span>
                                                                <?php
                                                            }
                                                        ?>
                                                         <div class="userlist-info">
                                                            <h5><?php echo $frschat['username'];?></h5>
                                                            <h6>
                                                                <?php 
                                                                    if($frschat['sender_id'] == $auth_token)
                                                                    {
                                                                        echo "You: ";
                                                                    }
                                                                    echo $frschat['user_message'];
                                                                ?>
                                                                <span class="float-right msgTime">
                                                                    <?php echo substr($frschat['sent_time'],11,-3); ?> 
                                                                </span>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php
                                }
                            }
                        }
                    }
                    else
                    {
                        ?>
                            <div class="p-2">
                                <h4 class="text-success">No chats yet! <br> Start chatting</h4>
                            </div>
                        <?php
                    }
                ?>
                   
                <div class="allContacts">
                    <i class="fa fa-comment" data-toggle="modal" data-target="#frndlst"></i>      
                </div>
            </div>
        </div>
    </div>
    <div class="msg-footer-owner">
        <a href="" class="om-prakash-link">
            Designed and Developed by <br> OM PRAKASH N
        </a>
    </div>
    
</div>
 