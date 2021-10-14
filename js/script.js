
$(document).ready(function () {

    refresh_chat();
    var refresh = 0;
    setInterval(() => {
        $('.parent-check').load(' .check-notified');
        
        $('.chatload').load(' .conversation');
        if($('.searchFriend').val() == '')
        {
            $('#userlist').load(' #userlist-inner');
        }
        if(refresh == 1)
        {
            var c = $(' .chat-section');
            c.scrollTop(c.prop("scrollHeight"));
            refresh = 0;
        }
    }, 1000);

    // Search from recent chats / History
    $('.searchFriend').keyup(function (e) { 
        var frndname = $('.searchFriend').val();

        $.ajax({
            method: "POST",
            url: "chatcode.php",
            data: {
                'searchForFriend' : true,
                'friendName': frndname,
            },
            success: function (response) {
                $('#load-laptop-sidebar').html(response);
            }
        });
    });

    // Search new friends
    $('#search_user').keyup(function (e) { 
        var user_name = $('#search_user').val();
        if(user_name == '')
        {
            $('#searchResults').html('');
        }
        else
        {
            $.ajax({
                method: "POST",
                url: "code.php",
                data: {
                    'searchUserBtn' : true,
                    'searchUserNname': user_name,
                },
                success: function (response) {
                    $('#searchResults').html(response);
                }
            });
        }
    });

    // Unsend friend requests
    $(document).on('click', '.unSendReq', function () {
        var unsend_id = $('.unSendReq').val();

        $.ajax({
            method: "POST",
            url: "code.php",
            data: {
                'unsendrequest_btn':true,
                'unsendId': unsend_id,
            },
            success: function (response) {
                swal("", response, "success");
                $('#searchResults').html('');
                window.location.reload();
            }
        });
    });

    // Open the side Nabvar
    $('.openNav').click(function (e) { 
        e.preventDefault();
        $('#mySidenav').addClass('customomsize');
    });

    // Close the side Navbar
    $('.closeNav').click(function (e) { 
        e.preventDefault();
        $('#mySidenav').removeClass('customomsize');
    });

    // To scroll down the chat 
    function refresh_chat()
    {
        var c = $('.chat-section');
        c.scrollTop(c.prop("scrollHeight"));
    }

    // To check username is available or not
    $(".username").on({
        keyup: function(e) {
            if (e.which === 32){
                return false;
            }

            var username = $('.username').val();
            var data = {
                'chk_user_click':true, 
                'username':username, 
            };
            $.ajax({
                method: "POST",
                url: "code.php",
                data: data,
                success: function (response) {
                    $('.user-avail').text(response);
                }
            });
        },
        change: function() {
          this.value = this.value.replace(/\s/g, "");
        }, 
    });

    // To delete/cancel friend request
    $('.delbtn').on('click',function (e) { 
        e.preventDefault();
        var oka = $(this).closest('.alldata').find('.delbtn').text('Deleted');
        var acc_id = $(this).closest('.alldata').find('.delbtn').val();
        var data = {
            'delbtn_btn':1,
            'friend_id':acc_id,
        }
        $.ajax({
            method: "POST",
            url: "code.php",
            data: data,
            success: function (response) {
                swal("Success","Request Deleted Successfully","success");
                windows.location.reload();
            }
        });


    });
    
    // Accept Friend Request
    $('.approvbtn').on('click',function (e) { 
        e.preventDefault();
        var oka = $(this).closest('.alldata').find('.approvbtn').text('Accepted');
        var acc_id = $(this).closest('.alldata').find('.approvbtn').val();
        var data = {
            'accept_btn':1,
            'friend_id':acc_id,
        }
        $.ajax({
            method: "POST",
            url: "code.php",
            data: data,
            success: function (response) {
                window.location.reload();
            }
        });
    });

    // Send Friend Request
    $(document).on('click', '.add-btn', function (e) {
        e.preventDefault();
        var ok = $(this).closest('.alldata').find('.add-btn').text('Requested');

        var req_id = $(this).closest('.alldata').find('.add-btn').val();
        var data = {
            'frnd_req_btn':1,
            'recievers_id':req_id,
        }
        $.ajax({
            method: "POST",
            url: "code.php",
            data: data,
            success: function (response) {
                var res= JSON.parse(response);
                swal("", res.msg , res.icon);
            }
        });


    });

    // Send Message
    $('.messsendbt').click(function (e) { 
        e.preventDefault();
        $('.messsendbt').attr("disabled", true);

        var thisClicked = $(this);

        var ms_friend = $('.dufhsiufsdfpiuf').val();   
        var messsage = $('.mgbxs').val();  
        var data = {
            'messsendbt':true,
            'fid':ms_friend,
            'messsage': messsage,
        } 
        $.ajax({
            method: "POST",
            url: "chatcode.php",
            data: data,
            success: function (response) {
                $('.chat-section').load(' .chatload');
                $('#userlist').load(' #load-laptop-sidebar');
                $('.mgbxs').val('');
                $('.messsendbt').attr("disabled", false);
                refresh = 1;
            }
        });

    });

    // To block a user/contact
    $('#blockContact').click(function (e) { 
        e.preventDefault();
        
        var blockFrndId = $('.blockfrndid').val();

        data = {
            'blockFrndid':blockFrndId,
            'block_btn': true 
        }
        $.ajax({
            method: "POST",
            url: "chatcode.php",
            data: data,
            success: function (response) {
                $('.blockuser').html('');
                $('.blockuser').html('Unblock');
                window.location.reload();
            }
        });
    });

    // To unblock a user/contact
    $('#unblockContact').click(function (e) { 
        e.preventDefault();
        
        var unBlockFrndId = $('.blockfrndid').val();

        data = {
            'unBlockFrndId':unBlockFrndId,
            'unBlockFrndId_btn': true 
        }
        $.ajax({
            method: "POST",
            url: "chatcode.php",
            data: data,
            success: function (response) {
                $('.Unblockuser').html('');
                $('.Unblockuser').html('Block');
                window.location.reload();
            }
        });
    });
    
    // To delete/Clear Chat
    $('#deleteChat').click(function (e) { 
        e.preventDefault();

        var frndtoken = $('#usertoken').val();
        $.ajax({
            method: "POST",
            url: "chatcode.php",
            data: {
                'frndtoken' : frndtoken,
                'clearChat': true,
            },
            success: function (response) {
                var res= JSON.parse(response);
                swal("", res.msg , res.icon)
            }
        });
    });
    
});



