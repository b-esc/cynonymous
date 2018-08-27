//chances are you'll want to remove all the dummy functions that were commented for the assignment and make your own, jordan

$(document).ready(function() {


    //gets and displays the featured posts in a table. Potentially going to be linked
    //to the displayed post.
    $.get("../../php_resources/retrieve_content/get_featured_posts.php",
        function(JSONObject, status){
         var featPosts = JSON.parse(JSONObject);
            $("#featPosts").append('<tr>');
            for (var i = 0; i < featPosts.length; i++) {
               // var boardUrl = featPosts[i].board_url;
               // var linkAddress = '../html/threadview.html?' + boardUrl;
                $("#featPosts").append('<th>' + featPosts[i].title + '</th>');

            }
            $("#featPosts").append('</tr>');
            $("#featPosts").append('<tr>');
            for (var i = 0; i < featPosts.length; i++) {
                //var boardUrl = featPosts[i].board_url;
               // var linkAddress = '../html/threadview.html?' + boardUrl;
                $("#featPosts").append('<td width = \"400\" >' + featPosts[i].content  + '</td>');
            }
            $("#featPosts").append('</tr>');
    });

    //gets and displays the featured boards in a table, with the title and descriptions acting
    // as links to the respective boards
    $.get("../../php_resources/retrieve_content/get_featured_boards.php",
        function(JSONObject) {
            var featBoards = JSON.parse(JSONObject);

            $("#featBoards").append('<tr>');
            for (var i = 0; i < featBoards.length; i++) {
                var boardUrl = featBoards[i].board_url;
                var linkAddress = '../html/threadview.html?' + boardUrl;
                $("#featBoards").append('<th>' + '<a href =' + linkAddress + ' style=\"color: black \" >' + featBoards[i].name + '</a>' + '</th>');

            }
            $("#featBoards").append('</tr>');
            //alert(JSON.stringify(featBoards[0].description) + JSON.stringify(featBoards[1].description) + JSON.stringify(featBoards[2].description));

            $("#featBoards").append('<tr>');
            for (var i = 0; i < featBoards.length; i++) {
                var boardUrl = featBoards[i].board_url;
                var linkAddress = '../html/threadview.html?' + boardUrl;
                $("#featBoards").append('<td width = \"400\" >' + '<a href =' + linkAddress + ' style=\"color: black \" >' + featBoards[i].description + '</a>' + '</td>');
            }
            $("#featBoards").append('</tr>');

        });

    //get and display all boards in the "boards" section of homepage.html.
    $.post("../../php_resources/retrieve_content/get_all_boards.php",
        {
            board_id: 0,
        },
        function(JSONObject, status){
            var boardList = JSON.parse(JSONObject);
            // Appends the boards as links to their respective threadview pages.
            for(var i = 0; i < boardList.length; i++){
                var boardUrl = boardList[i].board_url;
                //url is an issue on my computer, needs more testing
                //This seems to fix it, make sure that it works for actual server
                var linkAddress = '../html/threadview.html?' + boardUrl;
                var boardName = boardList[i].name;
                var newLink = ['<p>' ,
                                '<a href = ' + linkAddress + '>' + boardName + '</a>',
                            '</p>'];

                $("#boardList").append(newLink);
            }
        });


});

/**
 * Retrieves all boards in the boards portion of our mysql database to display.
 * Not static as the addition of new boards is a possibility.
 * @param {int} status
 *  Provides an exit code for the response to the server.
 */
function retrieve_home_page_boards(status){
    $.post("../../php_resources/retrieve_content/get_all_boards.php",
        {
            board_id: 0,
        },
        function(JSONObject, status){
            var boardList = JSON.parse(JSONObject);

            // Appends the boards as links to their respective threadview pages.
            for(var i = 0; i < boardList.length; i++){
                var boardUrl = boardList[i].board_url;
                //url is an issue on my computer, needs more testing
                //This seems to fix it, make sure that it works for actual server
                var linkAddress = '../html/threadview.html?' + boardUrl;
                var boardName = boardList[i].name;
                var newLink = ['<p>' ,
                    '<a href = ' + linkAddress + '>' + boardName + '</a>',
                    '</p>'];

                $("#boardList").append(newLink);
            }
        });
}

/**
 *
 * Retrieves random posts to encourage and promote discussion
 * @param {int} status
 *  Provides an exit code for the response to the server.
 */
function get_feature_posts(status){
    $.get("../../php_resources/retrieve_content/get_featured_posts.php",
        function(JSONObject, status){
            //Need to figure out how to break this apart into individual pieces
            var jsonArray = JSON.parse(JSONObject);
            $("#featured-post0-title").html(jsonArray[0].title);
            $("#featured-post1-title").html(jsonArray[1].title);
            $("#featured-post2-title").html(jsonArray[2].title);

            $("#featured-post0-content").html(jsonArray[0].content);
            $("#featured-post1-content").html(jsonArray[1].content);
            $("#featured-post2-content").html(jsonArray[2].content);
        });
}

/**
 * Retrieves needed information, passes exit codes to needed places.
 */
function initiate_load(){

}