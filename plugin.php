<?php
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


class WPKauRSP
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

    add_shortcode('list_research_projects', array($this, 'render_published_research_projects'));
    add_action('add_meta_boxes', array($this, 'add_research_project_meta_boxes'));
    add_action('save_post', array($this, 'save_research_project_meta'));
    add_filter('single_template', array($this, 'load_research_project_template'));
  }

  function render_published_research_projects()
  {
    $projects = $this->get_research_projects_from_all_sites();

    $string = '<ul>';
    foreach ($projects as $project) {
      $string .= '<li>' . $project['title'] . '</li>';
    }
    $string .= '</ul>';

    wp_reset_postdata();

    return $string;
  }

  function add_research_project_meta_boxes()
  {
    add_meta_box(
      'research_project_details',
      'Research Project Details',
      array($this, 'display_research_project_meta'),
      'research-project',
      'normal',
      'default'
    );
  }

  function display_research_project_meta($post)
  {
    $initial_value_department = get_post_meta($post->ID, 'department', true);
    $initial_value_researchers = get_post_meta($post->ID, 'researchers', true);
    $initial_value_research_status = get_post_meta($post->ID, 'research_status', true);
?>
    <style>
      .wp-kau-rsp-table,
      .wp-kau-rsp-table input,
      .wp-kau-rsp-table select {
        width: 100%;
      }

      .wp-kau-rsp-table tr {
        display: flex;
      }

      .wp-kau-rsp-table td {
        flex: 1;
      }
    </style>
    <?php echo json_encode(get_post_meta($post->ID)) ?>
    <table class="wp-kau-rsp-table">
      <tr>
        <td><label for="department">Department:</label></td>
        <td><input type="text" id="department" name="department" value="<?php echo esc_attr($initial_value_department); ?>" disabled="disabled" /></td>
      </tr>
      <tr>
        <td><label for="researchers">Researchers:</label></td>
        <td><input type="text" id="researchers" name="researchers" value="<?php echo esc_attr($initial_value_researchers); ?>" /></td>
      </tr>
      <tr>
        <td><label for="research_status">Status:</label></td>
        <td>
          <select id="research_status" name="research_status">
            <option value="in_progress" <?php if ($initial_value_research_status == 'in_progress') echo 'selected'; ?>>In Progress</option>
            <option value="completed" <?php if ($initial_value_research_status == 'completed') echo 'selected'; ?>>Completed</option>
          </select>
        </td>
      </tr>
    </table>
<?php
  }

  function save_research_project_meta($post_id)
  {
    $current_site_id = get_current_blog_id();
    update_post_meta($post_id, 'department', $current_site_id);

    if (array_key_exists('researchers', $_POST)) {
      update_post_meta($post_id, 'researchers', sanitize_text_field($_POST['researchers']));
    }

    if (array_key_exists('research_status', $_POST)) {
      update_post_meta($post_id, 'research_status', $_POST['research_status']);
    }
  }

  function load_research_project_template($template)
{
  global $post;

  if ($post->post_type == 'research-project') {
    $template = trailingslashit(plugin_dir_path(__FILE__)) . 'templates/single-research-project.php';
  }

  return $template;
}

function get_research_projects_from_all_sites()
{
  $sites = get_sites();
  $blog_posts = array();

  if ($sites) {
    foreach ($sites as $site) {
      switch_to_blog($site->blog_id);
      $args = array(
        'post_type' => 'research-project',
        'post_status' => 'publish'
      );

      $query = new WP_Query($args);
      if ($query->have_posts()) {
        while ($query->have_posts()) {
          $query->the_post();
          $blog_posts[] = array(
            'title' => get_the_title(),
            'researchers' => get_post_meta(get_the_ID(), 'researchers', true),
            'research_status' => get_post_meta(get_the_ID(), 'research_status', true),
            'department' => $site->blog_id
          );
        }
      }
    }
  }

  restore_current_blog();
  return $blog_posts;
}
}

new WPKauRSP();
