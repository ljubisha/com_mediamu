<?php
/**
com_mediamu - Advanced Media Manager

@author Ljubisa - ljufisha.blogspot.com
@copyright Copyright (C) 2012 ljufisha.blogspot.com. All Rights Reserved.
@license - http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
Technical Support: http://ljufisha.blogspot.com
**/

// no direct access
defined('_JEXEC') or die;

//import required libraries
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.folder');

/**
 * Folder create hanlder
 */
class MediamuControllerFolder extends JController {
    
    /**
     * Create task
     */
    public function create() {
        
        //enable valid JSON response if debugging is disabled
        if(!COM_MEDIAMU_DEBUG) 
        {
            error_reporting(0);
        }
        
        //get the current folder
        $currentFolderDecoded = base64_decode(JRequest::getVar('current_folder', null, 'post'));
        $currentFolderCleaned = JPath::check($currentFolderDecoded);
        $currentFolder = $currentFolderCleaned;
        
        //get the name of the folder
        $folderNameFromReq = JRequest::getVar('folder_name', null);
        $folderNameLC = strtolower($folderNameFromReq);
        $folderName = str_replace(' ', '_', $folderNameLC);
        
        //new directory to be created
        $newDir = $currentFolder . DS . $folderName;
        $newDirCleaned = JPath::check($newDir);
        $dir = $newDirCleaned;
        
        $user = JFactory::getUser();
                
        //check token
        if (!JSession::checkToken('post')) 
        {
            $this->_setResponse(1, JText::_('JINVALID_TOKEN'));
	} 
        
        //check folder name 
        if(strlen($folderName) < 1 ) 
        {
            $this->_setResponse(1, JText::_('COM_MEDIAMU_ERROR_FOLDER_EMPTY'));
        }
        
        //check user perms
        if(!$user->authorise('core.create', 'com_mediamu')) 
        {
            $this->_setResponse(1, JText::_('COM_MEDIAMU_ERROR_PERM_DENIDED'));
        }
        
        //validate folder name
        if(!preg_match("/^[a-zA-Z0-9_\s]+$/", $folderName)) 
        {
            $this->_setResponse(1, JText::_('COM_MEDIAMU_ERROR_BAD_CHARACTERS'));
        }
        
        //check if already exists
        if(file_exists($dir)) 
        {
            $this->_setResponse(1, JText::_('COM_MEDIAMU_ERROR_DIR_EXISTS'));
        }
        
        //create folder
        if(JFolder::create($dir)) 
        {
            //create index file to prevent dirrect access
            $indexFileName = $dir . DS . 'index.html';
            
            //write index file
            $fh = @fopen($indexFileName, 'w');
            @fwrite($fh, "<html><head></head><body></body></html>");
            
            //folder created
            $this->_setResponse(0, JText::_('COM_MEDIAMU_FOLDER_CREATED'));
            
        } 
        else 
        {
            //unable to create directory
            $this->_setResponse(1, JText::_('COM_MEDIAMU_ERROR_DIR_NOT_W'));
        }
    }
    /**
     * 
     * Set JSON Response and exit
     * 
     * @param type $error
     * @param type $message
     */
    private function _setResponse($error, $message)
    {
        $response = array (
            'error' => $error,
            'msg' => $message
        );
        
        die(json_encode($response));
    }
    
}

?>
