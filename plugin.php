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

function create_research_project_post_type()
{
  register_post_type('research_project', array(
    'labels' => array(
      'name' => __('Research Projects'),
      'singular_name' => __('Research Project')
    ),
    'public' => true,
    'show_in_rest' => true,
    'supports' => array('title', 'excerpt')
  ));
}

add_action('init', 'create_research_project_post_type');

function add_research_project_meta_boxes()
{
  add_meta_box(
    'research_project_details',
    'Research Project Details',
    'display_research_project_meta',
    'research_project',
    'normal',
    'default'
  );
}
add_action('add_meta_boxes', 'add_research_project_meta_boxes');

function display_research_project_meta($post)
{
  $initial_value_department = get_post_meta($post->ID, 'department', true);
  $initial_value_researchers = get_post_meta($post->ID, 'researchers', true);
  $initial_value_research_status = get_post_meta($post->ID, 'research_status', true);

  $current_blog_id = strval(get_current_blog_id());
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
  <?php  echo json_encode(get_post_meta($post->ID)) ?>
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
add_action('save_post', 'save_research_project_meta');
