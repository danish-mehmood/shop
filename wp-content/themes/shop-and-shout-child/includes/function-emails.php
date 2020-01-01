<?php
// generate email body
function generate_email_body( $content, $additional_css ) {
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<style type="text/css">
@media screen {
    @font-face {
        font-family: 'Muli';
        font-style: normal;
        font-weight: 400;
        src: local('Muli Regular'), local('Muli-Regular'), url(https://fonts.gstatic.com/s/muli/v12/7Auwp_0qiz-afTLGLQ.woff2) format('woff2');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
}
body *{
    font-family: 'Muli';
}
h1 {
    text-align: center;
}
h1,h2,h3,h4,h5{
    font-weight: normal;
}
p, li{
    font-size: 18px;
}
#body-table {
    padding: 15px;
}
#footer {
    font-size: 12px;
    font-family: Courier;
    text-align: center;
    padding: 20px;
}

<?php echo $additional_css; ?>
</style>
</head>
<body>
<table align="center" width="100%" height="100%">
<tr>
<td>
<table align="center" width="600" id="body-table">
<tr>
<td>
    <a href="https://shopandshout.com/"><img width="240" height="24" src="https://shopandshout.com/wp-content/themes/shop-and-shout-child/images/logos/shop-and-shout.png"></a>
</td>
</tr>
<tr>
<td>
   <?php echo $content; ?>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align="center">
    <br><br>
    <p>As seen on.</p>
    <br>
    <a style="padding: 5px;" href="http://dailyhive.com/vancouver/download-this-app-shoutouts-shopandshout-influencer" target="_blank"><img src="<?php echo get_stylesheet_directory_uri() . '/images/logos/'; ?>daily-hive-1.png" width="26" height="40"></a>


    &nbsp;&nbsp;<a style="padding: 5px;" href="https://www.huffingtonpost.com/entry/the-future-of-marketing_us_58f7de9be4b0b6ca134160db" target="_blank"><img src="<?php echo get_stylesheet_directory_uri() . '/images/logos/'; ?>Huffpost.jpg" width="33" height="40"></a>


    &nbsp;&nbsp;<a style="padding: 5px;" href="https://www.lifewithsass.com/mom/2017/6/27/lets-sponsor-a-classroom" target="_blank"><img src="<?php echo get_stylesheet_directory_uri() . '/images/logos/'; ?>life-with-sass.png" width="38" height="40"></a>


    &nbsp;&nbsp;<a style="padding: 5px;" href="https://theopenjournal.net/2017/08/24/business-behind-blogging/" target="_blank"><img src="<?php echo get_stylesheet_directory_uri() . '/images/logos/'; ?>open-journal.png" width="40" height="40"></a>


    &nbsp;&nbsp;<a style="padding: 5px;" href="https://thenovascoop.com/2017/08/12/shop-shout-a-social-marketplace/" target="_blank"><img src="<?php echo get_stylesheet_directory_uri() . '/images/logos/'; ?>the-nova-scoop.png" width="55" height="40"></a>

</td>
</tr>
<tr>
<td>
    <br>
    <p id="footer">Shop and Shout ltd. | 725 Granville St Suite 420 V7Y 1C6 | Vancouver BC Canada</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
<?php
return ob_get_clean();
}

/**
 * Test email
 */
function email_user_test( $user_email,  $message_from, $message_subject, $message_body ) {

    ob_start();
    ?>

    <p><?php echo $message_body ?></p>

    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, $additional_css);

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
    // Additional headers
    $headers[] = 'From: Shop and Shout <'.$message_from.'>';

    if( !SAS_TEST_MODE ) {

        $headers[] = 'Cc: dev@shopandshout.com';
        mail($user_email, $message_subject, $body, implode("\r\n", $headers));

    } else {

        mail('dev@shopandshout.com', $message_subject, $body, implode("\r\n", $headers));
    }
}
/*------------------------------------*/
/*-------- Emails for Admins ---------*/
/*------------------------------------*/

/**
 * Email admin on Influencer IG assistant opt in
 */
function email_admin_social_signup_error ( $email, $uuid, $social_id ) {

    ob_start();
    ?>
        <h1>Social Signup Error</h1>

        <p>Email: <?php var_dump($email); ?></p>
        <p>UUID: <?php var_dump($uuid); ?></p>
        <p>Social Channel: <?php var_dump(get_post_meta($uuid, 'social_channel', true)); ?></p>
        <p>SocialID: <?php var_dump($social_id); ?></p>

    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <influencers@shopandshout.com>';

    $subject = 'ERROR';

    mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
}

/**
 * Email admin on Influencer IG assistant opt in
 */
