<?php
/**
 * com_mediamu - Advanced Media Manager
 * @author Ljubisa - ljufisha.blogspot.com
 * @copyright Copyright (C) 2012 ljufisha.blogspot.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * Technical Support: http://ljufisha.blogspot.com
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//path definition
define("COM_MEDIAMU_MEDIA_FOLDER", "images");
define("COM_MEDIAMU_BASE_ROOT", JPATH_ROOT . DS . COM_MEDIAMU_MEDIA_FOLDER);
define("COM_MEDIAMU_BASE_URL", JURI::root() . COM_MEDIAMU_MEDIA_FOLDER);

//component debuging
define("COM_MEDIAMU_DEBUG", false);

// import joomla controller library
jimport('joomla.application.component.controller');

//require mediamu helpers
JLoader::register('MediamuScript', dirname(__FILE__) . DS . 'helpers' . DS . 'script.php');
 
// Get an instance of the controller prefixed by Mediamu
$controller = JController::getInstance('Mediamu');
 
// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

?>