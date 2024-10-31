<?php 
global $post, $custom;

if( !$custom )
	$custom = $this->get_post_custom( $post->ID );

$this_type = 'custom';
?>

<input type="hidden" name="content" value='empty' />
	
<table class="form-table">
	<tbody>
		<tr>
			<?php $nextItem = 'custom_url'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Link URL</label>
			</th>
			<td>
				<input class="widefat" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][ $nextItem ]; ?>">
				<span class="howto">This is the main url destination of the link; where visitors will end up at.</span>
			</td>
		</tr>
	</tbody>
</table>