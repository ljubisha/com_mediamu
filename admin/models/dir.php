<?php
/*------------------------------------------------------------------------
# com_mediamu - Advanced Media Manager
# ------------------------------------------------------------------------
# @author Ljubisa - ljufisha.blogspot.com
# @copyright Copyright (C) 2012 ljufisha.blogspot.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
# Technical Support: http://ljufisha.blogspot.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.modelitem');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

/**
 * Description of dir
 *
 * @author Ljubisha
 */
class MediamuModelDir extends JModelItem {
    
     /**
     * 
     * Save current directory in session for file upload
     * 
     * @param string $directoryPath
     * @return void
     */
    private function _setDirectoryState($directoryPath)
    {
        $session                = JFactory::getSession();
        $homeDirBase64          = base64_encode(COM_MEDIAMU_BASE_ROOT);
        $directoryPathCleaned   = JPath::clean($directoryPath);
        $currentDir             = JPath::check($directoryPathCleaned);
        $currentDirBase64       = base64_encode($currentDir);
        
        if(strpos($currentDir, COM_MEDIAMU_BASE_ROOT) === false)
        {
            //path is invalid, save default directory
            $session->set('current_dir', $homeDirBase64, 'com_mediamu');
        }
        else
        {
            $session->set('current_dir', $currentDirBase64, 'com_mediamu');
        }
        
    }
    
    /**
     * 
     * Get folder names and their links
     * 
     * @return array Filled with object with: name, link properties
     */
    public function getBreadcrumbs() 
    {
        $bc         = array();
        $currentDir = $this->_getCurrentDir();
        
        $parts = explode(DS, $currentDir);
        $link = '';
        $i = 0;
        
        //fill bc array with objects
        foreach ($parts as $part) 
        {
            if(strlen($part) && $part != ' ') 
            {
                $link.= DS . $part;
                $bc[$i] = new JObject();
                $bc[$i]->name = $part;
                $bc[$i]->link = $link;
                $i++;
            }
        }
        
        //prepend home dir
        $firstBC = new JObject();
        $firstBC->name = COM_MEDIAMU_MEDIA_FOLDER;
        $firstBC->link = '';
        array_unshift($bc, $firstBC);
        
        return $bc;
        
    }
    /**
     * Folders in current directory
     * 
     * @return array
     */
    public function getFolders() 
    {
        $currentDir = $this->_getCurrentDir(true);
        //get all folders in current dir
        $folders = JFolder::folders($currentDir, '.', false, true);
        //set current folder on first place in array
        array_unshift($folders, $currentDir);
        
        return $this->_setFolderInfo($folders);
    }
    
    /**
     * 
     * Files in current directory
     * 
     * @return array
     */
    public function getFiles() 
    {
        $currentDir = $this->_getCurrentDir(true);
        //get all files
        $files = JFolder::files($currentDir, '.', false, true, array('index.html'));
        return $this->_setFileInfo($files);
    }
    
    /**
     * 
     * Get current directory from request
     * 
     * @param bool $fullPath
     * @param string $separator 
     * @return string 
     */
    private function _getCurrentDir( $fullPath = false, $separator = DS ) 
    {
        $defaultDirVar = "";
        $defaultDirPath = COM_MEDIAMU_BASE_ROOT;
        
        //filter GET variable
        $directoryVarFromReq    = JRequest::getVar('dir', $separator);
        $directoryVarReplSep    = str_replace(array("/", "\\"), $separator, $directoryVarFromReq);
        $directoryVarWODots     = preg_replace(array("/\.\./", "/\./"), '', $directoryVarReplSep);
        $directoryVar           = $directoryVarWODots;
        
        //make filtered full directory path
        $fullDirPath        = COM_MEDIAMU_BASE_ROOT . $separator . $directoryVar;
        $dirPath            = JPath::check($fullDirPath, $separator);
        
        if(file_exists($dirPath))
        {
            //save current directory in session whenever this function gets called
            $this->_setDirectoryState($dirPath);
            
            if($fullPath)
            {
                return $dirPath;
            }
            else 
            {
                return $directoryVar;
            }
        }
        else
        {
            if($fullPath)
            {
                return $defaultDirPath;
            }
            else
            {
                return $defaultDirVar;
            }
            
        }
        
    }
    
    /**
     * 
     * Set information for each file
     * 
     * @param array $files
     * @return array
     */
    private function _setFileInfo( $filePaths ) 
    {
        $OFiles = array();
        
        for($i = 0; $i < count($filePaths); $i++)
        {
            $path = JPath::clean($filePaths[$i]);
            $OFiles[$i]           = new JObject();
            $OFiles[$i]->basename = basename($path);
            $OFiles[$i]->fullPath = dirname($path) . DS . basename($path);
            $OFiles[$i]->link     = COM_MEDIAMU_BASE_URL . $this->_getCurrentDir(false, "/") . '/' . basename($path);
            $OFiles[$i]->ext      = JFile::getExt($path);
            
            //image info, if file is image
            if(@getimagesize($path)) 
            {
                $OFiles[$i]->imgInfo = @getimagesize($path);
            } 
            else 
            {
                $OFiles[$i]->imgInfo = 0;
            }
            
            //file size
            $size = @filesize($path); //B
            $unit = ' B';
            if($size > 1024) 
            {
                $size = $size / 1024;
                $unit = 'KB';
            }
            if($size > 1024) 
            {
                $size = $size / 1024;
                $unit = 'MB';
            }
            if($size > 1024) 
            {
                $size = $size / 1024;
                $unit = 'GB';
            }
            $size = round($size, 2) . " " . $unit;
            $OFiles[$i]->size = $size;
            
            //last accessed and last modified time
            $OFiles[$i]->accessTime = @strftime("%d/%m/%Y %H:%M:%S", @fileatime($path));
            $OFiles[$i]->modifiedTime = @strftime("%d/%m/%Y %H:%M:%S", @filemtime($path));
        }
        
        return $OFiles;
    }
    
    
    /**
     * Sets the info and path for each folder
     * 
     * @param array $folders
     * @return array
     */
    private function _setFolderInfo( $folderPaths ) 
    {
        $OFolders = array();
        
        for($i = 0; $i < count($folderPaths); $i++)
        {
            $path = JPath::clean($folderPaths[$i]);
            $OFolders[$i]                     = new JObject();
            $OFolders[$i]->fullPath           = $path;
            $OFolders[$i]->basename           = basename($path);
            $OFolders[$i]->parentFullPath     = dirname($path) . DS;
            $OFolders[$i]->parentBasename     = basename(dirname($path) . DS);
            $OFolders[$i]->folderCount        = count( JFolder::folders($path, '.', false, false));
            $OFolders[$i]->fileCount          = count( JFolder::files($path, '.', false, false, array("index.html")));

            //make parent short path for go up directory
            if($path == COM_MEDIAMU_BASE_ROOT . DS . basename($path))
            {
                $OFolders[$i]->parentShort = "";
            }
            else
            {
                $OFolders[$i]->parentShort = dirname(str_replace(COM_MEDIAMU_BASE_ROOT, "", $path . DS));
            }
            
            $OFolders[$i]->folderLink = $OFolders[$i]->parentShort . DS . basename($path);
        }
        
        return $OFolders;
        
    }
    
    
}

?>
