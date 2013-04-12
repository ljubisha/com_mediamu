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
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * File and Folder remove hanlder
 */
class MediamuControllerPath extends JController
{
        private $errors = array();
        
        /**
         * Delete task
         */
	public function delete()
	{
            //enable valid JSON response
            if(!COM_MEDIAMU_DEBUG) 
            {
                error_reporting(0);
            }
            
            $user = JFactory::getUser();
            $session = JFactory::getSession();
            
            //current directory
            $currentDirBase64  = $session->get('current_dir', null, 'com_mediamu');
            $currentDirDecoded  = base64_decode($currentDirBase64);
            //check for snooping
            $currentDirCleaned  = JPath::check($currentDirDecoded);
            //assign
            $currentDir = $currentDirCleaned;
            
            //requested paths to delete
            $pathsBase64 = JRequest::getVar('paths', array(), 'post', 'array');
            $numPaths = count($pathsBase64);
            
            //check token
            if(!JSession::checkToken()) 
            {
                $this->_setResponse(1, JText::_('JINVALID_TOKEN'));
            }
            
            //check perms
            if(!$user->authorise('core.delete', 'com_mediamu')) 
            {
                $this->_setResponse(1, JText::_('COM_MEDIAMU_ERROR_PERM_DENIDED'));
            }
            
            //try to delete requested paths
            for($i = 0; $i < $numPaths; $i++) 
            {
                $pathName = base64_decode($pathsBase64[$i]);
                
                $fullPath = $currentDir . DS . $pathName;
                $fullPath = JPath::check($fullPath);
                
                if(file_exists($fullPath))
                {
                    //check if it is a file
                    if(is_file($fullPath)) 
                    {
                        if(!JFile::delete($fullPath)) 
                        {
                            $this->errors[]= JText::sprintf('COM_MEDIAMU_ERROR_FILES_CANT_DELETE', basename($fullPath));
                        } 
                    }
                    elseif(is_dir($fullPath))
                    {
                        //check if there are folders inside
                        if(count(JFolder::folders($fullPath, '.'))) 
                        {
                            $this->errors[] = JText::sprintf('COM_MEDIAMU_ERROR_FOLDER_CANT_DELETE', basename($fullPath));
                        } 
                        else 
                        {
                            if(!JFolder::delete($fullPath)) 
                            {
                                $this->errors[] = JText::sprintf('COM_MEDIAMU_ERROR_FOLDER_CANT_DELETE', basename($fullPath));
                            }
                        }
                    }
                }
                else
                {
                    $this->errors[] = JText::sprintf('COM_MEDIAMU_ERROR_PATH_INVALID', $fullPath);
                }
            }
            
            if(empty($this->errors))
            {
                $this->_setResponse(false, 'Files deleted!');
            }
            else
            {
                $this->_setResponse(true, "Error occurred!");
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
            $errorMsgs = "";
            
            if($error)
            {
                $errorMsgs = implode("<br/>", $this->errors);
                
                $response = array 
                (
                    'error' => $error,
                    'msg'   => $message . "<br/>" . $errorMsgs
                );
            }
            else 
            {
                $response = array 
                (
                    'error' => $error,
                    'msg'   => $message
                );
            }
            
            die(json_encode($response));
        }
}

?>