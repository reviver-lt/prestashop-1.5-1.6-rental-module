{*
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    Reviver <info@reviver.lt>
 *  @copyright 2015-2016 Reviver.lt
 *  @license   license.txt
*}

<script type="text/javascript">
$( document ).ready(function() {
    $('.del_button').on('click', function () {
        return confirm('{l s='Are you sure want to delete this field selection?' mod='reviverrental'}');
    });
});
</script>

<form id="form" action="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createField&id={$id|escape:'htmlall':'UTF-8'}" method="post" enctype="multipart/form-data">
<fieldset><legend>{l s='Actions with fields' mod='reviverrental'}</legend>

{if $id > 0}
	<p>
		<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Type ' mod='reviverrental'}:</span> 
			<select id="type" name="type">
				<option {if $type == "input"} selected {/if} value="input">{l s='Input field' mod='reviverrental'}</option>
				<option {if $type == "select"} selected {/if} value="select">{l s='Select field' mod='reviverrental'}</option>
				<option {if $type == "date"} selected {/if} value="date">{l s='Date field' mod='reviverrental'}</option>
			</select><br>
			
				<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Name' mod='reviverrental'}:</span> <input id="name" name="name" type="text" size="30" value="{$name|escape:'htmlall':'UTF-8'}"/><br>
				<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Title' mod='reviverrental'}:</span> <input id="title" name="title" type="text" size="30" value="{$title|escape:'htmlall':'UTF-8'}"/><br>
				<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Placeholder' mod='reviverrental'}:</span> <input id="placeholder" name="placeholder" type="text" size="30" value="{$placeholder|escape:'htmlall':'UTF-8'}"/><br>
				<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Value' mod='reviverrental'}:</span> <input id="value" name="value" type="text" size="30" value="{$value|escape:'htmlall':'UTF-8'}"/><br><br>

                <input id="edit_id" name="edit_id" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
				<input type="submit" class="button" id="createNewField" name="createNewField" value="{l s='Create new' mod='reviverrental'}"/>
				<input type="submit" class="button" id="cancel" name="cancel" value="{l s='Back' mod='reviverrental'}"/>
				<input type="submit" class="button" id="editField" name="editField" value="{l s='Save' mod='reviverrental'}"/><br /><br />

				{if $type == "select" || $type == "checkbox" || $type == "radio"}
					<br><b>{l s='Field selection options' mod='reviverrental'}</b><br><hr style="width:731px; margin: 0; margin-bottom: 10px;">
					
					{if $editSel > 0}
					
					    {l s='Name' mod='reviverrental'}: <input id="name_sel" name="name_sel" type="text" size="10" value="{$name_fld|escape:'htmlall':'UTF-8'}"/>
                        {l s='Title' mod='reviverrental'}: <input id="title_sel" name="title_sel" type="text" size="15" value="{$title_fld|escape:'htmlall':'UTF-8'}"/>
                        {l s='Value' mod='reviverrental'}: <input id="value_sel" name="value_sel" type="text" size="15" value="{$value_fld|escape:'htmlall':'UTF-8'}"/>
                        {l s='Position' mod='reviverrental'}: <input id="position_sel" name="position_sel" type="text" size="5" value="{$position_fld|escape:'htmlall':'UTF-8'}"/> 
                        <input id="id_field_sel" name="id_field_sel" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
                        <input id="edit_id" name="edit_id" type="hidden" value="{$editSel|escape:'htmlall':'UTF-8'}"/>
                        <input type="submit" class="button" id="editSelection" name="editSelection" value="{l s='Update selection' mod='reviverrental'}"/>
					
					{else}
					    
						{l s='Name' mod='reviverrental'}: <input id="name_sel" name="name_sel" type="text" size="10" value=""/>
                        {l s='Title' mod='reviverrental'}: <input id="title_sel" name="title_sel" type="text" size="15" value=""/>
                        {l s='Value' mod='reviverrental'}: <input id="value_sel" name="value_sel" type="text" size="15" value=""/>
                        {l s='Position' mod='reviverrental'}: <input id="position_sel" name="position_sel" type="text" size="5" value="{$maxpos|escape:'htmlall':'UTF-8'}"/> 
                        <input id="id_field_sel" name="id_field_sel" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
                        <input type="submit" class="button" id="createSelection" name="createSelection" value="{l s='Insert selection' mod='reviverrental'}"/>
					{/if}
					
					    <br /><br /><table class="table">
						<thead>
						<tr>
							<th style="width:20px;">{l s='ID' mod='reviverrental'}</th>
							<th style="width:150px;">{l s='Name' mod='reviverrental'}</th>
							<th style="width:174px;">{l s='Title' mod='reviverrental'}</th>
							<th style="width:174px;">{l s='Value' mod='reviverrental'}</th>
							<th style="width:100px;">{l s='Position' mod='reviverrental'}</th>
							<th style="width:30px;">{l s='Actions' mod='reviverrental'}</th>

						</tr>
						</thead>
						<tbody>
						
					{if $found}
					
						{foreach from=$fields item=field}
							<tr>
								<td>{$field.id_field_select|escape:'htmlall':'UTF-8'}</td>
								<td>{$field.name|escape:'htmlall':'UTF-8'}</td>
								<td>{$field.title|escape:'htmlall':'UTF-8'}</td>
								<td>{$field.value|escape:'htmlall':'UTF-8'}</td>
								<td>{$field.order|escape:'htmlall':'UTF-8'}</td>
								<td><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createField&id={$id|escape:'htmlall':'UTF-8'}&editSel={$field.id_field_select|escape:'htmlall':'UTF-8'}"><img src="../img/admin/edit.gif" alt="{l s='Edit' mod='reviverrental'}" /></a> <a class="del_button" href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createField&id={$id|escape:'htmlall':'UTF-8'}&deleteSelection={$field.id_field_select|escape:'htmlall':'UTF-8'}"><img src="../img/admin/delete.gif" alt="{l s='Delete' mod='reviverrental'}" /></a></td>
							<tr>
						{/foreach}	

					{else}
					
						<tr><td colspan="6" style="text-align:center;">{l s='No items found.' mod='reviverrental'}</td></tr>
					
					{/if}
				
				</tbody></table></p><br>
				
				{/if}
	</p>
{else}
	<p>
		<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Type' mod='reviverrental'}:</span> 
			<select id="type" name="type">
				<option value="input">{l s='Input field' mod='reviverrental'}</option>
				<option value="select">{l s='Select field' mod='reviverrental'}</option>
				<option value="date">{l s='Date field' mod='reviverrental'}</option>
		   </select><br>
		<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Name' mod='reviverrental'}:</span> <input id="name" name="name" type="text" size="30" value=""/><br>
		<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Title' mod='reviverrental'}:</span> <input id="title" name="title" type="text" size="30" value=""/><br>
		<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Placeholder' mod='reviverrental'}:</span> <input id="placeholder" name="placeholder" type="text" size="30" value=""/><br>
		<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Value' mod='reviverrental'}:</span> <input id="value" name="value" type="text" size="30" value=""/><br><br>

        <input type="submit" class="button" id="cancel" name="cancel" value="{l s='Back' mod='reviverrental'}"/>
		<input type="submit" class="button" id="createField" name="createField" value="{l s='Save' mod='reviverrental'}"/><br /><br />
		{l s='Note: Some additional field options available only after saving.' mod='reviverrental'}
	</p>
{/if}

</fieldset>
</form><br /><br />