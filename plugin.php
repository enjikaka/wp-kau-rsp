<?php

include('shortcode.php');
include('metaboxes.php');

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
      'supports' => array('title', 'excerpt')
    ));

    new Shortcode();
    new Metaboxes();

    add_filter('single_template', array($this, 'load_research_project_template'));
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
