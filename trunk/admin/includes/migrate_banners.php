<?php
/**
 * jUpgrade
 *
 * @version		$Id$
 * @package		MatWare
 * @subpackage	com_jupgrade
 * @copyright	Copyright 2006 - 2011 Matias Aguire. All rights reserved.
 * @license		GNU General Public License version 2 or later.
 * @author		Matias Aguirre <maguirre@matware.com.ar>
 * @link		http://www.matware.com.ar
 */

define('_JEXEC',		1);
//define('JPATH_BASE',	dirname(dirname(dirname(dirname(dirname(__FILE__))))));
define('JPATH_BASE',	dirname(__FILE__));
define('DS',			DIRECTORY_SEPARATOR);

require_once JPATH_BASE.'/defines.php';
require_once JPATH_BASE.'/jupgrade.class.php';

/**
 * Upgrade class for Banners
 *
 * This class takes the banners from the existing site and inserts them into the new site.
 *
 * @since	0.4.5
 */
class jUpgradeBanners extends jUpgrade
{
	/**
	 * @var		string	The name of the source database table.
	 * @since	0.4.5
	 */
	protected $source = '#__banner';

	/**
	 * @var		string	The name of the destination database table.
	 * @since	0.4.5
	 */
	protected $destination = '#__banners';


	/**
	 * Get the raw data for this part of the upgrade.
	 *
	 * @return	array	Returns a reference to the source data array.
	 * @since	0.4.5
	 * @throws	Exception
	 */
	protected function &getSourceData()
	{
		$rows = parent::getSourceData(
			'`bid` AS id,`cid`,`type`,`name`,`alias`, `imptotal` ,`impmade`, `clicks`, '
		 .'`clickurl`, `checked_out`, `checked_out_time`, `showBanner` AS state,'
		 .' `custombannercode`, `description`, `sticky`, `ordering`, `publish_up`, '
		 .' `publish_down`, `params`',
			null,
			'bid'
		);

		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row['params'] = $this->convertParams($row['params']);

			// Remove unused fields.
			unset($row['gid']);
		}

		return $rows;
	}
}

/**
 * Upgrade class for Banners categories
 *
 * This class takes the categories banners from the existing site and inserts them into the new site.
 *
 * @since	0.5.6
 */
class jUpgradeBannersCategories extends jUpgrade
{
	/**
	 * @var		string	The name of the source database table.
	 * @since	0.5.6
	 */
	protected $source = '#__categories';

	/**
	 * Get the raw data for this part of the upgrade.
	 *
	 * @return	array	Returns a reference to the source data array.
	 * @since	0.5.6
	 * @throws	Exception
	 */
	protected function &getSourceData()
	{

		$where = "section = 'com_banner'";

		$rows = parent::getSourceData(
			'`id` AS sid, `title`, `alias`, `section`, `description`, `published`, `checked_out`, `checked_out_time`, `access`, `params`',
		  null,
			$where,
			'id'
		);

		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row['params'] = $this->convertParams($row['params']);

			// Remove unused fields.
			unset($row['gid']);
		}

		return $rows;
	}


	/**
	 * Sets the data in the destination database.
	 *
	 * @return	void
	 * @since	0.5.6
	 * @throws	Exception
	 */
	protected function setDestinationData()
	{
		// Get the source data.
		$rows	= $this->getSourceData();

		//
		// Insert the categories
		//
		foreach ($rows as $row)
		{
			// Convert the array into an object.
			$row = (object) $row;

			// Insert category
			if (!$this->insertCategory($row)) {
				throw new Exception('JUPGRADE_ERROR_INSERTING_CATEGORY');
			}

			// Insert asset
			if (!$this->insertAsset($row)) {
				throw new Exception('JUPGRADE_ERROR_INSERTING_ASSET');
			}

		}
	}

	/**
	 * The public entry point for the class.
	 *
	 * @return	void
	 * @since	0.5.6
	 * @throws	Exception
	 */
	public function upgrade()
	{
		if (parent::upgrade()) {
			// Rebuild the categories table
			$table = JTable::getInstance('Category', 'JTable', array('dbo' => $this->db_new));

			if (!$table->rebuild()) {
				echo JError::raiseError(500, $table->getError());
			}

			// Rebuild the assets table
			$assets = JTable::getInstance('Asset', 'JTable', array('dbo' => $this->db_new));

			if (!$assets->rebuild()) {
				echo JError::raiseError(500, $assets->getError());
			}

		}
	}

}


// Migrate the banners.
$banners = new jUpgradeBanners;
$banners->upgrade();

// Migrate the categories of banners.
$bannersCat = new jUpgradeBannersCategories;
$bannersCat->upgrade();