SiteBuilder Dynamic Components
==============================

This is a WordPress plugin that automates the injection of dynamic html or javascript into a static page.
The idea is to be able to use full page caching on your WordPress site without sacrificing a more dynamic
user experience. Dynamic content is injected after the page loads using an ajax request.

Dynamic Components is part of the SiteBuilder suite of plugins designed to unleash the full potential of
WordPress as a full Content Management System.

# Installation

Copy sb-dynamic-components folder into the 'plugins' folder of your WordPress installation. Activate plugin 
through the WordPress Admin interface.

# Basic Usage

Here is a basic example of inserting some user control links into the header.

Here is the relevant block of code in the template itself:

    <header>

        <?php sb_dc_insert_component( 'user-controls' ) ?>

    </header>

This renders a placeholder into the html and registers 'user-controls' as a dynamic component that the
back-end will render.

    <header>

        <div id="sb-dc-user-controls"></div>

    </header>

Somewhere in the back-end, preferably your theme functions.php you must define a function that will handle
the rendering of the component.

   <?php

       function render_user_controls( $args = null ){

       		$user = wp_get_current_user();

            ?>       

            <ul>
               <li><a href="<?php echo get_author_posts_url( $user->ID ) ?>"><?php echo $user->display_name ?></a></li>
               <li><a href="wp-login.php?logout">Logout</a></li>
            </ul>

            <?php

       }
       add_action( 'sb_dc_component-user-controls', 'render_user_controls' );

   ?>

And that's all.