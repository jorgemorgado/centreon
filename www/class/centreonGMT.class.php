<?php
/*
 * Copyright 2005-2015 Centreon
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation ; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * As a special exception, the copyright holders of this program give Centreon
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of Centreon choice, provided that
 * Centreon also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 */

include_once(realpath(dirname(__FILE__) . "/../../config/centreon.config.php"));
require_once realpath(dirname(__FILE__) . "/centreonDBInstance.class.php");

class CentreonGMT
{
    protected $timezoneById;
    protected $timezones;
    protected $myGMT;
    public $use;
    /**
     *
     * @var array
     */
    protected $aListTimezone;

    /**
     *
     * @param array $myTimezone
     */
    protected $myTimezone;

    /**
     *
     * @param array $hostLocations
     */
    protected $hostLocations = array();

    /**
     *
     * @param array $pollerLocations
     */
    protected $pollerLocations = array();

    /**
     * Default timezone setted in adminstration/options
     * @var string $sDefaultTimezone
     */
    protected $sDefaultTimezone;

    /**
     *
     * @param string $myOffset
     */
    protected $myOffset;

    public function __construct()
    {
        /*
         * Define Table of GMT line
         */
        $this->timezoneById = $this->getList();

        /*
         * Flag activ / inactiv
         */
        $this->use = 1;
    }

    /**
     *
     * @return string
     */
    public function used()
    {
        return $this->use;
    }

    /**
     *
     * @param string $value
     */
    public function setMyGMT($value)
    {
        $this->myGMT = $value;
    }

    /**
     *
     * @return array
     */
    public function getGMTList()
    {
        return $this->timezoneById;
    }

    /**
     *
     * @return string
     */
    public function getMyGMT()
    {
        return $this->myGMT;
    }


    /**
     * This method return timezone of user
     *
     * @return string
     */
    public function getMyTimezone()
    {
        if (is_null($this->myTimezone)) {
            if (isset($this->timezoneById[$this->myGMT])) {
                $this->myTimezone = $this->timezoneById[$this->myGMT];
            } else {
                $this->getCentreonTimezone();
                if (!empty($this->sDefaultTimezone) && !empty($this->timezoneById[$this->sDefaultTimezone])) {
                    $this->myTimezone = $this->timezoneById[$this->sDefaultTimezone];
                } else { //if we take the empty PHP
                    $this->myTimezone = date_default_timezone_get();
                }
            }
        }
        return $this->myTimezone;
    }

    /**
     *
     * @return string
     */
    public function getMyOffset()
    {
        if (is_null($this->myOffset)) {
            if (count($this->aListTimezone) == 0) {
                $this->getList();
            }
            $this->myOffset = $this->aListTimezone[$this->myGMT]['timezone_offset'];
        }
        return $this->myOffset;
    }

    /**
     *
     * @return string
     */
    public function getMyGMTForRRD()
    {
        $sOffset = '';
        if (count($this->timezoneById) == 0) {
            $this->getList();
        }

        if (isset($this->aListTimezone[$this->myGMT]['timezone_offset'])) {
            $sOffset = $this->aListTimezone[$this->myGMT]['timezone_offset'];
        }
        return $sOffset;
    }

    /**
     *
     * @param type $format
     * @param string $date
     * @param type $gmt
     * @return string
     */
    public function getDate($format, $date, $gmt = null)
    {
        $return = "";
        if (!$date) {
            $date = "N/A";
        }
        if ($date == "N/A") {
            return $date;
        }

        if (!isset($gmt)) {
            $gmt = $this->myGMT;
        }

        if (isset($date) && isset($gmt)) {
            $sDate = new DateTime();
            $sDate->setTimestamp($date);
            $sDate->setTimezone(new DateTimeZone($this->getActiveTimezone($gmt)));
            $return = $sDate->format($format);
        }

        return $return;
    }

    /**
     * @param $date
     * @param null $gmt
     * @return int|string
     */
    public function getCurrentTime($date = "N/A", $gmt = null)
    {
        $return = "";
        if ($date == "N/A") {
            return $date;
        }

        if (is_null($gmt)) {
            $gmt = $this->myGMT;
        }

        $sDate = new DateTime();
        $sDate->setTimestamp($date);
        $sDate->setTimezone(new DateTimeZone($this->getActiveTimezone($gmt)));
        $return = $sDate->getTimestamp();
        return $return;
    }

    /**
     *
     * @param type $date
     * @param type $gmt
     * @param type $reverseOffset
     * @return string
     */
    public function getUTCDate($date, $gmt = null, $reverseOffset = 1)
    {
        $return = "";
        if (!isset($gmt)) {
            $gmt = $this->myGMT;
        }

        if (isset($date) && isset($gmt)) {
            if (!is_numeric($date)) {
                $sDate = new DateTime($date);
            } else {
                $sDate = new DateTime();
                $sDate->setTimestamp($date);
            }

            $sDate->setTimezone(new DateTimeZone($this->getActiveTimezone($gmt)));

            $iTimestamp = $sDate->getTimestamp();
            $sOffset = $sDate->getOffset();
            $return = $iTimestamp + ($sOffset * $reverseOffset);
        }

        return $return;
    }

