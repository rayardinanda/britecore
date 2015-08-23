<?php


class StringHelper
{
    public static function ifPostNotExistThenNull( $name ){
        return isset($_POST[$name]) ? $_POST[$name] : null;
    }
}