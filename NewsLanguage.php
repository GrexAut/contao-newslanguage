<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2011
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


class NewsLanguage extends Frontend
{

	/**
	 * Translate the URL parameters using the changelanguage module hook
	 *
	 * @param	array
	 * @param	string
	 * @param	array
	 * @return	array
	 * @see		ModuleChangeLanguage::compile()
	 */
	public function translateUrlParameters($arrGet, $strLanguage, $arrRootPage)
	{
		$strItem = $this->Input->get('items');
		
        if ($strItem != '')
        {
        	$objNews = $this->Database->prepare("SELECT tl_news.*, tl_news_archive.master FROM tl_news LEFT OUTER JOIN tl_news_archive ON tl_news.pid=tl_news_archive.id WHERE tl_news.id=? OR tl_news.alias=?")
        							  ->limit(1)
        							  ->execute((int)$strItem, $strItem);
        	
        	// We found a news item!!
        	if ($objNews->numRows)
        	{
        		$id = ($objNews->master > 0) ? $objNews->languageMain : $objNews->id;
        		$objItem = $this->Database->prepare("SELECT tl_news.id, tl_news.alias FROM tl_news LEFT OUTER JOIN tl_news_archive ON tl_news.pid=tl_news_archive.id WHERE tl_news_archive.language=? AND (tl_news.id=? OR languageMain=?)")->execute($strLanguage, $id, $id);

				if ($objItem->numRows)
				{
					$arrGet['url']['items'] = $objItem->alias ? $objItem->alias : $objItem->id;
				}
        	}
        }
        
		return $arrGet;
	}
}
