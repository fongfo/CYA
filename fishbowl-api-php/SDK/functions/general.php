<?php

function debug($item, $type = 0, $array = null) {
    global $error_msg;

    // Debug array
    if ($type == 0) {
        print_r($item);
    }

    // If variable
    if ($type == 1) {
        echo $item;
    }

    // If error code
    if ($type == 2) {
        echo $error_msg->check_code($item) . "<br/><br/>\n\n";
        print_r($array);
    }

    // End script
    die();
}

function add_link($url, $name) {
    echo '<a href="' . $url .'.php">' . $name . '</a><br/>';
}

function xml_specialchar($data) {
//    $data = html_entity_decode($data);
    $data = str_replace('&', '&#x26;', $data);
    $data = str_replace("'", "\'", $data);

    return $data;
}

?>