
/**
 * @public document onload
 * @author ben escobar vc_7_cynon
 * Postview's default behavior, goes about and loads the page after calling some methods.
 */
function loadPage(){

}
$(document).ready(function() {
    let currentThreadName = location.search.replace('?', '').split('=')[0];

    get_thread_posts_by_url(currentThreadName);

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
        alert("post created!");
        //!! super important!!! we need a delay in between the deletion and fetching of new threads
        //alert("needed pause for posts only should put same pause in thread updates");
        refresh_thread(currentThreadName);
    });

    $("#newThreadBt").click(function () {
        alert("todo: remove / new options");
    });

});

function refresh_thread(threadUrl){
    let newDummy = `<div id="dummy"></div>`;
    $("#dummy").remove();
    $("body").append(newDummy);
    //$$$$$$$$ this is where the delay needed to be.
    setTimeout(get_thread_posts_by_url(threadUrl),5000);
}

/**
 * Obtains an individual thread's posts given a thread's url
 * @param {string} currentThreadName
 *  The current post attribute from the url used to fetch the new posts.
 */
function get_thread_posts_by_url(currentThreadName){
    $.ajax({
        type: "POST",
        data : {thread_url: currentThreadName},
        url: '../../php_resources/retrieve_content/get_thread_posts_from_url.php',
        dataType: "json",
        success: function(JSONObject){
            for(key in JSONObject){
                if(JSONObject.hasOwnProperty(key)){
                    let newPost = `<div class="border border-primary">
                            <div class ="container-fluid"">
                                <div class="row">
                                    <div class="col-auto">
                                        <img class="img-thumbnail" src="../resources/chris1.jpg" >
                                    </div>
                                    <div class="col-sm">

                                        <a>user: ${JSONObject[key]["username"]} | </a><a>post ID:</a><a>${JSONObject[key]["post_id"]} | </a>
                                        <h4 id="nameThreadID${JSONObject[key]["post_id"]}">${JSONObject[key]["title"]}</h4>
                                        <a>${JSONObject[key]["content"]}</a>
                                    </div>
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
 * Obtains an individual thread's posts given a thread's url
 * @param {string} targetThreadUrl
 *  The current post attribute from the url used to fetch the new posts.
 * @param {string} subjVal
 *  The intended subject for the new post, given the url
 * @param {string} bodyVal
 *  The intended body, most of the content, for the new post, given the url
 * @param {int/boolean} anonflag
 *  Flag that will determine if, when fetching the post back again, his/her username will appear.
 */
function create_post_by_url(targetThreadUrl,subjVal,bodyVal,anonflag){
    $.ajax({
        type: "POST",
        url: '../../php_resources/create_content/create_post_by_url.php',
        data: {thread_url:targetThreadUrl,title:subjVal,content:bodyVal,anonymous:anonflag},
        success: function (data){
            //alert("post successful (php one)");
        }
    });
}