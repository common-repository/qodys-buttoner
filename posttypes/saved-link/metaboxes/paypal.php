<?php 
global $post, $custom;

if( !$custom )
	$custom = $this->get_post_custom( $post->ID );

$button_types = $this->GetPaypalButtonTypes();
$currency_types = $this->GetPaypalCurrencyTypes();
$billing_cycle_units = $this->GetPaypalBillingCycleUnits();

$this_type = 'paypal';
?>

<script>
function HidePaypalSections( exception )
{
	jQuery('.paypal_section').hide();
	
	if( exception != null )
		jQuery('#paypal_section-' + exception ).show();
}

jQuery(document).ready( function()
{
	jQuery('#button_type').change( function(e) {
		HidePaypalSections( jQuery(this).val() );		
	} );
	
	HidePaypalSections( '<?php echo $custom['type_settings'][$this_type]['button_type']; ?>' );
	
} );
</script>
	
<table class="form-table">
	<tbody>
		<tr>
			<?php $nextItem = 'button_type'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Payment Type</label>
			</th>
			<td>
				<select id="<?php echo $nextItem; ?>" data-placeholder="Choose a Payment Type" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextItem; ?>]" class="chzn-select">
					<option value=""></option> 
					
					<?php
					if( $button_types )
					{
						foreach( $button_types as $key => $value )
						{
							if( $custom['type_settings'][$this_type][$nextItem] == $key )
								$selected = 'selected="selected"';
							else
								$selected = ''; ?>
					<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php
						}
					} ?>
				</select>
				
			</td>
		</tr>
		<tr>
			<?php $nextItem = 'paypal_email'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Email address to receive payments</label>
			</th>
			<td>
				<input class="widefat" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][ $nextItem ]; ?>">
				<span class="howto">This should be your paypal email</span>
			</td>
		</tr>
		<tr>
			<?php $nextItem = 'item_name'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Item Name</label>
			</th>
			<td>
				<input class="widefat" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][ $nextItem ]; ?>">
				<span class="howto">This is the name of the product that will show up on their receipt.</span>
			</td>
		</tr>
		<tr>
			<?php $nextItem = 'item_id'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Item ID (optional)</label>
			</th>
			<td>
				<input class="widefat mini_size" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][ $nextItem ]; ?>">
				<span class="howto">A helpful ID to use when reviewing transaction records.</span>
			</td>
		</tr>
		<tr>
			<?php $nextItem = 'currency'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Currency</label>
			</th>
			<td>
				<select id="<?php echo $nextItem; ?>" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextItem; ?>]">
					<option value=""></option> 
					
					<?php
					if( $currency_types )
					{
						foreach( $currency_types as $key => $value )
						{
							if( $custom['type_settings'][$this_type][$nextItem] == $key )
								$selected = 'selected="selected"';
							else
								$selected = ''; ?>
					<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php
						}
					} ?>
				</select>
				
				<span class="howto"></span>
			</td>
		</tr>
	</tbody>
</table>

<?php $nextSection = 'services'; ?>
<div class="paypal_section" id="paypal_section-<?php echo $nextSection; ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<?php $nextItem = 'price'; ?>
				<th>
					<label for="<?php echo $nextItem; ?>">Price</label>
				</th>
				<td>
					<input class="widefat mini_size" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ]; ?>">
					<span class="howto">How much a customer will pay</span>
				</td>
			</tr>
			
			<tr>
				<?php $nextItem = 'shipping'; ?>
				<th>
					<label for="<?php echo $nextItem; ?>">Shipping</label>
				</th>
				<td>
					<input class="widefat mini_size" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ]; ?>">
					<span class="howto">How much the shipping will cost</span>
				</td>
			</tr>
			<tr>
				<?php $nextItem = 'tax'; ?>
				<th>
					<label for="<?php echo $nextItem; ?>">Tax rate</label>
				</th>
				<td>
					<input class="widefat mini_size" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ]; ?>"> %
					<span class="howto">How much the tax will be</span>
				</td>
			</tr>
			<tr>
				<?php $nextItem = 'allow_quantity_change'; ?>
				<th>
					<label for="<?php echo $nextItem; ?>">Do you want to let your customer change order quantities?</label>
				</th>
				<td>
					<label>
						<input type="radio" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="yes" <?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ] == 'yes' ? 'checked' : ''; ?>> 
						Yes
					</label>
					<br>
					<label>
						<input type="radio" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="no" <?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ] != 'yes' ? 'checked' : ''; ?>> 
						No
					</label>
				</td>
			</tr>
			<tr>
				<?php $nextItem = 'allow_special_instructions'; ?>
				<th>
					<label for="<?php echo $nextItem; ?>">Can your customer add special instructions in a message to you?</label>
				</th>
				<td>
					<label>
						<input type="radio" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="yes" <?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ] == 'yes' ? 'checked' : ''; ?>> 
						Yes
					</label>
					<br>
					<label>
						<input type="radio" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="no" <?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ] != 'yes' ? 'checked' : ''; ?>> 
						No
					</label>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<?php $nextSection = 'subscriptions'; ?>
