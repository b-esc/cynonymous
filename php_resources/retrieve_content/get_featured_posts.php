<?php
//Ryan Buls
    include("retrieve_functions.php");

    $DBH = connect_DB();
    echo json_encode(get_featured_posts($DBH));
