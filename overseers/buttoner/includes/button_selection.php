<?php
ob_get_contents();
require_once( dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))).'/wp-load.php' );
ob_end_clean();

$qodys_buttoner->EnqueueStyle( 'bootstrap' );
$buttons = $qodys_buttoner->GetOverseer()->GetMainButtons();
$saved_links = $qodys_buttoner->GetOverseer()->GetSavedLinks();

// check for custom buttons
$fields = array();
$fields['post_type'] = $qodys_buttoner->GetClass('posttype_custom-graphic')->m_type_slug;
$fields['numberposts'] = -1;

$custom_categories = get_posts( $fields );

if( $custom_categories )
{
	$custom_buttons = array();
	
	foreach( $custom_categories as $key => $value )
	{
		$fields = array();
		$fields['post_type'] = 'attachment';
		$fields['post_status'] = 'published';
		$fields['numberposts'] = -1;
		$fields['post_parent'] = $value->ID;
		
		$graphics = get_posts( $fields );
		
		if( $graphics )
		{
			foreach( $graphics as $key2 => $value2 )
			{
				$fields = array();
				$fields['image_url'] = wp_get_attachment_url( $value2->ID );
				$fields['attach_id'] = $value2->ID;
				
				$buttons['custom-graphics'][ $value->post_name ][ $value2->post_name ] = $fields;
			}
		}
		
		//ItemDebug( $graphics );
		//ItemDebug( $buttons ); // continue here; add shortcode attr of aid="ID"
	}
}

if( !$buttons )
	die( "No buttons available" );
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>
</title>
	<title>Qody's Buttoner</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<script src="<?php echo get_bloginfo('url').'/'.WPINC; ?>/js/tinymce/tiny_mce_popup.js"></script>
	<script src="<?php echo get_bloginfo('url').'/'.WPINC; ?>/js/tinymce/utils/mctabs.js"></script>
	<script src="<?php echo get_bloginfo('url').'/'.WPINC; ?>/js/tinymce/utils/form_utils.js"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
	
	<script src="<?php echo $qodys_buttoner->GetRegisteredSrc( 'bootstrap-tab', 'script' ); ?>"></script>
	<script src="<?php echo $qodys_buttoner->GetRegisteredSrc( 'bootstrap-dropdown', 'script' ); ?>"></script>
	
	<link href="<?php echo $qodys_buttoner->GetRegisteredSrc( 'bootstrap' ); ?>" rel="stylesheet">
	<link href="<?php echo $qodys_buttoner->GetRegisteredSrc( 'jquery-ui' ); ?>" rel="stylesheet">
	<link href="<?php echo $qodys_buttoner->GetOverseer()->GetAsset( 'css', 'button_picker', 'url' ); ?>" rel="stylesheet">
	
	<script language="javascript" type="text/javascript">
	function GenerateShortcode()
	{
		var link_url = jQuery('#link_url').val();
		var link_target = jQuery('input[name=link_target]:checked').val();
		var link_image = jQuery('input[name=link_image]').val();
		var link_alignment = jQuery('input[name=link_alignment]:checked').val();
		var image_width = jQuery('#button_preview').css('width');
		var button_type = jQuery('input[name=button_type]:checked').val();
		
		var the_shortcode = '[<?php echo $qodys_buttoner->GetPre(); ?>-button';
		the_shortcode += ' image="' + link_image + '"';
		the_shortcode += ' align="' + link_alignment + '"';
		the_shortcode += ' width="' + image_width + '"';
		the_shortcode += ' type="' + button_type + '"';
		
		if( button_type == 'link' )
		{
			the_shortcode += ' url="' + link_url + '"';
			the_shortcode += ' target="' + link_target + '"';
		}
		
		the_shortcode += ']';
		
		if( window.tinyMCE )
		{
			window.tinyMCE.execInstanceCommand( 'content', 'mceInsertContent', false, the_shortcode );
			
			tinyMCEPopup.editor.execCommand( 'mceRepaint' );
			tinyMCEPopup.close();
		}
	}
	
	jQuery(document).ready( function(e) {
		
		jQuery('#saved_link').change( function(e) {
			
			jQuery('#link_url').val( jQuery(this).val() );
			
		} );
		
		jQuery(".scroll").click(function(event){		
			event.preventDefault();
			jQuery('html,body').animate({scrollTop:jQuery(this.hash).offset().top}, 500);
		});
		
	} );
	</script>
    
