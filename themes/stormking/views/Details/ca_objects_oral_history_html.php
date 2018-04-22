<?php
/* ----------------------------------------------------------------------
 * themes/default/views/bundles/ca_objects_default_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2013-2015 Whirl-i-Gig
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
 * ----------------------------------------------------------------------
 */
 
	$t_object = 			$this->getVar("item");
	$va_comments = 			$this->getVar("comments");
	$va_tags = 				$this->getVar("tags_array");
	$vn_comments_enabled = 	$this->getVar("commentsEnabled");
	$vn_share_enabled = 	$this->getVar("shareEnabled");
	$vn_pdf_enabled = 		$this->getVar("pdfEnabled");
	$vn_id =				$t_object->get('ca_objects.object_id');
	$va_access_values =		caGetUserAccessValues($this->request);
?>
<div class="row">
	<div class='col-xs-12 '>
		<div class="container"><div class="row">
			<div class='col-sm-12'>
				<div class='detailNav'>{{{previousLink}}}{{{resultsLink}}}{{{nextLink}}}</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 ">
				<H4>{{{ca_objects.preferred_labels.name}}}</H4>
				<hr style='padding-bottom:5px;'>
			</div>	
				
		</div>
				
		<div class="row">	
			<div class='col-sm-12' style="text-align:center;">
<?php
				if ($vn_vimeo_id = $t_object->get('ca_objects.vimeo_id')) {			
					print '<iframe src="https://player.vimeo.com/video/'.$vn_vimeo_id.'?color=ffffff&title=0&byline=0&portrait=0" width="100%" height="460" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
				} else if ($va_oh_images = $t_object->representationsWithMimeType(array('image/jpeg', 'image/tiff', 'image/png', 'image/x-dcraw', 'image/x-psd', 'image/x-dpx', 'image/jp2', 'image/x-adobe-dng'), array('versions' => array('large'), 'return_with_access' => $va_access_values))) {
					foreach ($va_oh_images as $va_key => $va_oh_image) {
						print $va_oh_image['tags']['large'];
						break;
					}
				}
				if ($vs_caption = $t_object->get('ca_objects.caption')) {
					print "<div class='small ohCaption'>".$vs_caption."</div>";
				}	
?>			
				<hr>
			</div><!-- end col -->
			
		</div>	
		
		<div class="row">					
			<div class="col-sm-6 ">
<?php

?>
				{{{<ifdef code="ca_objects.description">
						<span >^ca_objects.description</span>
					</div>
				</ifdef>}}}
	

			</div>
			<div class="col-sm-5 col-sm-offset-1">		
<?php
/*
				if ($va_entity_rels = $t_object->get('ca_objects_x_entities.relation_id', array('returnAsArray' => true, 'excludeRelationshipTypes' => array('publisher')))) {
					$va_entities_by_type = array();
					foreach ($va_entity_rels as $va_key => $va_entity_rel) {
						$t_rel = new ca_objects_x_entities($va_entity_rel);
						if ($t_rel->get('ca_objects.access') != 0){ continue;}
						$vn_type_id = $t_rel->get('ca_relationship_types.preferred_labels');
						$va_entities_by_type[$vn_type_id][] = caDetailLink($this->request, $t_rel->get('ca_entities.preferred_labels'), '', 'ca_entities', $t_rel->get('ca_entities.entity_id'));
					}
					print "<div class='unit'>";
					foreach ($va_entities_by_type as $va_type => $va_entity_id) {
						print "<h6>".$va_type."</h6>";
						foreach ($va_entity_id as $va_key => $va_entity_link) {
							print "<div>".$va_entity_link."</div>";
						} 
					}
					print "</div>";
				}
*/
				if ($vs_interviewee = $t_object->get('ca_entities.preferred_labels', array('restrictToRelationshipTypes' => array('interviewee'), 'delimiter' => ', ', 'returnAsLink' => true, 'checkAccess' => $va_access_values))) {
					print "<div class='unit'><h6>Interview with</h6>".$vs_interviewee."</div>";
				}
				if ($vs_interviewer = $t_object->get('ca_entities.preferred_labels', array('restrictToRelationshipTypes' => array('interviewer'), 'delimiter' => ', ', 'returnAsLink' => true, 'checkAccess' => $va_access_values))) {
					print "<div class='unit'><h6>Conducted by</h6>".$vs_interviewer."</div>";
				}								
				if ($va_date = $t_object->get('ca_objects.object_date')) {
					print "<div class='unit'><h6>Interview Date</h6>".$va_date."</div>";
				}
				#if ($va_reps = $t_object->representationsWithMimeType(array('application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',' application/vnd.ms-excel', 'application/pdf'), array('return_with_access' => $va_access_values))) {
				#	foreach ($va_reps as $va_rep_num => $va_rep) {
				#	print "<h6><a href='#' onclick='caMediaPanel.showPanel(\"".caNavUrl($this->request, '', 'Detail', 'GetMediaOverlay/context/objects', array('id' => $vn_id, 'representation_id' => $va_rep['representation_id'], 'overlay' => 1))."\"); return false;'><span class='glyphicon glyphicon-file'></span>Interview Transcript</a></h6>";
				#	}
				#}
				if ($va_reps_transcript = $t_object->representationsWithMimeType(array('application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',' application/vnd.ms-excel', 'application/pdf'), array('versions' => array('original'), 'return_with_access' => $va_access_values))) {
					foreach ($va_reps_transcript as $va_rep_num => $va_rep) {
						print "<h6><span class='glyphicon glyphicon-file'></span><a href='".$va_rep['urls']['original']."'>Interview Transcript</a></h6>";
					}
				}	
				if ($vs_ext_link = $t_object->getWithTemplate('<ifcount min="1" code="ca_objects.external_link.url_entry"><unit relativeTo="ca_objects.external_link"><ifdef code="ca_objects.external_link.url_entry"><div class="unit zoomIcon"><h6><i class="fa fa-external-link-square"></i> <a href="^ca_objects.external_link.url_entry">^ca_objects.external_link.url_source</a></h6></div></ifdef></unit></ifcount>')) {
					print $vs_ext_link;
				}	
					
