<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');// no direct access


// Include Phoca Gallery
if (!JComponentHelper::isEnabled('com_phocagallery', true)) {
    echo '<div class="alert alert-danger">Phoca Gallery Error: Phoca Gallery component is not installed or not published on your system</div>';
    return;
}

if (!class_exists('PhocaGalleryLoader')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocagallery/libraries/loader.php');
}

phocagalleryimport('phocagallery.path.path');
phocagalleryimport('phocagallery.path.route');
phocagalleryimport('phocagallery.library.library');
phocagalleryimport('phocagallery.text.text');
phocagalleryimport('phocagallery.access.access');
phocagalleryimport('phocagallery.file.file');
phocagalleryimport('phocagallery.file.filethumbnail');
phocagalleryimport('phocagallery.image.image');
phocagalleryimport('phocagallery.image.imagefront');
phocagalleryimport('phocagallery.render.renderfront');
phocagalleryimport('phocagallery.render.renderadmin');
phocagalleryimport('phocagallery.render.renderdetailwindow');
phocagalleryimport('phocagallery.ordering.ordering');
phocagalleryimport('phocagallery.picasa.picasa');
phocagalleryimport('phocagallery.html.category');

$user 				= JFactory::getUser();
$db 				= JFactory::getDBO();
$document			= JFactory::getDocument();
$library 			= PhocaGalleryLibrary::getLibrary();
$path 				= PhocaGalleryPath::getPath();

// LIBRARY
$libraries['pg-group-shadowbox']		= $library->getLibrary('pg-group-shadowbox');
$libraries['pg-group-highslide']		= $library->getLibrary('pg-group-highslide');
$libraries['pg-group-jak-mod']			= $library->getLibrary('pg-group-jak-mod');

$lang = JFactory::getLanguage();
$lang->load('com_phocagallery');

PhocaGalleryRenderFront::renderAllCSS();
JHTML::stylesheet( 'media/mod_phocagallery_image/css/phocagallery.css' );
$component			= 'com_phocagallery';
$paramsC			= JComponentHelper::getParams($component) ;

// PARAMS
$tmpl['formaticon'] 		= $paramsC->get( 'icon_format', 'png' );
$limit_start 				= $params->get( 'limit_start', 0 );
$limit_count 				= $params->get( 'limit_count', 1 );
$category_id 				= $params->get( 'category_id', array() );
$display_description_detail = $params->get( 'display_description_detail', 0 );
$description_detail_height 	= $params->get( 'description_detail_height', 16 );
$display_categories         = $params->get( 'display_categories', '' );
$display_not_categories     = $params->get( 'display_not_categories', '' );
$font_color 				= $params->get( 'font_color', '#135cae' );
$background_color 			= $params->get( 'background_color', '#fcfcfc' );
$background_color_hover 	= $params->get( 'background_color_hover', '#f5f5f5' );
$image_background_color 	= $params->get( 'image_background_color', '#f5f5f5' );
$border_color 				= $params->get( 'border_color','#e8e8e8' );
$border_color_hover 		= $params->get( 'border_color_hover','#135cae' );
$phocagallery_module_width 	= $params->get( 'phocagallery_module_width', '' );
$tmpl['display_name'] 		= $params->get( 'display_name', 1 );
$tmpl['display_icon_detail']= $params->get( 'display_icon_detail', 1 );
$tmpl['display_icon_download'] 		= $params->get( 'display_icon_download', 0 );
$tmpl['display_rating']		= $paramsC->get( 'display_rating_img', 0);
$font_size_name 			= $params->get( 'font_size_name', 12 );
$char_length_name 			= $params->get( 'char_length_name', 11 );
$tmpl['detail_window'] 		= $params->get( 'detail_window', 0 );
$tmpl['imagewidth'] 		= $paramsC->get( 'medium_image_width' , 100 );
$tmpl['imageheight'] 		= $paramsC->get( 'medium_image_height', 100 );
$small_image_width 			= $paramsC->get( 'small_image_width' , 50 );
$small_image_height 		= $paramsC->get( 'small_image_height', 50 );
$large_image_width 			= $paramsC->get( 'large_image_width' , 640 );
$large_image_height 		= $paramsC->get( 'large_image_height', 480 );
$custom_image_width 		= $params->get( 'custom_image_width' , '' );
$custom_image_height 		= $params->get( 'custom_image_height', '' );
$minimum_box_width	 		= $params->get( 'minimum_box_width', '' );
$popup_width 				= $paramsC->get( 'front_modal_box_width', 680 );
$popup_height 				= $paramsC->get( 'front_modal_box_height', 560 );
$responsive 				= $paramsC->get( 'responsive', 1 );
$image_background_shadow 	= $params->get( 'image_background_shadow', 'none' );
$module_type 				= $params->get( 'module_type', 0 );
$module_link 				= $params->get( 'module_link', 0 );
$padding_mosaic 			= $params->get( 'padding_mosaic', 3 );
$image_ordering 			= $params->get( 'image_ordering', 9 );
$imageSize					= $params->get( 'image_size', 'small' ); //Additional variable take from parameters for image size override KM 08-05-12
$tmpl['displaying_tags_true'] 	= 0;
$tmpl['display_icon_vm']		= 0;
$tmpl['start_cooliris']			= 0;
$tmpl['trash']					= 0;
$tmpl['publish_unpublish']		= 0;
$tmpl['display_icon_geo_box']	= 0;
$tmpl['display_camera_info']	= 0;
$tmpl['display_icon_extlink1_box']		= 0;
$tmpl['display_icon_extlink2_box']		= 0;
$tmpl['approved_not_approved']			= 0;
$tmpl['display_icon_commentimg_box']	= 0;

