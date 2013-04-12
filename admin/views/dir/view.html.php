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
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Mediamu Directory View
 */
class MediamuViewDir extends JView
{
	/**
	 * Mediamu directory view display method
	 * @return void
	 */
	 
	function display($tpl = null) 
	{
            $document   = JFactory::getDocument();
            $mediaDir   = JURI::root() . "media/com_mediamu/";
            
            //add style sheet
            $document->addStyleSheet($mediaDir . 'css/com_mediamu.css');
            $bodyStyle = "body {background-color: #F4F4F4;}";
            $document->addStyleDeclaration($bodyStyle);
            
            //add scripts
            $document->addScript($mediaDir . 'js/jquery.min.js');
            $document->addScript($mediaDir . 'js/jquery.tooltip.js');
            $script = $this->_dirBroswerScript();
            $document->addScriptDeclaration($script);
            
            //setup template vars
            $this->breadcrumbs = $this->get('Breadcrumbs');
            $this->folders = $this->get('Folders');
            $this->files = $this->get('Files');
            $this->imgURL = $mediaDir . 'img/';
            $this->currentFolder = base64_encode($this->folders[0]->fullPath);
            
            // Display the template
            parent::display($tpl);
	}
        
        private function _dirBroswerScript() {
            ob_start();
            ?>
            $.noConflict();

            jQuery(document).ready(function ($) {
                
                function ajaxReq(dataString, action) {
                    $('span#proccess').addClass('loading');
                    $.ajax({
                        type: 'POST',  
                        url: action, 
                        data: dataString,
                        dataType : 'json',
                        success: function(response) {
                            $('span#proccess').removeClass('loading');
                            
                            if(response.error) {
                                var msgCont = $('#system-message-container');
                                var msgHTML = '';
                                //clean container
                                msgCont.html(' ');
                                
                                msgHTML+= '<dl id="system-message">';
                                msgHTML+= '<dt class="error">Error</dt>';
                                msgHTML+= '<dd class="error message">';
                                msgHTML+= '<ul><li>' + response.msg + '</li></ul>';
                                msgHTML+= '</dd>';
                                msgHTML+= '</dl>';
                                
                                msgCont.html(msgHTML);
                            } else {
                                window.location.reload();
                            }
                        }  
                    });
                }

                function rmPath( fileName ) {

                    var action = 'index.php?option=com_mediamu&no_html=1&task=path.delete';
                    var currentFolder = $('input#current_folder').val();
                    var token = '<?php echo JSession::getFormToken()  ?>';
                    var dataString = '';
                    
                    dataString+= 'paths[]=' + fileName + '&';
                    dataString+= 'current_folder=' + currentFolder + '&';
                    dataString+=  token + '=1';

                    ajaxReq(dataString, action);

                }

                $('#new_folder').click(function () {
                    $('#folder_input_form').slideToggle('slow');
                });

                $('#input_form').submit(function () {
                    var folder_name = $('input#folder_name').val();

                    if(folder_name == '') {
                        alert('Enter a name for new folder');
                    } else {
                        var dataString = $('#input_form').serialize();
                        var action = 'index.php?option=com_mediamu&no_html=1&task=folder.create';
                        ajaxReq(dataString, action);
                    }
                    return false;
                });
                
                $('a#select').click(function() {
                    var elClass = this.className;
                    if(elClass == 'select') {
                        this.className = 'deselect';
                        $('input.delete').attr('checked', true);
                    } else {
                        this.className = 'select';
                        $('input.delete').attr('checked', false);
                    }
                });

                //create tooltip
                $('a.finfo').mouseover(function() {
                    var elId = this.id;

                    $("a#" + elId + "").tooltip({ 
                        tip : "div." + elId + "",
                        delay : 20,
                        position : 'right right',
                        offset: [80, 3]
                    })
                });

                $('a.path_rm_btn').click(function() {
                    var cnfTxt = '<?php echo JText::_('COM_MEDIAMU_DIR_BROSWER_CONFIRM_DELETE');  ?>';
                    if(confirm(cnfTxt)) {
                        rmPath( this.name );
                    } else {
                        return false;
                    }
                });
            });

            <?php
            $script = ob_get_contents();
            ob_clean();
            return $script;
        }
}
?>