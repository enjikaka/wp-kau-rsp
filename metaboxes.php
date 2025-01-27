<?php

class Metaboxes
{
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_post'));
        wp_enqueue_style('wp-kau-rsp__metaboxs__styles', plugins_url('styles/metaboxes.css', __FILE__));
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'research_project_details',
            'Research Project Details',
            array($this, 'display_meta_boxes'),
            'research-project',
            'normal',
            'default'
        );
    }

    public function save_post($post_id)
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

    public function display_meta_boxes($post)
    {
        $initial_value_department = get_post_meta($post->ID, 'department', true);
        $initial_value_researchers = get_post_meta($post->ID, 'researchers', true);
        $initial_value_research_status = get_post_meta($post->ID, 'research_status', true);

        $department_value = esc_attr($initial_value_department);
        $researchers_value = esc_attr($initial_value_researchers);
        $in_progress_selected = $initial_value_research_status == 'in_progress' ? 'selected' : '';
        $completed_selected = $initial_value_research_status == 'in_progress' ? 'selected' : '';

        echo <<<HTML
            <table class="wp-kau-rsp-table">
                <tr>
                    <td><label for="department">Department:</label></td>
                    <td><input type="text" id="department" name="department" value="{$department_value}" disabled="disabled" /></td>
                </tr>
                <tr>
                    <td><label for="researchers">Researchers:</label></td>
                    <td><input type="text" id="researchers" name="researchers" value="{$researchers_value}" /></td>
                </tr>
                <tr>
                    <td><label for="research_status">Status:</label></td>
                    <td>
                        <select id="research_status" name="research_status">
                            <option value="in_progress" {$in_progress_selected}>In Progress</option>
                            <option value="completed" {$completed_selected}>Completed</option>
                        </select>
                    </td>
                </tr>
            </table>
        HTML;
    }
}
