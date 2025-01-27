<?php

class WPKauRSP_Helpers {
    public static function get_all_research_projects_from_department($current_blog_id)
    {
        $blog_posts = array();

        $args = array(
            'post_type' => 'research-project',
            'post_status' => 'any'
        );

        if ($current_blog_id == 1) {
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

    public static function get_published_research_projects_from_all_sites()
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