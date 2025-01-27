<?php
class Shortcode
{
    public function __construct()
    {
        wp_enqueue_script('web-component__research-project-card', plugins_url('web-components/research-project-card.js', __FILE__));
        wp_enqueue_style('wp-kau-rsp__shortcode__main-styles', plugins_url('styles/main.css', __FILE__));
        add_shortcode('list_research_projects', array($this, 'render'));
    }

    function render()
    {
        $projects = get_current_blog_id() == 1 ? $this->get_published_research_projects_from_all_sites() : $this->get_all_research_projects_from_department();
        $header = get_current_blog_id() == 1 ? 'Published Research Projects' : 'Our Research Projects';

        $listItems = implode('', array_map(function ($project) {
            $maybeDepartmentInformation = isset($project['departmentPath']) ? <<<HTML
                <a href="{$project['departmentPath']}" slot="department">{$project['departmentName']}</a>
            HTML : '';

            return <<<HTML
                <li>
                    <research-project-card research-status="{$project['research_status']}">
                        <a href="{$project['href']}" slot="title">
                            {$project['title']}
                        </a>
                        <p slot="excerpt">{$project['excerpt']}</p>
                        {$maybeDepartmentInformation}
                    </research-project-card>
                </li>
            HTML;
        }, $projects));

        $string = <<<HTML
            <div class="research-projects-list-wrapper">
                <strong>{$header}</strong>
                <ul>{$listItems}</ul>
            </div>
        HTML;

        wp_reset_postdata();
        return $string;
    }

    function get_all_research_projects_from_department()
    {
        $blog_posts = array();

        $args = array(
            'post_type' => 'research-project',
            'post_status' => 'any'
        );

        if (get_current_blog_id() == 1) {
            $args['post_status'] = 'publish';
        }

        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $blog_posts[] = array(
                    'href' => get_the_permalink(),
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'researchers' => get_post_meta(get_the_ID(), 'researchers', true),
                    'research_status' => get_post_meta(get_the_ID(), 'research_status', true)
                );
            }
        }

        return $blog_posts;
    }

    function get_published_research_projects_from_all_sites()
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
                            'href' => get_the_permalink(),
                            'title' => get_the_title(),
                            'excerpt' => get_the_excerpt(),
                            'researchers' => get_post_meta(get_the_ID(), 'researchers', true),
                            'research_status' => get_post_meta(get_the_ID(), 'research_status', true),
                            'departmentName' => get_blog_details($site->blog_id)->blogname,
                            'departmentPath' => get_blog_details($site->blog_id)->siteurl,
                            'departmentId' => $site->blog_id,
                        );
                    }
                }
            }
        }

        restore_current_blog();
        return $blog_posts;
    }
}