    /**
     *
     * @param type $date
     * @param type $gmt
     * @return string
     */
    public function getUTCDateFromString($date, $gmt = null, $reverseOffset = 1)
    {
        $return = "";
        if (!isset($gmt)) {
            $gmt = $this->myGMT;
        }
        if (isset($date) && isset($gmt)) {
            if (!is_numeric($date)) {
                $sDate = new DateTime($date);
            } else {
                $sDate = new DateTime();
                $sDate->setTimestamp($date);
            }

            $localDate = new DateTime();
            $sDate->setTimezone(new DateTimeZone($this->getActiveTimezone($gmt)));
            $iTimestamp = $sDate->getTimestamp();
            $sOffset = $sDate->getOffset();
            $sLocalOffset = $localDate->getOffset();
            $return = $iTimestamp - (($sOffset - $sLocalOffset) * $reverseOffset);
        }

        return $return;
    }


    /**
     *
     * @param type $gmt
     * @return string
     */
    public function getDelaySecondsForRRD($gmt)
    {
        $str = "";
        if ($gmt) {
            if ($gmt > 0) {
                $str .= "+";
            }
        } else {
            return "";
        }
    }

    /**
     *
     * @global type $pearDB
     * @param type $sid
     * @param type $DB
     * @return int
     */
    public function getMyGMTFromSession($sid = null)
    {
        if (!isset($sid)) {
            return 0;
        }

        try {
            $query = "SELECT `contact_location` FROM `contact`, `session` " .
                "WHERE `session`.`user_id` = `contact`.`contact_id` " .
                "AND `session_id` = '" . CentreonDB::escape($sid) . "' LIMIT 1";
            $DBRESULT = CentreonDBInstance::getConfInstance()->query($query);
            $info = $DBRESULT->fetchRow();
            $DBRESULT->closeCursor();
            $this->myGMT = $info["contact_location"];
        } catch (\PDOException $e) {
            $this->myGMT = 0;
        }
    }

    /**
     *
     * @global type $pearDB
     * @param int $userId
     * @param type $DB
     */
    public function getMyGTMFromUser($userId, $DB = null)
    {
        if (!empty($userId)) {

            try {
                $DBRESULT = CentreonDBInstance::getConfInstance()->query("SELECT `contact_location` FROM `contact` " .
                    "WHERE `contact`.`contact_id` = " . $userId . " LIMIT 1");
                $info = $DBRESULT->fetchRow();
                $DBRESULT->closeCursor();
                $this->myGMT = $info["contact_location"];
            } catch (\PDOException $e) {
                $this->myGMT = 0;
            }

        } else {
            $this->myGMT = 0;
        }

        return $this->myGMT;
    }

    /**
     *
     * @param type $host_id
     * @param type $date_format
     * @return \DateTime
     */
    public function getHostCurrentDatetime($host_id, $date_format = 'c')
    {
        $locations = $this->getHostLocations();
        $timezone = isset($locations[$host_id]) ? $locations[$host_id] : '';
        $sDate = new DateTime();
        $sDate->setTimezone(new DateTimeZone($this->getActiveTimezone($timezone)));
        return $sDate;
    }

    /**
     *
     * @param type $date
     * @param type $hostId
     * @param type $dateFormat
     * @param type $reverseOffset
     * @return string
     */
    public function getUTCDateBasedOnHostGMT($date, $hostId, $dateFormat = 'c', $reverseOffset = 1)
    {
        $locations = $this->getHostLocations();

        if (isset($locations[$hostId]) && $locations[$hostId] != '0') {
            $date = $this->getUTCDate($date, $locations[$hostId], $reverseOffset);
        }

        return date($dateFormat, $date);
    }

    /**
     *
     * @param type $date
     * @param type $hostId
     * @param type $dateFormat
     * @return string
     */
    public function getUTCTimestampBasedOnHostGMT($date, $hostId, $dateFormat = 'c')
    {
        $locations = $this->getHostLocations();

        if (isset($locations[$hostId]) && $locations[$hostId] != '0') {
            $date = $this->getUTCDate($date, $locations[$hostId]);
        }

        return $date;
    }

    /**
     *
     * @param type $hostId
     * @return array
     */
    public function getUTCLocationHost($hostId)
    {
        $locations = $this->getHostLocations();

        if (isset($locations[$hostId])) {
            return $locations[$hostId];
        }

        return null;
    }

