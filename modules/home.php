<?php
if($_POST['home']=='main'){
    echo main();
    exit();
}

if($_POST['home']=='home'){
    echo home();
    exit();
}

if($_POST['home']=='refresh'){
    echo show_all();
    exit();
}




?>