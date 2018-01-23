<?php

function fun_query(&$coords, $args)
{
    if (check_coord($args[0], $args[1], $coords) !== false) {
        echo 'full' . PHP_EOL;
    } else {
        echo 'empty' . PHP_EOL;
    }
}

function fun_add(&$coords, $args)
{
    if (check_coord($args[0], $args[1], $coords) !== false) {
        echo 'A cross already exists at this location' . PHP_EOL;
    } else {
        array_push($coords, $args);
    }
}

function fun_remove(&$coords, $args)
{
    $idx = check_coord($args[0], $args[1], $coords);
    if ($idx === false) {
        echo 'No cross exists at this location' . PHP_EOL;
    } else {
        array_splice($coords, $idx, $idx + 1);
    }
}

function fun_display(&$coords, $data)
{
    extract($data);
    render($width, $height, $coords);
}

function parse_input($input, &$coords, $data)
{
    $input = trim($input);
    $space_strpos = strpos($input, ' ');
    $cmd = $space_strpos === false ? $input : substr($input, 0, $space_strpos);
    $args = $space_strpos === false ? null : substr($input, $space_strpos + 1);

    switch ($cmd) {
        case 'query':
        eval("\$args = $args;");
        fun_query($coords, $args);
        return true;

        case 'add':
        eval("\$args = $args;");
        fun_add($coords, $args);
        return true;

        case 'remove':
        eval("\$args = $args;");
        fun_remove($coords, $args);
        return true;

        case 'render':
        case 'display':
        fun_display($coords, $data);
        return true;

        case 'quit':
        case 'exit':
        echo 'Bye !' . PHP_EOL;
        return false;

        default:
        echo 'Unknown command: ' . $cmd . PHP_EOL;
        return true;
    }
    return true;
}

function check_coord($x, $y, $coords)
{
    foreach ($coords as $idx => $coord) {
        if ($x == $coord[0] && $y == $coord[1]) {
            return $idx;
        }
    }
    return false;
}

function horizontal_line($width)
{
    for ($x = 0; $x < $width; $x++) {
        echo '+---';
        if ($x + 1 == $width) {
            echo '+' . PHP_EOL;
        }
    }
}

function vertical_line($width, $y, $coords)
{
    for ($x = 0; $x < $width; $x++) {
        if (check_coord($x, $y, $coords) !== false) {
            echo '| X ';
        } else {
            echo '|   ';
        }
        if ($x + 1 == $width) {
            echo '|' . PHP_EOL;
        }
    }
}

function render($width, $height, $coords = [])
{
    for ($y = 0; $y < $height; $y++) {
        horizontal_line($width);
        vertical_line($width, $y, $coords);
        if ($y + 1 == $height) {
            horizontal_line($width);
        }
    }
}

function colle($width, $height, $coords = [])
{
    render($width, $height, $coords);
    do {
        $input = readline('$> ');
        if (parse_input($input, $coords, compact('width', 'height')) === false) {
            break;
        }
    } while (!is_null($input));
}
