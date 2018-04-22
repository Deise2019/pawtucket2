<?php
/** ---------------------------------------------------------------------
 * themes/default/Listings/listing_html : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * @package CollectiveAccess
 * @subpackage Core
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License version 3
 *
 * ----------------------------------------------------------------------
 */
 
 	$va_lists = $this->getVar('lists');
 	$va_type_info = $this->getVar('typeInfo');
 	$va_listing_info = $this->getVar('listingInfo');
 	$va_access_values =		caGetUserAccessValues($this->request);
 	
 
	foreach($va_lists as $vn_type_id => $qr_list) {
		if(!$qr_list) { continue; }
		
		print "<h4>{$va_listing_info['displayName']}</h4>\n";
 		print "<p style='clear:both;'>The Storm King Art Center Oral History Program includes interviews with artists and institutional leaders integral to the evolution of Storm King since its founding in 1960. Artist oral histories focus on the role of Storm King in the career of the artist, as well as on the artists’ work located at Storm King. Oral histories with institutional leaders focus on efforts to shape Storm King with regard to movements in contemporary art, arts education, and sustainability. Short videos created from each oral history offer an entry point to the full interview transcripts, and are presented here alongside some of the physical items from Storm King’s Archives that illustrate the development of artworks, exhibitions, and programming. </p>";
		
		while($qr_list->nextHit()) {
			if ( $vn_i == 0) { print "<div class='row'>"; } 
			$vn_object_id = $qr_list->get('ca_objects.object_id');
			$t_object = new ca_objects($vn_object_id);
			$vs_icons = "";
			if ($t_object->get('ca_objects.vimeo_id')) {
				$vs_icons.= " <i class='fa fa-film'></i>";
			}
			if ($t_object->representationsWithMimeType(array('application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',' application/vnd.ms-excel', 'application/pdf'), array('versions' => array('original'), 'checkAccess' => $va_access_values, 'return_with_access' => $va_access_values))) {
				$vs_icons.= " <i class='fa fa-file'></i>";
			}
			print "<div class='col-sm-6'><div class='collectionTile'>";
			if ($qr_list->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values))) {
				print "<div class='colImage'><div class='text-center bResultItemImg'>".caDetailLink($this->request, $qr_list->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values)), '', 'ca_objects', $qr_list->get('ca_objects.object_id'))."</div></div>";
			} else {
				print "<div class='relImg'><div class='text-center bResultItemImg'><div class='bSimplePlaceholder'>".caDetailLink($this->request, caGetThemeGraphic($this->request, 'spacer.png'), '', 'ca_objects', $qr_list->get('ca_objects.object_id'))."</div></div></div>";
			}			
			print "<div class='title'>".caNavLink($this->request, $qr_list->get("ca_objects.preferred_labels"), "", "Detail", "oralhistory",  $qr_list->get("ca_objects.object_id"))." ".$vs_icons."</div>";	
			print "<div class='collectionDetail'>".$qr_list->get("ca_objects.description")."</div>";

			print "</div></div>";
			$vn_i++;
			if ($vn_i == 2) {
				print "</div><!-- end row -->\n";
				$vn_i = 0;
			}
		}
		if (($vn_i < 2) && ($vn_i != 0) ) {
			print "</div><!-- end row -->\n";
		}
	}
?>