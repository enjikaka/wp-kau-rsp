<?php
get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post();

?>
        <article>
            <header>
                <h1><?php the_title(); ?></h1>
            </header>
            <div>
                <?php the_content(); ?>
            </div>
            <div>
                <h2>Department</h2>
                <p><?php echo get_post_meta(get_the_ID(), 'department', true); ?></p>
            </div>
            <div>
                <h2>Researchers</h2>
                <p><?php echo get_post_meta(get_the_ID(), 'researchers', true); ?></p>
            </div>
            <div>
                <h2>Status</h2>
                <p><?php echo get_post_meta(get_the_ID(), 'research_status', true); ?></p>
            </div>
        </article>
<?php
    }
}

get_footer();

?>