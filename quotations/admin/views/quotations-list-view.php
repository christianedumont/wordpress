<div class="wrap">
	<div class="inline-block">
		<h1><?php _e('Quotations', 'quotations')?></h1>
		<a href="admin.php?page=quotations/admin/quotations-form.php" class="page-title-action"><?php _e('Add new', 'quotations')?></a>
	</div>
	<p class="icon32 icon32-posts-post" id="icon-edit"></p>
    <?php echo $message; ?>
    <form id="quotations-form" method="post">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>">
        <?php $table->display() ?>
    </form>
</div>