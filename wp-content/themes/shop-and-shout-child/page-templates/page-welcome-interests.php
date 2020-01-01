<?php

get_header();

?>
<div id="main-content">
    <div class="welcome-interests-wrapper">
        <h1>One more step</h1>
        <p>Let us know what sort of Influencer you are.</p>
        <form id="inf-welcome-form" class="sas-form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
            <p>(Select up to 3 interests)</p>
            <?php
                if( isset( $_GET['err'] ) && $_GET['err'] == 1 ) {
                    echo '<p style="color: red;">You can only select up to 3 interests</p>';
                }
                echo do_shortcode('[inf-interests-boxes]');
            ?>
            <br><br><br>
            <button type="submit" class="welcome-button welcome-shop" disabled="disabled"><img class="welcome-img-left" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/Heart.svg'; ?>"><span>Go to the marketplace<span><img class="welcome-img-right" src="<?php echo get_stylesheet_directory_uri() . '/images/icons/arrow_white.svg'; ?>"></button>

            <input type="hidden" name="action" value="select_interests" />
        </form>
    </div>
</div> <!-- #main-content -->

<?php

get_footer();