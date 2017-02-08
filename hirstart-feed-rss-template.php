<?php
/**
 * Template Name: Custom RSS Template - Hírstart
 */
date_default_timezone_set('Europe/Budapest');
$timezone = new DateTimeZone('Europe/Budapest');
$postCount = 20; // The number of posts to show in the feed
$posts = query_posts('showposts=' . $postCount);
header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?><rss version="2.0"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:wfw="http://wellformedweb.org/CommentAPI/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:atom="http://www.w3.org/2005/Atom"
        xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
        xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
        <?php do_action('rss2_ns'); ?>>
<channel>
        <title><?php bloginfo_rss('name'); ?></title>
        <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
        <link><?php bloginfo_rss('url') ?></link>
        <description><?php bloginfo_rss('description') ?></description>
        <lastBuildDate><?php
        $myDateTime = new DateTime(get_lastpostmodified('GMT'), $timezone);
        echo $myDateTime->format('D, d M Y H:i:s +0'.($timezone->getOffset($myDateTime)/3600).'00');
        ?></lastBuildDate>
        <language><?php /*echo get_option('rss_language');*/ ?>hu-HU</language>
        <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
        <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
        <?php do_action('rss2_head'); ?>
        <?php while(have_posts()) : the_post(); ?>
                <item>
                        <?
                        $categories = get_the_category();
                        $categories = json_decode(json_encode($categories), true);
                        $tags = get_the_tags();
                        ?>
                        <title><?php the_title_rss(); ?></title>
                        <link><?php the_permalink_rss(); ?></link>
                        <pubDate><?php
                        $myDateTime = new DateTime(get_post_time('Y-m-d H:i:s', true), $timezone);
                        echo $myDateTime->format('D, d M Y H:i:s +0'.($timezone->getOffset($myDateTime)/3600).'00');
                        ?></pubDate>
                        <dc:creator><?php bloginfo_rss('name'); ?></dc:creator>
                        <guid isPermaLink="false"><?php the_guid(); ?></guid>
                        <description><?php the_excerpt_rss(); ?></description>
                        <?php
                        $hirstart_category = array();
                        foreach($categories as $k => $v){
                                $hirstart_category[get_term_meta($v['term_id'], '_hirstart_cat_title', true)] += 1;
                        }
                        $hirstart_category = array_merge(array_flip($hirstart_category));
                        foreach($hirstart_category as $k => $v){
                                print "<category>".$v."</category>";
                        }
                        ?>
                        <?php rss_enclosure(); ?>
                        <?php do_action('rss2_item'); ?>
                </item>
        <?php endwhile; ?>
</channel>
</rss>