//chances are you'll want to remove all the dummy functions that were commented for the assignment and make your own, jordan
$(document).ready(function(){
    var safariTimer;

    //Checks if safari mode is currently active
    if(getCookie("safari") == "true"){
        $("#beginSafari").hide();
        $("#endSafari").show();
        alert("New Thread!\nPress \"End Safari\" to stop.");
        safariTimer = setTimeout(goToRandomThread, 20000);
    }

    //If a username cookie is set, the page will begin using it immediately.
    //Login and signup are hidden and helloUser and logout are shown
    if(getCookie("check") == "chris"){
        $("#helloUser").append( " " + "<a href = \"../html/profilepage.html\">" + getCookie("name") + "!" + "</a>");
        $("#login-bar").hide();
        $("#signup-bar").hide();
        $("#logout-bar").show();
        $("#helloUser").show();
    }

    //Starts safari mode, a mode in which the website will automatically take users
    //to a random board after a set amount of time
    $("#beginSafari").click(function(){
        $("#beginSafari").hide();
        $("#endSafari").show();
        document.cookie = "safari=true";
        goToRandomThread();


    });

    //Ends Safari Mode
    $("#endSafari").click(function(){
        $("#endSafari").hide();
        $("#beginSafari").show();
        clearTimeout(safariTimer);
        document.cookie = "safari=";
    });


    //Takes the user to a random thread
    function goToRandomThread(){
        $.get("../../php_resources/retrieve_content/get_safari_thread.php",
            function(JSONObject){
                var test = JSON.parse(JSONObject);
                 location.href = "../html/postview.html?" + test[0].thread_url;
            });
    }

    //Toggles whether the login form is hidden or shown. Hides the sign up form if clicked.
    $("#login-bar").click(function(){
        $("#signup").hide();
        $("#login").toggle();

    });
    //Toggles whether the sign up form is hidden or shown. Hides the login form if clicked.
    $("#signup-bar").click(function(){
        $("#login").hide();
        $("#signup").toggle();

    });

    //Logs a user out
    $("#logout-bar").click(function(){
        //Posts to log a user out.
        //USE AN ABSOLUTE PATH TO THE WEB ROOT

        $.post('../../php_resources/user_accts/logout.php',
            {
            },
            function(status){
                alert("Logout " + status);
                deleteUsernameCookie();
                $('#homeLink')[0].click();
            });
        $("#logout-bar").hide();
        $("#helloUser").hide();
        $("#signup-bar").show();
        $("#login-bar").show();



    });

    //closes the login window
    $("#close-login").click(function(){
        $("#login").hide();
    });

    //closes the sign up window
    $("#close-sign-up").click(function(){
        $("#signup").hide();
    });



    //A function to pass the entered username and password to the php login file
    $("#submit-login").on("click", function(){

        //hides the login window
        $("#login").hide();


        //Posts username and password to login
        //USE AN ABSOLUTE PATH TO THE WEB ROOT
        $.post('../../php_resources/user_accts/login.php',
            {
                user: $("#user").val(),
                pass: $("#pass").val()
            },
            //A function to confirm the information entered was properly sent.
            function(JSONObject){
                var cookie = JSON.parse(JSONObject);
                if(cookie == null){
                    alert("Login Failed");
                }
                else {

                    alert("Logged in as " + cookie);
                    setUsernameCookie(cookie);
                    $("#helloUser").append( " " + "<a href = \"../html/profilepage.html\">" + getCookie("name") + "!" + "</a>");


                    //Hides the login and signup buttons, displays logout.
                    $("#login-bar").hide();
                    $("#signup-bar").hide();
                    $("#logout-bar").show();
                    $("#helloUser").show();
                }
            });
        //Clears the Username and Password fields.
        $("#user").val("");
        $("#pass").val("");

    });

    //A function to pass a new user's account information to the php account creation page
    $("#submit-sign-up").on("click", function(){

        //hides the sign up window
        $("#signup").hide();

        //Bad check to see if form is full. Working on a better one, but this
        //will work for the time being.
        if($("#firstName").val() == ""){
            alert("First Name field required");
            if($("#lastName").val() == ""){
                alert("Last Name field required");
                if ($("#email").val() == ""){
                    alert("Email field required");
                    if($("#newUserName").val() == ""){
                        alert("Username field required");
                        if($("#newPassword").val() == ""){
                            alert("Password field required");

                        }
                    }
                }
            }
        }

        else {
            // Posts the new username, password, email, and first and last name
            //USE AN ABSOLUTE PATH TO THE WEB ROOT

            $.post('../../php_resources/user_accts/create_acct.php',

                {
                    user: $("#newUserName").val(),
                    pass: $("#newPassword").val(),
                    email: $("#email").val(),
                    fname: $("#firstName").val(),
                    lname: $("#lastName").val()
                },
                //A function to confirm the information was properly sent.
                function (data, status) {
                    alert("Data: " + data + "\nStatus: " + status);


                });

            //AUTO LOGIN
            $("#user").val($("#newUserName").val());
            $("#pass").val($("#newPassword").val());
            $("#submit-login").click();

            // Clears all of the sign up fields.
            $("#newUserName").val("");
            $("#newPassword").val("");
            $("#email").val("");
            $("#firstName").val("");
            $("#lastName").val("");
        }
    });

    /**
     * Sets the username field in the cookie.
     * If any more fields are added to the cookie this will need to be modified accordingly
     * Potentially could be modified to set any value in the cookie.
     * @param newName
     *  cookie
     */

    function setUsernameCookie(newName){
        document.cookie = "name=" + newName;
        document.cookie = "check=chris";
        document.cookie = "safari=";
    }

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

    /**
     * clears the username field in the cookie.
     */
    function deleteUsernameCookie(){
        document.cookie = "name=";
        document.cookie = "check=";
        document.cookie = "safari=";
    }



});



/**
 * Login and parse cookies to php function pages/
 * @param abs
 *  flag indicating if an absolute path to the webroot is needed
 */
function absolute_login(abs){
    $("#submit-login").on("click", function(){

        //hides the login window
        $("#login").hide();


        //Posts username and password to login
        //USE AN ABSOLUTE PATH TO THE WEB ROOT
        $.post('../../php_resources/user_accts/login.php',
            {
                user: $("#user").val(),
                pass: $("#pass").val()
            },
            //A function to confirm the information entered was properly sent.
            function(JSONObject){
                var cookie = JSON.parse(JSONObject);
                if(cookie == null){
                    alert("Login Failed");
                }
                else {

                    alert("Logged in as " + cookie);
                    setUsernameCookie(cookie);
                    $("#helloUser").html("Hello " + getUsernameCookie() + "!");


                    //Hides the login and signup buttons, displays logout.
                    $("#login-bar").hide();
                    $("#signup-bar").hide();
                    $("#logout-bar").show();
                    $("#helloUser").show();
                }
            });
        //Clears the Username and Password fields.
        $("#user").val("");
        $("#pass").val("");


    });
}



