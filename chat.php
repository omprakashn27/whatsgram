<?php include('authentication.php');  ?>
<?php include('includes/header.php') ?>

<div class="d">
    <?php include('includes/chat-conversation.php'); ?>
</div>
<!-- to check any new messages available -->
<!-- This page is visible in small devices like Mobile Phones -->

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
