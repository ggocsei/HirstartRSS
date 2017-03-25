<?php
/**
 * Template Name: Custom RSS Template - Hírstart
 */

$gmt_offset = get_option('gmt_offset');
$gmt_offset_value = abs($gmt_offset);
switch (strlen($gmt_offset_value)) {
		case 1:
				$gmt_offset_value = '0'.$gmt_offset_value.'00';
				break;
		case 2:
				$gmt_offset_value = $gmt_offset_value.'00';
				break;
		case 3:
				$gmt_offset_value = '0'.$gmt_offset_value.'0';
				$gmt_offset_value = str_replace(".","",$gmt_offset_value);
				break;
		case 4:
				$gmt_offset_value = $gmt_offset_value.'0';
				$gmt_offset_value = str_replace(".","",$gmt_offset_value);
				break;
}

$gmt_offset_value = ($gmt_offset<0?'-':'+').$gmt_offset_value;
$expiresdate = date('D, d M Y H:i:s ',strtotime(get_lastpostmodified('blog'))).$gmt_offset_value;

session_cache_limiter('');
header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
header("Cache-Control: private, no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0");
header("Expires: ".$expiresdate);
header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
$postCount = 20; // The number of posts to show in the feed
query_posts('showposts=' . $postCount);

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
		<lastBuildDate><?php echo $expiresdate;?></lastBuildDate>
		<language><?php echo get_bloginfo("language"); ?></language>
		<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
		<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '0' ); ?></sy:updateFrequency>
		<?php do_action('rss2_head'); ?>
		<?php while(have_posts()) : the_post(); ?>
				<item><?
						?>
						<title><?php the_title_rss(); ?></title>
						<link><?php the_permalink_rss(); ?></link>
						<pubDate><?= get_post_time('D, d M Y H:i:s '.$gmt_offset_value, false);?></pubDate>
						<dc:creator><?php bloginfo_rss('name'); ?></dc:creator>
						<guid isPermaLink="false"><?php the_id(); ?></guid>
						<description><?php the_excerpt_rss(); ?></description>
						<?php
						$categories = get_the_category();
						$categories = json_decode(json_encode($categories), true);
						$hirstart_category = array();
						$original_categories = '';
						$tagcounter = 0;
						$categorycounter = 0;
						$origcategorycounter = 0;
						foreach($categories as $k => $v){
								$hs_variables = get_term_meta($v['term_id']);
								$hs_cat = false;
								$hs_cat_tag = false;
								if(is_array($hs_variables) && count($hs_variables)){
									if(isset($hs_variables["_hirstart_cat_title"][0])){
											$hs_cat = $hs_variables["_hirstart_cat_title"][0];
									}
									if(isset($hs_variables["_hirstart_cat_tag"][0])){
											$hs_cat_tag = $hs_variables["_hirstart_cat_tag"][0];
									}
								}
								$tags = '';
								$tags_valid_item = false;
								if($hs_cat_tag == 1){
									$tags = get_the_tags();
									if(is_array($tags)){
										foreach($tags as $kk => $vv){
											if(!strpos($vv->description, '!nohirstart')){
												if(!isset($hirstart_category[ucfirst($vv->name)])){$hirstart_category[ucfirst($vv->name)]=(0+$tagcounter);}
												$tagcounter = $tagcounter + 100;
												$hirstart_category[ucfirst($vv->name)] += 1;
												$tags_valid_item = true;
											}
										}
									}
								}
								if($tags_valid_item == false){
									if($hs_cat){
										if(!isset($hirstart_category[$hs_cat])){$hirstart_category[$hs_cat]=(10000+$categorycounter);}
										$categorycounter = $categorycounter + 100;
										$hirstart_category[$hs_cat] += 1;
									} else {
										if(!isset($hirstart_category[$v["name"]])){$hirstart_category[$v["name"]]=(20000+$origcategorycounter);}
										$origcategorycounter = $origcategorycounter + 100;
										$hirstart_category[$v["name"]] += 1;
									}
								}
						}
						if(count($hirstart_category)){
								$hirstart_category = array_flip($hirstart_category);
								ksort($hirstart_category);
								foreach($hirstart_category as $k => $v){
										print "<category>".$v."</category>";
								}
						}
						?>
						<?php rss_enclosure(); ?>
						<?php do_action('rss2_item');?>
				</item>
		<?php endwhile; ?>
</channel>
</rss>