$tmpl['highslidedescription']		= $params->get( 'highslide_description', 0 );
$tmpl['jakslideshowdelay']			= $params->get( 'jak_slideshow_delay', 5);
$tmpl['jakorientation']				= $params->get( 'jak_orientation', 'none');
$tmpl['jakdescription']				= $params->get( 'jak_description', 1);
$tmpl['jakdescriptionheight']		= $params->get( 'jak_description_height', 0);
$alternative_layout                 = $params->get( 'alternative_layout', 'default');


if ($imageSize == 'small') {
	$tmpl['imagewidth']	= $small_image_width;
	$tmpl['imageheight']= $small_image_height;
} else if ($imageSize == 'large'){
	$tmpl['imagewidth']	= $large_image_width;
	$tmpl['imageheight']= $large_image_height;
}

//Custom image width and size
if ((int)$custom_image_width > 0) {
	$tmpl['imagewidth'] = $custom_image_width;
}
/*
if ((int)$custom_image_height > 0) {
	$tmpl['imageheight'] = $custom_image_height;
}*/

//Customisation for allowing admins ot override the size of the image used
// Created by Keith Mountifield 08/05/2012
if($imageSize == 'auto'){
	if ($module_type == 1) {
		$imgCatSize	= 'small';
	} else {
		$imgCatSize	= 'medium';
	}
} else {
	$imgCatSize = $imageSize;
}
// If Module link is to category or categories, the detail window method needs to be set to no popup
if ((int)$module_link > 0) {
	$tmpl['detail_window'] = 7;
}


// PARAMS - Background shadow
if ($module_type == 0) {
	$document->addCustomTag( "\n" ."<style type=\"text/css\">\n"
	." #phocagallery-module-ri .pg-cv-name-mod-ri {color: $font_color ;}\n"
	." #phocagallery-module-ri .pg-cv-box {background: $background_color ; border:1px solid $border_color ;}\n"
	." #phocagallery-module-ri .pg-box1 {  }\n"
	." #phocagallery-module-ri .pg-cv-box:hover, .pg-cv-box.hover {border:1px solid $border_color_hover ; background: $background_color_hover ;}\n"
	." </style>\n"
	. "\n");
}

//END CSS

// PARAMS
if ($display_description_detail == 1) {
	$popup_height	= $popup_height + $description_detail_height;
}

$tmpl['category_box_space'] 	= $params->get( 'category_box_space', 0 );
$detail_buttons 		= $params->get( 'detail_buttons', 1 );
if ($detail_buttons != 1) {
	$popup_height	= $popup_height - 45;
}
$popup_height_rating = $popup_height;
if ($tmpl['display_rating'] == 1) {
	$popup_height_rating	= $popup_height + 35;
}

// PARAMS
$modal_box_overlay_color 	= $params->get( 'modal_box_overlay_color', '#000000' );
$modal_box_overlay_opacity 	= $params->get( 'modal_box_overlay_opacity', 0.3 );
$modal_box_border_color 	= $params->get( 'modal_box_border_color', '#6b6b6b' );
$modal_box_border_width 	= $params->get( 'modal_box_border_width', '2' );
$highslide_class			= $params->get( 'highslide_class', 'rounded-white');
$highslide_opacity			= $params->get( 'highslide_opacity', 0);
$highslide_outline_type		= $params->get( 'highslide_outline_type', 'rounded-white');
$highslide_fullimg			= $params->get( 'highslide_fullimg', 0);
$highslide_slideshow		= $params->get( 'highslide_slideshow', 1);
$highslide_close_button		= $params->get( 'highslide_close_button', 0);


// =======================================================
// DIFFERENT METHODS OF DISPLAYING THE DETAIL VIEW
// =======================================================
// MODAL - will be displayed in case e.g. highslide or shadowbox too, because in there are more links
JHtml::_('behavior.modal', 'a.pg-modal-button');

