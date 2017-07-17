<?php 

function add_role_viajero()
{
    add_role(
        'viajero',
        'Viajero',
        [
            'read'          => true,
            'edit_posts'    => true,
            'upload_files'  => true,
            'delete_posts'  => true,
            'publish_posts' => true
        ]
    );
}
 
// add the simple_role
add_action('init', 'add_role_viajero');