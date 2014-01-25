<?php echo '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n"; ?>
<rss version="2.0"
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
   xmlns:admin="http://webns.net/mvcb/"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:content="http://purl.org/rss/1.0/modules/content/">


	<channel>


		<link><?php echo $feed_url; ?></link>
		<description><?php echo $page_description; ?></description>
		<dc:language><?php echo $page_language; ?></dc:language>
		<dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>


		<?php foreach($posts->result() as $post): ?>
		<item>
			<title><?php echo xml_convert($post->c_title); ?></title>
			<link><?php echo base_url($post->r_url_rw . '/' . $post->c_url_rw); ?></link>
			<guid><?php echo base_url($post->r_url_rw . '/' . $post->c_url_rw); ?></guid>
			<?php $post->c_content = strip_tags($post->c_content); ?>
			<description>
				<?php echo $post->c_content; ?>
			</description>
			<?php $date = strtotime($post->c_cdate); // Conversion date to timestamp ?>
			<pubDate><?php echo date('r', $date);?></pubDate>
		</item>
	<?php endforeach; ?>


	</channel>


</rss>