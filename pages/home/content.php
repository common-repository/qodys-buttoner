<?php
wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false );
wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false );

$plugin_data = $this->GetPluginData();
?>

<div class="wrap">
	
	<h2><?php echo $plugin_data['Name']; ?>, version <?php echo $plugin_data['Version']; ?></h2>
	
	<?php $this->GetClass('postman')->DisplayMessages(); ?>
	
	<div id="poststuff" class="metabox-holder has-right-sidebar">            
		<div id="side-info-column" class="inner-sidebar">
			
			<img style="width:180px;" class="owl_sidebar" src="<?php echo $this->GetRandomOwlImage( 10 ); ?>" />
			<?php $this->do_meta_boxes( 'side' ); ?>
			
		</div>
		<div id="post-body" class="has-sidebar">
			<div id="post-body-content" class="has-sidebar-content">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					
					<p>Need help? <a target="_blank" href="http://nexus.qody.co/tutorials/qodys-buttoner/">Watch the tutorial videos</a></p>
					
					<h3>There's not really anything to do here; the magic is performed on the post/page edit screens!</h3>
					
				</div>
			</div>
		</div>
	</div>
</div>
	
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready( function($) {
	// close postboxes that should be closed
	$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
	// postboxes setup
	postboxes.add_postbox_toggles('<?php echo $pagehook; ?>');
});
//]]>
</script>