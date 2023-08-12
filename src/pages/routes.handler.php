<?php
MyPackage::import(ROUTES_MANAGER);
$RoutesHandler = new RoutesHandler(PROJECT_ROOT_DIR);
$RoutesHandler->handleFile($content, "error.404.php");