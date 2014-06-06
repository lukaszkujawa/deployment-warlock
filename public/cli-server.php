<?php

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|txt|js|eot|svg|ttf|woff|html)$/', $_SERVER["REQUEST_URI"])) {
    return false; // serve the requested resource as-is.}
}
else {
	include "index.php";
}
