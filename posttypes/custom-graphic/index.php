<?php
class qodyPosttype_ButtonerCustomGraphics extends QodyPostType
{
	 function __construct()
    {
        $this->SetOwner( func_get_args() );
		
		$this->m_raw_file = __FILE__;
		
		$this->m_priority = 4;
        
        $this->m_show_in_menu = $this->GetPre().'-home.php';
        
       	$this->m_supports[] = 'title';
        $this->m_supports[] = 'editor';
        //$this->m_supports[] = 'thumbnail';
        //$this->m_supports[] = 'excerpt';
         //$this->m_supports[] = null;
		
		$this->m_rewrite = array( 'slug' => 'custom-graphic' );
        
        $this->SetMassVariables( 'custom graphic', 'custom graphics', true );
		
		$this->m_list_columns['cb'] = '<input type="checkbox" />';
        $this->m_list_columns['title'] = 'Name';
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
		
		//$this->EnqueueStyle('chosen');
		//$this->EnqueueScript('chosen');
	}	
    
    
}
?>