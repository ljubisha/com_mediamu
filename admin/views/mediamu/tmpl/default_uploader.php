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

?>

<form action="" method="post">

	<div id="uploader">
	
		<p><?php JText::printf('COM_MEDIAMU_ERROR_RUNTIME_NOT_SUPORTED', $this->runtime) ?></p>
		
	</div>
    <input type="hidden" name="<?php echo JSession::getFormToken(); ?>" value="1" />
    
</form>
<?php if($this->enableLog) : ?>
<button id="log_btn"><?php echo JText::_('COM_MEDIAMU_UPLOADER_LOG_BTN'); ?></button>
<div id="log"></div>
<?php endif; ?>