function email_admin_ig_assistant_opt_in ( $influencer_id, $already_opted ) {

    // Influencer info
    $userdata = get_userdata( $influencer_id );
    $email = $userdata->data->user_email;
    $name = $userdata->data->display_name;
    $handle = get_user_meta( $influencer_id, 'social_prism_user_instagram', true );
    $reach = get_user_meta( $influencer_id, 'social_prism_user_instagram_reach', true );
    $engagement = get_user_meta( $influencer_id, 'instagram-engagement-rate', true );
    $assistant_info = get_user_meta( $influencer_id, 'ig_assistant_info', true );

    ob_start();
    ?>
        <h1>Instagram Assistant</h1>

        <p><?php echo esc_html($name); ?>(<?php echo esc_html($email); ?>)  has <?php echo ( $already_opted ? 'updated their Instagram Assistant info' : 'opted in to the Instagram Assistant program' ); ?></p>

        <table>
            <tr>
                <th>IG Handle:</th>
                <td>@<?php echo esc_html($handle); ?></td>
            </tr>
            <tr>
                <th>Reach:</th>
                <td><?php echo esc_html($reach); ?></td>
            </tr>
            <tr>
                <th>Engagement:</th>
                <td><?php echo esc_html($engagement); ?>%</td>
            </tr>
            <tr>
                <th>Hashtags:</th>
                <td><?php echo esc_html($assistant_info['hashtags']); ?></td>
            </tr>
            <tr>
                <th>Accounts:</th>
                <td><?php echo esc_html($assistant_info['accounts']); ?></td>
            </tr>
        </table>
        <br><br>
        <b>Why are you most interested in using our Instagram Assistant?</b>
        <p><?php echo esc_html($assistant_info['reason']); ?></p>

        <p>View this Influencer's Instagram info <a href="<?php echo get_site_url() . '/wp-admin/user-edit.php?user_id=' . $influencer_id . '#ig-assistant'; ?>">here</a></p>

    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <influencers@shopandshout.com>';

    $subject = 'Instagram Assistant';

    if( !SAS_TEST_MODE ) {

        mail( 'vinod@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );

    } else {

        mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
    }
}

/**
 * Email admin on Influencer IG boost opt in
 */
function email_admin_ig_boost_opt_in ( $brand_id, $already_opted ) {

    // Influencer info
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);
    $boost_info = get_post_meta( $brand_id, 'ig_boost_info', true );

    ob_start();
    ?>
        <h1>Instagram Boost</h1>

        <p><?php echo esc_html($brand_name); ?> has <?php echo ( $already_opted ? 'updated their Instagram Boost info.' : 'opted in to the Instagram Boost program' ) ?></p>

        <p>View this Brand's Instagram info <a href="<?php echo get_site_url() . '/wp-admin/post.php?post=' . $brand_id . '&action=edit#instagram-boost-info'; ?>">here</a></p>

        <h5>Brand Contacts</h5>
        <table cellpadding="5">
            <tr>
                <th>Name</th>
                <th>email</th>
                <th>phone</th>
            </tr>
            <?php foreach( $brand_contacts as $contact ) : ?>
                <tr>
                    <td><?php echo esc_html($contact['name']) . ($contact['primary_contact'] ? ' <b>(primary contact)</b>' : ''); ?></td>
                    <td><?php echo esc_html($contact['email']); ?></td>
                    <td><?php echo esc_html($contact['phone']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <influencers@shopandshout.com>';

    $subject = 'Instagram Boost';

    if( !SAS_TEST_MODE ) {

        mail( 'brands@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );

    } else {

        mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
    }
}

/**
 * Email admin on brand signup
 */
function email_admin_brand_signup ( $brand_id ) {

    // Brand info
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);

    ob_start();
    ?>
        <h1>New brand signup</h1>

        <p><?php echo $brand_contacts[0]['name'] . ' (' . $brand_contacts[0]['email'] . ')'; ?> has signed up for the brand <b><?php echo $brand_name ?></b></p>
    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

    $subject = 'New brand signup';

    if( !SAS_TEST_MODE ) {

        mail( 'brands@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );

    } else {
        mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
    }
}


/**
 * Email admin on brand request demo
 */
function email_admin_brand_demo ( $info, $contact_name, $company_name, $contact_email, $contact_phone ) {

    ob_start();
    ?>
        <h1>A brand just requested more info</h1>

        <p>
            <?php echo $contact_name ?> representing <b><?php echo $company_name ?></b> would like more info about:
        </p>

        <p>
            <?php echo implode( '<br>', $info ); ?>
        </p>

        <p>
            <b>Email: <?php echo $contact_email; ?></b>

            <br>

            <b>Phone: <?php echo $contact_phone; ?></b>
        </p>
    <?php

    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

    $subject = 'Brand would like more info';

    if( !SAS_TEST_MODE ) {

        mail( 'brands@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );

    } else {

        mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
    }
}

/**
 * Email admin on brand request demo
 */
function email_admin_brand_demo_simple ( $contact_email ) {

    ob_start();
    ?>
        <h1>A brand just requested a demo</h1>

        <p>
            <?php echo $contact_email ?> just requested a demo
        </p>
    <?php

    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

    $subject = 'Brand would like more info';

    if( !SAS_TEST_MODE ) {

        mail( 'brands@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );

    } else {

        mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
    }
}

function email_admin_brand_shoutout_error ( $order_id, $campaign_id, $channel, $report ) {

    // Brand info
    $brand_id = get_post_meta( $campaign_id, 'brand', true );
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);
    $campaign_title = get_the_title($campaign_id);
    ?>
        <h1>ShoutOut error</h1>

        <p><?php echo esc_html($brand_name); ?> has reported an error with a<?php echo ($channel == 'instagram' ? 'n' : '' ); ?> <?php echo esc_html($channel); ?> ShoutOut for their <b><?php echo esc_html($campaign_title); ?></b> campaign.</p>

        <p>
            <b>issue:</b>

            <br>

            <?php echo esc_html($report); ?>
        </p>

        <h5>Brand Contacts</h5>
        <table cellpadding="5">
            <tr>
                <th>Name</th>
                <th>email</th>
                <th>phone</th>
            </tr>
            <?php foreach( $brand_contacts as $contact ) : ?>
                <tr>
                    <td><?php echo esc_html($contact['name']) . ($contact['primary_contact'] ? ' <b>(primary contact)</b>' : ''); ?></td>
                    <td><?php echo esc_html($contact['email']); ?></td>
                    <td><?php echo esc_html($contact['phone']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <a href="<?php echo get_site_url() . '/wp-admin/post.php?post=' . $order_id . '&action=edit'; ?>">View Order</a>
    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

    $subject = 'New brand signup';

    if( !SAS_TEST_MODE ) {

        mail( 'influencers@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );

    } else {

        mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
    }
}

//email influencers@shopandshout on ShoutOut rating
function email_admin_shoutout_rating ( $order_id, $channel, $product_id, $rating, $notes, $brand_id ) {

    // Brand Info
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);

    // Influencer
    $influencer_id = get_order_influencer($order_id);
    $influencer_data = get_userdata($influencer_id);
    $influencer_name = $influencer_data->first_name . ' ' . $influencer_data->last_name;

    $post_url = get_post_meta( $order_id, $channel . '_url', true );

    ob_start();
    ?>
    <h1><?php echo $brand_name ?> just scored a ShoutOut!</h1>
    <p><?php echo $brand_name ?> has submitted a ShoutOut score of <i style="color: #0099ff;"><?php echo $rating ?> hearts</i> for <?php echo $influencer_name ?>'s <?php echo $channel ?> post <a href="<?php echo $post_url ?>"><?php echo $post_url ?></a></p>
    <?php if( $rating <= 3 ) : ?>
        <p style="color: red;">This ShoutOut has been given a low score and needs attention, please contact the brand</p>
    <?php endif; ?>
    <p>Notes: <?php echo $notes ?></p>

    <h5>Brand Contacts</h5>
    <table cellpadding="5">
        <tr>
            <th>Name</th>
            <th>email</th>
            <th>phone</th>
        </tr>
        <?php foreach( $brand_contacts as $contact ) : ?>
            <tr>
                <td><?php echo esc_html($contact['name']) . ($contact['primary_contact'] ? ' <b>(primary contact)</b>' : ''); ?></td>
                <td><?php echo esc_html($contact['email']); ?></td>
                <td><?php echo esc_html($contact['phone']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
    // Additional headers
    $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

    if( !SAS_TEST_MODE ) {
        // $headers[] = 'Cc: influencers@shopandshout.com';

        mail('influencers@shopandshout.com', $brand_name . ' has submitted a ShoutOut review', $body, implode("\r\n", $headers));
    } else {
        mail('dev@shopandshout.com', $brand_name . ' has submitted a ShoutOut review', $body, implode("\r\n", $headers));
    }
}

//email admin on new product creation or when brand selects membership level
function email_admin_new_campaign ( $campaign_id ) {

    // Brand
    $brand_id = get_post_meta($campaign_id, 'brand', true);
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);

    // Campaign
    $campaign_title = get_the_title($campaign_id);

    ob_start();
    ?>
    <h1><?php echo $brand_name ?> has submitted a new Campaign for review</h1>

    <p>View <?php echo $campaign_title ?> campaign <a href="<?php echo get_site_url() . '/wp-admin/post.php?post=' . $campaign_id . '&action=edit'; ?>">here</a></p>

    <h5>Brand Contacts</h5>
    <table cellpadding="5">
        <tr>
            <th>Name</th>
            <th>email</th>
            <th>phone</th>
        </tr>
        <?php foreach( $brand_contacts as $contact ) : ?>
            <tr>
                <td><?php echo esc_html($contact['name']) . ($contact['primary_contact'] ? ' <b>(primary contact)</b>' : ''); ?></td>
                <td><?php echo esc_html($contact['email']); ?></td>
                <td><?php echo esc_html($contact['phone']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    //To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

    if( !SAS_TEST_MODE ) {
        // $headers[] = 'Cc: influencers@shopandshout.com';

        mail('brands@shopandshout.com', 'ShoutOut Campaign Ready For Review', $msg, implode("\r\n", $headers));
    } else {
        mail('dev@shopandshout.com', 'ShoutOut Campaign Ready For Review', $msg, implode("\r\n", $headers));
    }
}

function email_admin_draft_campaign( $campaign_id ) {

    $brand_id = get_post_meta( $campaign_id, 'brand', true );
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);
    $campaign_title = get_the_title( $campaign_id );

    ob_start();
    ?>
    <h1><?php echo $brand_name ?> just saved a draft for their <?php echo $campaign_title ?> ShoutOut Campaign</h1>

    <p><?php echo $brand_name ?> saved a draft, they may need help finalizing their submission.</p>

    <p><a href="<?php echo get_site_url() . '/wp-admin/post.php?post=' . $campaign_id . '&action=edit'; ?>"><?php echo get_site_url() . '/wp-admin/post.php?post=' . $campaign_id . '&action=edit'; ?></a></p>


    <h5>Brand Contacts</h5>
    <table cellpadding="5">
        <tr>
            <th>Name</th>
            <th>email</th>
            <th>phone</th>
        </tr>
        <?php foreach( $brand_contacts as $contact ) : ?>
            <tr>
                <td><?php echo esc_html($contact['name']) . ($contact['primary_contact'] ? ' <b>(primary contact)</b>' : ''); ?></td>
                <td><?php echo esc_html($contact['email']); ?></td>
                <td><?php echo esc_html($contact['phone']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, $additional_css);

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
    // Additional headers
    $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

    if( !SAS_TEST_MODE ) {

        // $headers[] = 'Cc: influencers@shopandshout.com';
        mail('brands@shopandshout.com', $brand_name . ' has submitted a ShoutOut review', $body, implode("\r\n", $headers));

    } else {

        mail('dev@shopandshout.com', $brand_name . ' has submitted a ShoutOut review', $body, implode("\r\n", $headers));
    }
}

function email_admin_edit_campaign( $campaign_id, $differences ) {

    $brand_id = get_post_meta( $campaign_id, 'brand', true );
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);
    $campaign_title = get_the_title( $campaign_id );

    $additional_css = '
        .values-table th, .values-table td{
            text-align: left;
            padding: 7px 2px 7px 0;
        }
    ';

    ob_start();
    ?>
    <h1><?php echo $brand_name ?> just edited their <?php echo $campaign_title ?> ShoutOut Campaign</h1>
    <p><?php echo $brand_name ?> has changed the following values on their Campaign.</p>

    <table class="values-table">
        <tr>
            <th>Field</th>
            <th>Old value</th>
            <th>New value</th>
        </tr>

        <?php foreach( $differences as $difference ) : ?>

            <?php if( $difference ) : ?>

            <tr>
                <th><?php echo $difference['title']; ?></th>
                <td><?php echo $difference['old']; ?></td>
                <td><?php echo $difference['new']; ?></td>
            </tr>

            <?php endif; ?>

        <?php endforeach; ?>
    </table>

    <h5>Brand Contacts</h5>
    <table cellpadding="5">
        <tr>
            <th>Name</th>
            <th>email</th>
            <th>phone</th>
        </tr>
        <?php foreach( $brand_contacts as $contact ) : ?>
            <tr>
                <td><?php echo esc_html($contact['name']) . ($contact['primary_contact'] ? ' <b>(primary contact)</b>' : ''); ?></td>
                <td><?php echo esc_html($contact['email']); ?></td>
                <td><?php echo esc_html($contact['phone']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, $additional_css);

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
    // Additional headers
    $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

    if( !SAS_TEST_MODE ) {

        // $headers[] = 'Cc: influencers@shopandshout.com';
        mail('brands@shopandshout.com', $brand_name . ' has submitted a ShoutOut review', $body, implode("\r\n", $headers));

    } else {

        mail('dev@shopandshout.com', $brand_name . ' has submitted a ShoutOut review', $body, implode("\r\n", $headers));
    }
}

/*------------------------------------*/
/*------ Emails for Influencers ------*/
/*------------------------------------*/

// email influencer on brand tracking link submission
function email_inf_brand_tracking_link( $order_id, $campaign_id, $brand_id, $tracking_link ) {
    $influencer_id = get_order_influencer($order_id);
    $influencer_data = get_userdata($influencer_id);
    $influencer_first_name = $influencer_data->first_name;
    $influencer_email = $influencer_data->user_email;
    $brand_name = get_the_title($brand_id);
    $campaign_name = get_the_title($campaign_id);

    ob_start();
    ?>
        <h1><?php echo $brand_name; ?> is shipping to you!</h1>
        <p>Hi <?php echo $influencer_first_name ?>, <?php echo $brand_name ?> has shipped your product for the "<b><?php echo $campaign_name ?></b>" ShoutOut campaign!</p>

        <?php if( $tracking_link ) : ?>

            <p>You can track it by following this link: <a href="<?php echo $tracking_link ?>"><?php echo $tracking_link ?></a></p>

        <?php endif; ?>
    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <influencers@shopandshout.com>';

    if( !SAS_TEST_MODE ) {
        // $headers[] = 'Cc: influencers@shopandshout.com';

        mail($influencer_email, $brand_name . ' has submitted a code for your ShoutOut', $body, implode("\r\n", $headers));
    } else {
        mail( 'dev@shopandshout.com', $brand_name . ' has shipped to you', $body, implode("\r\n", $headers));
    }
}

// email influencer on brand code submission
function email_inf_brand_web_redemption_code( $order_id, $campaign_id, $brand_id, $code ) {
    $influencer_id = get_order_influencer($order_id);
    $influencer_data = get_userdata($influencer_id);
    $influencer_first_name = $influencer_data->first_name;
    $influencer_email = $influencer_data->user_email;
    $brand_name = get_the_title($brand_id);
    $campaign_name = get_the_title($campaign_id);
    $web_redemption_url = get_post_meta($campaign_id, 'promo_code_url', true);

    ob_start();
    ?>
        <h1><?php echo $brand_name; ?> just sent you a web redemption code!</h1>
        <p>Hi <?php echo $influencer_first_name ?>, <?php echo $brand_name ?> has sent you a web redemption code for the "<b><?php echo $campaign_name ?></b>" ShoutOut campaign!</p>
        <p>Your code is: <b><?php echo $code ?></b></p>
        <p>You can redeem it here: <a href="<?php echo $web_redemption_url ?>"><?php echo $web_redemption_url ?></a></p>
    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <influencers@shopandshout.com>';

    if( !SAS_TEST_MODE ) {

        mail($influencer_email, $brand_name . ' has submitted a code for your ShoutOut', $body, implode("\r\n", $headers));

    } else {

        mail( 'dev@shopandshout.com', $brand_name . ' has submitted a code for your ShoutOut', $body, implode("\r\n", $headers));
    }
}


// email influencer on brand code submission
function email_inf_brand_reject_shoutout( $order_id, $campaign_id, $boxes, $other ) {

    $influencer_id = get_order_influencer( $order_id );
    $influencer_data = get_userdata($influencer_id);
    $influencer_first_name = $influencer_data->first_name;
    $influencer_last_name = $influencer_data->last_name;
    $influencer_email = $influencer_data->user_email;
    $brand_id = get_post_meta($campaign_id, 'brand', true);
    $brand_name = get_the_title($brand_id);
    $campaign_title = get_the_title( $campaign_id );

    ob_start();
    ?>
        <h1>It's not you, it's us.</h1>

        <p>Hi <?php echo esc_html($influencer_first_name) . ' ' . esc_html($influencer_last_name); ?>,</p>

        <p>Unfortunately, <?php echo esc_html($brand_name); ?> has decided that you will not be able to participate in the <b><?php echo esc_html($campaign_title); ?></b> ShoutOut campaign due to:</p>

        <ul>
        <?php foreach( $boxes as $option ) : ?>

            <li><?php echo ($option != 'other' ? $option : $other); ?></li>

        <?php endforeach; ?>
        </ul>

        <p>We love having you in our community, and hope you can find another ShoutOut that better aligns with your persona.</p>

        <p>If you have any questions please email influencers@shopandshout.com</p>
    <?php
    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <influencers@shopandshout.com>';

    $subject = 'ShoutOut Cancelled for ' . $brand_name;

    if( !SAS_TEST_MODE ) {

        mail( $influencer_email, $subject, $body, implode("\r\n", $headers));

    } else {

        mail( 'dev@shopandshout.com', $subject, $body, implode("\r\n", $headers));
    }
}

/*------------------------------------*/
/*-------- Emails for Brands ---------*/
/*------------------------------------*/

/**
 * Email brand on campaign submission
 */
function email_brand_brand_signup ( $brand_id ) {

    // Brand info
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);

    foreach($brand_contacts as $contact) {
        
        if(!$contact['email_opt_out']) {
             ob_start();
            ?>
                <h1>Welcome to Shop and Shout!</h1>

                <p>Hi <?php echo $contact['name']; ?>,</p>

                <p>Thanks for joining our community! We're excited to get started :)</p>

                <p>If you'd like to launch an Influencer campaign please</p>

                <a href="<?php echo get_site_url() . '/design-campaign/?b=' . $brand_id; ?>"><img src="<?php echo get_stylesheet_directory_uri() . '/images/buttons/submit-a-shoutout-campaign.png'; ?>"></a>

                <p>Don't worry about nailing it, fill it out as best as possible. One of our campaign success managers will be in touch shortly to review it and ensure you're set up for success! You will have the opportunity to preview the campaign, and make any final edits before it goes live.</p>

                <p>If you'd like to speak to a member of our brand memberships team first, please <a href="https://meetings.hubspot.com/vinod2">schedule a time that works for you</a>.</p>

                <p>We look forward to working with you, and helping you grow.</p>

                <p>Sincerely,</p>

                <p>Your Brand Support Team<br>brands@shopandshout.com</p>
            <?php
            $msg = ob_get_clean();

            $body = generate_email_body($msg, '');

            // To send HTML mail, the Content-type header must be set
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';

            // Additional headers
            $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

            $subject = 'Welcome to Shop and Shout, ' . $brand_name . '!';

            if( !SAS_TEST_MODE ) {

                mail( $contact['email'], $subject, $body, implode( "\r\n", $headers ) );

            } else {

                mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
            }
        }
    }
}

/**
 * Email brand user invite
 */
function email_brand_user_invite($email, $invite_string, $brand_id) {

    $brand = get_post($brand_id);

    $brand_name = $brand->post_title;

    $invite_url = get_site_url() . '/brand-signup/?invite=' . $invite_string;

    ob_start();
    ?>
        <p>Hi there, <?php echo esc_html($brand_name); ?> has invited you to join ShopandShout as a Brand representative</p>
        <p>Click the following link to sign up: <a href="<?php echo esc_url($invite_url) ?>"><?php echo esc_url($invite_url) ?></a></p>
    <?php

    $msg = ob_get_clean();

    $body = generate_email_body($msg, '');

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

    $subject = 'You\'re invited to ShopandShout';

    mail( $email, $subject, $body, implode( "\r\n", $headers ) );
}

/**
 * Email brand new campaign
 */
function email_brand_new_campaign ( $brand_id, $campaign_id ) {

    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);

    $campaign_title = get_the_title( $campaign_id );

    foreach($brand_contacts as $contact) {
        if(!$contact['email_opt_out']) {

            ob_start();
            ?>
                <h1>ShoutOut campaign submitted</h1>

                <p>Hi <?php echo $contact['name']; ?>,</p>

                <p>Thanks for submitting your <b><?php echo $campaign_title; ?></b> ShoutOut Campaign!</p>

                <p>Our team is reviewing your submission, and we'll send a preview link with any notes or questions for you before it goes live. It will require your final approval before we push it live in the marketplace.</p>

                <p>Look forward to putting <?php echo $brand_name; ?> in the social feeds of some awesome Influencers soon!</p>

                <p>Sincerely,</p>

                <p>Your Brand Support Team<br>brands@shopandshout.com</p>
            <?php
            $msg = ob_get_clean();

            $body = generate_email_body($msg, '');

            // To send HTML mail, the Content-type header must be set
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';

            // Additional headers
            $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

            $subject = 'Almost ready to launch, ' . $brand_name;

            if( !SAS_TEST_MODE ) {

                mail( $contact['email'], $subject, $body, implode( "\r\n", $headers ) );

            } else {

                mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
            }
        }
    }
}

/**
 * Email brand when an Influencer makes a ShoutOut order
 */

add_action( 'woocommerce_checkout_order_processed', 'email_brand_shoutout_order', 10, 1 );
function email_brand_shoutout_order( $order_id ) {
    
    // Campaign info
    $campaign_id = get_order_campaign( $order_id );

    $order = wc_get_order( $order_id );
    $order_items = $order->get_items();
    $item_product = reset($order_items);
    $product = $item_product->get_product();

    if( $product->get_type() == 'variation' ) {

        $campaign_title = $product->get_data()['name'];

    } else {

        $campaign_title = get_the_title( $campaign_id );
    }

    $fulfillment_type = get_post_meta( $campaign_id, 'fulfillment_type', true );

    $brand_id = get_post_meta($campaign_id, 'brand', true);

    if( $brand_id !== '' ) {

        // Brand info
        $brand_name = get_the_title($brand_id);
        $brand_contacts = get_brand_contacts($brand_id);

        // Influencer info
        $influencer_id = get_order_influencer( $order_id );
        $influencer_data = get_userdata( $influencer_id );
        $influencer_nicename = $influencer_data->data->user_nicename;
        $influencer_first_name = ucfirst( strtolower( get_user_meta( $influencer_id, 'first_name', true ) ) );
        $influencer_last_name = ucfirst( strtolower( get_user_meta( $influencer_id, 'last_name', true ) ) );

        $extra_info = '';

        if( $fulfillment_type = 'shipping' ) {

            // Shipping info
            $shipping_first_name = get_user_meta( $influencer_id, 'shipping_first_name', true );
            $shipping_last_name = get_user_meta( $influencer_id, 'shipping_last_name', true );
            $shipping_company = get_user_meta( $influencer_id, 'shipping_company', true );
            $shipping_country = get_user_meta( $influencer_id, 'shipping_country', true );
            $shipping_country = WC()->countries->countries[$shipping_country];
            $shipping_state = get_user_meta( $influencer_id, 'shipping_state', true );
            $shipping_city = get_user_meta( $influencer_id, 'shipping_city', true );
            $shipping_postcode = get_user_meta( $influencer_id, 'shipping_postcode', true );
            $shipping_address_1 = get_user_meta( $influencer_id, 'shipping_address_1', true );
            $shipping_address_2 = get_user_meta( $influencer_id, 'shipping_address_2', true );

            $extra_info = '<h2>Shipping Details:</h2>'
                . '<p>'
                . '<b>Name</b>: ' . $shipping_first_name . ' ' . $shipping_last_name
                . ( $shipping_company !== '' ? '<br>Company: ' . $shipping_company : '' )
                . '<br><br><b>Address</b>:<br> ' . $shipping_address_1
                . ( $shipping_address_2 !== '' ? ' ' . $shipping_address_2 : '' )
                . '<br>' . $shipping_city
                . ' ' . $shipping_state
                . ', ' . $shipping_country
                . ', ' . $shipping_postcode
                . '</p>';
        }

        foreach($brand_contacts as $contact) {
            if(!$contact['email_opt_out']) {
                ob_start();
                ?>
                    <h1>New ShoutOut coming from <?php echo $influencer_first_name . ' ' . $influencer_last_name; ?>!</h1>

                    <p>Hi <?php echo $contact['name']; ?>, <a href="<?php echo get_site_url() . '/influencer/' . $influencer_nicename; ?>"><?php echo $influencer_first_name . ' ' . $influencer_last_name; ?></a> is about to give a ShoutOut for your <b><?php echo $campaign_title ?></b> campaign.</p>

                    <?php echo $extra_info; ?>

                    <p>Please visit <a href="<?php echo get_site_url() . '/brand-account/'; ?>">your account</a> as further action may be required on your side.</p>

                    <p>Sincerely,</p>

                    <p>Your Brand Support Team<br>brands@shopandshout.com</p>
                <?php
                $msg = ob_get_clean();

                $body = generate_email_body($msg, '');

                // To send HTML mail, the Content-type header must be set
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';

                // Additional headers
                $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

                $subject = 'Wooo! You got another ' . $brand_name . ' Influencer about to give you a ShoutOut!';

                if( !SAS_TEST_MODE ) {

                   $success = mail( $contact['email'], $subject, $body, implode( "\r\n", $headers ) );

                } else {

                    $success = mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
                }

                file_put_contents(get_stylesheet_directory().'/email-log.txt', $success, FILE_APPEND);
            }
        }
    }
}

//email brand on ShoutOut link submission
function email_brand_shoutout_links( $order_id ) {

    $order = wc_get_order( $order_id );
    $order_date = $order->get_data()['date_created']->date('d M Y');

    // Campaign info
    $campaign_id = get_order_campaign( $order_id );
    $campaign_name = get_the_title( $campaign_id );
    $shoutout_channels = get_post_meta( $campaign_id, 'shoutout_channels', true );

    $links = '';
    foreach ( $shoutout_channels as $channel ) {

        $channel_url = get_post_meta( $order_id, $channel . '_url', true );

        $links .= '<p>' . ucfirst( $channel ) . ' ShoutOut: <a href="' . $channel_url . '">' . $channel_url . '</a></p>';
    }

    // Influencer info
    $influencer_id = get_order_influencer( $order_id );
    $influencer_data = get_userdata( $influencer_id );
    $influencer_first_name = $influencer_data->first_name;
    $influencer_last_name = $influencer_data->last_name;

    $brand_id = get_post_meta( $campaign_id, 'brand', true );

    if( $brand_id !== '' ) {

        // Brand info
        $brand_name = get_the_title($brand_id);
        $brand_contacts = get_brand_contacts($brand_id);

        foreach($brand_contacts as $contact) {
            if(!$contact['email_opt_out']) {

                ob_start();
                ?>
                    <h1>New ShoutOut submitted!</h1>

                    <p>Hi <?php echo $contact['name'] ?>,</p>

                    <p>Remember <?php echo $influencer_first_name . ' ' . $influencer_last_name; ?> who opted in on <b><?php echo $order_date; ?></b>? They've completed their ShoutOut and now it's time to review it!</p>

                    <p>We've created a scoring system based on 5 hearts to guide you.<br>Scoring the Influencer's content helps us ensure that you are happy with the content. Our Influencers are incentivized by us to obtain higher scores - so we hope you love your ShoutOut!</p>

                    <?php echo $links ?>

                    <p>Please <a href="<?php echo get_site_url() . '/brand-account/'; ?>">score the ShoutOut</a> as soon as you can to remove the hold on the Influencer's card!</p>

                    <p>Sincerely,</p>

                    <p>Your Brand Support Team<br>brands@shopandshout.com</p>
                <?php

                $msg = ob_get_clean();

                $body = generate_email_body($msg, '');

                // To send HTML mail, the Content-type header must be set
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';

                // Additional headers
                $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

                $subject = 'We\'ve got another ShoutOut for you, ' . $brand_name . '!';

                if( !SAS_TEST_MODE ) {

                    mail( $contact['email'], $subject, $body, implode( "\r\n", $headers ) );

                } else {

                    mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
                }
            }
        }
    }
}

//email brand on ShoutOut link submission
function email_brand_nudge_shipping( $order_id ) {

    $influencer_id = get_order_influencer( $order_id );
    $influencer_data = get_userdata( $influencer_id );
    $first_name = $influencer_data->first_name;
    $last_name = $influencer_data->last_name;
    $insta_handle = get_user_meta( $influencer_id, 'social_prism_user_instagram', true );

    $campaign_id = get_order_campaign( $order_id );
    $campaign_title = get_the_title( $campaign_id );

    $brand_id = get_post_meta( $campaign_id, 'brand', true );
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);

    foreach($brand_contacts as $contact) {
        if(!$contact['email_opt_out']) {
            ob_start();
            ?>
                <h1>Shipment info needed</h1>

                <p>Dear <?php echo esc_html($brand_name); ?>,</p>

                <p><?php echo esc_html( $first_name . ' ' . $last_name ); ?> here, just looking for the tracking details so I can complete my Shoutout on time for you!</p>

                <p>Please send me the updated tracking information at your earliest convenience <a href="<?php echo get_site_url() . '/brand-account/'; ?>">here</a>.</p>

                <p>Thank you kindly!<br><a href="<?php echo 'https://www.instagram.com/' . $insta_handle; ?>">@<?php echo $insta_handle; ?></a></p>
            <?php

            $msg = ob_get_clean();

            $body = generate_email_body($msg, '');

            // To send HTML mail, the Content-type header must be set
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';

            // Additional headers
            $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

            $subject = esc_html( $first_name . ' ' . $last_name ) . ' has not recieved their ' . $campaign_title . ' yet!';

            if( !SAS_TEST_MODE ) {

                mail( $contact['email'], $subject, $body, implode( "\r\n", $headers ) );

            } else {

                mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
            }
        }
    }
}