?>	
			</div><!-- end col -->	
		</div><!-- end row -->
				
<?php				
			if ($va_related_artworks = $t_object->get('ca_objects.related.object_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values, 'restrictToTypes' => array('loaned_artwork', 'sk_artwork'), 'sort' => 'ca_object_labels.name'))) {
				print "<hr>";
				print '<div class="row objInfo">';
				

				print '	<div class="col-sm-12"><h6 class="header">Artworks Discussed</h6></div>';
				foreach ($va_related_artworks as $va_id => $va_related_artwork_id) {
					$t_rel_obj = new ca_objects($va_related_artwork_id);
					print "<div class='col-sm-3'>";
					print "<div class='relatedArtwork'>";
					if ($t_rel_obj->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values))) {
						print "<div class='relImg'><div class='text-center bResultItemImg'>".caDetailLink($this->request, $t_rel_obj->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values)), '', 'ca_objects', $t_rel_obj->get('ca_objects.object_id'))."</div></div>";
					} else {
						print "<div class='relImg'><div class='text-center bResultItemImg'><div class='bSimplePlaceholder'>".caGetThemeGraphic($this->request, 'spacer.png')."</div></div></div>";
					}
					print "<div class='relArtTitle'><p>".$t_rel_obj->get('ca_entities.preferred_labels', array('restrictToRelationshipTypes' => array('artist'), 'checkAccess' => $va_access_values))."</p>";
					print "<p>".caDetailLink($this->request, ( $t_rel_obj->get('ca_objects.preferred_labels') == "Untitled" ? $t_rel_obj->get('ca_objects.preferred_labels') : "<i>".$t_rel_obj->get('ca_objects.preferred_labels')."</i>"), '', 'ca_objects', $t_rel_obj->get('ca_objects.object_id'));
					if ($vs_art_date = $t_rel_obj->get('ca_objects.display_date')) {
						print ", ".$vs_art_date;
					}
					print "</p></div></div>";
					print "</div><!-- end col -->";
				}
				print "</div><!-- end row -->";			
			}
	
			# Related Exhibitions
			if ($va_related_exhibitions = $t_object->get('ca_occurrences.occurrence_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values, 'restrictToTypes' => array('exhibition', 'program'), 'sort' => 'ca_occurrences.exhibition_dates', 'sortDirection' => 'desc'))) {
				$va_ex_images = caGetDisplayImagesForAuthorityItems('ca_occurrences', $va_related_exhibitions, array('version' => 'iconlarge', 'relationshipTypes' => 'includes', 'objectTypes' => 'artwork', 'checkAccess' => $va_access_values));
				print "<hr><div class='row relatedExhibitions'>";
				print '<div class="col-sm-12"><h6 class="header">Exhibitions and programs discussed</h6></div>';
				foreach ($va_related_exhibitions as $va_key => $va_related_exhibition_id) {
					$t_exhibition = new ca_occurrences($va_related_exhibition_id);
					print "<div class='col-sm-12'> <div class='relatedArtwork' style='margin-bottom:20px;'>";
					print "<p>".caDetailLink($this->request, $t_exhibition->get('ca_occurrences.preferred_labels'), '', 'ca_occurrences', $t_exhibition->get('ca_occurrences.occurrence_id'))."</p>";
					print "<p>".$t_exhibition->get('ca_occurrences.exhibition_dates', array('delimiter' => '<br/>'))."</p>";
					print "</div><!-- end relArtwork --></div><!-- end col -->";
				}
				print "</div><!-- end row -->";
			}
			#Related Archival
			if ($va_related_archival = $t_object->get('ca_objects.related.object_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values, 'restrictToTypes' => array('archival'), 'restrictToRelationshipTypes' => array('related_front')))) {
				$vs_arch_count = 0;
				print "<hr><div class='row'>";
				print '<div class="col-sm-12"><h6 class="header">Related Archives</h6></div>';
				foreach ($va_related_archival as $va_key => $vn_related_archival_id) {
					if ($vs_arch_count < 4) {
						$vs_style = "noBorder";
					} else {
						$vs_style = "";
					}
					$t_archival = new ca_objects($vn_related_archival_id);
					print "<div class='col-sm-3'> <div class='relatedArtwork {$vs_style}'>";
					print "<div class='relImg bResultItemContent'><div class='text-center bResultItemImg'>".caDetailLink($this->request, $t_archival->get('ca_object_representations.media.widepreview'), '', 'ca_objects', $t_archival->get('ca_objects.object_id'))."</div></div>";
					print "<p style='height:55px;'>".caDetailLink($this->request, $t_archival->get('ca_objects.preferred_labels'), '', 'ca_objects', $t_archival->get('ca_objects.object_id'))."</p>";
					print "</div></div>";
					$vs_arch_count++;
				}
				print "</div><!-- end col --></div><!-- end row -->";
			}	
?>										
		</div><!-- end container -->
	</div><!-- end col -->
</div><!-- end row -->

<?php
		#if($this->request->isLoggedIn()){
			print "<a href='http://stormking.collectihost.com/admin/index.php/editor/objects/ObjectEditor/Edit/object_id/".$vn_id."'>Edit This Record</a>";
		#}
?>	

<script type='text/javascript'>
	jQuery(document).ready(function() {
		$('.trimText').readmore({
		  speed: 75,
		  maxHeight: 120
		});
	});
</script>