<?php

session_start();

session_unset();
session_destroy();
header('Location:../student/loginstudent.php');
exit();
