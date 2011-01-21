<?php
/**
 * @version		$Id: media.php 15757 2010-04-01 11:06:27Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	Media
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @package		Joomla.Site
 * @subpackage	com_projects
 */
abstract class ProjectsHelperACL {
    /**
     * Method to determine if a user is member of a project
     *
     * @param $project_id ID of a project
     * @param $user_id ID of a user
     *
     * @return False in case the user is not a member of the project or ID of user group
     * @since	1.6
     */
    public function isMember($project_id=0, $user_id=0) {
        if (!((int) $project_id)) {
            return true;
        }

        if (!((int) $user_id)) {
            return false;
        }

        // Check if is member
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('COUNT(project_id)');
        $query->from('#__project_members AS a');
        $query->where('a.project_id = ' . (int) $project_id . ' AND a.user_id=' . (int) $user_id);
        $db->setQuery($query);

        return $db->loadResult();
    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @param	int		The category ID.
     * @return	JObject
     * @since	1.6
     */
    public static function getActions($portfolio_id=0, $project_id=0, $record=null) {
        $user = JFactory::getUser();
        $params	= JComponentHelper::getParams('com_projects');
        $assets = new JObject;
		
        $is_member = self::isMember($project_id, $user->id);
        $assetName = empty($portfolio_id) ?
                'com_projects' :
                'com_projects.category.' . (int) $portfolio_id;
        
        // is owner
        if ($record instanceof JObject) {
            $assets->set('is.owner',
                    (
                    $user->id == $record->get('created_by') ||
                    $user->id == $record->get('created_user_id')
                    )
            );
        }

        // is.authorized
        $authorisedLevels = &$user->authorisedLevels();
        $is_authorised = (is_object($record) && !empty($record->access))? 
        	in_array($record->access, $authorisedLevels): 
        	true; 
        $assets->set('is.authorised', $is_authorised);        

        // acctions
        $resources = array(
            'task',
            'ticket',
            'document',
            'core'
        );
        $actions = array(
            '.create',
            '.edit',
            '.delete', 
        );

        $assets->set('is.member', $is_member);
        foreach ($resources as &$resource) {

            // Actions
            foreach ($actions as &$action) {
                $assets->set($resource . $action,
                		$user->authorise($resource.$action, $assetName)
                );
            }

            // View
            $assets->set($resource . '.view',
                ( 
                	in_array($params->get($resource.'_access'), $authorisedLevels) ||   
            		(
	                    $is_member &&
	                    $assets->get($resource . '.create') ||
	                    $assets->get($resource . '.edit') ||
	                    $assets->get($resource . '.delete')
                	)
                )
            );
        }
        
        // More
        $assets->set('members.view', in_array($params->get('members_access'), $authorisedLevels));
        
        //var_dump($params);die();        
        
        return $assets;
    }

    /**
     *
     * Enter description here ...
     * @param unknown_type $action
     * @param unknown_type $portfolio_id
     * @param unknown_type $project_id
     */
    public static function canDo($action, $portfolio_id=0, $project_id=0, $record=null) {
        static $assets;
        if (empty($assets)) {
            $assets = self::getActions($portfolio_id, $project_id, $record);
        }

        return $assets->get($action, false);
    }   
}