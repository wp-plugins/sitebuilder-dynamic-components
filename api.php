<?php

/**
 * register component
 * @param  string                    $name [description]
 * @param  array                     $args [description] 
 */
function sb_dc_register_component( $name, $args = null ){

	SB_DC_Components::getInstance()->registerComponent( $name, $args );

}

/**
 * get registered components 
 * @return array
 */
function sb_dc_get_registered_components(){

	return SB_DC_Components::getInstance()->getComponents();

}

/**
 * register page argument
 * @param  string                    $key   [description]
 * @param  mixed                     $value [description]
 */
function sb_dc_register_page_arg( $key, $value ){

	SB_DC_Components::getInstance()->registerPageArg( $key, $value );

}

/**
 * get registered page arguments
 * @return array
 */
function sb_dc_get_page_args(){

	return SB_DC_Components::getInstance()->getPageArgs();

}

/**
 * register and insert component into page
 * @param  string                    $name [description]
 * @param  array                     $args [description]
 * @param  string                    $tag  [description] 
 */
function sb_dc_insert_component( $name, $args = null, $tag = 'div' ){

	sb_dc_register_component( $name, $args );

	echo "<{$tag} id=\"sb-dc-{$name}\"></{$tag}>";

}