<?php
/*
 * Copyright 2005-2014 MERETHIS
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
 * As a special exception, the copyright holders of this program give MERETHIS
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of MERETHIS choice, provided that
 * MERETHIS also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 *
 */

namespace Centreon\Custom\Module;

/**
 * Description of ModuleInformations
 *
 * @author lionel
 */
class ModuleInformations
{
    /**
     * 
     * @param array $module
     * @return boolean
     */
    public static function checkDependency($module)
    {
        $dependencySatisfied = false;
        $db = \Centreon\Internal\Di::getDefault()->get('db_centreon');
        $sql = "SELECT name, version FROM module WHERE name = '$module[name]'";
        $res = $db->query($sql);
        $dependency = $res->fetchAll(\PDO::FETCH_ASSOC);
        
        if (is_array($dependency) && count($dependency) > 0) {
            if (version_compare($dependency[0]['version'], $module['version'], '>=')) {
                $dependencySatisfied = true;
            }
        }
        
        return $dependencySatisfied;
    }
    
    public static function isModuleActivated($moduleName)
    {
        $resultModule = \Centreon\Models\Module::getIdByParameter('name', $moduleName);
        $result = \Centreon\Models\Module::getParameters($resultModule[0]['id'], 'isactivated');
        return (boolean)$result[0]['isactivated'];
    }
    
    public static function isModuleInstalled($moduleName)
    {
        $resultModule = \Centreon\Models\Module::getIdByParameter('name', $moduleName);
        $result = \Centreon\Models\Module::getParameters($resultModule[0]['id'], 'isinstalled');
        return (boolean)$result[0]['isinstalled'];
    }
    
    /**
     * Chzeck to see if the module routes can be reached
     * @return boolean
     */
    public static function isModuleReachable($moduleName)
    {
        $isReachable = false;
        if (self::isModuleActivated($moduleName) && self::isModuleInstalled($moduleName)) {
            $isReachable = true;
        }
        return $isReachable;
    }
    
    /**
     * 
     */
    public static function addInformationsInDb()
    {
        
    }
    
    /**
     * 
     */
    public static function updateInformationsInDb()
    {
        
    }
}
