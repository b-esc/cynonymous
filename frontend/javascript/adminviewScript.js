$(document).ready(function () {
    $(function () {
        $('[data-toggle="popover"]').popover()
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
    $('#aCreateBoard').click(function () {
        let x = pargeAdminArgs();
        create_board(x[0],x[1],x[2],x[3]);
    });
    $('#aDeleteBoard').click(function () {
            let x = pargeAdminArgs();
            delete_board(x[0]);
        }
    );
    $('#aDeleteThread').click(function () {
            let x = pargeAdminArgs();
            delete_thread(x[0]);
        }
    );
    $('#aDeletePost').click(function(){
        let x = pargeAdminArgs();
        delete_post(x[0]);
        }
    );
    $('#aBanUser').click(function(){
            let x = pargeAdminArgs();
            ban_user(x[0]);
        }
    );
    $('#aUnbanUser').click(function(){
            let x = pargeAdminArgs();
            unban_user(x[0]);
        }
    );

    /**
     *
     * @returns {*|string[]|jQuery} array of args in text box, comma seperated
     */
    function pargeAdminArgs(){
        return $('#adminArgs').val().split(',');
    }

    let currentBoardName = location.search.replace('?', '').split('=')[0];
    get_board_threads_from_url(currentBoardName);

    //onclick view thread's posts on a different page
    $(document).on('click', '[id^=nameThreadID]', function(event){
        let targetThreadID = event.target.id.slice(12);
        let targString = "nameThreadID"+targetThreadID;
        window.location = '/frontend/html/postview.html?'+currentBoardName+"/"+$("#"+targString).html().toLowerCase();
    });

    //onclick of dynamically generated ViewThread, generate threads posts without changing page
    $(document).on('click', '[id^=viewThreadID]', function(event){
        let targetThreadID = event.target.id.slice(12);
        alert(targetThreadID);
        get_thread_posts_dyn(targetThreadID);
    });

    //onclick hide dynamically fetched threads
    $(document).on('click', '[id^=collapseID]', function(event){
        let targetCollapseID = event.target.id.slice(7);
        $(`#appendID${targetCollapseID}`).toggle();
    });

    //onclick creates thread
    $("#submitBtn").click(function(){
        //grabbing subject, body
        //alert("pushed thread button");
        let subjVal = $("#inputTitle").val();
        let bodyVal = $("#inputBody").val();
        create_thread(subjVal,bodyVal,currentBoardName)

    });

    //onclick, post to desired thread
    $("#postBtn").click(function(){
        anonflag = 0;
        let targetThread = $("#postOptionsInput").val();
        let subjVal = $("#postSubjectInput").val();
        let bodyVal = $("#postInputBody").val();
        if(document.getElementById("anonPostToggle").checked){
            alert("checked");
            anonflag = 1;
        }
    });

    //onclick view newThread form
    $("#newThreadBt").click(function () {
        $("#newThreadForm").toggle();
    });
    //$_POST["name"], $_POST["description"], (int)$_POST["family_friendly"], $_POST["board_url"])
    function create_board(name, description,famFriendInt,board_url){
        $.ajax({
            type: "POST",
            url: '../../php_resources/create_content/create_board.php',
            data: {name:name,description:description,family_friendly:famFriendInt,board_url:board_url},
            success: function(data){
                alert("board created!");
            }
        })
    }

    function delete_board(board_id){
        $.ajax({
            type: "POST",
            url: '../../php_resources/delete_content/delete_board.php',
            data: {board_id:board_id},
            success: function(data){
                alert("board deleted");
            }
        })
    }

    function delete_thread(thread_id){
        $.ajax({
            type: "POST",
            url: '../../php_resources/delete_content/delete_thread.php',
            data: {thread_id:thread_id},
            success: function(data){
                alert("thread deleted");
            }
        })
    }

    function delete_post(post_id){
        $.ajax({
            type: "POST",
            url: '../../php_resources/delete_content/delete_post.php',
            data: {post_id:post_id},
            success: function(data){
                alert("post deleted");
            }
        })
    }

    function ban_user(uid){
        $.ajax({
            type: "POST",
            url: '../../php_resources/user_accts/banning/ban_user.php',
            data: {uid:uid},
            success: function(data){
                alert("user banned");
            }
        })
    }

    function unban_user(uid){
        $.ajax({
            type: "POST",
            url: '../../php_resources/user_accts/banning/unban_user.php',
            data: {uid:uid},
            success: function(data){
                alert("user unbanned (if they were already banned)");
            }
        })
    }
    /**
     * Creates a thread given a subject, body, and url value
     * @param {string} subjVal
     *  Desired subject attribute to be posted
     * @param {string} bodyVal
     *  The intended body for the new post, given the url
     * @param {string} boardUrl
     *  The url, for most of the content, for the new post, given via url posted to php
     */
    function create_thread(subjVal, bodyVal, boardUrl){
        $.ajax({
            type: "POST",
            url: '../../php_resources/create_content/create_thread.php',
            //takes subject.. the echo in function_page.php is making this come up
            data: {board_url:boardUrl,name:subjVal,description:bodyVal,thread_url:boardUrl+"/"+subjVal},
            success: function (data){
                // alert("post thread successful (php one)");
                //alert(data);
                let newClientPost = `<div class="border border-primary">
                            <div class ="container-fluid">
                                <div class="row">
                                    <div class="col-auto">
                                        <img class="img-thumbnail" src="../resources/chris1.jpg" >
                                    </div>
                                    <div class="col-sm">
                                        <h4>${subjVal}</h4>
                                        <a>${bodyVal}</a>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                $("#dummy").prepend(newClientPost);
            }
        });
    }
    /**
     * Obtains an individual thread's posts given a thread's url
     * @param {string} targetThreadUrl
     *  The current post attribute from the url used to fetch the new posts.
     * @param {string} subjVal
     *  The intended subject for the new post, given the url
     * @param {string} bodyVal
     *  The intended body, most of the content, for the new post, given the url posted to php
     * @param {int/boolean} anonflag
     *  Flag that will determine if, when fetching the post back again, his/her username will appear. posted in php
     */
    function create_post_by_url(targetThread, subjVal, bodyVal, anonflag){
        $.ajax({
            type: "POST",
            url: '../../php_resources/create_content/create_post_by_url.php',
            //takes subject.. the echo in function_page.php is making this come up
            data: {thread_url:targetThread,title:subjVal,content:bodyVal,anonymous:anonflag},
            success: function (data){
                alert("post successful (php one)");
            }
        });
    }

    /**
     * Obtains an individual boards's threads given a board's url
     * @param {string} currentBoardName
     *  The current post attribute from the url used to fetch the new posts.
     */
    function get_board_threads_from_url(boardUrl) {
        $.ajax({
            type: "POST",
            data : {board_url: boardUrl},
            url: '../../php_resources/retrieve_content/get_board_threads_from_url.php',
            dataType: "json",
            success: function(JSONObject){
                //alert("getting board threads");
                // alert("post worked");
                //Associative array with keys "name", "description", "num_posts", "date_created"
                for(key in JSONObject){
                    // alert("inside json object");
                    if(JSONObject.hasOwnProperty(key)){
                        //alert("test");
                        let newPost = `<div class="adminInline">
                        <div class="border border-primary">
                            <div class ="container-fluid"">
                                <div class="row">
                                    <div class="col-auto">
                                        <img class="img-thumbnail" src="../resources/chris1.jpg" >
                                    </div>
                                    <div class="col-sm">

                                        <h6><a>Thread ID:</a><a>${JSONObject[key]["thread_id"]}</a>
                                        <a>| ${JSONObject[key]["date_created"]}</a><a>: </a>
                                        <a>Num posts: ${JSONObject[key]["num_posts"]}</a>
                                        <h4>name:</h4><h4 id="nameThreadID${JSONObject[key]["thread_id"]}">${JSONObject[key]["name"]}</h4>
                                        <a>${JSONObject[key]["description"]}</a>
                                        <a id="viewThreadID${JSONObject[key]["thread_id"]}">view/update</a>
                                        <a id="collapseID${JSONObject[key]["thread_id"]}" style="display: none">collapse</a>
                                    </div>
                                </div>
                                <div id="hideID${JSONObject[key]["thread_id"]}">
                                <div id="appendID${JSONObject[key]["thread_id"]}"></div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                        $("#adminAppendTarg").append(newPost);
                    }
                }
            }
        });
    }

    /**
     * Obtains an individual thread's posts given a thread's ID/URL
     * @param {string} targetThreadID
     *  The current thread attribute that will post to a php function and return posts
     */
    function get_thread_posts(targetThreadID){
        $.ajax({
            type: "POST",
            data : {thread_id: targetThreadID},
            url: '../../php_resources/retrieve_content/get_thread_posts.php',
            dataType: "json",
            success: function(JSONObject){
                alert("thread_id posted (php posted) successfully!");
                //removes (shouldn't do anything if not there) and adds again to prevent dups
                $(`#subappendID${targetThreadID}`).remove();
                let subappendHTML = `<div id="subappendID${targetThreadID}"></div>`;
                $(`#appendID${targetThreadID}`).append(subappendHTML);
                //JSON: Associative array with keys "name", "description", "num_posts", "date_created"
                for(key in JSONObject){
                    if(JSONObject.hasOwnProperty(key)){
                        //appends thread immediately to client view
                        let newPost = `<div class="border border-primary" >
                            <div class ="container-fluid" style="background-color: antiquewhite;">
                                <div class="row">
                                    <div class="col-auto">
                                        <img class="img-thumbnail" src="../resources/chris1.jpg" >
                                    </div>
                                    <div class="col-sm">                                
                                        <h6><a>Thread ID:</a><a>${targetThreadID}</a>
                                        <a>Post ID:</a><a>${JSONObject[key]["post_id"]}</a>  
                                        <a>| test</a><a>: </a>
                                        <a>test</a>
                                        <h4>${JSONObject[key]["username"]}</h4>
                                        <h4>${JSONObject[key]["title"]}</h4>
                                        <a>${JSONObject[key]["content"]}</a>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                        //two different append div's to allow deletion / reloading of posts when rq'd
                        $(`#subappendID${targetThreadID}`).append(newPost);
                    }
                }
                //makes collapse appear if not already here, won't take it away if its needed
                if($(`#collapseID${targetThreadID}`).css('display')=='none') {
                    $(`#collapseID${targetThreadID}`).toggle();
                }
            }
        });
    }
    /**
     * Obtains an individual thread's posts given a thread's ID/URL, intended for use on mobile
     * @param {string} targetThreadID
     *  The current thread attribute that will post to a php function and return posts dynamically
     */
    function get_thread_posts_dyn(targetThreadID){
        $.ajax({
            type: "POST",
            data : {thread_id: targetThreadID},
            url: '../../php_resources/retrieve_content/get_thread_posts.php',
            dataType: "json",
            success: function(JSONObject){
                //removes (shouldn't do anything if not there) and adds again to prevent dups
                $(`#subappendID${targetThreadID}`).remove();
                let subappendHTML = `<div id="subappendID${targetThreadID}"></div>`;
                $(`#appendID${targetThreadID}`).append(subappendHTML);
                //JSON: Associative array with keys "name", "description", "num_posts", "date_created"
                for(key in JSONObject){
                    if(JSONObject.hasOwnProperty(key)){
                        //appends thread immediately to client view
                        let newPost = `<div class="border border-primary" >
                            <div class ="container-fluid" style="background-color: antiquewhite;">
                                <div class="row">
                                    <div class="col-auto">
                                        <img class="img-thumbnail" src="../resources/chris1.jpg" >
                                    </div>
                                    <div class="col-sm">                                
                                        <h6><a>Thread ID:</a><a>${targetThreadID}</a>
                                        <a>Post ID:</a><a>${JSONObject[key]["post_id"]}</a>  
                                        <a>| test</a><a>: </a>
                                        <a>test</a>
                                        <h4>${JSONObject[key]["username"]}</h4>
                                        <h4>${JSONObject[key]["title"]}</h4>
                                        <a>${JSONObject[key]["content"]}</a>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                        //two different append div's to allow deletion / reloading of posts when rq'd
                        $(`#subappendID${targetThreadID}`).append(newPost);
                    }
                }
                //makes collapse appear if not already here, won't take it away if its needed
                if($(`#collapseID${targetThreadID}`).css('display')=='none') {
                    $(`#collapseID${targetThreadID}`).toggle();
                }
            }
        });
    }
});
//adminAppendTarg