<?php
require_once("configs/class.import.php");
function fetchLink(string $url): void
{
    $CLEAN_URL = "";
    if (!empty($url)) {
        $CLEAN_URL = filter_var($url, FILTER_SANITIZE_URL);
    }
    MyPackage::import(APP_INDEX_HTML);
}