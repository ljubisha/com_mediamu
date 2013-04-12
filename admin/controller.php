<?php
/**
com_mediamu - Advanced Media Manager
@author Ljubisa - ljufisha.blogspot.com
@copyright Copyright (C) 2012 ljufisha.blogspot.com. All Rights Reserved.
@license - http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
Technical Support: http://ljufisha.blogspot.com
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

//import joomla component librabry
jimport('joomla.application.component.controller');
jimport( 'joomla.application.component.helper' );

/**
 * General Controller of MediaMU component
 */
class MediamuController extends JController 
{
    /**
     * Display task
     *
     * @return void
     */
    function display($cachable = false, $urlparams = false) 
    {
        // set default view if not set
        JRequest::setVar('view', JRequest::getCmd('view', 'Mediamu'));
        // call parent behavior
        parent::display($cachable);
    }
 }
 

?>