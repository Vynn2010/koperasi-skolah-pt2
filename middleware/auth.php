<?php
const LOGIN_URL = "../../pages/auth/login.php";
const PRODUCT_INDEX_URL = "../../pages/products/index.php";
const FORBIDDEN_URL = "../../pages/errors/403.php";

function sessionStart()
{
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function requireLogin()
{
    session_start();
    if(!isset($_SESSION['user'])) {
        $_SESSION['flash'] = ['type' => 'error','message' => "Silahkan login terlebih dahulu."];
        header("Location: " . LOGIN_URL);
        exit;
    }
}

function redirectIfLoggedIn()
{
    sessionStart();
    if (isset($_SESSION['user'])) {
        header("Location: " . PRODUCT_INDEX_URL);
        exit;
    }
}
?>