$btn = new PhocaGalleryRenderDetailWindow();
$btn->popupWidth 			= $popup_width;
$btn->popupHeight 			= $popup_height;
$btn->mbOverlayOpacity		= $modal_box_overlay_opacity;
$btn->sbSlideshowDelay		= $paramsC->get( 'sb_slideshow_delay', 5 );
$btn->sbSettings			= $paramsC->get( 'sb_settings', "overlayColor: '#000',overlayOpacity:0.5,resizeDuration:0.35,displayCounter:true,displayNav:true" );
$btn->hsSlideshow			= $highslide_slideshow;
$btn->hsClass				= $highslide_class;
$btn->hsOutlineType			= $highslide_outline_type;
$btn->hsOpacity				= $highslide_opacity;
$btn->hsCloseButton			= $highslide_close_button;
$btn->hsFullImg				= $highslide_fullimg;
$btn->jakDescHeight			= $tmpl['jakdescriptionheight'];
$btn->jakDescWidth			= '';
$btn->jakOrientation		= $tmpl['jakorientation'];
$btn->jakSlideshowDelay		= $tmpl['jakslideshowdelay'];
$btn->bpTheme 				= $paramsC->get( 'boxplus_theme', 'lightsquare');
$btn->bpBautocenter 		= (int)$paramsC->get( 'boxplus_bautocenter', 1);
$btn->bpAutofit 			= (int)$paramsC->get( 'boxplus_autofit', 1);
$btn->bpSlideshow 			= (int)$paramsC->get( 'boxplus_slideshow', 0);
$btn->bpLoop 				= (int)$paramsC->get( 'boxplus_loop', 0);
$btn->bpCaptions 			= $paramsC->get( 'boxplus_captions', 'bottom');
$btn->bpThumbs 				= $paramsC->get( 'boxplus_thumbs', 'inside');
$btn->bpDuration 			= (int)$paramsC->get( 'boxplus_duration', 250);
$btn->bpTransition 			= $paramsC->get( 'boxplus_transition', 'linear');
$btn->bpContextmenu 		= (int)$paramsC->get( 'boxplus_contextmenu', 1);
$btn->extension				= 'ri';

// Random Number - because of more modules on the site
$randName	= 'PhocaGalleryRIM' . substr(md5(uniqid(time())), 0, 8);
//$randName2	= 'PhocaGalleryRIM2' . substr(md5(uniqid(time())), 0, 8);
$btn->jakRandName 			= 'optgjaksMod'.$randName;

$btn->setButtons($tmpl['detail_window'], $libraries, $library);
$button = $btn->getB1();
$button2 = $btn->getB2();
$buttonOther = $btn->getB3();


$tmpl['highslideonclick']	= '';// for using with highslide
if (isset($button->highslideonclick)) {
	$tmpl['highslideonclick'] = $button->highslideonclick;// TODO
}
$tmpl['highslideonclick2']	= '';
if (isset($button->highslideonclick2)) {
	$tmpl['highslideonclick2'] = $button->highslideonclick2;// TODO
}


$folderButton = new JObject();
$folderButton->set('name', 'image');
$folderButton->set('options', "");
// End open window parameters
// ==================================================================


// END DETAIL WINDOW



$userACLArray = implode( ',',$user->getAuthorisedViewLevels());
// Category ID - If the category is set, the images are taken from this category and the selection above is not accepted
// 1) User has selected categories
$whereSelectedCat = '';
if ($category_id != '' && is_array($category_id) && count($category_id)) {
	//$implodeAllowedCategoriesArray = implode( ',', $category_id);
	//$categories = $category_id;
	$whereSelectedCat = ' AND cc.id IN ('. implode( ',', $category_id) .')';

}

// 2) User has selected only one catetory
else if ($category_id != '' && !is_array($category_id)) {
	//$implodeAllowedCategoriesArray = (int)$category_id;
	//$categories = array(0 => $category_id);
	$whereSelectedCat = ' AND cc.id IN ('. (int)$category_id .')';
}
// 3) If no category was selected, all will be used

// ACCESS RIGHTS
// All categories where the user has access
$query = 'SELECT cc.title AS text, cc.id AS id, cc.parent_id as parentid, cc.alias as alias, cc.access as access, cc.accessuserid as accessuserid'
		. ' FROM #__phocagallery_categories AS cc'
		. ' WHERE cc.published = 1'
		. ' AND cc.access IN ('. $userACLArray.')';
if ($display_categories) {
	$query .= ' AND cc.id IN ('. $display_categories . ')' ;
}
if ($display_not_categories) {
	$query .= ' AND cc.id NOT IN ('. $display_not_categories . ')' ;
}
if ($whereSelectedCat != '') {
	$query .= $whereSelectedCat;
}
$query .= ' ORDER BY cc.ordering';

$db->setQuery( $query );
$categories = $db->loadObjectList();

//$neededAccessLevels	= PhocaGalleryAccess::getNeededAccessLevels();
//$access				= PhocaGalleryAccess::isAccess($user->authorisedLevels(), $neededAccessLevels);






