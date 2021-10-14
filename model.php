<?php include('dbcon.php'); 

function redirect($pagename, $msg)
{
    $_SESSION['status'] = $msg;
    header("Location: $pagename");
    exit(0);
}


function get($table,$con)
{
    $query = "SELECT * FROM $table "; 
    $query_run = mysqli_query($con, $query);
    if(mysqli_num_rows($query_run) > 0)
    {
        if(mysqli_fetch_array($query_run) > 0)
        {
            return $query_run;
        }
    }
    else{
        return $result = "No record found";
    }
}

function getauth($table,$con)
{
    $auth_id = $_SESSION['auth']['user_id'];
    $query = "SELECT * FROM $table where sender_id= '$auth_id'"; 
    $query_run = mysqli_query($con, $query);
    if(mysqli_num_rows($query_run) > 0)
    {
        if(mysqli_fetch_array($query_run) > 0)
        {
            return $query_run;
        }
    }
    else{
        return $result = "No record found";
    }
}

function random_generate()
{
    // Character List to Pick from
    $chrList = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Minimum/Maximum times to repeat character List to seed from
    $chrRepeatMin = 1; // Minimum times to repeat the seed string
    $chrRepeatMax = 10; // Maximum times to repeat the seed string

    // Length of Random String returned
    $chrRandomLength = 18;

    // The ONE LINE random command with the above variables.
    $token = substr(str_shuffle(str_repeat($chrList, mt_rand($chrRepeatMin,$chrRepeatMax))), 1, $chrRandomLength);
    return $token;
}

?>