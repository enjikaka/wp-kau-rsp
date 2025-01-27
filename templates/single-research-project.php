<?php
get_header();

wp_enqueue_style('wp-kau-rsp__shortcode__main-styles', plugins_url('styles/main.css', __FILE__));
wp_enqueue_style('wp-kau-rsp__single-research-project__styles', plugins_url('../styles/single-research-project.css', __FILE__));

if (have_posts()) {
    while (have_posts()) {
        the_post();

?>
        <article>
            <header>
                <h1><?php the_title(); ?></h1>
            </header>
            <div class="content">
                <?php the_excerpt(); ?>
            </div>
            <dl>
                <dt>Department</dt>
                <dd><?php echo get_post_meta(get_the_ID(), 'department', true); ?></dd>

                <dt>Researchers</dt>
                <dd><?php echo get_post_meta(get_the_ID(), 'researchers', true); ?></dd>

                <dt>Status</dt>
                <dd><?php echo get_post_meta(get_the_ID(), 'research_status', true); ?></dd>
            </dl>
        </article>
<?php
    }
}

get_footer();

?>