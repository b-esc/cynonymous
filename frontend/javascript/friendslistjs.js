$(document).ready(function() {
    var color;
    var friends = [];
    $("#profileTitle").html(getCookie("name") + "'s Friends");
    updateFriends();
    updatePreferences();
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


    //Allows the user to add a friend
    $("#addFriend").click(function(){
       $.post("../../php_resources/user_accts/friends/add_friend.php",
           {
               friend_id: $("#friendField").val()
           });
       $("#friendField").val("");
        $("#friendsList").html("");
        friends.length = 0;
       updateFriends();
    });

    //Allows the user to remove a friend
    $("#removeFriend").click(function(){
        $.post("../../php_resources/user_accts/friends/remove_friend.php",
            {
                friend_id: $("#friendField").val()
            });
        $("#friendField").val("");
        $("#friendsList").html("");
        friends.length = 0;
        updateFriends();
    });

  //Updates the displayed friends list
    function updateFriends() {
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
    }

    //pushes information from ajax get request to the array
    //Only displays last array  value pushed
    function friendsArrayPush(pushedValue){
        friends.push(pushedValue.toString());
        $("#friendsList").html("");
        friendsList();
    }
    //Handles the display of the friends list
    function friendsList(){
        if (friends.length == 0) {
            $("#friendsList").append('<tr>');
            $("#friendsList").append('<th>No friends to display. Go make some!</th>');
            $("#friendsList").append('</tr>');
        }
        else {
            for (var i = 0; i < friends.length; i++) {
                $("#friendsList").append('<tr>');
                $("#friendsList").append('<td>' + friends[i] + '</td>');
                $("#friendsList").append('</tr>');
            }
        }

    }

    //Updates all fields relating to the preferences on the page
    function updatePreferences(){

        $.get("../../php_resources/retrieve_content/get_user_preferences.php",
            function(JSONObject) {

                var temp = JSON.parse(JSONObject);
                color = temp[0].background_color;
                $(".page").css("background-color", color);
            });
    }
});