<?php

/**
 * Model for pulling coin data from coins and finds tables
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $finds = new Coins();
 * $schema = $finds->info();
 * ?>
 * </code>
 *
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/ConfigureCoinCopyForm.php
 */
class Coins extends Pas_Db_Table_Abstract
{

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'coins';

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';


    /** Get all coin data with multiple joins
     * @access public
     * @param int $id
     * @return array
     */
    public function getCoinData($id)
    {
        $coins = $this->getAdapter();
        $select = $coins->select()
            ->from($this->_name, array(
                'id', 'obverse_description', 'obverse_inscription',
                'cciNumber', 'reverse_description', 'ruler_id',
                'mint_ID', 'reverse_inscription', 'denomination',
                'degree_of_wear', 'allen_type', 'va_type',
                'mack' => 'mack_type', 'reeceID', 'ruler_id',
                'ruler2_id', 'denom' => 'denomination', 'mint_id',
                'die' => 'die_axis_measurement', 'wearID' => 'degree_of_wear',
                'moneyer', 'revtypeID', 'categoryID',
                'typeID', 'tribeID' => 'tribe', 'createdBy',
                'reverse_mintmark', 'initial_mark', 'bmc_type',
                'rudd_type', 'rrcID', 'ricID'
            ))
            ->joinLeft('finds', 'finds.secuid = coins.findID',
                array('broadperiod', 'returnID' => 'id', 'old_findID'))
            ->joinLeft('ironagetribes', 'coins.tribe = ironagetribes.id',
                array('tribe'))
            ->joinLeft('geographyironage', 'geographyironage.id = coins.geographyID', array('r' => 'region', 'a' => 'area'))
            ->joinLeft('denominations', 'denominations.id = coins.denomination',
                array('d' => 'denomination'))
            ->joinLeft('rulers', 'rulers.id = coins.ruler_id',
                array('ruler1' => 'issuer', 'dbpedia', 'nomismaID'))
            ->joinLeft(array('rulers_2' => 'rulers'), 'rulers_2.id = coins.ruler2_id',
                array('ruler2' => 'issuer'))
            ->joinLeft('reeceperiods', 'coins.reeceID = reeceperiods.id',
                array('period_name', 'date_range'))
            ->joinLeft('mints', 'mints.id = coins.mint_ID', array('mint_name'))
            ->joinLeft('weartypes', 'coins.degree_of_wear = weartypes.id',
                array('wear' => 'term'))
            ->joinLeft('dieaxes', 'coins.die_axis_measurement = dieaxes.id',
                array('die_axis_name'))
            ->joinLeft('categoriescoins', 'categoriescoins.id = coins.categoryID',
                array('category'))
            ->joinLeft('medievaltypes', 'medievaltypes.id = coins.typeID',
                array('type'))
            ->joinLeft('moneyers', 'moneyers.id = coins.moneyer',
                array('name'))
            ->joinLeft('emperors', 'emperors.pasID = rulers.id',
                array('pasID', 'empid' => 'emperors.id'))
            ->joinLeft('romanmints', 'romanmints.pasID = mints.id',
                array('mintid' => 'id', 'pleiadesID'))
            ->joinLeft('revtypes', 'coins.revtypeID = revtypes.id',
                array('revname' => 'type'))
            ->joinLeft('statuses', 'coins.status = statuses.id',
                array('status' => 'term'))
            ->joinLeft('jettonClasses', 'coins.jettonClass = jettonClasses.id',
                array('jettonClass' => 'className'))
            ->joinLeft('jettonGroup', 'coins.jettonGroup = jettonGroup.id',
                array('jettonGroup' => 'groupName'))
            ->joinLeft('jettonTypes', 'coins.jettonType = jettonTypes.id',
                array('jettonType' => 'typeName'))
            ->where('finds.id = ?', (int)$id)
            ->group('finds.id');
        return $coins->fetchAll($select);
    }

    /** Get coin data to delete (parameters for deletion)
     * @access public
     * @param int $id
     * @return array
     */
    public function getFindToCoinDelete($id)
    {
        $coins = $this->getAdapter();
        $select = $coins->select()
            ->from($this->_name)
            ->joinLeft('finds', 'finds.secuid = coins.findID',
                array('old_findID', 'returnID' => 'id'))
            ->where('coins.id = ?', (int)$id);
        return $coins->fetchAll($select);
    }

    /** Get all coin data as a check
     * @access public
     * @param string $secuid
     * @throws Exception
     */
    public function checkCoinData($secuid)
    {
        $select = $this->select()->where('findID = ?', (string)$secuid);
        $coin = $this->fetchRow($select);
        if ($coin) {
            throw new Exception('Coin data exists already for this record', 500);
        }
    }

    /** Get all coin data to populate editing form could deprecate this and
     * fetch row instead and make sure form fields match names
     * @access public
     * @param int $id
     * @return array
     */
    public function getCoinToEdit($id)
    {
        $coins = $this->getAdapter();
        $select = $coins->select()
            ->from($this->_name, array(
                'ruler_id', 'ruler_qualifier', 'denomination',
                'denomination_qualifier', 'mint_id', 'mint_qualifier',
                'status', 'status_qualifier', 'obverse_description',
                'obverse_inscription', 'reverse_description', 'reverse_inscription',
                'reverse_mintmark', 'degree_of_wear', 'die_axis_measurement',
                'die_axis_certainty', 'moneyer', 'reeceID',
                'revtypeID', 'revTypeID_qualifier', 'ruler2_id',
                'ruler2_qualifier', 'tribe', 'tribe_qualifier',
                'geographyID', 'geography_qualifier', 'bmc_type',
                'allen_type', 'mack_type', 'rudd_type',
                'va_type', 'cciNumber', 'phase_date_1',
                'phase_date_2', 'context', 'depositionDate',
                'numChiab', 'categoryID', 'typeID',
                'type', 'initial_mark', 'greekstateID',
                'createdBy', 'institution', 'rrcID',
                'ricID'
            ))
            ->where('coins.id = ?', (int)$id);
        return $coins->fetchAll($select);
    }

    /** Retrieve all coin data from the last entered record for adding a duplicate
     * Not sure why this is in this model!
     * @access public
     * @param int $userid
     * @return array
     * @todo Move to correct model
     */
    public function getLastRecord($userid)
    {
        $fieldList = new CopyCoin();
        $fields = $fieldList->getConfig();
        $finds = $this->getAdapter();
        $select = $finds->select()
            ->from($this->_name, $fields)
            ->where('coins.createdBy = ?', (int)$userid)
            ->order('coins.id DESC')
            ->limit(1);
        return $finds->fetchAll($select);
    }

    /** Retrieve coin record with findID
     * @access public
     */
    public function getCoinByfindID($findID)
    {
        $select = $this->select()->where('findID = ?', (string)$findID);
        return $this->fetchRow($select);
    }
}