//email brand on ShoutOut link submission
function email_brand_nudge_scoring( $order_id ) {

    $influencer_id = get_order_influencer( $order_id );
    $influencer_data = get_userdata( $influencer_id );
    $first_name = $influencer_data->first_name;
    $last_name = $influencer_data->last_name;
    $insta_handle = get_user_meta( $influencer_id, 'social_prism_user_instagram', true );

    $campaign_id = get_order_campaign( $order_id );
    $campaign_title = get_the_title( $campaign );

    $brand_id = get_post_meta( $campaign_id, 'brand', true );
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);

    foreach($brand_contacts as $contact) {
        if(!$contact['email_opt_out']) {
            ob_start();
            ?>
                <h1>ShoutOut waiting to be scored</h1>

                <p>Dear <?php echo esc_html($brand_name); ?>,</p>

                <p><?php echo esc_html( $first_name . ' ' . $last_name ); ?> here, just letting you know that I've submitted my ShoutOut for scoring, and would love for you to take a look!</p>

                <p>Please view and score my ShoutOut <a href="<?php echo get_site_url() . '/brand-account/'; ?>">here</a>.</p>

                <p>Thank you kindly!<br><a href="<?php echo 'https://www.instagram.com/' . $insta_handle; ?>">@<?php echo $insta_handle; ?></a></p>
            <?php

            $msg = ob_get_clean();

            $body = generate_email_body($msg, '');

            // To send HTML mail, the Content-type header must be set
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';

            // Additional headers
            $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

            $subject = esc_html( $first_name . ' ' . $last_name ) . ' is requesting you to score their ShoutOut!';

            if( !SAS_TEST_MODE ) {

                mail( $contact['email'], $subject, $body, implode( "\r\n", $headers ) );

            } else {

                mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
            }
        }
    }
}

