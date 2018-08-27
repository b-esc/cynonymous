$(document).ready(function(){

    //Update all fields that should display the user's name
    $("#profileTitle").html(getCookie("name") + "'s Posts");
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

    //Gets all posts by logged in user and displays them
    //Potential to link to posts if desired (eg home_page's featured boards)
    $.get("../../php_resources/retrieve_content/get_user_posts.php",
        function (JSONObject){
        var color;
            var posts = JSON.parse(JSONObject);
                if(posts.length == 0){
                    $("#postsList").append('<tr>');
                    $("#postsList").append('<th> No posts to display. Go make some!</th>');
                    $("#postsList").append('</tr>');
                }
                else {

                    for (var i = 0; i < posts.length; i++) {
                        if(i%2 == 0){
                            color = "white";
                        }
                        else{
                            color = "lightgrey";
                        }
                        $("#postsList").append('<tr>');
                        // var boardUrl = featPosts[i].board_url;
                        // var linkAddress = '../html/threadview.html?' + boardUrl;
                        $("#postsList").append('<th style=\" background-color: ' + color + '\">' + posts[i].title + '</th>');

                        $("#postsList").append('</tr>');
                        $("#postsList").append('<tr>');
                        //var boardUrl = featPosts[i].board_url;
                        // var linkAddress = '../html/threadview.html?' + boardUrl;
                        $("#postsList").append('<td style=\"background-color: ' + color + '\">' + posts[i].content + '</td>');

                        $("#postsList").append('</tr>');
                    }

                }


        });

    //Updates all fields relating to the preferences on the page
    function updatePreferences(){

        $.get("../../php_resources/retrieve_content/get_user_preferences.php",
            function(JSONObject) {

                var temp = JSON.parse(JSONObject);
                var color = temp[0].background_color;
                $(".page").css("background-color", color);
            });
    }
});