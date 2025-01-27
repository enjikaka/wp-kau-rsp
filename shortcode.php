<?php
class WPKauRSP_Shortcode
{
    public function __construct()
    {
        wp_enqueue_script('web-component__research-project-card', plugins_url('web-components/research-project-card.js', __FILE__));
        wp_enqueue_style('wp-kau-rsp__shortcode__main-styles', plugins_url('styles/main.css', __FILE__));
        add_shortcode('list_research_projects', array($this, 'render'));
    }

    function render()
    {
        $current_site = get_current_blog_id();
        $is_main_site = $current_site == 1;
        $projects = $is_main_site ? WPKauRSP_Helpers::get_published_research_projects_from_all_sites() : WPKauRSP_Helpers::get_all_research_projects_from_department($current_site);
        $header = $is_main_site ? 'Published Research Projects' : 'Our Research Projects';

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
                        <span slot="researchers">{$project['researchers']}</span>
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
}
