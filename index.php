<php
    sessions_start();
    if(!$SESSIOM['user']){
    header('location:login.php');
    }

    ?>
    <p> Hiii Welcome to websote
    <p>