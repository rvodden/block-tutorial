<?php
global $post;
$composerId = get_post_meta($post->ID, 'composer', true);
error_log("Composer ID: " . $composerId);
if ($compserId) {
	$composer = get_the_title($composerId);
} else {
	$composer = "Composer";
}
?>
<p><?= $composer ?></p>

