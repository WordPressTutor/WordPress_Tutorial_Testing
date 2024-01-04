<?php

function search_option() {
    $search_query = get_search_query();

    ?>
    <form role="search" method="get" id="searchform">
        <div>
            <label for="s">Search for:</label>
            <input type="text" value="<?php echo $search_query; ?>" name="s" id="s" />
            <input type="submit" id="searchsubmit" value="Search" />
        </div>
    </form>

    <?php
    $post_args = array(
        'post_type'      => 'custompost',
        'posts_per_page' => -1,
        's'              => $search_query,
    );
    $post_query = new WP_Query($post_args);
    if ($post_query->have_posts()) :
        ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Categories</th>
                </tr>
            </thead>
            <tbody>
        <?php
        while ($post_query->have_posts()) :
            $post_query->the_post();
            ?>
            <tr>
                <td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
                <td><?php the_excerpt(); ?></td>
                <td><?php the_category(', '); ?></td>
            </tr>
            <?php
        endwhile;
        ?>
            </tbody>
        </table>
        <?php
    else :
        echo 'No posts found.';
    endif;
}

add_action('get_search_form', 'search_option');


?>
