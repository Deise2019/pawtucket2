<?php
	$t_item = $this->getVar("item");
	$va_comments = $this->getVar("comments");
	$vn_comments_enabled = 	$this->getVar("commentsEnabled");
	$vn_share_enabled = 	$this->getVar("shareEnabled");	
	$va_access_values =		caGetUserAccessValues($this->request);
?>
<div class="row">
	<div class='col-xs-12 '>
		<div class="container">
			<div class="row">
				<div class='col-sm-12'>
					<div class='detailNav'>{{{previousLink}}}{{{resultsLink}}}{{{nextLink}}}</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 objectInfo">
<?php
					print "<div class='artistName'>".trim($t_item->get('ca_entities.preferred_labels'))."</div>";
					print "<div>";
					if ($vs_nationality = $t_item->get('ca_entities.nationality_text')) {
						print $vs_nationality.", ";
					}
					if ($vs_lifespan = $t_item->get('ca_entities.entity_display_date')) {
						print $vs_lifespan;
					}
					print "</div>";					
?>				
					<hr></hr> 
				</div>		
			</div><!-- end row -->
			<div class="row">			
				<div class='col-sm-6 col-md-6 col-lg-6'>
					<div >
					{{{representationViewer}}}		
					</div>	
				</div><!-- end col -->
				<div class='col-sm-6 col-md-6 col-lg-6'>
<?php
					if ($vs_bio = $t_item->get('<unit relativeTo="ca_entities.biography"><if rule="^ca_objects.biography.display_bio =~ /yes/">^ca_entities.biography.bio_text</if></unit>')) {
						print $vs_bio;
					}
					if ($vs_ext_link = $t_item->getWithTemplate('<ifcount min="1" code="ca_entities.external_link.url_entry"><unit relativeTo="ca_entities.external_link"><ifdef code="ca_entities.external_link.url_entry"><div class="unit zoomIcon"><h6><i class="fa fa-external-link-square"></i> <a href="^ca_entities.external_link.url_entry">^ca_entities.external_link.url_source</a></h6></div></ifdef></unit></ifcount>')) {
						print $vs_ext_link;
					}
/*					if ($va_remarks_images = $t_item->get('ca_entities.bibliography', array('returnWithStructure' => true, 'version' => 'medium'))) {
						foreach ($va_remarks_images as $vn_attribute_id => $va_remarks_image_info) {
							foreach ($va_remarks_image_info as $vn_value_id => $va_remarks_image) {
								print "<div class='unit' style='margin-bottom:20px;'>";

								$o_db = new Db();
								$t_element = ca_attributes::getElementInstance('bibliography');
								$vn_media_element_id = $t_element->getElementID('bibliography');							

								$qr_res = $o_db->query('SELECT value_id FROM ca_attribute_values WHERE attribute_id = ? AND element_id = ?', array($vn_value_id, $vn_media_element_id)) ;
								if ($qr_res->nextRow()) {
									print "<div class='zoomIcon'><a href='#' onclick='caMediaPanel.showPanel(\"".caNavUrl($this->request, '', 'Detail', 'GetMediaOverlay', array('id' => $t_item->get("entity_id"), 'context' => 'entities', 'identifier' => 'attribute:'.$qr_res->get("value_id"), 'overlay' => 1))."\"); return false;'><h6>View Bibliography <i class='fa fa-file'></i></h6></a></div>";
								}
								print "</div>";
							}
						}
					}
*/					
?>					
				</div><!-- end col -->
			</div><!-- end row -->
