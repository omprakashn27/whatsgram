<?php
include('dbcon.php');
session_start();

// Sending Message
if(isset($_POST['messsendbt']))
{
    $auth_token = $_SESSION['auth']['user_token'];
    $friend_token = $_POST['fid'];
    $messsage = $_POST['messsage'];
    if($messsage == NULL)
    {
        $_SESSION['status'] = "Please type the message to be sent!";
        header('Location: index.php');
    }   
    else
    {
        $check_user_query = "SELECT * FROM users WHERE token='$friend_token' LIMIT 1";
        $check_user_querys_run = mysqli_query($con, $check_user_query);
        $frnd_user_data = mysqli_fetch_array($check_user_querys_run);
        $loggedinID = $_SESSION['auth']['user_id'];
       
        if(mysqli_num_rows($check_user_querys_run) > 0)
        {
            $frndkaId = $frnd_user_data['id'];
            $frnd_check_query = "SELECT * FROM friends WHERE users_id='$loggedinID' AND friend_id='$frndkaId' AND status='0' ";
            $frnd_check_query_run = mysqli_query($con, $frnd_check_query);
            if(mysqli_num_rows($frnd_check_query_run) > 0)
            {
                $msg_ins_query = "INSERT INTO messages (sender_id,receiver_id,user_message) VALUES ('$auth_token','$friend_token','$messsage')";
                $msg_ins_query_run = mysqli_query($con,$msg_ins_query);
                if($msg_ins_query_run)
                {
                }
                else{
                    echo "something went wrong!!!";
                }
            }
        }
    }
}

// Clear Chat
if(isset($_POST['clearChat']))
{
    $auth_id = $_SESSION['auth']['user_token'];
    $fr_id = $_POST['frndtoken'];

    //To check if there is chat history
    $msg_query = "SELECT * FROM (SELECT * FROM messages WHERE ((sender_id='$auth_id' AND receiver_id = '$fr_id' AND sender_delete='0') 
    OR (receiver_id='$auth_id' AND sender_id='$fr_id' AND receiver_delete='0')) AND status != '1' ORDER BY id DESC LIMIT 300) sub ORDER BY id ASC";
    $msg_query_run = mysqli_query($con, $msg_query);
    if(mysqli_num_rows($msg_query_run) > 0)
    {
        $clear_chats_query = "UPDATE messages SET sender_delete = if(sender_id='$auth_id' AND receiver_id = '$fr_id' AND sender_delete='0', 1, sender_delete), 
        receiver_delete=if(receiver_id='$auth_id' AND sender_id = '$fr_id' AND receiver_delete='0', 1, receiver_delete) ";
        $clear_chats_query_run = mysqli_query($con, $clear_chats_query);
        if($clear_chats_query_run)
        {
            $data = [
                'msg' => "Chat Cleared",
                'icon' => "success"
            ];
            echo json_encode($data);
        }
        else
        {
            $data = [
                'msg' => "Something Went Wrong",
                'icon' => "error"
            ];
            echo json_encode($data);
        }
    }
    else
    {
        $data = [
            'msg' => "No chats to delete",
            'icon' => "warning"
        ];
        echo json_encode($data);
    }
}

