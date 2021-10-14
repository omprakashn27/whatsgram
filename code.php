<?php
session_start();
include('model.php');
include('dbcon.php');

// username availability
if(isset($_POST['chk_user_click']))
{
    $username = $_POST['username'];
    
    $query = "SELECT * FROM users where username='$username' ";
    $query_run = mysqli_query($con, $query); 

    if(mysqli_num_rows($query_run) > 0)
    {
        echo "Username already taken";
    }
    else
    {
        echo "This username is avaialable";
    }
}

// update profile
if(isset($_POST['update_profile']))
{
    $auth_id = $_SESSION['auth']['user_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $uname = $_POST['uname'];
    $about = $_POST['about'];

    // if the username is not changed
    $username_check = "SELECT * FROM users WHERE username='$uname' AND id='$auth_id' LIMIT 1";
    $userquery_run = mysqli_query($con, $username_check);

    $row = mysqli_fetch_array($userquery_run);
    $usremail = $row['email'];

    if(mysqli_num_rows($userquery_run) > 0)
    {
        // this query will run when the username is not changed
        $upd_query = "UPDATE users SET fname='$fname', lname='$lname', phone='$phone',email='$usremail', about='$about' WHERE id='$auth_id' ";
        $upd_query_run = mysqli_query($con, $upd_query);
        if($upd_query_run)
        {
            header('location: index.php');
            $_SESSION['status'] = "Profile updated Successfully";
        }   
        else{
            $_SESSION['status'] = "Something went Wrong!!";
        }
    }
    else
    {
        // if user has given a new username
        $username_check2 = "SELECT * FROM users WHERE username='$uname'";
        $username_check2_run = mysqli_query($con, $username_check2);
        if(mysqli_num_rows($username_check2_run) > 0)
        {
            redirect("index.php","Username already taken");
        }
        else{

            // this query will run when the username is changed
            $upd_query = "UPDATE users SET fname='$fname', lname='$lname', username='$uname', phone='$phone', about='$about' WHERE id='$auth_id' ";
            $upd_query_run = mysqli_query($con, $upd_query);
            if($upd_query_run)
            {
                header('location: index.php');
                $_SESSION['status'] = "Profile updated Successfully";
            }   
            else{
                $_SESSION['status'] = "Something went Wrong!!";
            }
        }
    }
}

// user login
if(isset($_POST['login_btn']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $query = "SELECT * FROM users where email='$email' AND password='$password' ";
    $query_run = mysqli_query($con, $query); 
    
    $check_row = mysqli_num_rows($query_run) > 0;
    $check_available = mysqli_fetch_array($query_run);
    foreach($query_run as $row)
    {
        $id = $row['id'];
        $name = $row['username'];
        $email = $row['email'];
        $token = $row['token'];
    }

    if($check_row)
    {
        if($check_available)
        {

            $_SESSION['auth'] = [
                'user_id' => $id,
                'user_name' => $name,
                'user_email' => $email,
                'user_token' => $token,
            ];
            $_SESSION['login'] = "true";
            $_SESSION['status'] = "Logged In Successfully";
            header('location: index.php');
        }
    }
    else
    {
        $_SESSION['status'] = "Invalid credentials";
        header('location: login.php');
    }
}

// user registeration
if(isset($_POST['reg_btn']))
{
    $username = $_POST['username'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $token = random_generate();
    if($password != $cpassword)
    {
        redirect("register.php", "Passords do not match");
    }
    else
    {
        $check_email = "SELECT * FROM users WHERE email='$email'";
        $check_email_run = mysqli_query($con,$check_email);

        $check_username = "SELECT * FROM users WHERE username='$username'";
        $check_username_run = mysqli_query($con,$check_username);
        
        if(mysqli_num_rows($check_email_run) > 0)
        {
            redirect("register.php", "Email already exists");
        }
        else if(mysqli_num_rows($check_username_run) > 0)
        {
            redirect("register.php", "Username already exists");
        }
        else
        {
            $reg_query = "INSERT INTO users (username,fname,lname,phone,email,password,token) VALUES ('$username','$fname','$lname','$phone','$email','$password','$token')"; 
            $reg_query_run = mysqli_query($con, $reg_query);

            if($reg_query_run)
            {
                redirect("login.php", "Registered Successfully! Login to continue");
            }
            else{
                redirect("register.php", "Something went wrong");
            }
        }
    }
}

// delete friend request
if(isset($_POST['delbtn_btn']))
{
    $auth_id = $_SESSION['auth']['user_id'];
    $friend_id = $_POST['friend_id'];
    $queery = "UPDATE requests SET status='2' WHERE receiver_id='$auth_id' and sender_id='$friend_id'";
    $queery_run = mysqli_query($con, $queery);
}

// accept friend request
if(isset($_POST['accept_btn']))
{
    $auth_id = $_SESSION['auth']['user_id'];
    $friend_id = $_POST['friend_id'];
    $today_date = date("Y-m-d");

    $existing_req_check = "SELECT * FROM friends WHERE ((users_id='$friend_id' AND friend_id='$auth_id') OR (users_id='$auth_id' AND friend_id='$friend_id')) AND status='0' ";
    $existing_req_check_run = mysqli_query($con, $existing_req_check);
    if(mysqli_num_rows($existing_req_check_run) == 0)
    {
        $queery = "UPDATE requests SET status='1',accepted_date= '$today_date' WHERE receiver_id='$auth_id' and sender_id='$friend_id'";
        $queery_run = mysqli_query($con, $queery);
        if($queery_run)
        {
            $queeryy = "INSERT INTO friends (users_id,friend_id) VALUES('$auth_id','$friend_id')";
            $queeryy_run = mysqli_query($con, $queeryy);
            $queeryy2 = "INSERT INTO friends (friend_id,users_id) VALUES('$auth_id','$friend_id')";
            $queeryy2_run = mysqli_query($con, $queeryy2);
            $_SESSION['status'] = "Request Accepted Successfully";
        }
        else{
            echo "Something went wrong!!";
        }
    }
    else{
        $remove_duplicate_request = "DELETE FROM requests WHERE status='0' AND sender_id='$friend_id' and receiver_id='$auth_id'";
        $remove_duplicate_request_run = mysqli_query($con, $remove_duplicate_request);
    }
}

// send friend request
if(isset($_POST['frnd_req_btn']))
{
    $sender_id = $_SESSION['auth']['user_id'];
    $receiver_id = $_POST['recievers_id'];

    $req_check = "SELECT sender_id,receiver_id FROM requests WHERE sender_id='$sender_id' AND receiver_id='$receiver_id' AND status='0' ";
    $req_check_run = mysqli_query($con, $req_check);
    if(mysqli_num_rows($req_check_run) > 0)
    {
        $data = [
            'msg' => "Already Request Sent",
            'icon' => "warning"
        ];
        echo json_encode($data);
    }
    else
    {
        $query = "INSERT INTO requests (sender_id,receiver_id) VALUES ('$sender_id','$receiver_id')";
        $query_run = mysqli_query($con, $query);
        $data = [
            'msg' => "Request Sent",
            'icon' => "success"
        ];
        echo json_encode($data);
    }
}

// Search New friends
if(isset($_POST['searchUserBtn']))
{
    $authid = $_SESSION['auth']['user_id'];
    $search = $_POST['searchUserNname'];
    $query = "SELECT * FROM users WHERE id NOT IN(SELECT receiver_id FROM requests WHERE sender_id='$authid' AND status='1') AND CONCAT(username,fname,lname) LIKE '%$search%' ";
    $query_run = mysqli_query($con, $query);
    
    if(mysqli_num_rows($query_run) > 0)
    {
        if(mysqli_fetch_array($query_run) > 0)
        {
            $searchedUsers = "";
            
            foreach($query_run as $usr)
            {
                if($usr['id'] != $authid)
                {
                    $searchedUsers .='
                    <div class="userlist-row alldata bg-f5f5f5">
                        <div class="row">
                            <div class="col-md-9 col-8">
                                <div class="userlist-img">
                                    <img src="images/user.jpg" alt="Image">
                                </div>
                                <div class="userlist-info">';

                                    $searchedUsers .= '
                                    <h5>'.$usr['username'].'</h5>
                                    <h6>'.$usr['fname'].' '.$usr['lname'].'</h6>
                                </div>
                            </div>
                            <div class="col-md-3 col-4 my-auto">';
                                $userKaId = $usr['id'];
                                $alreadyRequested_query = "SELECT * FROM requests WHERE sender_id='$authid' AND receiver_id='$userKaId' AND status='0' ";
                                $alreadyRequested_query_run = mysqli_query($con, $alreadyRequested_query);

                                $alreadyFrnds_query = "SELECT * FROM friends WHERE (users_id='$userKaId' AND friend_id='$authid') AND status='0' ";
                                $alreadyFrnds_query_run = mysqli_query($con, $alreadyFrnds_query);
                                if(mysqli_num_rows($alreadyFrnds_query_run) > 0)
                                {

                                    $searchedUsers .= '
                                    <a href="chat.php?frusrkaid='.$usr['token'].'" type="button" class="d-block d-sm-none float-right btn bg-primary text-white btn-sm px-2 f-12">Friends</a>
                                    <a href="index.php?frusrkaid='.$usr['token'].'" type="button" class="d-none d-sm-block float-right btn bg-primary text-white btn-sm px-2 f-12">Friends</a>';
                                }
                                else if(mysqli_num_rows($alreadyRequested_query_run) > 0)
                                {
                                    $searchedUsers .= '<button type="button" value="'.$usr['id'].'" class="float-right btn bg-info text-white btn-sm px-2 f-12 unSendReq">Requested</button>';
                                }
                                else
                                {
                                    $searchedUsers .='<button value=" '.$usr['id'].'" class="float-right btn bg-green text-white btn-sm px-2 f-12 reqbtn add-btn">Add Friend</button>';
                                }
                                    
                            $searchedUsers .='
                            </div>
                        </div>
                    </div>';
                }   
            }
                            
            echo $searchedUsers;
        }
    }
}

// Unsend friend request
if(isset($_POST['unsendrequest_btn']))
{
    $usendId = $_POST['unsendId'];
    $auth_id = $_SESSION['auth']['user_id'];

    $check_request_query = "SELECT * FROM requests WHERE sender_id='$auth_id' AND receiver_id='$usendId' AND status='0' ";
    $check_request_query_run = mysqli_query($con, $check_request_query);
    if(mysqli_num_rows($check_request_query_run) > 0)
    {
        $unSend_query = "DELETE FROM requests WHERE sender_id='$auth_id' AND receiver_id='$usendId'  AND status='0' ";
        $unSend_query_run = mysqli_query($con,$unSend_query);

        if($unSend_query_run)
        {
            echo "Request Unsent";
        }
    }
}
?>


