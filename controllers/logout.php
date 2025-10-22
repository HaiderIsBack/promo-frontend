<?php

require_once "../global.php";

session_unset();
session_destroy();
header("Location: " . SITE_URL . "/login.php");
exit;