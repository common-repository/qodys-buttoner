<?php
class qodyOverseer_Buttoner extends QodyOverseer
{
	function __construct()
	{
		$this->SetOwner( func_get_args() );
		
		$this->m_raw_file = __FILE__;
		
		//$this->SetParent( 'home' );
		//$this->m_icon_url = '';
		
		//$this->SetTitle( 'Settings' );
		
		if( $this->PassApiCheck() )
		{
			add_action( 'admin_init', array( $this, 'CreateEditorButton' ) );
		
			$this->CreateShortcode();
		}
		
		parent::__construct();
	}
	
	function CreateShortcode()
	{
		add_shortcode( $this->GetPre().'-button', array( $this, 'DoShortcode' ) );
	}
	
	function DoShortcode( $atts, $content = null )
	{
		// if it's an attachment (custom image upload)
		if( is_numeric( $atts['image'] ) )
		{
			$image = wp_get_attachment_url( $atts['image'] );
		}
		else
		{
			$file_path = str_replace( '@@', '/', $atts['image'] );
			$image = 'http://qody.s3.amazonaws.com/qodys-buttoner/buttons/'.$file_path;
		}
		
		$url = $atts['url'];
		$target = $atts['target'];
		
		$width = $atts['width'];
		$alignment = $atts['align'];
		$type = $atts['type'];
		
		if( $type == 'link' )
		{
			$data = '<a href="'.$url.'" target="'.$target.'">';
			$data .= '<img src="'.$image.'" style="display:block;'.($width ? 'width:'.$width.';' : '').($alignment == 'center' ? 'margin:0px auto;' : 'float:'.$alignment.';').'">';
			$data .= '</a>';
		}
		else
		{
			$data = '<input type="image" src="'.$image.'" style="display:block;'.($width ? 'width:'.$width.';' : '').($alignment == 'center' ? 'margin:0px auto;' : 'float:'.$alignment.';').'">';
		}
		
		return $data;
	}
	
	function HasExtraGraphicAccess()
	{
		$access = $this->get_option( 'has_oto_access' );
		
		if( $access != 1 )
		{
			$access = $this->VerifyOwnershipByAssociation( $this->Owner(), 10685 );
			
			$this->update_option( 'has_oto_access', $access );
		}
		
		if( $access == 1 )
			return true;
		
		return false;
	}
	
	function CreateEditorButton()
	{
		if( !current_user_can('edit_posts') && !current_user_can('edit_pages') )
		{
			return;
		}
		
		add_filter( 'mce_external_plugins', array( $this, 'add_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'register_button' ) );
	}
	
	function GetSavedLinks()
	{
		$fields = array();
		$fields['post_type'] = $this->GetClass('posttype_saved-link')->m_type_slug;
		$fields['numberposts'] = -1;
		
		$data = get_posts( $fields );
		
		return $data;
	}
	
	function GetMainButtons()
	{
		$file = $this->GetAsset( 'includes', 'buttons' );
		
		$file_handle = fopen( $file['container_dir'].'/'.$file['file_name'], "r");
		
		while( !feof($file_handle) )
		{
			$data .= fgets($file_handle);
		}
		
		fclose($file_handle);
		
		$data = json_decode( $data );
		$data = $this->ObjectToArray( $data );
		
		// check for extra graphic package
		if( !$this->GetOverseer()->HasExtraGraphicAccess() || $_GET['do'] == 1 )
		{
			// remove OTO-only graphic sections
			unset( $data['marketing']['add-to-cart-bonus'] );
			unset( $data['marketing']['badges'] );
			unset( $data['marketing']['buy-now-priced'] );
			unset( $data['marketing']['buy-now-bonus'] );
			unset( $data['marketing']['download-bonus'] );
			unset( $data['marketing']['get-instant-access-bonus'] );
			unset( $data['marketing']['join-now-bonus'] );
			unset( $data['marketing']['order-now-bonus'] );
			unset( $data['marketing']['paypal'] );
			unset( $data['marketing']['signup-bonus'] );
			unset( $data['media'] );
			unset( $data['navigation']['other'] );
			unset( $data['social']['pdf'] );
		}
		
		return $data;
	}
	
	function add_plugin( $plugin_array )
	{
		$plugin_array[$this->GetPre()."buttons"] = $this->GetAsset( 'js', 'shortcode', 'url' );
		//$this->ItemDebug( $plugin_array );
		return $plugin_array;
	}
	
	function register_button( $buttons )
	{
		array_push( $buttons, "|", $this->GetPre()."buttons" );
		//$this->ItemDebug( $buttons );
		return $buttons;
	}
}
?>