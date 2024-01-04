<?php
function listing_post() {
    get_header();

    // Check if a category filter is applied
    $category_filter = isset($_GET['category']) ? $_GET['category'] : '';

    $title_filter = isset($_GET['title']) ? sanitize_text_field($_GET['title']) : ''; // Sanitize title input

    // Set the current page number.
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    // Set the number of posts to display per page.
    $posts_per_page = 10;

    // Query the posts.
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
    );

    // Apply category filter if it is set
    if (!empty($category_filter)) {
        $args['category_name'] = $category_filter;
    }

    // Apply title filter if it is set
    if (!empty($title_filter)) {
        $args['s'] = $title_filter; // Use 's' parameter to search post titles
    }

    $query = new WP_Query($args);

    // Output category filter form
    $categories = get_categories();
    ?>
    <form method="get">
        <label for="category">Filter by Category:</label>
        <select name="category" id="category">
            <option value="">All Categories</option>
            <?php
            foreach ($categories as $cat) {
                echo '<option value="' . esc_attr($cat->slug) . '" ' . selected($category_filter, $cat->slug, false) . '>' . esc_html($cat->name) . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Filter">
    </form>

    <?php
    // Fetch distinct post titles for title filter dropdown
    $title_args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'fields'         => 'ids', // Fetch only post IDs
    );
    $title_query = new WP_Query($title_args);
    $titles = $title_query->posts;

    // Output title filter form
    ?>
    <form method="get">
        <label for="title">Filter by Title:</label>
        <select name="title" id="title">
            <option value="">All Titles</option>
            <?php
            foreach ($titles as $title_post_id) {
                $title = get_the_title($title_post_id);
                echo '<option value="' . esc_attr($title) . '" ' . selected($title_filter, $title, false) . '>' . esc_html($title) . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Filter">
    </form>

    <br><br>

    <?php
    if ($query->have_posts()) :
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
        while ($query->have_posts()) :
            $query->the_post();
            // Your post content here.
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

        // Custom pagination.
        $total_pages = $query->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="pagination">';
            echo paginate_links(array(
                'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format'    => '?paged=%#%',
                'current'   => max(1, get_query_var('paged')),
                'total'     => $total_pages,
                'prev_text' => __('« Prev'),
                'next_text' => __('Next »'),
            ));
            echo '</div>';
        }

        // Reset post data.
        wp_reset_postdata();

    else :
       
        echo __('No posts found', 'textdomain');
    endif;
}
add_action('wp_listing_post', 'listing_post');
?>
