<?php

/** Controller for index of help for the site topics
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Help
 * @uses Pas_Exception_Param
 */
class Help_SiteController extends Pas_Controller_Action_Admin
{

    /** The help model
     * @var NULL
     */
    protected $_help;

    /** Get the help
     * @access public
     * @return \Help
     */
    public function getHelp()
    {
        $this->_help = new Help();
        return $this->_help;
    }

    /** Setup the ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->acl->allow('public', null);
    }

    /** Display help index
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->view->help = $this->getHelp()->getTopics($this->getParam('page'), 'help');
    }

    /** Display an individual topic
     * @access public
     * @return void
     */
    public function topicAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->help = $this->getHelp()->getTopic($section = 'help', $this->getParam('id'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}