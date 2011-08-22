<?php
/**
 * @package project
 * @version 0.4.0.0
 * @author Roman Quiring <quiring@open-lims.org>
 * @copyright (c) 2008-2011 by Roman Quiring, Roman Quiring
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
 * 
 */
$GLOBALS['autoload_prefix'] = "../";
require_once("../../base/ajax.php");

/**
 * Project AJAX IO Class
 * @package project
 */
class ProjectAjax extends Ajax
{
	function __construct()
	{
		parent::__construct();
	}
	
	private function get_name()
	{
		echo "Project";
	}
	
	private function get_html()
	{
		$template = new Template("../../../../template/projects/navigation/left.html");
		$template->output();
	}
	
	public function get_array()
	{
		global $session;

		if ($session->is_valid())
		{
			if ($session->is_value("LEFT_NAVIGATION_PROJECT_ID"))
			{
				$project_id = $session->read_value("LEFT_NAVIGATION_PROJECT_ID");
			}

			if ($session->is_value("LEFT_NAVIGATION_PROJECT_ARRAY") and $project_id == $_GET[project_id])
			{
				echo json_encode($session->read_value("LEFT_NAVIGATION_PROJECT_ARRAY"));
			}
			else
			{
				$session->delete_value("LEFT_NAVIGATION_PROJECT_ARRAY");
				$session->write_value("LEFT_NAVIGATION_PROJECT_ID", $_GET[project_id], true);
				
				$return_array = array();
				
				$project = new Project($_GET[project_id]);
				if ($_GET[project_id] != ($master_project_id = $project->get_master_project_id()))
				{
					$project = new Project($master_project_id);
					$project_id = $master_project_id;
				}
				else
				{
					$project_id = $_GET[project_id];
				}
						
				$return_array[0][0] = 0;
				$return_array[0][1] = $project_id;
				$return_array[0][2] = $project->get_name();
				$return_array[0][3] = "project.png";
				$return_array[0][4] = true; // Permission
				$return_array[0][5] = true;
				
				$paramquery['username'] = $_GET['username'];
				$paramquery['session_id'] = $_GET['session_id'];
				$paramquery['nav'] = "project";
				$paramquery['run'] = "detail";
				$paramquery['project_id'] = $project_id;
				$params = http_build_query($paramquery, '', '&#38;');
				
				$return_array[0][6] = $params;
				$return_array[0][7] = false;
		
				echo json_encode($return_array);
			}
		}
	}
	
	/**
	 * @param array $array
	 */
	public function set_array($array)
	{
		global $session;
		
		$var = json_decode($array);
		if (is_array($var))
		{
			$session->write_value("LEFT_NAVIGATION_PROJECT_ARRAY", $var, true);
		}
	}
	
	/**
	 * @param integer $id
	 */
	public function get_children($id)
	{
		if (is_numeric($id))
		{
			$return_array = array();
			
			$project = new Project($id);
			$project_array = $project->list_project_related_projects();
			
			if (is_array($project_array) and count($project_array ) >= 1)
			{
				$counter = 0;
				
				foreach($project_array as $key => $value)
				{
					$project = new Project($value);
						
					$return_array[$counter][0] = -1;
					$return_array[$counter][1] = $value;
					$return_array[$counter][2] = $project->get_name();
					$return_array[$counter][3] = "project.png";
					$return_array[$counter][4] = true; // Permission
					$return_array[$counter][5] = true;
					
					$paramquery['username'] = $_GET['username'];
					$paramquery['session_id'] = $_GET['session_id'];
					$paramquery['nav'] = "project";
					$paramquery['run'] = "detail";
					$paramquery['project_id'] = $value;
					$params = http_build_query($paramquery, '', '&#38;');
					
					$return_array[$counter][6] = $params; //link
					$return_array[$counter][7] = false; //open
					$counter++;
				}
				echo json_encode($return_array);
			}
		}
	}
	
	public function method_handler()
	{
		global $session;
		
		if ($session->is_valid())
		{
			switch($_GET[run]):	
				case "get_name":
					$this->get_name();
				break;
				
				case "get_html":
					$this->get_html();
				break;
				
				case "get_array":
					$this->get_array();
				break;
				
				case "set_array":
					$this->set_array($_POST['array']);
				break;
				
				case "get_children":
					$this->get_children($_GET['id']);
				break;
			endswitch;
		}
	}
}

$organisation_unit_ajax = new ProjectAjax;
$organisation_unit_ajax->method_handler();

?>