    /**
     * Get the list of timezone
     *
     * @return array
     */
    public function getList()
    {
        $aDatas = array();

        $queryList = "SELECT timezone_id, timezone_name, timezone_offset FROM timezone ORDER BY timezone_name asc";
        try {
            $res = CentreonDBInstance::getConfInstance()->query($queryList);
        } catch (\PDOException $e) {
            return array();
        }

        $aDatas[null] = null;
        while ($row = $res->fetchRow()) {
            $this->timezones[$row['timezone_name']] = $row['timezone_id'];
            $aDatas[$row['timezone_id']] = $row['timezone_name'];
            $this->aListTimezone[$row['timezone_id']] = $row;
        }

        return $aDatas;
    }

    /**
     * @param array $values
     * @param array $options
     * @return array
     */
    public function getObjectForSelect2($values = array(), $options = array())
    {
        $items = array();

        $listValues = '';
        $queryValues = array();
        if (!empty($values)) {
            foreach ($values as $k => $v) {
                $listValues .= ':timezone' . $v . ',';
                $queryValues['timezone' . $v] = (int)$v;
            }
            $listValues = rtrim($listValues, ',');
        } else {
            $listValues .= '""';
        }

        # get list of selected timezones
        $query = "SELECT timezone_id, timezone_name FROM timezone "
            . "WHERE timezone_id IN (" . $listValues . ") ORDER BY timezone_name ";

        $stmt = CentreonDBInstance::getConfInstance()->prepare($query);

        if (!empty($queryValues)) {
            foreach ($queryValues as $key => $id) {
                $stmt->bindValue(':' . $key, $id, PDO::PARAM_INT);
            }
        }
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $items[] = array(
                'id' => $row['timezone_id'],
                'text' => $row['timezone_name']
            );
        }

        return $items;
    }

    /**
     * Get list of timezone of host
     * @return array
     */
    public function getHostLocations()
    {
        if (count($this->hostLocations)) {
            return $this->hostLocations;
        }

        $this->getPollerLocations();

        $this->hostLocations = array();

        $query = 'SELECT host_id, instance_id, timezone FROM hosts WHERE enabled = 1 ';
        try {
            $res = CentreonDBInstance::getMonInstance()->query($query);
            while ($row = $res->fetchRow()) {
                if ($row['timezone'] == "" && isset($this->pollerLocations[$row['instance_id']])) {
                    $this->hostLocations[$row['host_id']] = $this->pollerLocations[$row['instance_id']];
                } else {
                    $this->hostLocations[$row['host_id']] = str_replace(':', '', $row['timezone']);
                }
            }
        } catch (\PDOException $e) {
            // Nothing to do
        }
        return $this->hostLocations;
    }

    /**
     * Get list of timezone of pollers
     * @return array
     */
    public function getPollerLocations()
    {
        if (count($this->pollerLocations)) {
            return $this->pollerLocations;
        }

        $query = 'SELECT ns.id, t.timezone_name ' .
            'FROM cfg_nagios cfgn, nagios_server ns, timezone t ' .
            'WHERE cfgn.nagios_activate = "1" ' .
            'AND cfgn.nagios_server_id = ns.id ' .
            'AND cfgn.use_timezone = t.timezone_id ';
        try {
            $res = CentreonDBInstance::getConfInstance()->query($query);
            while ($row = $res->fetchRow()) {
                $this->pollerLocations[$row['id']] = $row['timezone_name'];
            }
        } catch (\Exception $e) {
            // Nothing to do
        }

        return $this->pollerLocations;
    }

    /**
     * Get default timezone setted in admintration/options
     *
     * @return string
     */
    public function getCentreonTimezone()
    {
        if (is_null($this->sDefaultTimezone)) {
            $sTimezone = '';

            $query = "SELECT `value` FROM `options` WHERE `key` = 'gmt' LIMIT 1";
            try {
                $res = CentreonDBInstance::getConfInstance()->query($query);
                $row = $res->fetchRow();
                $sTimezone = $row["value"];
            } catch (\Exception $e) {
                // Nothing to do
            }
            $this->sDefaultTimezone = $sTimezone;
        }
        return $this->sDefaultTimezone;
    }

    /**
     * This method verifies the timezone which is to be used in the other appellants methods.
     * In priority, it uses timezone of the object, else timezone of centreon, then lattest timezone PHP
     *
     * @param string $gmt
     * @return string timezone
     */
    public function getActiveTimezone($gmt)
    {
        $sTimezone = "";
        if (count($this->timezones) == 0) {
            $this->getList();
        }

        if (isset($this->timezones[$gmt])) {
            $sTimezone = $gmt;
        } elseif (isset($this->timezoneById[$gmt])) {
            $sTimezone = $this->timezoneById[$gmt];
        } else {
            $this->getCentreonTimezone();
            if (!empty($this->sDefaultTimezone) && !empty($this->timezones[$this->sDefaultTimezone])) {
                $sTimezone = $this->timezones[$this->sDefaultTimezone];
            } else { //if we take the empty PHP
                $sTimezone = date_default_timezone_get();
            }
        }
        return $sTimezone;
    }
}