</head>
<body>
	
	<input type="hidden" name="link_image">
	
	<div style="padding:10px;">
		<div class="row-fluid">
			<div class="span12">
				
				<ul id="tab" class="nav nav-tabs">
					<?php
					foreach( $buttons as $key => $value )
					{
						switch( $key )
						{
							case 'actions': $icon_image = 'icon-flag'; break;
							case 'marketing': $icon_image = 'icon-shopping-cart'; break;
							case 'media': $icon_image = 'icon-play'; break;
							case 'navigation': $icon_image = 'icon-road'; break;
							case 'social': $icon_image = 'icon-comment'; break;
							case 'custom-graphics': $icon_image = 'icon-folder-open'; break;
							default: $icon_image = 'icon-shopping-cart'; break;
						}?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="<?php echo $icon_image; ?>"></i>
							<?php echo ucwords( str_replace( '-', ' ', $key ) ); ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
						<?php
						foreach( $value as $key2 => $value2 )
						{
							$class = '';
							
							if( !$first_set )
							{
								$first_set = $key.$key2;
								$class = 'first_item';
							} ?>
							<li><a class="<?php echo $class; ?>" href="#<?php echo $key.$key2; ?>" data-toggle="tab"><?php echo ucwords( str_replace( '-', ' ', $key2 ) ); ?></a></li>
						<?php
						} ?>
						</ul>
					</li>
					<?php
					} ?>
				</ul>
				
				<div id="myTabContent" class="tab-content">
				
					<?php
					
					foreach( $buttons as $key => $value )
					{
						foreach( $value as $key2 => $value2 )
						{ ?>
					<div class="tab-pane fade in active" id="<?php echo $key.$key2; ?>">
						<div class="row-fluid" style="margin-left:0px;">
							<?php
							$iter = 0;
							foreach( $value2 as $key3 => $value3 )
							{
								$iter++;
								$image_link = is_array( $value3 ) ? $value3['image_url'] : $value3;
								$radio_value = is_array( $value3 ) ? $value3['attach_id'] : $key.'@@'.$key2.'@@'.$key3; ?>
						
							<div class="span4 image_holder" style="margin-left:0px;">
								<label style="text-align:center;">
									<a class="scroll" href="#button_preview_anchor"><img rel="<?php echo $radio_value; ?>" src="<?php echo $image_link; ?>"></a>
								</label>
							</div>
							<?php
							} ?>
						</div>
					</div>
						<?php
						}
					}?>
				</div>
				
			</div>
		</div>
		
		<script>
		jQuery(document).ready( function()
		{
			jQuery('#tab a.first_item').tab('show');
			jQuery('.dropdown-toggle').dropdown();
			
			jQuery('.image_holder').click( function(e)
			{
				jQuery('.image_holder').removeClass('chosen_button');
				jQuery(this).addClass('chosen_button');
				
				jQuery('input[name=link_image]').val( jQuery(this).find('img').attr( 'rel' ) );
				
				ChangePreviewImage( jQuery(this).find( 'img' ).attr( 'src' ) );
			} );
			
			jQuery('.link_alignment').click( function(e)
			{ 
				var direction = jQuery(this).find('input').val();
				
				if( direction == -1 )
				{
					jQuery('#button_preview').css( 'float', 'none' );
					jQuery('#button_preview').css( 'margin', '0px' );
				}
				else if( direction == 'center' )
				{
					jQuery('#button_preview').css( 'float', 'none' );
					jQuery('#button_preview').css( 'margin', '0px auto' );
				}
				else
				{
					jQuery('#button_preview').css( 'float', direction );
					jQuery('#button_preview').css( 'margin', '0px' );
				}
				
			} );
			
			function ChangePreviewImage( image_src )
			{
				jQuery( "#button_preview" ).css( 'width', '' );
				
				jQuery('#button_preview').attr( 'src', image_src );
				
				jQuery( "#max_email_slider" ).slider( "value", jQuery('#button_preview').width() );
			}
			
			jQuery( "#max_email_slider" ).slider({
				value: 350,
				min: 1,
				max: 500,
				step: 1,
				slide: function( event, ui ) {
					jQuery( "#button_preview" ).css( 'width', ui.value + 'px' );
					jQuery( "#slider_text" ).html( ui.value + 'px' );
				},
				change: function( event, ui ) {
					jQuery( "#button_preview" ).css( 'width', ui.value + 'px' );
					jQuery( "#slider_text" ).html( ui.value + 'px' );
				}
			});
			
			/*jQuery('.image_holder img').each( function()
			{
				jQuery(this).attr( 'src', jQuery(this).attr('delayedsrc') );
			});*/
		} );
		</script>
		<a id="button_preview_anchor"></a>
		
		<div class="row-fluid" style="margin-top:20px;">
			
			<div class="span12">
				<form class="form-horizontal" onSubmit="return false;">
					<fieldset>
						<legend>Button Preview</legend>
						
						<div class="control-group">
							<label class="control-label" for="input01">Width </label>
							<div class="controls">
								<div style="width:150px; margin-top:10px;" id="max_email_slider"></div> 
								<span id="slider_text">0px</span>
							</div>
						</div>
						
						<?php $nextItem = 'link_alignment'; ?>
						<div class="control-group">
							<label class="control-label" for="input01">Alignment </label>
							<div class="controls">
								<label class="radio inline <?php echo $nextItem; ?>">
									<input type="radio" name="<?php echo $nextItem; ?>" value="none" checked>
									None
								</label>
								<label class="radio inline <?php echo $nextItem; ?>">
									<input type="radio" name="<?php echo $nextItem; ?>" value="left">
									Left
								</label>
								<label class="radio inline <?php echo $nextItem; ?>">
									<input type="radio" name="<?php echo $nextItem; ?>" value="center">
									Center
								</label>
								<label class="radio inline <?php echo $nextItem; ?>">
									<input type="radio" name="<?php echo $nextItem; ?>" value="right">
									Right
								</label>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Preview </label>
							<div class="controls">
								<img id="button_preview" style="display:block;">
							</div>
						</div>
						
					</fieldset>	
				</form>
			</div>
		</div>
		
		<div class="row-fluid" style="margin-top:20px;">
			
			<div class="span12">
				<form class="form-horizontal" onSubmit="return false;">
					<fieldset>
						<legend>Button settings</legend>
						
						<?php $parentItem = $nextItem = 'button_type'; ?>
						<div class="control-group">
							<label class="control-label">Button Type </label>
							<div class="controls">
								
								<label onClick="jQuery('#<?php echo $nextItem; ?>1').show(); jQuery('#<?php echo $nextItem; ?>2').hide();" class="radio inline <?php echo $nextItem; ?>">
									<input type="radio" name="<?php echo $nextItem; ?>" value="link" checked>
									Link Click
								</label>
								<label onClick="jQuery('#<?php echo $nextItem; ?>1').hide(); jQuery('#<?php echo $nextItem; ?>2').show();" class="radio inline <?php echo $nextItem; ?>">
									<input type="radio" name="<?php echo $nextItem; ?>" value="input">
									Form Submit
								</label>
								 
								 <p class="help-block">Choose how you plan on using this button</p>
							</div>
						</div>
						
						<div id="<?php echo $parentItem; ?>1" style="display:block;">
							
							<?php $nextItem = 'saved_link'; ?>
							<div class="control-group">
								<label class="control-label" for="<?php echo $nextItem; ?>">Saved Links </label>
								<div class="controls">
									<select id="<?php echo $nextItem; ?>" name="<?php echo $nextItem; ?>">
										<option value="">-- select --</option>
									<?php
									if( $saved_links )
									{
										foreach( $saved_links as $key => $value )
										{
											$link_url = $qodys_buttoner->GetOverseer()->GenerateUrlFromSavedLink( $value->ID ); ?>
										<option value="<?php echo $link_url; ?>"><?php echo $value->post_title; ?></option>
										<?php
										}
									} ?>
									</select>
									<p class="help-block">Pick a saved link to prefill the url field below (optional)</p>
									
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="input01">Link URL </label>
								<div class="controls">
									<div class="input-prepend">
										<span class="add-on"><i class="icon-screenshot"></i></span>
										<input type="text" class="span6" id="link_url">
										<p class="help-block">This is where you get sent to when clicking the button</p>
									</div>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label">Link Target </label>
								<div class="controls">
									<label class="radio">
										<input type="radio" name="link_target" value="_self" checked>
										Self - loads the link url in the <strong>current</strong> tab/page
									</label>
									<label class="radio">
										<input type="radio" name="link_target" value="_blank">
										Blank - loads the link url in a <strong>new</strong> tab/page
									</label>
									<label class="radio">
										<input type="radio" name="link_target" value="_blank">
										Top - loads the link url in the <strong>top</strong> tab/page (for iframes)
									</label>
									<label class="radio">
										<input type="radio" name="link_target" value="_blank">
										Parent - loads the link url in the <strong>parent</strong> tab/page (for popups)
									</label>
								</div>
							</div>
							
						</div>
						
						<div class="form-actions">
							<a class="btn btn-success" onclick="GenerateShortcode();" href="javascript:null(0);"><i class="icon-ok icon-white"></i> Insert to Content</a>
							<a class="btn" onclick="tinyMCEPopup.close();" href="javascript:null(0);"><i class="icon-remove-circle"></i> Cancel</a>
						</div>
						
					</fieldset>	
				</form>
				
			</div>
		</div>
		
	</div>
</body>
</html>