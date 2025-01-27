<?php

include_once 'shortcode.php';
include_once 'metaboxes.php';
include_once 'user-roles.php';
include_once 'helpers.php';

/*
Plugin Name: KAU Research Searching Platform (RSP) Plugin
Description: Track research projects
Version: 0.1
Author: Jeremy Karlsson
License: MIT
*/

/**
 * @package WordPress
 */


class WPKauRSP_Plugin
{
  public function __construct()
  {
    add_action('init', array($this, 'init'));
  }

  function init()
  {
    register_post_type('research-project', array(
      'labels' => array(
        'name' => __('Research Projects'),
        'singular_name' => __('Research Project')
      ),
      'public' => true,
      'show_in_rest' => true,
      'rest_base' => 'research-projects',
      'supports' => array('title', 'excerpt'),
      'capability_type'    => 'post',
      'capabilities' => array(
        'edit_post'          => 'edit_rsp',
        'read'               => 'read_rsp',
        'delete_post'        => 'delete_rsp',
        'edit_posts'         => 'edit_rsp',
        'edit_others_posts'  => 'edit_others_rsp',
        'publish_posts'      => 'publish_rsp',
        'create_posts'       => 'create_rsp',
      )
    ));

    add_filter('single_template', array($this, 'load_research_project_template'));
    // add_action('rest_api_init', array($this, 'register_rest_route'));

    new WPKauRSP_Shortcode();
    new WPKauRSP_Metaboxes();
    new WPKauRSP_UserRoles();
  }

  function register_rest_route() {
    register_rest_route('wp-kau-rsp/v1', '/research-projects', array(
      'methods' => 'GET',
      'callback' => 'WPKauRSP_Helpers::get_research_projects',
    ));
  }

  function load_research_project_template($template)
  {
    global $post;

    if ($post->post_type == 'research-project') {
      $template = trailingslashit(plugin_dir_path(__FILE__)) . 'templates/single-research-project.php';
    }

    return $template;
  }
}

new WPKauRSP_Plugin();
