<?php
class qodyPosttype_ButtonerSavedLink extends QodyPostType
{
	 function __construct()
    {
        $this->SetOwner( func_get_args() );
		
		$this->m_raw_file = __FILE__;
		
		$this->m_priority = 3;
        
        $this->m_show_in_menu = $this->GetPre().'-home.php';
        
       	$this->m_supports[] = 'title';
        //$this->m_supports[] = 'editor';
        //$this->m_supports[] = 'thumbnail';
        //$this->m_supports[] = 'excerpt';
        //$this->m_supports[] = null;
		
		$this->m_rewrite = array( 'slug' => 'saved-link' );
        
        $this->SetMassVariables( 'saved link', 'saved links', true );
		
		$this->m_list_columns['cb'] = '<input type="checkbox" />';
        $this->m_list_columns['title'] = 'Name';
		$this->m_list_columns['link_url'] = 'Link URL';
		$this->m_list_columns['date'] = 'Date';
        
        parent::__construct();
    }
	
    function WhenViewingPostList()
    {
		if( !parent::WhenViewingPostList() )
			return;
			
		$this->EnqueueStyle( 'nicer-tables' );
    }
	
	function WhenEditing()
	{
		if( !parent::WhenEditing() )
			return;
		
		$this->RemoveAllMetaboxesButMine( null, true );
		
		$this->EnqueueStyle('chosen');
		$this->EnqueueScript('chosen');
		
		$this->AddMetabox( 'type', 'Link Type' );
		$this->AddMetabox( 'custom', 'Link Settings - Custom Type' );
		$this->AddMetabox( 'paypal', 'Link Settings - Paypal Type' );
	}
	
	function GetLinkTypes()
	{
		$fields = array();
		$fields['custom'] = 'Custom';
		$fields['paypal'] = 'Paypal';
		
		return $fields;
	}
	
	function GetPaypalButtonTypes()
	{
		$fields = array();
		//$fields['products'] = 'Shopping cart';
		$fields['services'] = 'Buy Now';
		//$fields['donations'] = 'Donations';
		//$fields['gift_certs'] = 'Gift certificates';
		$fields['subscriptions'] = 'Subscriptions';
		//$fields['auto_billing'] = 'Automatic Billing';
		//$fields['payment_plan'] = 'Installment Plan';
		
		return $fields;
	}
	
	function GetPaypalBillingCycleUnits()
	{
		$fields = array();
		$fields['D'] = 'day(s)';
		$fields['W'] = 'week(s)';
		$fields['M'] = 'month(s)';
		$fields['Y'] = 'year(s)';
		
		return $fields;
	}
	
	function GetPaypalCurrencyTypes()
	{
		$fields = array();
		$fields['USD'] = 'USD';
		$fields['AUD'] = 'AUD';
		$fields['BRL'] = 'BRL';
		$fields['GBP'] = 'GBP';
		$fields['CAD'] = 'CAD';
		$fields['CZK'] = 'CZK';
		$fields['DKK'] = 'DKK';
		$fields['EUR'] = 'EUR';
		$fields['HKD'] = 'HKD';
		$fields['HUF'] = 'HUF';
		$fields['ILS'] = 'ILS';
		$fields['JPY'] = 'JPY';
		$fields['MXN'] = 'MXN';
		$fields['TWD'] = 'TWD';
		$fields['NZD'] = 'NZD';
		$fields['NOK'] = 'NOK';
		$fields['PHP'] = 'PHP';
		$fields['PLN'] = 'PLN';
		$fields['SGD'] = 'SGD';
		$fields['SEK'] = 'SEK';
		$fields['CHF'] = 'CHF';
		$fields['THB'] = 'THB';
		
		return $fields;
	}
	
