<?php

spl_autoload_register(function ($class) {
    require dirname(__DIR__) . "/src/{$class}.php";
});

require dirname(__DIR__) . '/config.php';

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");
