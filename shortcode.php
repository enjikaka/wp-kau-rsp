<?php
class WPKauRSPShortcode
{
    public function __construct()
    {
        add_shortcode('list_research_projects', array($this, 'render'));
    }

    function render()
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