<?php
		#Related Artworks	
		if ($va_related_artworks = $t_item->get('ca_objects.object_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values, 'restrictToTypes' => array('loaned_artwork', 'sk_artwork'), 'sort' => 'ca_object_labels.name'))) {
			$vs_art_count = 0;
			print '<div class="row objInfo">';
			print '	<div class="col-sm-12"><hr><h6 class="header">Artworks</h6></div>';
			foreach ($va_related_artworks as $va_id => $va_related_artwork_id) {
				$t_rel_obj = new ca_objects($va_related_artwork_id);
				print "<div class='col-sm-3'>";
				print "<div class='relatedArtwork'>";
				if ($t_rel_obj->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values))) {
					$vs_art_image = caDetailLink($this->request, $t_rel_obj->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values)), '', 'ca_objects', $t_rel_obj->get('ca_objects.object_id'));
				} else {
					$vs_art_image = null;
				}
				print "<div class='relImg'>".($vs_art_image ? $vs_art_image : "<div class='bSimplePlaceholder'>".caGetThemeGraphic($this->request, 'spacer.png')."</div>")."</div>";
				print "<div class='relArtTitle'><p>".$t_rel_obj->get('ca_entities.preferred_labels', array('restrictToRelationshipTypes' => array('artist'), 'checkAccess' => $va_access_values))."</p>";
				print "<p>".caDetailLink($this->request, ( $t_rel_obj->get('ca_objects.preferred_labels') == "Untitled" ? $t_rel_obj->get('ca_objects.preferred_labels') : "<i>".$t_rel_obj->get('ca_objects.preferred_labels')."</i>"), '', 'ca_objects', $t_rel_obj->get('ca_objects.object_id'));
				if ($vs_art_date = $t_rel_obj->get('ca_objects.display_date')) {
					print ", ".$vs_art_date;
				}
				print "</p></div></div>";
				print "</div><!-- end col -->";
				$vs_art_count++;
				if ($vs_art_count == 4) {
					break;
				}

			}
			if ($vs_art_count == 4) {
				print "<div class='viewAll'>".caNavLink($this->request, "View all <i class='fa fa-angle-right'></i>", '', '', 'Browse', 'allworks', array('facet' => 'occurrence_facet', 'id' => $vn_item_id))."</div>";
			}
			print "</div><!-- end row -->";			
		}
		# Related Exhibitions
		if ($va_related_exhibitions = $t_item->get('ca_occurrences.occurrence_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values, 'restrictToTypes' => array('exhibition', 'program'), 'sort' => 'ca_occurrences.exhibition_dates', 'sortDirection' => 'desc'))) {
			$va_ex_images = caGetDisplayImagesForAuthorityItems('ca_occurrences', $va_related_exhibitions, array('version' => 'iconlarge', 'relationshipTypes' => 'includes', 'objectTypes' => 'artwork', 'checkAccess' => $va_access_values));
			print "<div class='row relatedExhibitions'><div class='col-sm-12'><hr><h6 class='header'>Exhibitions and Programs</h6></div></div>";
			print "<div class='row'>";
			foreach ($va_related_exhibitions as $va_key => $va_related_exhibition_id) {
				$t_exhibition = new ca_occurrences($va_related_exhibition_id);
				print "<div class='col-sm-12'> <div class='relatedArtwork' style='margin-bottom:20px;'>";
				print "<p>".caDetailLink($this->request, $t_exhibition->get('ca_occurrences.preferred_labels'), '', 'ca_occurrences', $t_exhibition->get('ca_occurrences.occurrence_id'))."</p>";
				print "<p>".$t_exhibition->get('ca_occurrences.exhibition_dates', array('delimiter' => '<br/>'))."</p>";
				print "</div></div>";
			}
			print "</div><!-- end row -->";
		}				
		#Related Archival Items
		if ($va_related_archival = $t_item->get('ca_objects.object_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values, 'restrictToTypes' => array('archival'), 'sort' => 'ca_object_labels.name'))) {
			$vs_archival_count = 0;
			print '<div class="row objInfo">';

			print '	<div class="col-sm-12"><hr><h6 class="header">Archival Items</h6></div>';
			foreach ($va_related_archival as $va_id => $va_related_archival_id) {
				$t_rel_archival = new ca_objects($va_related_archival_id);
				print "<div class='col-sm-3'>";
				print "<div class='relatedArtwork'>";
				if ($t_rel_archival->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values))) {
					$vs_archival_image = caDetailLink($this->request, $t_rel_archival->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values)), '', 'ca_objects', $t_rel_archival->get('ca_objects.object_id'));
				} else {
					$vs_archival_image = null;
				}				
				print "<div class='relImg'>".caDetailLink($this->request, ($vs_archival_image ? $vs_archival_image : "<div class='bSimplePlaceholder'>".caGetThemeGraphic($this->request, 'spacer.png')."</div>"), '', 'ca_objects', $t_rel_archival->get('ca_objects.object_id'))."</div>";
				print "<p>".$t_rel_archival->get('ca_entities.preferred_labels', array('restrictToRelationshipTypes' => array('artist'), 'checkAccess' => $va_access_values))."</p>";
				print "<p>".caDetailLink($this->request, $t_rel_archival->get('ca_objects.preferred_labels'), '', 'ca_objects', $t_rel_archival->get('ca_objects.object_id'));
				print "</p></div>";
				print "</div><!-- end col -->";
				$vs_archival_count++;
				if ($vs_archival_count == 4) {
					break;
				}				
			}
			if ($vs_archival_count == 4) {
				print "<div class='viewAll'>".caNavLink($this->request, "View all <i class='fa fa-angle-right'></i>", '', '', 'Browse', 'install', array('facet' => 'archive_item_facet', 'id' => $vn_item_id))."</div>";
			}	
			print "</div><!-- end row -->";			
		}
		#Related Oral Histories
		if ($va_related_oral = $t_item->get('ca_objects.object_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values, 'restrictToTypes' => array('oral_history'), 'sort' => 'ca_object_labels.name'))) {
			$vs_oral_count = 0;
			print '<div class="row objInfo">';

			print '	<div class="col-sm-12"><hr><h6 class="header">Oral Histories</h6></div>';
			foreach ($va_related_oral as $va_id => $va_related_oral_id) {
				$t_rel_oral = new ca_objects($va_related_oral_id);
				print "<div class='col-sm-3'>";
				print "<div class='relatedArtwork'>";
				if ($t_rel_oral->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values))) {
					$vs_oral_image = caDetailLink($this->request, $t_rel_oral->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values)), '', 'ca_objects', $t_rel_oral->get('ca_objects.object_id'));
				} else {
					$vs_oral_image = null;
				}				
				print "<div class='relImg'>".caDetailLink($this->request, ($vs_oral_image ? $vs_oral_image : "<div class='bSimplePlaceholder'>".caGetThemeGraphic($this->request, 'spacer.png')."</div>"), '', 'ca_objects', $t_rel_oral->get('ca_objects.object_id'))."</div>";
				print "<p>".$t_rel_oral->get('ca_entities.preferred_labels', array('restrictToRelationshipTypes' => array('artist'), 'checkAccess' => $va_access_values))."</p>";
				print "<p>".caDetailLink($this->request, $t_rel_oral->get('ca_objects.preferred_labels'), '', 'ca_objects', $t_rel_oral->get('ca_objects.object_id'));
				print "</p></div>";
				print "</div><!-- end col -->";
				$vs_oral_count++;
				if ($vs_oral_count == 4) {
					break;
				}				
			}
			if ($vs_oral_count == 4) {
				print "<div class='viewAll'>".caNavLink($this->request, "View all <i class='fa fa-angle-right'></i>", '', '', 'Browse', 'install', array('facet' => 'archive_item_facet', 'id' => $vn_item_id))."</div>";
			}	
			print "</div><!-- end row -->";			
		}					
