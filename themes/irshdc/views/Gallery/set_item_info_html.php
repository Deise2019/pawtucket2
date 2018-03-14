<?php print "(".$this->getVar("set_item_num")."/".$this->getVar("set_num_items").")<br/>"; ?>
<div class="galleryTombstoneScroll">

	{{{<ifdef code="ca_objects.preferred_labels.name"><H4>^ca_objects.preferred_labels.name</H4></ifdef>}}}
	{{{<ifdef code="ca_objects.dc_website"><div class='unit'><a href="^ca_objects.dc_website" target="_blank">View Website <span class="glyphicon glyphicon-new-window"></span></a></div></ifdef>}}}
	
	{{{<ifdef code="ca_objects.displayDate"><div class='unit'>^ca_objects.displayDate</div></ifdef>}}}<!-- RG10 date -->
	{{{<ifdef code="ca_objects.MARC_copyrightDate"><div class='unit'>&copy; ^ca_objects.MARC_copyrightDate</div></ifdef>}}}<!-- library date -->
<!-- NEED TO MAKE SURE OTHER RECORD TYPE DATES ARE CONFIGURED -->
	
	<!-- desc for resources -->
	{{{<ifdef code="ca_objects.description"><div class='unit'>^ca_objects.description</div></ifdef>}}}


	{{{<ifdef code="ca_objects.curators_comments.comments">
		<div class="unit" data-toggle="popover" data-placement="left" data-trigger="hover" title="Source" data-content="^ca_objects.curators_comments.comment_reference"><h6>Curatorial comment</h6>
			<span class="trimText">^ca_objects.curators_comments.comments</span>
		</div>
	</ifdef>}}}
	
	
	
	{{{<case>
		<if rule='^ca_objects.type_id%convertCodesToDisplayText=1 =~ /Library/'>^ca_objects.description_new.description_new_txt</if>
		<if rule='^ca_objects.type_id%convertCodesToDisplayText=1 =~ /RG10/'>
			<ifdef code='ca_objects.record_group_id|ca_objects.file_series'>^ca_objects.record_group_id: ^ca_objects.file_series</ifdef>
		</if>
		<if rule='^ca_objects.type_id%convertCodesToDisplayText=1 =~ /Archival/'>^ca_objects.scope_new.scope_new_text</if>
		<if rule='^ca_objects.type_id%convertCodesToDisplayText=1 =~ /Museum/'>^ca_objects.curatorial_description.curatorial_desc_value</if>
		<if rule='^ca_objects.type_id%convertCodesToDisplayText=1 =~ /Statement/'>^ca_objects.description_new.description_new_txt</if>
	</case>}}}
	
	

	{{{<ifcount code="ca_entities" restrictToTypes="school" min="1"><div class="unit"><ifcount code="ca_entities" restrictToTypes="school" min="1" max="1"><h6>School</h6></ifcount><ifcount code="ca_entities" restrictToTypes="school" min="2"><h6>Schools</h6></ifcount><unit relativeTo="ca_entities" restrictToTypes="school" delimiter=", "><l>^ca_entities.preferred_labels.displayname</l></unit></div></ifcount>}}}

</div>
<?php print "<div class='unit text-center'>".caDetailLink($this->request, _t("More Information"), 'btn-default', $this->getVar("table"),  $this->getVar("row_id"))."</div>"; ?>