<?php
// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Select project form field
 */
class JFormFieldTask_Parent extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'task_parent';
        
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions($config = array()) 
        {
                $db = JFactory::getDBO();
                $query = new JDatabaseQuery;
                $query->select('a.id AS value, a.title AS text, a.level');
                $query->from('#__project_tasks AS a');
                $query->where(' `type` = 1 AND `state` >= 1 ');
                $query->order('a.lft ASC, ordering ASC');

                $db->setQuery($query);
                $rows = $db->loadObjectList();
                $options = array();
                $options[] = JHtml::_('select.option', 0, ' - '.JText::_('JNONE').' - ');
                foreach($rows as $item)
                {	
                	$repeat = ( $item->level - 1 >= 0 ) ? $item->level - 1 : 0;
                    $item->text = str_repeat('- ', $repeat).$item->text;
                	$options[] = JHtml::_('select.option', $item->value, $item->text);
                }
                $options = array_merge(parent::getOptions() , $options);
                return $options;
        }
}