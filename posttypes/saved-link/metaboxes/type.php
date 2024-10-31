<?php 
global $post, $custom;

if( !$custom )
	$custom = $this->get_post_custom( $post->ID );

$link_types = $this->GetLinkTypes();
?>

<input type="hidden" name="content" value='empty' />
	
<table class="form-table">
	<tbody>
		<tr>
			<?php $nextItem = 'link_type'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Link Type</label>
			</th>
			<td>
				<select id="<?php echo $nextItem; ?>" data-placeholder="Choose a Link Type" name="field_<?php echo $nextItem; ?>" class="chzn-select">
					<option value=""></option> 
					
					<?php
					if( $link_types )
					{
						foreach( $link_types as $key => $value )
						{
							if( $custom[ $nextItem ] == $key )
								$selected = 'selected="selected"';
							else
								$selected = ''; ?>
					<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php
						}
					} ?>
				</select>
				<span class="howto">Choose what type of link this will be (determine's the settings)</span>
			</td>
		</tr>
		
	</tbody>
</table>

<script>
function HideAllBoxes( exception )
{
	<?php
	foreach( $link_types as $key => $value )
	{ ?>
	jQuery('#<?php echo $this->GetPre(); ?>-<?php echo $key; ?>').hide();
	<?php
	} ?>
	
	if( exception != null )
		jQuery('#<?php echo $this->GetPre(); ?>-' + exception ).show();
}

jQuery(document).ready( function()
{
	jQuery('#link_type').change( function(e) {
		HideAllBoxes( jQuery(this).val() );		
	} );
	
	jQuery('.chzn-select').chosen();
	
	HideAllBoxes( '<?php echo $custom['link_type']; ?>' );
	
} );
</script>
