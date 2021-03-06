<?php

/**
 * A view helper for displaying an image based on political house assigned to.
 *
 * Example use:
 *
 * <code>
 * <?php
 * echo $this->politicalHouse()->setHouse(1);
 * ?>
 * </code>
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see  Zend_View_Helper_Abstract
 * @uses Zend_Registry Zend registry
 * @uses Zend_Cache Zend Cache
 */
class Pas_View_Helper_PoliticalHouse extends Zend_View_Helper_Abstract
{
    /** Path for the house of commons logo
     * @access protected
     * @var string
     */
    protected $_commons = '/logos/commons.jpg';

    /** Path for the house of lords logo
     * @access protected
     * @var string
     */
    protected $_lords = '/logos/lords.jpg';

    /** The house to query
     * @access public
     * @var int
     */
    protected $_house;

    /** Get the house to query
     * @access public
     * @return int
     */
    public function getHouse()
    {
        return $this->_house;
    }

    /** Set the house to query
     * @access public
     * @param  int $house
     * @return \Pas_View_Helper_PoliticalHouse
     */
    public function setHouse($house)
    {
        $this->_house = $house;

        return $this;
    }

    /** Set up the cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** Get the commons path
     * @access public
     * @return string
     */
    public function getCommons()
    {
        return $this->_commons;
    }

    /** Get the lords path
     * @access public
     * @return string
     */
    public function getLords()
    {
        return $this->_lords;
    }

    /** set a different path for commons logo
     * @access public
     * @param  string $commons
     * @return \Pas_View_Helper_PoliticalHouse
     */
    public function setCommons($commons)
    {
        $this->_commons = $commons;

        return $this;
    }

    /** Set a different path for the lords logo
     * @access public
     * @param  string $lords
     * @return \Pas_View_Helper_PoliticalHouse
     */
    public function setLords($lords)
    {
        $this->_lords = $lords;

        return $this;
    }

    /** get the cache
     * @access public
     * @return object
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');

        return $this->_cache;
    }

    /** Return the function
     * @access public
     * @return \Pas_View_Helper_PoliticalHouse
     */
    public function politicalHouse()
    {
        return $this;
    }

    /** Return the string to render
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getLogo($this->getHouse());
    }

    /** Build the image
     * @access public
     * @param  string $image
     * @param  string $house
     * @return string
     */
    public function buildImage($image, $house)
    {

        $html = '';
        if (file_exists(ASSETS_PATH . $image)) {
            list($w, $h, $type, $attr) = getimagesize(ASSETS_PATH . $image);
            $html .= '<img src="/assets/';
            $html .= $image;
            $html .= '" alt="Political house logo for ';
            $html .= $house;
            $html .= '" width="';
            $html .= $w;
            $html .= '" height="';
            $html .= $h;
            $html .= '" />';
        }

        return $html;
    }

    /** Get the logo to display
     * @access public
     * @param  int $house
     * @return string
     */
    public function getLogo($house)
    {
        switch ($house) {
            case '1':
                $houseImage = $this->buildImage($this->getCommons(), $house);
                break;
            case '2':
                $houseImage = $this->buildImage($this->getLords(), $house);
                break;
            default:
                $houseImage = '';
                break;
        }
        return $houseImage;
    }

}