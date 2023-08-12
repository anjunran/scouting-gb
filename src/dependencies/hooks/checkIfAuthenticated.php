<?php
function checkIfAuthenticated(): bool
{
    return isset($_SESSION['userId']);
}