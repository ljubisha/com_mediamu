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
defined("_JEXEC") or die();

?>
        <input type="hidden" name="current_folder" value="<?php echo $this->currentFolder; ?>" />
        <input type="hidden" name="<?php echo JSession::getFormToken(); ?>" value="1" />
        </tbody>
    </table>
</form>
<?php if(COM_MEDIAMU_DEBUG) : ?>
BREADCRUMBS
<?php var_dump($this->breadcrumbs); ?>
FOLDERS
<?php var_dump($this->folders); ?>
Files
<?php var_dump($this->files); ?>
<?php endif; ?>


