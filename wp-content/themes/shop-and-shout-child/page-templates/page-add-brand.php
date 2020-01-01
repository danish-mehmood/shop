<?php

get_header();

$current_user = wp_get_current_user();

if(!in_array('administrator', $current_user->roles) && !in_array('brand', $current_user->roles)) {
    wp_redirect(get_site_url());
}

?>
<div id="main-content">
    <div id="add-brand-form-wrapper">

        <form id="add-brand-form" class="sas-form">
            <h2>Please enter your Brand's information below.</h2>

            <label><span class="form-required">*</span> <b>Required</b></label>

            <br><br>

            <div class="form-errors"></div>

            <h3>Brand Info</h3>
            <div class="form-section">
                <div class="form-row">
                    <div class="form-item">
                        <label>Brand Name <span class="form-required">*</span></label>
                        <input type="text" id="brand-name" required>
                    </div>
                    <div class="form-item">
                        <label>Brand Website <span class="form-required">*</span></label>
                        <input type="text" name="brand_website" id="brand-website" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-item">
                        <label for="story">Brand Story<br>Tell us and our Influencers what you're all about.<span class="form-required">*</span></label>
                        <textarea placeholder="Describe your business" id="brand-story" rows=5 required><?php echo esc_html($brand_story); ?></textarea>
                    </div>  
                </div>
            </div>

            <?php wp_nonce_field('add_brand', 'add_brand_nonce'); ?>
            <button class="sas-round-button-primary" type="submit">Submit</button>
        </form>

        <div id="add-brand-success" style="display: none;">
            <h2>Brand creation successful</h2>
            <p>Head back to <a href="<?php echo get_site_url() . '/brand-account' ?>">your brand dashboard</a> and get started by Designing a new ShoutOut Campaign.</p>
        </div>
    </div>
</div> <!-- #main-content -->

<?php

get_footer();