<?php 
    if(isset($_GET['frusrkaid']))
    {
        $fr_id = $_GET['frusrkaid'];
        $query_users = "SELECT * FROM users WHERE token='$fr_id' ";
        $query_users_run = mysqli_query($con, $query_users);
        $frnd_user_data = mysqli_fetch_array($query_users_run);
        $auth_id = $_SESSION['auth']['user_token'];
        $loggedinID = $_SESSION['auth']['user_id'];

        if(mysqli_num_rows($query_users_run) > 0)
        {
            $frndkaId = $frnd_user_data['id'];
            $frnd_check_query = "SELECT * FROM friends WHERE users_id='$loggedinID' AND friend_id='$frndkaId' ";
            $frnd_check_query_run = mysqli_query($con, $frnd_check_query);
            $frndData = mysqli_fetch_array($frnd_check_query_run);
            if(mysqli_num_rows($frnd_check_query_run) > 0)
            {
                $unseen_count = "SELECT * FROM messages WHERE receiver_id='$auth_id' AND sender_id='$fr_id' AND notify_status ='1' ";
                $unseen_count_run = mysqli_query($con, $unseen_count);
                if(mysqli_num_rows($unseen_count_run) > 0)
                {
                    $seen_update_query = "UPDATE messages SET notify_status='2' WHERE receiver_id='$auth_id' AND sender_id='$fr_id' ";
                    $seen_update_query_run = mysqli_query($con, $seen_update_query);
                }
                ?>
                <div class="chat-content-container">
                    
                    <?php foreach($query_users_run as $usrrow) : ?>
                    <div class="frnd-name">
                        <div class="mobile-back-btn d-sm-none">
                            <a href="index.php">
                                <i class="fa fa-angle-left"></i>
                            </a>
                        </div>
                        <div class="user-img-container">
                            <img src="images/user.jpg" class="nav-user-img" alt="Image">
                        </div>
                        <div class="user-container my-auto">
                            <h5 class="chat-username"><?php echo $usrrow['username']; ?></h5>
                            <h6 class="chat-username-status">-<?php echo $usrrow['about']; ?></h6>
                        </div>
                        <div class="user-menu-option float-right">
                            <div class="btn-group">
                                <button type="button" class="btn text-white btn-secondarys dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-white"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right user-menubar-dropdowm">
                                    <!-- <a href="javascript:void(0)">View Contact</a> -->
                                    <input type="hidden" id="usertoken" value="<?= $usrrow['token']; ?>">
                                    <a href="javascript:void(0)" id="deleteChat">
                                        Clear Chat
                                    </a>
                                    <input type="hidden" class="blockfrndid" value="<?= $frndkaId; ?>">
                                    <?php 
                                        if(($frndData['status'] == '1') && ($frndData['blocker_id'] == $loggedinID))
                                        {
                                            ?>
                                                <a href="javascript:void(0)" class="Unblockuser" id="unblockContact">Unblock</a>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <a href="javascript:void(0)" class="blockuser" id="blockContact">Block</a>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <span class="offon"></span>
                    
                    <div class="chat-section shadow-sm px-md-4 px-0">
                        <div class="chatload">
                            <div class="conversation">
                                <div class="conversation-container">
                                    <?php
                                    // To get the messages of the logged in user with
                                    $msg_query = "SELECT * FROM (SELECT * FROM messages WHERE ((sender_id='$auth_id' AND receiver_id = '$fr_id' AND sender_delete='0') 
                                    OR (receiver_id='$auth_id' AND sender_id='$fr_id' AND receiver_delete='0')) AND status != '1' ORDER BY id DESC LIMIT 700) sub ORDER BY id ASC";
                                    $msg_query_run = mysqli_query($con, $msg_query);
                                    if(mysqli_num_rows($msg_query_run) > 0)
                                    {
                                        foreach($msg_query_run as $msgrow)
                                        {
                                            $date = strtotime($msgrow['sent_time']);
                                            if($msgrow['sender_id'] == $auth_id)
                                            {
                                                ?>
                                                <div class="message sent ">
                                                    <span> <?php echo $msgrow['user_message']; ?></span>
                                                    <span class="metadata float-right">
                                                        <span class="time">
                                                            <?php echo date('d/M/Y h:i A', $date); 
                                                                if($msgrow['notify_status'] == '0')
                                                                {
                                                                    ?>
                                                                        <i class="check-icon">
                                                                            &#10003;
                                                                        </i>
                                                                    <?php
                                                                } 
                                                                else if($msgrow['notify_status'] == '1')
                                                                {
                                                                    ?>
                                                                        <i class="check-icon" style="margin-right: -9px;">&#10003;</i>
                                                                        <i class="check-icon">&#10003;</i>
                                                                    <?php
                                                                } 
                                                                else if($msgrow['notify_status'] == '2')
                                                                {
                                                                    ?>
                                                                        <i class="seen-check-icon" style="margin-right: -9px;">&#10003;</i>
                                                                        <i class="seen-check-icon">&#10003;</i>
                                                                    <?php
                                                                } 
                                                            ?>
                                                        </span>
                                                    </span>
                                                </div>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <div class="message received">
                                                    <span> 
                                                        <?php 
                                                            echo $msgrow['user_message'];
                                                        ?>
                                                    </span>
                                                    <span class="metadata">
                                                        <span class="time">
                                                        <?php echo date('d/M/Y h:i A', $date); ?>
                                                        </span>
                                                    </span>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <div class="text-center p-3">
                                            <h4 class="text-white">No chats yet! Start Chatting</h4>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="user-msg">
                        <div class="msg-type-container">
                            <div class="input-group">
                                <?php 
                                    if(($frndData['status'] == '1') && ($frndData['blocker_id'] == $loggedinID))
                                    {
                                        ?>
                                            <textarea name="messsage" class="form-control mgbxs" id="exampleFormControlTextarea1" rows="1"></textarea>
                                            <span>Unblock to start chatting</span>
                                            <input type="hidden" class="dufhsiufsdfpiuf" value="<?php echo $fr_id; ?>">
                                        <?php
                                    }
                                    elseif(($frndData['status'] == '1') && ($frndData['blocker_id'] != $loggedinID))
                                    {
                                        ?>
                                            <div class="float-right">
                                                <span class="float-right"><?php echo $usrrow['username']; ?> has Blocked You ! You cannot send messages to them</span>
                                            </div>

                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <textarea name="messsage" class="form-control mgbxs" id="exampleFormControlTextarea1" rows="1" style="max-height: 2.6rem;"></textarea>
                                            <input type="hidden" class="dufhsiufsdfpiuf" value="<?php echo $fr_id; ?>">
                                            <div class="input-group-append">
                                                <button type="submit" name="messsendbt" class="messsendbt input-group-text" id="basic-addon2"><i class="fa fa-send"></i></button>
                                            </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
            }
            else{
                ?>
                    <div class="p-5">
                        <h2 class="text-white">Send Friend request to start chatting with them</h2>
                    </div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="text-center">
                <div class="mt-5">
                    <h4 class="text-white">Start chatting with ur friends</h4>
                </div>
            </div>
            <?php
        }

    }
    else 
    {
        ?>
        <div class="text-center">
            <div class="mt-5">
                <h4 class="text-white">Start chatting with ur friends</h4>
            </div>
        </div>
        <?php 
    } 
    
?>


