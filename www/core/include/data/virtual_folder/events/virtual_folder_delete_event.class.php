<?php
/**
 * @package data
 * @version 0.4.0.0
 * @author Roman Konertz
 * @copyright (c) 2008-2010 by Roman Konertz
 * @license GPLv3
 * 
 * This file is part of Open-LIMS
 * Available at http://www.open-lims.org
 * 
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * version 3 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Virtual Folder Delete Event
 * @package data
 */
class VirtualFolderDeleteEvent extends Event
{    
	private $virtual_folder_id;
	
	function __construct($virtual_folder_id)
    {
    	if (is_numeric($virtual_folder_id))
    	{
    		parent::__construct();
    		$this->virtual_folder_id = $virtual_folder_id;
    	}
    	else
    	{
    		$this->virtual_folder_id = null;
    	}
    }
    
    public function get_virtual_folder_id()
    {
    	if ($this->virtual_folder_id)
    	{
    		return $this->virtual_folder_id;
    	}
    	else
    	{
    		return null;
    	}
    }
}

?>