$unSet = 0;
foreach ($categories as $key => $category) {
	// USER RIGHT - ACCESS - - - - - -
	$rightDisplay	= 1;

	if (isset($categories[$key])){
		$rightDisplay = PhocaGalleryAccess::getUserRight('accessuserid', $categories[$key]->accessuserid, $categories[$key]->access, $user->getAuthorisedViewLevels(), $user->get('id', 0), 0);
	}
	if ($rightDisplay == 0) {
		unset($categories[$key]);
		$unSet = 1;
	}
	// - - - - - - - - - - - - - - - -
}
if ($unSet == 1) {
	$categories = array_values($categories);
}
$allowedCategories = $categories;

// From objects to array only
$allowedCategoriesArray = array();
foreach ($allowedCategories as $key => $value) {
	$allowedCategoriesArray[] = $value->id;
}

// Implode the array
$implodeAllowedCategoriesArray = implode( ',', $allowedCategoriesArray);



if ($image_ordering == 9) {
	$imageOrdering = ' ORDER BY RAND()';
} else {

	$iOA = PhocaGalleryOrdering::getOrderingString($image_ordering);
	$imageOrdering = $iOA['output'];
}

if (!empty($allowedCategories)) {
	$image = '';
	$query = 'SELECT cc.id AS idcat, a.id AS idimage'
	.' FROM #__phocagallery_categories AS cc'
	.' LEFT JOIN #__phocagallery AS a ON a.catid = cc.id'
	.' WHERE a.published = 1'
	.' AND a.approved = 1'
	.' AND cc.published = 1';
	if ($implodeAllowedCategoriesArray != '') {
		$query .= ' AND cc.id IN ('.$implodeAllowedCategoriesArray.')'; // not images from not accessable categories
	}
	$query .= $imageOrdering
	.' LIMIT ' . $limit_start . ',' . $limit_count ;

	$db->setQuery($query);
	$images 		= $db->loadObjectList();
	$imageArray 	= array();
} else {
	$images = array();
}


