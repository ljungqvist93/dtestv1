<?php
    if(!isset($subpage)) {
        include 'engine/connect.php';
    }
    $themes = [
        'light',
        'dark',
    ];
    $theme = null;
    if(isset($_COOKIE['theme'])) {
        $theme = in_array($_COOKIE['theme'], $themes) ? $_COOKIE['theme'] : 'light';
    }
    if(isset($_GET['theme'])) {
        $location = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
        $params = $_GET;
        unset($params['theme']);
        if(count($params)) {
            $location .= '?'.http_build_query($params);
        }
        if($_GET['theme'] === 'auto') {
            setcookie('theme', null, -1);
            header('Location: '.$location);
            die();
        }
        $theme = in_array($_GET['theme'], $themes) ? $_GET['theme'] : 'light';
        setcookie('theme', $theme, intval(time()+60*60*24*30*360));
        header('Location: '.$location);
        die();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php if($postPage != 1): ?> <title>Daniel Ljungqvist</title> <?php endif; ?>
        <link rel="icon" href="/assets/favicon.png" type="image/x-icon" />
        <link rel="stylesheet" href="/assets/style/style.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.11.2/css/all.css" integrity="sha384-zrnmn8R8KkWl12rAZFt4yKjxplaDaT7/EUkKm7AovijfrQItFWR7O/JJn4DAa/gx" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,900|Pacifico:400&display=swap" rel="stylesheet" />
        <meta data-n-head="true" name="viewport" content="width=device-width, initial-scale=1"/>

        <meta name="description" content="Daniel Ljungqvist provides really rad, easy and interesting guides and articles about technology, IT, and much more!" />


        <script>
            function getCookie(cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');
                for(var i = 0; i <ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                    }
                }
                return "";
            }
            if(window.matchMedia('(prefers-color-scheme').media === 'not all' && getCookie('theme') !== 'dark') {
                var head = document.getElementsByTagName('HEAD')[0];
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.type = 'text/css';
                link.href = '/assets/style/lightstyle.css';
                head.appendChild(link);
            }
        </script>

        <?php if($theme === 'light'): ?>
            <link rel="stylesheet" href="/assets/style/lightstyle.css" type="text/css" />
        <?php elseif($theme === 'dark'): ?>
            <link rel="stylesheet" href="/assets/style/darkstyle.css" type="text/css" />
        <?php elseif(!$theme): ?>
            <link rel="stylesheet" href="/assets/style/lightstyle.css" type="text/css" media="(prefers-color-scheme: no-preference), (prefers-color-scheme: light)" />
            <link rel="stylesheet" href="/assets/style/darkstyle.css" type="text/css" media="(prefers-color-scheme: dark)" />
        <?php endif; ?>

        <?php if($postPage != 1): ?>
        </head>
        <body>
        <?php endif; ?>
        <?php
            if ($postPage != 1) {
                include 'menu.php';
            }
        ?>