<div class="paypal_section" id="paypal_section-<?php echo $nextSection; ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<?php $nextItem = 'a3'; ?>
				<th>
					<label for="<?php echo $nextItem; ?>">Billing amount each cycle</label>
				</th>
				<td>
					<input class="widefat mini_size" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ]; ?>">
					<span class="howto">How much a customer will pay</span>
				</td>
			</tr>
			<tr>
				<th>
					<label for="<?php echo $nextItem; ?>">Billing cycle</label>
				</th>
				<td>
					<?php $nextItem = 'p3'; ?>
					<select id="<?php echo $nextItem; ?>" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]">
						<?php
						for( $i = 1; $i <= 30; $i++ )
						{
							if( $custom['type_settings'][$this_type][$nextSection][$nextItem] == $i )
								$selected = 'selected="selected"';
							else
								$selected = ''; ?>
						<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php
						} ?>
					</select>
					
					<?php $nextItem = 't3'; ?>
					<select id="<?php echo $nextItem; ?>" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]">
						<?php
						if( $billing_cycle_units )
						{
							foreach( $billing_cycle_units as $key => $value )
							{
								if( $custom['type_settings'][$this_type][$nextSection][$nextItem] == $key )
									$selected = 'selected="selected"';
								else
									$selected = ''; ?>
						<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
							<?php
							}
						} ?>
					</select>
					
				</td>
			</tr>
			<tr>
				<?php $nextItem = 'srt'; ?>
				<th>
					<label for="<?php echo $nextItem; ?>">After how many cycles should billing stop?</label>
				</th>
				<td>
					<select id="<?php echo $nextItem; ?>" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]">
						<option value="-1">Never</option>
						<?php
						for( $i = 1; $i <= 30; $i++ )
						{
							if( $custom['type_settings'][$this_type][$nextSection][$nextItem] == $i )
								$selected = 'selected="selected"';
							else
								$selected = ''; ?>
						<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php
						} ?>
					</select>
					
				</td>
			</tr>
			<tr>
				<?php $nextItem = 'offer_trial_1'; ?>
				<th>
					<label>Offer a trial period?</label>
				</th>
				<td>
					<label>
						<input onclick="jQuery('#<?php echo $nextItem; ?>').hide(); jQuery('#<?php echo $nextItem; ?>').show();" type="radio" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="yes" <?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ] == 'yes' ? 'checked' : ''; ?>>
						Yes
					</label>
					<label>
						<input onclick="jQuery('#<?php echo $nextItem; ?>').show(); jQuery('#<?php echo $nextItem; ?>').hide();" type="radio" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="no" <?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ] != 'yes' ? 'checked' : ''; ?>>
						No
					</label>
					
					<span class="howto"></span>
				</td>
			</tr>
		</tbody>
	</table>
	
	<div id="offer_trial_1" style="display:<?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ] == 'yes' ? 'block' : 'none'; ?>;">
		<table class="form-table">
			<tbody>
				<tr>
					<?php $nextItem = 'a1'; ?>
					<th>
						<label class="sub_setting_label" for="<?php echo $nextItem; ?>">- Amount to bill for the trial period</label>
					</th>
					<td>
						<input class="widefat mini_size" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ]; ?>">
						<span class="howto">Customers will receive just one bill for each trial period.</span>
					</td>
				</tr>
				<tr>
					<th>
						<label class="sub_setting_label" for="<?php echo $nextItem; ?>">- Define the trial period</label>
					</th>
					<td>
						<?php $nextItem = 'p1'; ?>
						<select id="<?php echo $nextItem; ?>" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]">
							<?php
							for( $i = 1; $i <= 30; $i++ )
							{
								if( $custom['type_settings'][$this_type][$nextSection][$nextItem] == $i )
									$selected = 'selected="selected"';
								else
									$selected = ''; ?>
							<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php
							} ?>
						</select>
						
						<?php $nextItem = 't1'; ?>
						<select id="<?php echo $nextItem; ?>" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]">
							<?php
							if( $billing_cycle_units )
							{
								foreach( $billing_cycle_units as $key => $value )
								{
									if( $custom['type_settings'][$this_type][$nextSection][$nextItem] == $key )
										$selected = 'selected="selected"';
									else
										$selected = ''; ?>
							<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
								<?php
								}
							} ?>
						</select>
						
					</td>
				</tr>
				<tr>
					<?php $nextItem = 'offer_trial_2'; ?>
					<th>
						<label class="sub_setting_label">- Offer a second trial period?</label>
					</th>
					<td>
						<label>
							<input onclick="jQuery('#<?php echo $nextItem; ?>').hide(); jQuery('#<?php echo $nextItem; ?>').show();" type="radio" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="yes" <?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ] == 'yes' ? 'checked' : ''; ?>>
							Yes
						</label>
						<label>
							<input onclick="jQuery('#<?php echo $nextItem; ?>').show(); jQuery('#<?php echo $nextItem; ?>').hide();" type="radio" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="no" <?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ] != 'yes' ? 'checked' : ''; ?>>
							No
						</label>
						
						<span class="howto"></span>
					</td>
				</tr>
			</tbody>
		</table>
		
		<div id="offer_trial_2" style="display:<?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ] == 'yes' ? 'block' : 'none'; ?>;">
			<table class="form-table">
				<tbody>
					<tr>
						<?php $nextItem = 'a2'; ?>
						<th>
							<label for="<?php echo $nextItem; ?>"><p class="sub_sub_setting_label">-- Amount to bill for this trial period</p></label>
						</th>
						<td>
							<input class="widefat mini_size" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][$nextSection][ $nextItem ]; ?>">
							<span class="howto">Customers will receive just one bill for each trial period.</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo $nextItem; ?>"><p class="sub_sub_setting_label">-- How long should the trial period last?</p></label>
						</th>
						<td>
							<?php $nextItem = 'p2'; ?>
							<select id="<?php echo $nextItem; ?>" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]">
								<?php
								for( $i = 1; $i <= 30; $i++ )
								{
									if( $custom['type_settings'][$this_type][$nextSection][$nextItem] == $i )
										$selected = 'selected="selected"';
									else
										$selected = ''; ?>
								<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
								<?php
								} ?>
							</select>
							
							<?php $nextItem = 't2'; ?>
							<select id="<?php echo $nextItem; ?>" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextSection; ?>][<?php echo $nextItem; ?>]">
								<?php
								if( $billing_cycle_units )
								{
									foreach( $billing_cycle_units as $key => $value )
									{
										if( $custom['type_settings'][$this_type][$nextSection][$nextItem] == $key )
											$selected = 'selected="selected"';
										else
											$selected = ''; ?>
								<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php
									}
								} ?>
							</select>
							
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<table class="form-table">
	<tbody>
		<tr>
			<?php $nextItem = 'require_address'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Do you need your customer's shipping address?</label>
			</th>
			<td>
				<label>
					<input type="radio" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextItem; ?>]" value="yes" <?php echo $custom['type_settings'][ $this_type ][ $nextItem ] == 'yes' ? 'checked' : ''; ?>> 
					Yes
				</label>
				<br>
				<label>
					<input type="radio" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextItem; ?>]" value="no" <?php echo $custom['type_settings'][ $this_type ][ $nextItem ] != 'yes' ? 'checked' : ''; ?>> 
					No
				</label>
			</td>
		</tr>
		<tr>
			<?php $nextItem = 'cancel_url'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Take customers to this URL when they cancel their checkout</label>
			</th>
			<td>
				<input class="widefat" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][ $nextItem ]; ?>">
				<span class="howto">Example: https://www.mystore.com/cancel</span>
			</td>
		</tr>
		<tr>
			<?php $nextItem = 'success_url'; ?>
			<th>
				<label for="<?php echo $nextItem; ?>">Take customers to this URL when they finish checkout</label>
			</th>
			<td>
				<input class="widefat" id="<?php echo $nextItem; ?>" type="text" name="field_type_settings[<?php echo $this_type; ?>][<?php echo $nextItem; ?>]" value="<?php echo $custom['type_settings'][ $this_type ][ $nextItem ]; ?>">
				<span class="howto">Example: https://www.mystore.com/success</span>
			</td>
		</tr>
	</tbody>
</table>