	function CustomDefaults()
	{
		$fields = array();
		$fields['type_settings']['paypal']['button_type'] = 'services';
		$fields['type_settings']['paypal']['currency'] = 'USD';
		$fields['type_settings']['paypal']['require_address'] = 'yes';
		$fields['type_settings']['paypal']['allow_special_instructions'] = 'yes';
		
		$fields['type_settings']['paypal']['subscriptions']['t1'] = 'M';
		$fields['type_settings']['paypal']['subscriptions']['t2'] = 'M';
		$fields['type_settings']['paypal']['subscriptions']['t3'] = 'M';
		
		return $fields;
	}
	
	function get_post_custom( $post_id )
	{
		$data = parent::get_post_custom( $post_id );
		$data['type_settings'] = maybe_unserialize( $data['type_settings'] );
		
		return $data;
	}
	
	function GeneratePaypalLink( $data )
	{
		$base_url = 'https://www.paypal.com/cgi-bin/webscr';
		$button_type = $data['button_type'];
		
		//$this->ItemDebug( $data );
		
		$fields = array();
		$fields['business'] = $data['paypal_email'];
		$fields['item_name'] = $data['item_name'];
		$fields['item_number'] = $data['item_id'];
		$fields['currency_code'] = $data['currency'];
		$fields['rm'] = '1';
		$fields['return'] = $data['success_url'];
		$fields['cancel_return'] = $data['cancel_url'];
		
		if( $data['require_address'] == 'no' )	
			$fields['no_shipping'] = '1';
			
		switch( $button_type )
		{
			// recurring
			case 'subscriptions':
				
				$fields['cmd'] = '_xclick-subscriptions';
				
				// trial 1
				$fields['a1'] = $data[ $button_type ]['a1'];
				$fields['p1'] = $data[ $button_type ]['p1'];
				$fields['t1'] = $data[ $button_type ]['t1'];
				
				// trial 2
				$fields['a2'] = $data[ $button_type ]['a2'];
				$fields['p2'] = $data[ $button_type ]['p2'];
				$fields['t2'] = $data[ $button_type ]['t2'];
				
				// main cycle
				$fields['a3'] = $data[ $button_type ]['a3'];
				$fields['p3'] = $data[ $button_type ]['p3'];
				$fields['t3'] = $data[ $button_type ]['t3'];
				
				if( $data[ $button_type ]['srt'] > 1 || $data[ $button_type ]['srt'] == -1 )
				{
					$fields['src'] = 1; // is recurring
					$fields['srt'] = $data[ $button_type ]['srt'] == -1 ? '' : $data[ $button_type ]['srt'];
				}
				
				break;
			
			// buy now
			case 'services':
				
				$fields['cmd'] = '_xclick';
				$fields['amount'] = $data[ $button_type ]['price'];
				$fields['tax_rate'] = $data[ $button_type ]['tax'];
				$fields['shipping'] = $data[ $button_type ]['shipping'];
				
				if( $data[ $button_type ]['allow_special_instructions'] == 'no' )
					$fields['no_note'] = '1';
				
				if( $data[ $button_type ]['allow_quantity_change'] == 'yes' )	
					$fields['undefined_quantity'] = '1';
				
				break;
		}
		
		return $base_url.'?'.http_build_query( $fields );
	}
	
	function GenerateUrlFromSavedLink( $post_id )
	{
		$custom = $this->get_post_custom( $post_id );
		
		$link_type = $custom['link_type'];
				
		if( $link_type == 'custom' )
		{
			$link_url = $custom['type_settings'][ $link_type ]['custom_url'];
		}
		else if( $link_type == 'paypal' )
		{
			$link_url = $this->GeneratePaypalLink( $custom['type_settings'][ $link_type ] );
		}
		
		return $link_url;
	}

    function DisplayListColumns( $column )
    {
		global $post;
		
		$post_id = $post->ID;
		
		$custom = $this->get_post_custom( $post_id );
        $the_meta = get_post_meta( $post_id, $column, true);
        
        switch( $column )
        {
			case "link_url":
				
				$link_url = $this->GenerateUrlFromSavedLink( $post_id );
				
				echo '<input class="embed_input" type="text" onclick="this.select()" readonly="readonly" value=\''.$link_url.'\'">';
					
				break;
        }
    }
    
    
}
?>