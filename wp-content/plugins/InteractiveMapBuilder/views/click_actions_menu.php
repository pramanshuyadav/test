
<?php foreach($click_actions as $click_action): ?>
	
		<?php if($highlighted_id == $click_action->get_id()) : ?>
			<div class="element highlighted">
		<?php else: ?>
			<div class="element linked">
		<?php endif; ?>
				<div class="link">
					<a href="<?php echo $click_action->get_pagelink("show"); ?>" actionid="<?php echo $click_action->get_id(); ?>"><?php echo esc_html($click_action->get_name()); ?></a>
				</div>
				<div class="nolink"><?php echo esc_html($click_action->get_name()); ?></div>
				<div class="edit_field"><input form="none" type="text" value="<?php echo esc_html($click_action->get_name()); ?>" /></div>
				<div class="small_arrow"></div>
			</div>

<?php endforeach; ?>