// QUERIES - all data we need to display the image
if ($images) {

	foreach ($images as $valueImage) {
		$imageArray[] = $valueImage->idimage;
	}
	$imageIds = implode(',', $imageArray);



	$query = 'SELECT cc.id, cc.alias as catalias, a.id, a.catid, a.title, a.alias, a.filename, a.description, a.extm, a.exts,a.extl, a.exto, a.extw, a.exth, a.extid,'
	. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(\':\', cc.id, cc.alias) ELSE cc.id END as catslug, '
	. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END as slug'
	. ' FROM #__phocagallery_categories AS cc'
	. ' LEFT JOIN #__phocagallery AS a ON a.catid = cc.id'
	. ' WHERE a.id in (' . $imageIds . ') AND (cc.language IN ('.$db->Quote(JFactory::getLanguage()->getTag()).','.$db->Quote('*').') AND a.language IN ('.$db->Quote(JFactory::getLanguage()->getTag()).','.$db->Quote('*').'))'
	.$imageOrdering;

	$db->setQuery($query);
	$imagesArray = $db->loadObjectList();
	$output	= array();

	// Maximum size of module image is 100 x 100
	jimport( 'joomla.filesystem.file' );

	$tmpl['boxsize']			= PhocaGalleryImage::setBoxSize($tmpl, 2);
	// CSS Specific
	$s = "\n";

	if ($responsive == 1) {
		$s .= '.pg-cv-box-mod-ri {'."\n";
		//$s .= '   max-height: '.$tmpl['boxsize']['height'].'px;'."\n";
		//$s .= '   max-width: '.$tmpl['boxsize']['width'].'px;"'."\n";
		$s .= '}'."\n";


		$s .= '.pg-cv-box-img-mod-ri {'."\n";
		$s .= '   max-height: '.$tmpl['imageheight'].'px;'."\n";
		$s .= '   max-width: '.$tmpl['imagewidth'].'px;"'."\n";
		$s .= '}'."\n";

	} else {
		$s .= '.pg-cv-box-mod-ri {'."\n";
		$s .= '   height: '.$tmpl['boxsize']['height'].'px;'."\n";
		$s .= '   width: '.$tmpl['boxsize']['width'].'px;"'."\n";
		$s .= '}'."\n";


		$s .= '.pg-cv-box-img-mod-ri {'."\n";
		$s .= '   height: '.$tmpl['imageheight'].'px;'."\n";
		$s .= '   width: '.$tmpl['imagewidth'].'px;"'."\n";
		$s .= '}'."\n";
	}



	$document->addCustomTag('<style type="text/css">'.$s.'</style>');




	$i = 0;
	foreach($imagesArray as $valueImages){
		$output[$i] = '';
		// Path
		// Get file thumbnail or No Image
			if ($valueImages->extm != '') {

				if ($valueImages->extw != '') {
					$extw 				= explode(',',$valueImages->extw);
					if($module_type == 1) {
						$valueImages->extw	= $extw[2];//small
					} else {
						$valueImages->extw	= $extw[1];//medium
					}

				}
				if ($valueImages->exth != '') {
					$exth 				= explode(',',$valueImages->exth);
					if($module_type == 1) {
						$valueImages->exth	= $exth[2];//small
					} else {
						$valueImages->exth	= $exth[1];//medium
					}
				}
				$valueImages->extpic	= 1;
				$valueImages->linkthumbnailpathabs	= $valueImages->extm;
			} else {

				$valueImages->linkthumbnailpath  	= PhocaGalleryImageFront::displayCategoryImageOrNoImage($valueImages->filename,$imgCatSize);
				$file_thumbnail 					= PhocaGalleryFileThumbnail::getThumbnailName($valueImages->filename, $imgCatSize);
				$valueImages->linkthumbnailpathabs	= $file_thumbnail->abs;
			}


		// Different links for different actions: image, zoom icon, download icon
		$thumbLink	= PhocaGalleryFileThumbnail::getThumbnailName($valueImages->filename, 'large');
		$thumbLinkM	= PhocaGalleryFileThumbnail::getThumbnailName($valueImages->filename, 'medium');

		// ROUTE
		if ($tmpl['detail_window'] == 7) {
			$suffix	= 'detail='.$tmpl['detail_window'].'&buttons='.$detail_buttons;
		} else {
			$suffix	= 'tmpl=component&detail='.$tmpl['detail_window'].'&buttons='.$detail_buttons;
		}
		$siteLink 	= JRoute::_(PhocaGalleryRoute::getImageRoute($valueImages->id, $valueImages->catid, $valueImages->alias, $valueImages->catalias, 'detail', $suffix ));
		$siteLinkDownload  = $siteLink;

		$imgLinkOrig= JURI::base(true) . '/' .PhocaGalleryFile::getFileOriginal($valueImages->filename, 1);
		$imgLink	= $thumbLink->rel;

		// Different Link - to all categories
		if ((int)$module_link == 2) {
			$siteLink = $imgLinkOrig = $imgLink = PhocaGalleryRoute::getCategoriesRoute();

		}
		// Different Link - to all category
		else if ((int)$module_link == 1) {
			$siteLink = $imgLinkOrig = $imgLink = PhocaGalleryRoute::getCategoryRoute($valueImages->catid, $valueImages->catalias);
		}

		if (isset($valueImages->extid) &&  $valueImages->extid != '') {
			$imgLink		= $valueImages->extl;
			$imgLinkOrig	= $valueImages->exto;
		}

		if ($tmpl['detail_window'] == 2 ) {
			$valueImages->link 		= $imgLink;
			$valueImages->link2		= $imgLink;
			$valueImages->linkother	= $siteLink;
			$valueImages->linkorig	= $imgLinkOrig;

		} else if ( $tmpl['detail_window'] == 3 ) {

			$valueImages->link 		= $imgLink;
			$valueImages->link2 	= $imgLink;
			$valueImages->linkother	= $siteLink;
			$valueImages->linkorig	= $imgLinkOrig;

		} else if ( $tmpl['detail_window'] == 5 ) {

			$valueImages->link 		= $imgLink;
			$valueImages->link2 	= $siteLink;
			$valueImages->linkother	= $siteLink;
			$valueImages->linkorig	= $imgLinkOrig;

		} else if ( $tmpl['detail_window'] == 6 ) {

			$valueImages->link 		= $imgLink;
			$valueImages->link2 	= $imgLink;
			$valueImages->linkother	= $siteLink;
			$valueImages->linkorig	= $imgLinkOrig;

			// jak data js
			switch ($tmpl['jakdescription']) {
				case 0:
					$descriptionJakJs = '';
				break;

				case 2:
					$descriptionJakJs = PhocaGalleryText::strTrimAll(addslashes( $valueImages->description));
				break;

				case 3:
					$descriptionJakJs = PhocaGalleryText::strTrimAll(addslashes($valueImages->title));
					if ($valueImages->description != '') {
						$descriptionJakJs .='<br />' .PhocaGalleryText::strTrimAll(addslashes($valueImages->description));
					}
				break;

				case 1:
				default:
					$descriptionJakJs = PhocaGalleryText::strTrimAll(addslashes($valueImages->title));
				break;
			}
			$valueImages->linknr		= $i;
			$tmpl['jakdatajs'][$i] = "{alt: '".PhocaGalleryText::strTrimAll(addslashes($valueImages->title))."',";
			if ($descriptionJakJs != '') {
				$tmpl['jakdatajs'][$i] .= "description: '".$descriptionJakJs."',";
			} else {
				$tmpl['jakdatajs'][$i] .= "description: ' ',";
			}


			if(isset($valueImages->extid) && $valueImages->extid != '') {
				$tmpl['jakdatajs'][$i] .= "small: {url: '".PhocaGalleryText::strTrimAll(addslashes($valueImages->extm))."'},"
				."big: {url: '".PhocaGalleryText::strTrimAll(addslashes($valueImages->extl))."'} }";
			} else {
				$tmpl['jakdatajs'][$i] .= "small: {url: '".htmlentities(JURI::base(true).'/'.PhocaGalleryText::strTrimAll(addslashes($thumbLinkM->rel)))."'},"
				."big: {url: '".htmlentities(JURI::base(true).'/'.PhocaGalleryText::strTrimAll(addslashes($imgLink)))."'} }";
			}
		}
		// Added Slimbox URL settings
		else if ( $tmpl['detail_window'] == 8 ) {

			$valueImages->link 		= $imgLink;
			$valueImages->link2 	= $imgLink;
			$valueImages->linkother	= $imgLink;
			$valueImages->linkorig	= $imgLinkOrig;

		// End Slimbox URL settings
		}

		else if ( $tmpl['detail_window'] == 9 ) {

			$valueImages->link 		= $siteLink;
			$valueImages->link2 		= $siteLink;
			$valueImages->linkother	= $siteLink;
			$valueImages->linkorig	= $imgLinkOrig;

		}

		else if ( $tmpl['detail_window'] == 10 ) {

			$valueImages->link 		= $imgLink;
			$valueImages->link2 		= $imgLink;
			$valueImages->linkother	= $siteLink;
			$valueImages->linkorig	= $imgLinkOrig;

		}

		else {

			$valueImages->link 		= $siteLink;
			$valueImages->link2 	= $siteLink;
			$valueImages->linkother	= $siteLink;
			$valueImages->linkorig	= $imgLinkOrig;

		}


		// Different types
		switch($module_type) {
			// Mosaic
			case 1:
				if (isset($valueImages->extid) && $valueImages->extid != '') {
						list($width, $height) = getimagesize( $valueImages->exts );
						$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($valueImages->extw, $valueImages->exth, $small_image_width, $small_image_height);
					$imageOrigWidth = $correctImageRes['width'];
					$imageOrigHeight = $correctImageRes['height'];

				} else if (JFile::exists($valueImages->linkthumbnailpathabs)) {
					list($imageOrigWidth, $imageOrigHeight) = getimagesize( $valueImages->linkthumbnailpathabs );

					if ((int)$custom_image_width > 0) {
						$imageOrigWidth = $custom_image_width;
					}
					if ((int)$custom_image_height > 0) {
						$imageOrigHeight = $custom_image_height;
					}
				}



				$output[$i] .= '<div class="mosaic" style="float:left;padding:'.(int)$padding_mosaic.'px;width:'.$imageOrigWidth.'px">' . "\n";
				$output[$i] .= '<a class="'.$button->methodname.'" title="'.$valueImages->title.'" href="'. JRoute::_($valueImages->link).'"';

				if ($tmpl['detail_window'] == 1) {
					$output[$i] .= ' onclick="'. $button->options.'"';
				} else if ($tmpl['detail_window'] == 4 || $tmpl['detail_window'] == 5) {
					$highSlideOnClick = str_replace('[phocahsfullimg]',$valueImages->linkorig, $tmpl['highslideonclick']);
					$output[$i] .= ' onclick="'. $highSlideOnClick.'"';
				} else if ($tmpl['detail_window'] == 6 ) {
					$output[$i] .= ' onclick="gjaksMod'.$randName.'.show('.$valueImages->linknr.'); return false;"';
				} else if ($tmpl['detail_window'] == 7 ) {
					$output[$i] .= '';
				}
				//Begin Slimbox Method
				else if ($tmpl['detail_window'] == 8) {
					$output[$i] .=' rel="lightbox-'.$randName.'" ';
				//End Slimbox Method
				} else {
					$output[$i] .= ' rel="'.$button->options.'"';
				}



				$output[$i] .= ' >' . "\n";

				if (isset($valueImages->extid) && $valueImages->extid != '') {
					$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($valueImages->extw, $valueImages->exth, $small_image_width, $small_image_height);
					$output[$i] .= '<img src="'.$valueImages->exts.'" alt="'.$valueImages->title.'" width="'.$correctImageRes['width'].'" height="'.$correctImageRes['height'].'" />';
				} else {

					$output[$i] .= '<img src="'.JURI::base(true).'/'.$valueImages->linkthumbnailpath.'" alt="'.$valueImages->title.'" width="'.$imageOrigWidth.'" height="'.$imageOrigHeight.'" />';
				}
				$output[$i] .= '</a>';
				if ( $tmpl['detail_window'] == 5) {
					if ($tmpl['highslidedescription'] == 1 || $tmpl['highslidedescription'] == 3) {
						$output[$i]	.='<div class="highslide-heading">';
						$output[$i]	.=$valueImages->title;
						$output[$i]	.='</div>';
					}
					if  ($tmpl['highslidedescription'] == 2 || $tmpl['highslidedescription'] == 3) {
						$output[$i]	.='<div class="highslide-caption">';
						$output[$i]	.= $valueImages->description;
						$output[$i]	.= '</div>';
					}
				}
				$output[$i] .= '</div>';
				$i++;
			break;

			case 0:
			default:
				$imageWidth['size']		= (int)$tmpl['imagewidth']; //100;
				$imageHeight['size']	= (int)$tmpl['imageheight'];
				$imageHeight['boxsize'] = (int)$tmpl['imageheight'];
				$imageWidth['boxsize'] 	= (int)$tmpl['imagewidth'] + 20;//120;

				$imageOrigHeight		= (int)$tmpl['imageheight'];
				$imageOrigWidth			= (int)$tmpl['imagewidth'];//100;




				if (JFile::exists($valueImages->linkthumbnailpathabs)) {
					list($width, $height) = GetImageSize( $valueImages->linkthumbnailpathabs );

					$imageHeight 	= PhocaGalleryImage::correctSize($height, $imageHeight['size'], $imageHeight['boxsize'], 0);
					$imageWidth 	= PhocaGalleryImage::correctSize($width, $imageWidth['size'], $imageWidth['boxsize'], 20);
					$imageOrigHeight		= $height;
					$imageOrigWidth			= $width;
				}

				if ((int)$minimum_box_width > 0) {
					$imageWidth['boxsize'] = $minimum_box_width;
				}



				$output[$i] .= '<div class="pg-cv-box-mod-ri item">' . "\n";
				$output[$i] .= ''  . "\n";
				$output[$i] .= '<div class="pg-cv-box-img-mod-ri pg-box1" >'. "\n"
					.'<div class="pg-box2">' . "\n"
					.'<div class="pg-box3">' . "\n"
					.'' . "\n"
					.'<a class="'.$button->methodname.'" title="'.$valueImages->title.'" href="'. JRoute::_($valueImages->link).'"';

				if ($tmpl['detail_window'] == 1) {
					$output[$i] .= ' onclick="'. $button->options.'"';
				} else if ($tmpl['detail_window'] == 4 || $tmpl['detail_window'] == 5) {
					$highSlideOnClick = str_replace('[phocahsfullimg]',$valueImages->linkorig, $tmpl['highslideonclick']);
					$output[$i] .= ' onclick="'. $highSlideOnClick.'"';
				} else if ($tmpl['detail_window'] == 6 ) {
					$output[$i] .= ' onclick="gjaksMod'.$randName.'.show('.$valueImages->linknr.'); return false;"';
				} else if ($tmpl['detail_window'] == 7 ) {
					$output[$i] .= '';
				}
				//Begin Slimbox Method
				else if ($tmpl['detail_window'] == 8) {
					$output[$i] .=' rel="lightbox-'.$randName.'" ';
				//End Slimbox Method
				} else {
					$output[$i] .= ' rel="'.$button->options.'"';
				}


				$output[$i] .= ' >' . "\n";

				if (isset($valueImages->extid) && $valueImages->extid != '') {
					$correctImageRes = PhocaGalleryPicasa::correctSizeWithRate($valueImages->extw, $valueImages->exth, $tmpl['imagewidth'], $tmpl['imageheight']);

					if ((int)$custom_image_width > 0) {
						$correctImageRes['width'] = $custom_image_width;
					}
				/*	if ((int)$custom_image_height > 0) {
						$correctImageRes['height'] = $custom_image_height;
					}*/

					$style = 'style="max-width:'.$correctImageRes['width'].'px;max-height:'.$correctImageRes['height'].'px;"';

					$output[$i] .= '<img src="'.$valueImages->extm.'" alt="'.$valueImages->title.'" '.$style.'  class="img img-responsive" />';
				} else {
					$style = 'style="max-width:'.$imageOrigWidth.'px;max-height:'.$imageOrigHeight.'px;"';

					$output[$i] .= '<img src="'.JURI::base(true).'/'.$valueImages->linkthumbnailpath.'" alt="'.$valueImages->title.'" '.$style.' class="img img-responsive" />';
				}
				$output[$i] .= '</a>';

				if ( $tmpl['detail_window'] == 5) {
					if ($tmpl['highslidedescription'] == 1 || $tmpl['highslidedescription'] == 3) {
						$output[$i]	.='<div class="highslide-heading">';
						$output[$i]	.=$valueImages->title;
						$output[$i]	.='</div>';
					}
					if  ($tmpl['highslidedescription'] == 2 || $tmpl['highslidedescription'] == 3) {
						$output[$i]	.='<div class="highslide-caption">';
						$output[$i]	.= $valueImages->description;
						$output[$i]	.= '</div>';
					}
				}

				$output[$i]	.='' . "\n"
					 .'</div>' . "\n"
					 .'</div>' . "\n"
					 .'</div>' . "\n"
					 .'' . "\n";

				// Name
				if ($tmpl['display_name'] == 1) {
					$output[$i] .= '<div class="phocaname" style="text-align:center;color: '.$font_color.' ;font-size:'.$font_size_name.'px;">'.PhocaGalleryText::wordDelete($valueImages->title, $char_length_name, '...').'</div>';
				}

				// Icons
				if ($tmpl['display_icon_detail'] == 1 || $tmpl['display_icon_download'] == 1 || $tmpl['display_icon_download'] == 2) {

					$output[$i] .= '<div class="detail" style="text-align:right;margin:0;padding:0">';

					// Icon Detail
					if ($tmpl['display_icon_detail'] == 1) {
						$output[$i] .= '<a class="'.$button2->methodname.'" title="'. JText::_('MOD_PHOCAGALLERY_IMAGE_IMAGE_DETAIL').'" href="'.JRoute::_($valueImages->link2).'"';

						if ($tmpl['detail_window'] == 1) {
							$output[$i] .= ' onclick="'. $button2->options.'"';
						} else if ($tmpl['detail_window'] == 2) {
							$output[$i] .= ' rel="'. $button2->options.'"';
						} else if ($tmpl['detail_window'] == 4 ) {
							$output[$i] .= ' onclick="'. $tmpl['highslideonclick'].'"';
						} else if ($tmpl['detail_window'] == 5 ) {
							$output[$i] .= ' onclick="'. $tmpl['highslideonclick2'].'"';
						} else if ($tmpl['detail_window'] == 6) {
							$output[$i] .=  ' onclick="gjaksMod'.$randName.'.show('.$valueImages->linknr.'); return false;"';
						} else if ($tmpl['detail_window'] == 7 ) {
							$output[$i] .= '';
						} else {
							$output[$i] .= ' rel="'. $button2->options.'"';
						}
						$output[$i] .= ' >';
						//$output[$i] .= JHTML::_('image', 'media/com_phocagallery/images/icon-view.png', JText::_('MOD_PHOCAGALLERY_IMAGE_VIEW');
						$output[$i] .= PhocaGalleryRenderFront::renderIcon('view', 'media/com_phocagallery/images/icon-view.png', JText::_('MOD_PHOCAGALLERY_IMAGE_VIEW'));
						$output[$i] .= '</a>';
					}

					// Icon Download
					if ($tmpl['display_icon_download'] > 0) {


					// Direct Download but not if there is a youtube
					if ((int)$tmpl['display_icon_download'] == 2) {
						$output[$i] .= ' <a title="'. JText::_('COM_PHOCAGALLERY_IMAGE_DOWNLOAD').'"'
							.' href="'.JRoute::_($siteLinkDownload . '&phocadownload='.(int)$tmpl['display_icon_download'] ).'"';

					}  else {

							$output[$i] .= ' <a class="'. $buttonOther->methodname.'" title="'. JText::_('MOD_PHOCAGALLERY_IMAGE_IMAGE_DOWNLOAD').'" href="'. JRoute::_($siteLinkDownload . '&phocadownload='.(int)$tmpl['display_icon_download']).'"';

							if ($tmpl['detail_window'] == 1) {
								$output[$i] .= ' onclick="'. $buttonOther->options.'"';
							} else if ($tmpl['detail_window'] == 4 ) {
								$output[$i] .= ' onclick="'. $tmpl['highslideonclick'].'"';
							} else if ($tmpl['detail_window'] == 5 ) {
								$output[$i] .= ' onclick="'. $tmpl['highslideonclick2'].'"';
							} else if ($tmpl['detail_window'] == 7 ) {
								$output[$i] .= '';
							} else {
								$output[$i] .= ' rel="'. $buttonOther->options.'"';
							}
						}
						$output[$i] .= ' >';
						//$output[$i] .= JHTML::_('image', 'media/com_phocagallery/images/icon-download.png', JText::_('MOD_PHOCAGALLERY_IMAGE_IMAGE_DOWNLOAD'));
						$output[$i] .= PhocaGalleryRenderFront::renderIcon('download', 'media/com_phocagallery/images/icon-download.png', JText::_('MOD_PHOCAGALLERY_IMAGE_IMAGE_DOWNLOAD'));
						$output[$i] .= '</a>';
					}

					$output[$i] .= '</div>';// End detail

				}
				$output[$i] .= '</div>';

				$i++;
			break;
		}


	}


	// ADD JAK DATA CSS style
		if ( $tmpl['detail_window'] == 6 ) {
			$document->addCustomTag('<script type="text/javascript">'
			. 'var dataJakJsMod'.$randName.' = ['
			. implode($tmpl['jakdatajs'], ',')
			. ']'
			. '</script>');
		}

} else {
	$i = 0;
	$output[$i] = ''; // there is no image to get it as random image
}

require(JModuleHelper::getLayoutPath('mod_phocagallery_image', $alternative_layout));
?>
