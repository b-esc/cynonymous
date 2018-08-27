/**
 * @public document onload
 * @author ben escobar vc_7_cynon
 * Postview's default behavior, goes about and loads the page after calling some methods.
 */
function loadPage(){

}
function previewFile() {
    var preview = document.querySelector('img');
    var file    = document.querySelector('input[type=file]').files[0];
    var reader  = new FileReader();

    reader.addEventListener("load", function () {
        preview.src = reader.result;
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
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
                    let newPost = `<div class="border border-primary">
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
                            </div>`;
                    $("body").append(newPost);
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

$(document).ready(function() {
    let currentBoardName = location.search.replace('?', '').split('=')[0];
    get_board_threads_from_url(currentBoardName);
    //credit prabeesh hinjab3 for the ajax tutorial
    // let formObj = $("#uploadTest")[0];
    // let formData = new FormData(formObj);
    // $("#uploadTest").on('submit',(function(e) {
    //     e.preventDefault();
    //     $.ajax({
    //         url: '../../php_resources/create_content/upload_profile_image.php',
    //         //url: "../../php_resources/create_content/upload.php",
    //         type: "POST",
    //         data:  new formData,
    //         contentType: false,
    //         cache: false,
    //         processData:false,
    //         success: function(data)
    //         {
    //             $("#targetLayer").html(data);
    //         },
    //         error: function()
    //         {
    //         }
    //     });
    // }));

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
        //$(`#appendID${targetCollapseID}`).toggle();
        $(`#subappendID${targetCollapseID}`).toggle();
        //$(`#hideID${targetCollapseID}`).toggle();
    });

    //onclick creates thread
   $("#submitBtn").click(function(){
        //grabbing subject, body
        //alert("pushed thread button");
       let subjVal = $("#inputTitle").val();
       let bodyVal = $("#inputBody").val();

       let formObj = $("#uploadTest")[0];
       let formData = new FormData(formObj);
       //url:currentBoardName+"/"+subjVal
       formData.append("url",currentBoardName+"/"+subjVal);
       $.ajax({
           url: '../../php_resources/create_content/upload_thread_image.php',
           //url: "../../php_resources/create_content/upload.php",
           type: "POST",
           data:  formData,
           contentType: false,
           cache: false,
           processData:false,
           success: function(data)
           {
               $("#targetLayer").html(data);
           },
           error: function()
           {
           }
       });
        create_thread(subjVal,bodyVal,currentBoardName);
        alert("thread created!");
        refresh(currentBoardName);

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
        create_post_by_url(targetThread,subjVal,bodyVal,anonflag);
        //!! super important!!! we need a delay in between the deletion and fetching of new threads
        //alert("needed pause for posts only should put same pause in thread updates");
        refresh_thread(currentThreadName);
    });

    //onclick view newThread form
    $("#newThreadBt").click(function () {
        $("#newThreadForm").toggle();
    });

    function refresh_thread(threadUrl){
        let newDummy = `<div id="dummy"></div>`;
        $("#dummy").remove();
        $("body").append(newDummy);
        //$$$$$$$$ this is where the delay needed to be.
        setTimeout(get_thread_posts_by_url(threadUrl),500);
    }

    function refresh(boardUrl){
        let newDummy = `<div id="dummy"></div>`;
        $("#dummy").remove();
        $("body").append(newDummy);
        get_board_threads_from_url(boardUrl);
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
            //$("#dummy").prepend(newClientPost);
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
                                        <img class="img-thumbnail" src="http://${JSONObject[key]["thread_image_url"]}" >
                                    </div>
                                    <div class="col-sm">

                                        <h6><a>Thread ID:</a><a>${JSONObject[key]["thread_id"]}</a>
                                        <a>| ${JSONObject[key]["date_created"]}</a><a>: </a>
                                        <a>Num posts: ${JSONObject[key]["num_posts"]}</a>
                                        <h4 id="nameThreadID${JSONObject[key]["thread_id"]}">${JSONObject[key]["name"]}</h4>
                                        <a>${JSONObject[key]["description"]}</a>
                                        <a id="viewThreadID${JSONObject[key]["thread_id"]}">view/update</a>
                                        <!--<a id="collapseID${JSONObject[key]["thread_id"]}" style="display: none">collapse</a>-->
                                    </div>
                                </div>
                                <div id="hideID${JSONObject[key]["thread_id"]}">
                                <div id="appendID${JSONObject[key]["thread_id"]}"></div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    $("#dummy").append(newPost);
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

});
