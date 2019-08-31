<?php
    session_start();
    /*
     * session_start();
     * Starting a new session before clearing it
     * assures you all $_SESSION vars are cleared
     * correctly, but it's not strictly necessary.
    */
    setcookie("PHPSESSID", "", time() - 3600);
    session_unset();
    session_destroy();
    header('Location: index.php');
    /* Or whatever document you want to show afterwards */
?>