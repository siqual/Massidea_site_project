<?php
/**
 *  UserWeblinks -> UserWeblinks database model for usr_weblinks_uwl table.
 *
 * 	Copyright (c) <2009>, Mikko Korpinen
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied  
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for  
 * more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free 
 * Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * License text found in /license/
 */

/**
 *  UserWeblinks - class
 *
 *  @package 	models
 *  @author     Mikko Korpinen
 *  @copyright 	2010 Mikko Korpinen
 *  @license 	GPL v2
 *  @version 	1.0
 */ 
class Default_Model_UserWeblinks extends Zend_Db_Table_Abstract
{
	// Name of table
    protected $_name = 'usr_weblinks_uwl';
	
	// Primary key of table
	protected $_primary = 'id_uwl';
	
	// Tables reference map
	protected $_referenceMap    = array(
        'UserWeblinks' => array(
            'columns'           => array('id_usr_uwl'),
            'refTableClass'     => 'Default_Model_User',
            'refColumns'        => array('id_usr')
        )
    );
	
    /**
     * setWeblink - Set web link
     * 
     * @author Mikko Korpinen
     * @param int $id_usr
     * @param string (45) $name
     * @param string (150) $url
     * @param int $count
     */
    public function setWeblink($id_usr, $name, $url, $count) {
        // get old values
        $select = $this->select()
            ->from($this, array('name_uwl', 'url_uwl'))
            ->where('id_usr_uwl = ?', $id_usr)
            ->where('count_uwl = ?', $count);
        $result = $this->fetchAll($select)->toArray();

        $new = array(
                'id_usr_uwl' => $id_usr,
                'name_uwl' => $name,
                'url_uwl' => $url,
                'count_uwl' => $count,
                'created_uwl' => new Zend_Db_Expr('NOW()'),
                'modified_uwl' => new Zend_Db_Expr('NOW()')
        );

        $update = array(
                'id_usr_uwl' => $id_usr,
                'name_uwl' => $name,
                'url_uwl' => $url,
                'count_uwl' => $count,
                'modified_uwl' => new Zend_Db_Expr('NOW()')
        );

        // if old values found (= not new link field)
		if(isset($result[0]['name_uwl'])) {
            if ($result[0]['name_uwl'] == $name
            && $result[0]['url_uwl'] == $url
            && $result[0]['count_uwl'] == $count) {
                return true;    // dont set the same values
            }

            // update old values
            $where[] = $this->getAdapter()->quoteInto('id_usr_uwl = ?', $id_usr);
            $where[] = $this->getAdapter()->quoteInto('count_uwl = ?', $count);

			if($this->update($update, $where)) {
                return true;
            } else {
                return false;
            }
        // if no old values (insert new data)
		} else {
            // insert new values
			$this->insert($new);
        }
    }

    /**
     * getWeblink - Get specified weblink
     *
     * @author Mikko Korpinen
     * @param int $id
     * @return array
     */
    public function getWeblink($id) {
        $select = $this->select()
				->from($this, array('*'))
				->where('id_uwl = ?', $id);

		$result = $this->fetchAll($select)->toArray();

        return $result[0];
    }

    /**
     * getUserWeblinks - Get user weblinks
     *
     * @author Mikko Korpinen
     * @param int $id_usr
     * @return array
     */
    public function getUserWeblinks($id_usr) {
        $select = $this->select()
				->from($this, array('*'))
				->where('id_usr_uwl = ?', $id_usr)
                ->order('count_uwl');

		$result = $this->fetchAll($select)->toArray();

        return $result;
    }

    /**
     * getAllWeblinks - Get all weblinks
     *
     * @author Mikko Korpinen
     * @return array
     */
    public function getAllWeblinks() {
        $select = $this->select()
				->from($this, array('*'));

		$result = $this->fetchAll($select)->toArray();

        return $result;
    }

    /**
     * removeUserWeblinks - Remove user weblinks
     *
     * @author Mikko Korpinen
     * @param int $id_usr
     */
    public function removeUserWeblinks($id_usr) {
        $where = $this->getAdapter()->quoteInto('id_usr_uwl = ?', $id_usr);
        if ($this->delete($where)) {
            return true;
        } else {
            return false;
        }
    }
    
} // end of class