/*
			#Related Artworks
			if ($va_related_artworks = $t_item->get('ca_objects.object_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values, 'restrictToTypes' => array('loaned_artwork', 'sk_artwork'), 'sort' => 'ca_object_labels.name'))) {
				$vs_art_count = 0;
				print "<hr><div class='row'>";
				print '<div class="col-sm-12"><h6 class="header">Artworks</h6></div>';
				print "</div>";
				print "<div class='row'>";
				foreach ($va_related_artworks as $va_key => $vn_related_artwork_id) {
					if ($vs_art_count < 4) {
						$vs_style = "noBorder";
					} else {
						$vs_style = "";
					}
					$t_artwork = new ca_objects($vn_related_artwork_id);
					print "<div class='col-sm-3'> <div class='relatedArtwork {$vs_style}'>";
					if ($t_artwork->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values))) {
						print "<div class='relImg'><div class='text-center bResultItemImg'>".caDetailLink($this->request, $t_artwork->get('ca_object_representations.media.widepreview', array('checkAccess' => $va_access_values)), '', 'ca_objects', $t_artwork->get('ca_objects.object_id'))."</div></div>";
					} else {
						print "<div class='relImg'><div class='text-center bResultItemImg'><div class='bSimplePlaceholder'>".caGetThemeGraphic($this->request, 'spacer.png')."</div></div></div>";
					}
					print "<div class='relArtTitle'>";
					print "<p>".caDetailLink($this->request, ($t_artwork->get('ca_objects.preferred_labels') == "Untitled" ? $t_artwork->get('ca_objects.preferred_labels') : "<i>".$t_artwork->get('ca_objects.preferred_labels')."</i>"), '', 'ca_objects', $t_artwork->get('ca_objects.object_id'));
					if ($vs_art_date = $t_artwork->get('ca_objects.display_date')) {
						print ", ".$vs_art_date;
					}
					$vs_art_count++;
					print "</p></div>";
					print "</div></div>";
				}
				print "</div><!-- end row -->";
			}
			#Related Archival
			if ($va_related_archival = $t_item->get('ca_objects.object_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values, 'restrictToTypes' => array('archival')))) {
				$vs_arch_count = 0;
				print "<hr>";
				print '<div class="row"><div class="col-sm-12"><h6 class="header">Archives</h6></div></div>';
				print "<div class='row'>";
				foreach ($va_related_archival as $va_key => $vn_related_archival_id) {
					if ($vs_arch_count < 4) {
						$vs_style = "noBorder";
					} else {
						$vs_style = "";
					}
					$t_archival = new ca_objects($vn_related_archival_id);
					print "<div class='col-sm-3'> <div class='relatedArtwork {$vs_style}'>";
					print "<div class='relImg bResultItemContent'><div class='text-center bResultItemImg'>".caDetailLink($this->request, $t_archival->get('ca_object_representations.media.widepreview'), '', 'ca_objects', $t_archival->get('ca_objects.object_id'))."</div></div>";
					print "<div class='relArtTitle'><p>".caDetailLink($this->request, $t_archival->get('ca_objects.preferred_labels'), '', 'ca_objects', $t_archival->get('ca_objects.object_id'))."</p></div>";
					print "</div></div>";
					$vs_arch_count++;
				}
				print "</div><!-- end row -->";
			}	
			#Related Oral History
			if ($va_related_oralh = $t_item->get('ca_objects.object_id', array('returnAsArray' => true, 'checkAccess' => $va_access_values, 'restrictToTypes' => array('oral_history')))) {
				$vs_oh_count = 0;
				array_unique($va_related_oralh);
				print "<hr>";
				print '<div class="row"><div class="col-sm-12"><h6 class="header">Oral History</h6></div></div>';
				print "<div class='row'>";
				foreach ($va_related_oralh as $va_key => $vn_related_oralh_id) {
					if ($vs_oh_count < 4) {
						$vs_style = "noBorder";
					} else {
						$vs_style = "";
					}
					$t_oralh = new ca_objects($vn_related_oralh_id);
					print "<div class='col-sm-3'> <div class='relatedArtwork {$vs_style}'>";
					print "<div class='relImg bResultItemContent'><div class='text-center bResultItemImg'>".caDetailLink($this->request, $t_oralh->get('ca_object_representations.media.widepreview'), '', 'ca_objects', $t_oralh->get('ca_objects.object_id'))."</div></div>";
					print "<p>".caDetailLink($this->request, $t_oralh->get('ca_objects.preferred_labels'), '', 'ca_objects', $t_oralh->get('ca_objects.object_id'))."</p>";
					print "</div></div>";
					$vs_oh_count++;
				}
				print "</div><!-- end row -->";
			}
*/												
?>			
		</div><!-- end container -->
	</div><!-- end col -->
</div><!-- end row -->
<script type='text/javascript'>
	jQuery(document).ready(function() {
		$('.trimText').readmore({
		  speed: 75,
		  maxHeight: 120
		});
	});
</script>