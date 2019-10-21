<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require $root.'/vendor/autoload.php';

use Carbon\Carbon;

function d($var) {
    var_dump($var);
}
function dump($var) {
    d($var);
}
function dd($var) {
    die(d($var));
}

function get_post_tags($postId, $connection) {
    $tags = $connection->prepare("
        SELECT *
        FROM post_tag
        LEFT JOIN tags ON post_tag.tag_id = tags.id
        WHERE post_id = :post_id
    ");
    $tags->execute(['post_id' => $postId]);
    $tags = $tags->fetchAll(PDO::FETCH_ASSOC);

    return $tags;
}

function get_theme() {
    if(!isset($_COOKIE['theme'])) {
        return null;
    }
    if($_COOKIE['theme'] === 'light') {
        return 'light';
    } else if($_COOKIE['theme'] === 'dark') {
        return 'dark';
    }
    return null;
}

function is_theme($theme = null) {
    if($theme === get_theme()) {
        return true;
    }

    return false;
}

function build_theme_link($theme) {
    $location = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
    if($location === '/') {
        $location = '';
    }
    $params = $_GET;
    $params['theme'] = $theme;
    if(count($params)) {
        $location .= '?'.http_build_query($params);
    }

    return $location;
}

function human_readable_time_diff($datetime) {
    $date = Carbon::parse($datetime);
    $now = Carbon::now();
    $diff = $now->diffInMinutes($date);

    return $now->subMinutes($diff)->diffForHumans();
}
