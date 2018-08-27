$(document).ready(function() {

    var privacy, color;
    var friends = [];
    //Update all fields that should display the user's name
    $("#profileTitle").html(getCookie("name") + "'s Profile");
    $("#bioTitle").html(getCookie("name") + "'s Bio");
    updateProfileImage();
    updatePreferences();
    getBio();


    /**
     *Seaches document.cookie for the given cookie name and returns the value of that cookie.
     * More modular version of older cookie search functions
     *
     *  @param cookieName
     */
    function getCookie(cookieName){
        var name = cookieName + '=';
        var cookieArray = document.cookie.split(';');
        for(var i = 0; i < cookieArray.length; i++){
            var currentValue = cookieArray[i];
            while(currentValue.charAt(0) == ' '){
                currentValue = currentValue.substring(1);
            }
            if(currentValue.indexOf(name) == 0){
                return currentValue.substring(name.length, currentValue.length);
            }
        }
        return "";
    }

    function updateProfileImage(){
        $.get("../../php_resources/retrieve_content/get_user_image.php",
            function(data){
                $("#profilePicture").attr("src","http://" + data);
            });
    }

    //TODO

    $("#submitPic").click(function(){
        alert("clicked");
        var formObj = $("#newPicture")[0];
        var formData = new FormData(formObj);
        $.ajax({
            url: "../../php_resources/create_content/upload_profile_image.php",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data){
                alert("chris");
                updateProfileImage();
            },
            error: function (data) {
                alert("no chris");
            }

        });


    });


    //Handles Update Bio Button
    $("#updateBio").click(function () {
        $("#newBio").show();
        $("#submitBio").show();
        $("#closeBio").show();
        $("#updateBio").hide();

    });

    //Handles Close Button
    $("#closeBio").click(function () {
        $("#newBio").hide();
        $("#submitBio").hide();
        $("#closeBio").hide();
        $("#updateBio").show();

    });

    //Handles Submit Button
    $("#submitBio").click(function () {
        $("#newBio").hide();
        $("#submitBio").hide();
        $("#closeBio").hide();
        $("#updateBio").show();

        //Posts the new bio to the server
        $.post("../../php_resources/user_accts/profile/set_bio.php",
            {
                bio: $("#newBio").val()
            });


        //Clears the entry field
        $("#newBio").val("");
        //Updates the bio without a refresh
        getBio();
    });


    //Gets the logged in user's bio and displays it
    function getBio() {
        $.get("../../php_resources/retrieve_content/get_user_bio.php",
            function (JSONObject) {
                var bio = JSON.parse(JSONObject);
                $("#profileBio").html(bio[0].bio);
            });
    }


    //Gets all posts by logged in user and displays the three most recent posts.
    //Potential to link to posts if desired (eg home_page's featured boards)
    $.get("../../php_resources/retrieve_content/get_user_posts.php",
        function (JSONObject){
            var posts = JSON.parse(JSONObject);
            if(posts.length < 3){
                if(posts.length == 0){
                    $("#profilePosts").append('<tr>');
                    $("#profilePosts").append('<th> No posts to display. Go make some!</th>');
                    $("#profilePosts").append('</tr>');
                }
                else {
                    $("#profilePosts").append('<tr>');
                    for (var i = 0; i < posts.length; i++) {
                        // var boardUrl = featPosts[i].board_url;
                        // var linkAddress = '../html/threadview.html?' + boardUrl;
                        $("#profilePosts").append('<th>' + posts[i].title + '</th>');

                    }
                    $("#profilePosts").append('</tr>');
                    $("#profilePosts").append('<tr>');
                    for (var i = 0; i < posts.length; i++) {
                        //var boardUrl = featPosts[i].board_url;
                        // var linkAddress = '../html/threadview.html?' + boardUrl;
                        $("#profilePosts").append('<td width = \"400\" >' + posts[i].content + '</td>');
                    }
                    $("#profilePosts").append('</tr>');
                }
            }
            else{
                $("#profilePosts").append('<tr>');
                for (var i = 0; i < 3; i++) {
                    // var boardUrl = featPosts[i].board_url;
                    // var linkAddress = '../html/threadview.html?' + boardUrl;
                    $("#profilePosts").append('<th>' + posts[i].title + '</th>');

                }
                $("#profilePosts").append('</tr>');
                $("#profilePosts").append('<tr>');
                for (var i = 1; i < 4; i++) {
                    //var boardUrl = featPosts[i].board_url;
                    // var linkAddress = '../html/threadview.html?' + boardUrl;
                    $("#profilePosts").append('<td width = \"400\" >' + posts[i].content  + '</td>');
                }
                $("#profilePosts").append('</tr>');
            }

        });



    //Gets friends and turns ids into usernames
    $.get("../../php_resources/retrieve_content/get_friends_list.php",
        function (JSONObject) {
            var uids = JSON.parse(JSONObject);

            for (var i = 0; i < uids.length; i++) {
                var temp;
                $.post("../../php_resources/retrieve_content/get_username_from_id.php",
                    {
                        uid: uids[i]
                    },
                    function (JSONObject) {
                        temp = JSON.parse(JSONObject);
                        friendsArrayPush(temp[0].username);
                    });

            }


        });



    //pushes information from ajax get request to the array
    //Only displays last array  value pushed
    function friendsArrayPush(pushedValue){
        friends.push(pushedValue.toString());
        $("#profileFriends").html("");
        friendsList();
    }

    //Handles display of top friends
    function friendsList()
    {
        if (friends.length < 3) {
            if (friends.length == 0) {
                $("#profileFriends").append('<tr>');
                $("#profileFriends").append('<th>No friends to display. Go make some!</th>');
                $("#profileFriends").append('</tr>');
            }
            else {
                for (var i = 0; i < friends.length; i++) {
                    $("#profileFriends").append('<tr>');
                    $("#profileFriends").append('<td>' + friends[i] + '</td>');
                    $("#profileFriends").append('</tr>');
                }
            }
        }
        else {
            for (var i = 0; i < 3; i++) {
                $("#profileFriends").append('<tr>');
                $("#profileFriends").append('<td>' + friends[i] + '</td>');
                $("#profileFriends").append('</tr>');
            }
        }
    }

    //Sets the user's privacy preference to public
    $("#privacyFalse").click(function(){
        $.get("../../php_resources/user_accts/preferences/make_profile_public.php",
            function(){
                updatePreferences();

        });
    });

    //Sets the user's privacy preference to private
    $("#privacyTrue").click(function(){
        $.get("../../php_resources/user_accts/preferences/make_profile_private.php",
            function(){
                updatePreferences();
            });
    });

    //Updates the user's color preference
    $("#colorPickerUpdate").click(function() {
        $.post("../../php_resources/user_accts/preferences/set_background_color.php",
            {
                color: $("#colorPicker").val()
            }, function () {
                updatePreferences();
            });
    });

    //Updates all fields relating to the preferences on the page
    function updatePreferences(){

        $.get("../../php_resources/retrieve_content/get_user_preferences.php",
            function(JSONObject) {

                var temp = JSON.parse(JSONObject);
                // alert(temp[0].background_color);
                privacy = temp[0].private;
                color = temp[0].background_color;
                $(".profile").css("background-color", color);
                if(privacy == 0){
                    $("#userPrivacy").html("Your privacy is currently set to public!");
                }
                else{
                    $("#userPrivacy").html("Your privacy is currently set to private!");
                }
            });
    }

    //Deletes the user's account
    $("#delete").click(function() {
        $.post("../../php_resources/user_accts/deleting/delete_acct.php",
            {
                iamsure: 'chris'
            },
            function(){
                alert('Account Deleted');
                $('#logout-bar').click();
            });
    });
});