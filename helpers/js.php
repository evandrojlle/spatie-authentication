<?php
if (! function_exists('set_item_local_storage')) {
    function set_item_local_storage($name, $value)
    {
        echo "<script>localStorage.setItem('{$name}', '{$value}');</script>";
    }
}

if (! function_exists('get_item_local_storage')) {
    function get_item_local_storage($name)
    {
        echo "<script>localStorage.getItem('{$name}');</script>";
    }
}