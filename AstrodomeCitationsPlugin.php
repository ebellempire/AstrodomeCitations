<?php
class AstrodomeCitationsPlugin extends Omeka_Plugin_AbstractPlugin
{

	protected $_hooks = array(
		'install',
		'uninstall',
	);

	protected $_filters = array(
		'item_citation',
	);

	protected $_options = array(
	);

	public function hookInstall()
	{
		$this->_installOptions();
	}

	public function hookUninstall()
	{
		$this->_uninstallOptions();
	}
	
	/* Specification:
	** [Creator], [Title] [Date]. [Provenance], [Astrodome Memories], accessed [date], [URL].
	** "Note the removal of the punctuation formatting (quotation marks and comma) in Title--that's because we are going to add the publication info and quotation marks in each item title. We won't be able to retain the italics in the publication name, since they don't get pulled in, but I figure that's not a big deal. And the other change is the Date and Provenance, with formatting, to match Chicago citation as closely as possible. Provenance gets us the institution name, which seems like the big thing. If there's no date I'll note "Undated" and let that get pulled in too."
    */	
	
	public function filterItemCitation($citation,$args)
	{
		$item = $args['item'];
        
        $publication = option('site_title');
        
        $creator = metadata($item,array('Dublin Core','Creator')) ? metadata($item,array('Dublin Core','Creator')).', ' : null;
        
        $title = metadata($item,array('Dublin Core','Title')) ? metadata($item,array('Dublin Core','Title')) : null;
        
        $date = metadata($item,array('Dublin Core','Date')) ? ' '.metadata($item,array('Dublin Core','Date')) : null;
        
        if(element_exists('Dublin Core','Provenance')){
	        
	       $provenance=metadata($item,array('Dublin Core','Provenance')) ? metadata($item,array('Dublin Core','Provenance')).', ' : null; 
	       
        }else{
	        
	        $provenance = null;
	        
        }
        
        $today = date("F j, Y");
        
        $url = WEB_ROOT.'/items/show/'.$item->id;

		$citation = $creator.$title.$date.'. '.$provenance.'<em>'.$publication.'</em>, accessed '.$today.', '.$url;

        return $citation;
    }   
}
	