//email brand on Giveaway winner submission
function email_brand_giveaway_winner( $order_id ) {

    $influencer_id = get_order_influencer( $order_id );
    $influencer_data = get_userdata( $influencer_id );
    $first_name = $influencer_data->first_name;
    $last_name = $influencer_data->last_name;
    $insta_handle = get_user_meta( $influencer_id, 'social_prism_user_instagram', true );

    $campaign_id = get_order_campaign( $order_id );
    $campaign_title = get_the_title( $campaign_id );

    $brand_id = get_post_meta( $campaign_id, 'brand', true );
    $brand_name = get_the_title($brand_id);
    $brand_contacts = get_brand_contacts($brand_id);

    $giveaway_winner = get_post_meta($order_id, 'giveaway_winner', true);

    foreach($brand_contacts as $contact) {
        if(!$contact['email_opt_out']) {
            ob_start();
            ?>
                <h1>Giveaway Winner Submitted</h1>

                <p>Dear <?php echo esc_html($brand_name); ?>,</p>

                <p><?php echo esc_html( $first_name . ' ' . $last_name . ' (@' . $insta_handle . ')' ); ?> has submitted the winner of their giveaway for your "<?php echo $campaign_title ?>" Campaign</p>

                <p><b>The winner's email is <?php echo $giveaway_winner ?></b></p>

                <p>You can view the ShoutOut <a href="<?php echo get_site_url() . '/brand-account/'; ?>">here</a>.</p>
            <?php

            $msg = ob_get_clean();

            $body = generate_email_body($msg, '');

            // To send HTML mail, the Content-type header must be set
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';

            // Additional headers
            $headers[] = 'From: Shop and Shout <brands@shopandshout.com>';

            $subject = esc_html( $first_name . ' ' . $last_name ) . ' has submitted their Giveaway winner';

            if( !SAS_TEST_MODE ) {

                mail( $contact['email'], $subject, $body, implode( "\r\n", $headers ) );

            } else {

                mail ( 'dev@shopandshout.com', $subject, $body, implode( "\r\n", $headers ) );
            }
        }
    }
}
?>
