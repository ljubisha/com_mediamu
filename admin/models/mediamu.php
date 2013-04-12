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
/**
 * Mediamu admin Model
 */
class MediamuModelMediamu extends JModelItem
{
        
        /**
         * Get component parameters
         * 
         * @return object
         */
	public function getParams() 
        {
            return JComponentHelper::getParams('com_mediamu');
	}
}

?>