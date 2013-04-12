<?php
/*------------------------------------------------------------------------
# com_mediamu - Advanced Media Manager
# ------------------------------------------------------------------------
# @author Ljubisa - ljufisha.blogspot.com
# @copyright Copyright (C) 2012 ljufisha.blogspot.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
# Technical Support: http://ljufisha.blogspot.com
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Mediamu View
 */
class MediamuViewMediamu extends JView
{
	/**
	 * Mediamu view display method
	 * @return void
	 */
	 
	function display($tpl = null) 
	{
		$mediaDir		= JURI::root() . "media/com_mediamu/";
		$document 		= JFactory::getDocument();
		$params 		= $this->get('Params');
		$mediamuScript          = new MediamuScript($params, $mediaDir);
		$runtimeScript		= $mediamuScript->runtimeScript;
                $runtime                = $mediamuScript->runtime;
                
		//add default mediamu css
		$document->addStyleSheet($mediaDir . 'css/com_mediamu.css');
		
		//add plupload styles and scripts
		$document->addStyleSheet($mediaDir . 'js/jquery.plupload.queue/css/jquery.plupload.queue.css', 'text/css', 'screen');
		$document->addScript($mediaDir . 'js/jquery.min.js');
		$document->addScript($mediaDir . 'js/browserplus-min.js');
		$document->addScript($mediaDir . 'js/plupload.js');
		$document->addScript($mediaDir . 'js/plupload.' . $runtimeScript . '.js');
		$document->addScript($mediaDir . 'js/jquery.plupload.queue/jquery.plupload.queue.js');
		$document->addScriptDeclaration( $mediamuScript->getScript() );
		
                //set variables for the template
                $this->enableLog = $params->get('enable_uploader_log', 0);
		$this->runtime = $runtime;
		$this->currentDir = $this->get('CurrentDir');
                
		//set toolbar
		$this->addToolBar();
		
		// Display the template
		parent::display($tpl);
	}
	
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
		//get toolbar object
		$toolbar = JToolBar::getInstance('toolbar');
		//get user object
		$user = JFactory::getUser();
		
		//set title bar text
		JToolBarHelper::title(JText::_('COM_MEDIAMU_MANAGER_TOOLBAR_TITLE'), 'mediamu.png');
	
		//set delete button
		if($user->authorise('core.delete', 'com_mediamu')) 
		{
			$title = JText::_('JTOOLBAR_DELETE');
			$dhtml = "<a href=\"#\" id=\"delete_selected\" class=\"toolbar\">
						<span class=\"icon-32-delete\" title=\"$title\"></span>
						$title</a>";
			$toolbar->appendButton('Custom', $dhtml, 'delete');
			JToolBarHelper::divider();
		}
		
		//set preferences button
		if ($user->authorise('core.admin', 'com_mediamu'))
		{
			JToolBarHelper::preferences('com_mediamu', 450, 800, 'JToolbar_Options', '', 'window.location.reload()');
		}
		
	}
}