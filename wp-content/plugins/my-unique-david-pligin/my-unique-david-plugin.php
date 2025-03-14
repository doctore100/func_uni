<?php

/*
Plugin Name: Plugin Fictional University
Description: This is my first plugin
Author: Ivan David Guzman Ruiz
Version: 1.0
 */

class wordCountUniquePlugin {

}

$wordCountUniquePlugin = new wordCountUniquePlugin();

add_action('admin_menu', 'myUniqueDavidPlugin');
function myUniqueDavidPlugin($content)
{
    add_options_page(
        'Word Count page',
        'Word Count',
        'manage_options',
        'my-unique-david-plugin',
        'ourSettingsPageUniqueHtml'
    );
}

function ourSettingsPageUniqueHtml()
{
    ?>
    echo "Hello World";
<?php }