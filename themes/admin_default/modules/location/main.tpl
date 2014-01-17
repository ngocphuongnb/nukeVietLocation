<!-- BEGIN: main -->
<div id="page-msg"></div>
<!-- BEGIN:search -->
<form action="">
	<input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
    <table style="font-weight: bold" class="tab1">
        <colgroup>
            <col class="w150">
            <col>
            <col>
            <col>
        </colgroup>
        <tbody>
            <tr class="center">
            	<td>{LANG.search}</td>
                <td>
                	{LANG.keyword}
                	<input name="keyword" value="{S.keyword}" />
              	</td>
                <td>
                	{LANG.parent_id}
                	<select name="parent_id">
                        <!-- BEGIN: locations -->
                        <option {LOCATION.slt} value="{LOCATION.location_id}">{LOCATION.location_name}</option>
                        <!-- END: locations -->
                  	</select>
              	</td>
                <td>
                	{LANG.location_type}
                	<select name="location_type">
                        <!-- BEGIN: location_type -->
                        <option {LOCTYPE.slt} value="{LOCTYPE.locID}">{LOCTYPE.locName}</option>
                        <!-- END: location_type -->
                  	</select>
              	</td>
				<td colspan="2" class="center"><input type="submit" value="{LANG.search}"/></td>
			</tr>
			</tr>
     	</tbody>
 	</table>
</form>
<!-- END:search -->
<i class="icon-trash icon-large">&nbsp;</i> <a href="javascript:void(0);" id="remove-all">Xóa tất cả</a>
<table class="tab1">
	<colgroup>
		<col class="w50">
		<col>
		<col class="w150">
		<col class="w100">
	</colgroup>
	<thead>
		<tr class="center">
        	<td><input id="toggle-all" value="1" type="checkbox"></td>
			<td>{LANG.location_name}</td>
            <td>{LANG.parent_id}</td>
            <td>{LANG.location_type}</td>
			<td>{LANG.latitude}</td>
			<td>{LANG.longitude}</td>
			<td>{LANG.feature}</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: row -->
		<tr class="center">
        	<td><input class="item-toggle" value="{ROW.location_id}" type="checkbox" /></td>
			<td><a href="{ROW.show_child_url}">{ROW.location_name}</a></td>
            <td><a href="{ROW.parent_url}">{ROW.parent_location}</a></td>
            <td>{ROW.location_type}</td>
            <td>{ROW.latitude}</td>
			<td>{ROW.longitude}</td>
			<td class="center">
				<i class="icon-edit icon-large">&nbsp;</i> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp; 
				<i class="icon-trash icon-large">&nbsp;</i> <a href="javascript:void(0);" onclick="remove_location({ROW.location_id});">{GLANG.delete}</a>
			</td>
		</tr>
		<!-- END: row -->
	</tbody>
</table>
<!-- BEGIN: generate_page -->
{GENERATE_PAGE}
<!-- END: generate_page -->
<style type="text/css">
.msg-heading {
    color: #AD0000;
    font-weight: bold;
}
.msg-content {
	border: 3px solid #CCC;
	padding: 10px;
	margin-bottom: 10px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	var checkedInputs = new Array();
	$('#toggle-all').InputToggle({
		childInput: '.item-toggle', 
		storageVar: 'checkedInputs',
		featureAction: [
			{container: '#remove-all', callback: "removeall(checkedInputs)" }
		]
	});
})
</script>
<!-- END: main -->