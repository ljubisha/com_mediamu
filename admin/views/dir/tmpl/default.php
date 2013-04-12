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
defined('_JEXEC') or die('Restricted Access');

echo $this->loadTemplate('header');
echo $this->loadTemplate('dirs');
echo $this->loadTemplate('files');
echo $this->loadTemplate('footer');
?>

