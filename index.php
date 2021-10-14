<?php include('authentication.php');  ?>
<?php include('includes/header.php') ?>
<?php include('includes/sidebar.php');  ?>



<div id="frndlst" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Click one to start chatting </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-bodys">
                 <div class="msg-header-sectionsw">
                    <div class="msg-header-userlist">
                        <?php    
                            $authid = $_SESSION['auth']['user_id'];
                            $frnd_qury = "SELECT f.friend_id, u.id,u.about, u.token ,u.username FROM friends f,users u WHERE users_id='$authid' AND u.id=f.friend_id " ;
                            $frnd_q_run = mysqli_query($con,$frnd_qury);
                            if(mysqli_num_rows($frnd_q_run) > 0)
                            {
                                foreach($frnd_q_run as $frs)   
                                {
                                    ?>
                                        <div class="laptop-view d-none d-sm-block">
                                            <a href="index.php?frusrkaid=<?php echo $frs['token'];?>" >
                                                <div class="userlist-row">
                                                    <div class="userlist-img">
                                                        <img src="images/user.jpg" alt="Image">
                                                    </div>
                                                    <div class="userlist-info">
                                                        <h5><?= $frs['username']; ?></h5>
                                                        <h6 class="text-dark"><?php if($frs['about'] != NULL) { echo $frs['about']; }else { echo "Hey there! I am using pigeon"; } ?> </h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="mobile-view d-block d-sm-none">
                                            <a href="chat.php?frusrkaid=<?php echo $frs['token'];?>">
                                                <div class="userlist-row">
                                                    <div class="userlist-img">
                                                        <img src="images/user.jpg" alt="Image">
                                                    </div>
                                                    <div class="userlist-info">
                                                        <h5><?= $frs['username']; ?></h5>
                                                        <h6 class="text-dark"><?php if($frs['about'] != NULL) { echo $frs['about']; }else { echo "Hey there! I am using pigeon"; } ?> </h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php
                                }
                            }
                            else{
                                ?> 
                                <h4 class="p-3">You have no friends! Start making friends to chat with them</h4>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="chat-container">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 px-0">
                <?php include('includes/side-chat.php'); ?>
            </div>

            <div class="col-md-9 px-0 bg-dark">
                <?php include('includes/chat-conversation.php'); ?>
            </div>
           
        </div>
    </div>
</section>

<!-- to check any new messages available -->
<div class="parent-check" id="links">
    <div class="check-notified">
        <?php
            $loggedIn_token = $_SESSION['auth']['user_token'];
            $new_msg = "SELECT m.sender_id, m.receiver_id, u.id,u.token, u.username FROM messages m,users u 
            WHERE m.receiver_id='$loggedIn_token' AND notify_status='0' ORDER BY m.sent_time DESC";
            $new_msg_run = mysqli_query($con, $new_msg);

            if(mysqli_num_rows($new_msg_run) > 0)
            {
                $notify_update_query = "UPDATE messages SET notify_status='1' WHERE receiver_id='$loggedIn_token' AND notify_status='0' ";
                $notify_update_query_run = mysqli_query($con, $notify_update_query);
                {
                    if($notify_update_query_run)
                    {
                    ?>  
                        <script>
                            refresh = 1;
                        </script>
                        <audio autoplay src="notify.mp3"></audio>
                    <?php
                    }
                }
            }
        ?>
    </div>
</div>

<?php include('includes/footer.php') ?>

