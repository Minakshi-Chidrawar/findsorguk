<?php

/**
 * A view helper for  creating an image based on political party
 *
 * Example use:
 *
 * <code>
 * <?php
 * echo $this->politicalParty()->setParty('Conservative');
 * ?>
 * </code>
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_PoliticalParty extends Zend_View_Helper_Abstract
{

    /** The conservatives logo url
     * @access protected
     * @var string
     */
    protected $_conservatives = '/political/conservatives.png';

    /** The labour logo image url
     * @access protected
     * @var string
     */
    protected $_labour = '/political/labour.jpg';

    /** The lib dem logo image url
     * @access protected
     * @var string
     */
    protected $_libdem = '/political/libdem.jpg';

    /** Initiate a cache
     * @access protected
     * @var Zend_Cache
     */
    protected $_cache;

    /** The party to query
     * @access protected
     * @var string
     */
    protected $_party;

    /** Get the party you are querying
     * @access public
     * @return string
     */
    public function getParty()
    {
        return $this->_party;
    }

    /** Set the party to query
     * @access public
     * @param string $party
     * @return \Pas_View_Helper_Politicalparty
     */
    public function setParty($party)
    {
        $this->_party = $party;
        return $this;
    }

    /** Get the cache
     * @access public
     * @return Zend_Cache
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Build the image
     * @access public
     * @param string $image
     * @param string $party
     */
    public function buildImage($image, $party)
    {
        $party = str_replace(' ', '_', $party);
        $html = '';
        if(file_exists(ASSETS_PATH . $image)) {
            list($w, $h, $type, $attr) = getimagesize(ASSETS_PATH . $image);
            $html .= '<img src="';
            $html .= '/assets' . $image;
            $html .= '" alt="Party political logo for the ' . $party;
            $html .= ' party" width="';
            $html .= $w;
            $html .= '" height="';
            $html .= $h;
            $html .= '" />';
        } else {
            $html .= '<p>Party logo unavailable</p>';
        }
        return $html;
    }

    /** Determine which image to build based on political party
     * @access public
     * @param string
     */
    public function __toString()
    {
        $party = $this->getParty();
        if (!is_null($party) || $party != "") {
            switch ($party) {
                case($party == 'Labour'):
                    $partyImage = $this->buildImage($this->_labour, $party);
                    break;
                case($party == 'Conservative'):
                    $partyImage = $this->buildImage($this->_conservatives, $party);
                    break;
                case($party == 'Liberal Democrat');
                    $partyImage = $this->buildImage($this->_libdem, $party);
                    break;
                default:
                    $partyImage = NULL;
            }

            return $partyImage;
        }
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_Politicalparty
     */
    public function politicalParty()
    {
        return $this;
    }

}