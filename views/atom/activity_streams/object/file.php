<?php

$file = $vars['entity'];
$download_url = elgg_get_site_url() . "file/download/{$file->getGUID()}";

?>

<id>
	<?php echo "{$vars['url']}$file->guid"; ?>
</id>
<title>
	<?php echo elgg_view('output/text', array('value' => $file->title)); ?>
</title>
<content type="html">
	<?php echo elgg_view('output/text', array('value' => $file->description)); ?>
</content>
<published>
	<?php echo date(DATE_ATOM, $file->time_created); ?>
</published>
<updated>
	<?php echo date(DATE_ATOM, $file->time_updated); ?>
</updated>
<activity:object-type>file</activity:object-type>
<link rel="preview" type="image/png" href="<?php echo htmlspecialchars($file->getIcon('small')); ?>" />
<link rel="alternate" type="text/html" href="<?php echo htmlspecialchars($file->getURL()); ?>" />
<link rel="enclosure" type="<?php echo $file->getMimeType(); ?>" href="<?php echo htmlspecialchars($download_url); ?>" />