// Search from recent chats
if(isset($_POST['searchForFriend']))
{
    $friendName = $_POST['friendName'];
    $auth_token = $_SESSION['auth']['user_token'];
    $frnd_cht_hstry = "SELECT m.sender_id, m.receiver_id, m.user_message,m.sent_time, m.status,m.notify_status, u.id,u.token, u.username FROM messages m,users u 
    WHERE  ((m.sender_id='$auth_token' AND sender_delete='0') OR (m.receiver_id='$auth_token' AND m.receiver_delete='0')) AND u.username LIKE '%$friendName%' ORDER BY m.sent_time DESC";
    $frnd_cht_hstry_run = mysqli_query($con,$frnd_cht_hstry);
    $shown_users = [];
    
    if(mysqli_num_rows($frnd_cht_hstry_run) > 0)
    {
        foreach($frnd_cht_hstry_run as $frschat)   
        {
            if($frschat['token'] == $auth_token)
            {
                    
            }
            else
            {
                if(!in_array($frschat['token'], $shown_users))
                {
                    array_push($shown_users, $frschat['token']);
                    $dataom = '
                        <div class="laptop-view d-none d-sm-block parentToken">
                            <a href="index.php?frusrkaid='.$frschat['token'].'" class="userlistname">
                                <input type="hidden" value='.$frschat['token'].' class="utokenid">
                                <div class=" ">
                                    <div class="userlist-row">
                                        <div class="userlist-img">
                                            <img src="images/user.jpg" alt="Image">
                                        </div>';
                                        if($frschat['notify_status'] == '1' && $frschat['receiver_id'] == $auth_token) 
                                        { 
                                            $sender_token = $frschat['sender_id'];
                                            $unread_msgs = "SELECT id FROM messages WHERE receiver_id='$auth_token' AND sender_id='$sender_token' AND notify_status='1' ";
                                            $unread_msgs_run = mysqli_query($con, $unread_msgs);
                                            $total_no = mysqli_num_rows($unread_msgs_run);

                                            $dataom .='<span id="unread-msg">'. $total_no .'</span>';

                                        }
                                        $dataom .='<div class="userlist-info">
                                            <h5>'. $frschat['username'] .'</h5>
                                            <h6>';
                                                if($frschat['sender_id'] == $auth_token)
                                                {
                                                    $dataom .='You: ';
                                                }
                                                $dataom .= $frschat['user_message'].'
                                                <span class="float-right msgTime">
                                                    '. substr($frschat['sent_time'],11,-3) .' 
                                                </span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="mobile-view d-block d-sm-none parentToken">
                            <a href="chat.php?frusrkaid='.$frschat['token'].'" class="userlistname">
                                <input type="hidden" value='.$frschat['token'].' class="utokenid">
                                <div class=" ">
                                    <div class="userlist-row">
                                        <div class="userlist-img">
                                            <img src="images/user.jpg" alt="Image">
                                        </div>';
                                        if($frschat['notify_status'] == '1' && $frschat['receiver_id'] == $auth_token) 
                                        { 
                                            $sender_token = $frschat['sender_id'];
                                            $unread_msgs = "SELECT id FROM messages WHERE receiver_id='$auth_token' AND sender_id='$sender_token' AND notify_status='1' ";
                                            $unread_msgs_run = mysqli_query($con, $unread_msgs);
                                            $total_no = mysqli_num_rows($unread_msgs_run);

                                            $dataom .='<span id="unread-msg">'. $total_no .'</span>';

                                        }
                                        $dataom .='<div class="userlist-info">
                                            <h5>'. $frschat['username'] .'</h5>
                                            <h6>';
                                                if($frschat['sender_id'] == $auth_token)
                                                {
                                                    $dataom .='You: ';
                                                }
                                                $dataom .= $frschat['user_message'].'
                                                <span class="float-right msgTime">
                                                    '. substr($frschat['sent_time'],11,-3) .' 
                                                </span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>';
                    echo $dataom;
                }
            }
        }
    }
}

// Block user
if(isset($_POST['block_btn']))
{
    $auth_id = $_SESSION['auth']['user_id'];
    $friend_id = $_POST['blockFrndid'];
    
    $blockContact_query = "UPDATE friends SET status='1', blocker_id='$auth_id' WHERE blocker_id='0' AND (users_id='$auth_id' AND friend_id = '$friend_id') OR (friend_id='$auth_id' AND users_id='$friend_id')  ";
    $blockContact_query_run = mysqli_query($con, $blockContact_query);
    
}

// Unblock user
if(isset($_POST['unBlockFrndId_btn']))
{
    $auth_id = $_SESSION['auth']['user_id'];
    $friend_id = $_POST['unBlockFrndId'];
    
    $blockContact_query = "UPDATE friends SET status='0', blocker_id='0' WHERE blocker_id='$auth_id' AND (users_id='$auth_id' AND friend_id = '$friend_id') OR (friend_id='$auth_id' AND users_id='$friend_id') ";
    $blockContact_query_run = mysqli_query($con, $blockContact_query);
    
}



?>
