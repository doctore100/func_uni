<?php
/*
Plugin Name: Plugin Fictional University
Description: This is my first plugin
Author: Ivan David Guzman Ruiz
Version: 1.0
 */
add_filter('the_content', 'myUniqueDavidPlugin');
function myUniqueDavidPlugin($content) {
    $content .= 'This is my first plugin';
    return $content;
}