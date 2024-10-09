<?php

session_start();
session_destroy();
$_SESSION[] = "";
session_abort();

header("Location: /hrd_hub/test/self-qr/");