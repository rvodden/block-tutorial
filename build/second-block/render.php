<?php
$firstname = get_post_meta( $block->context['postId'], 'firstname', true );
?>
<BlockQuote>
<p><?php $firstname ?></p>
</BlockQuote>

