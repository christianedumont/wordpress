<div class="wrap">
	<div class="inline-block">
		<h1><?php _e('Edit quotation', 'quotations')?></h1>
		<a href="admin.php?page=quotations/admin/quotations-list.php" class="page-title-action"><?php _e('Back to list', 'quotations')?></a>
	</div>
	<?php if (!empty($error)): ?>
    <div id="error" class="error"><p><?php echo $error ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="success"><p><?php echo $message ?></p></div>
    <?php endif;?>
	<form action="<?php esc_url( $_SERVER["REQUEST_URI"]) ?>" method="post">
		<input type="hidden" name="tokenonce" value="<?php echo wp_create_nonce($current_user->ID . '-' . $current_user->user_login) ?>">
		<input type="hidden" name="id" value="<?php echo $item['id'] ?>">
		<div class="form-row">
			<label for="content"><?php _e('Content', 'quotations')?> (*)</label>
			<input type="text" name="content" id="content" value="<?php echo esc_attr($item['content'])?>" maxlength="255" placeholder="<?php _e('Enter your content', 'quotations')?>" aria-required="true" required>
		</div>
		<div class="form-row">
			<label for="author"> <?php _e('Author', 'quotations')?> (*)</label>
			<input type="text" name="author" id="author" value="<?php echo esc_attr($item['author'])?>" maxlength="50" placeholder="Enter the author">
		</div>
		<div class="form-row">
			<button class="button button-primary" type="submit" name="btn-submit" value="Send"><?php _e('Send', 'quotations')?></button>
		</div>
	</form>
</div>