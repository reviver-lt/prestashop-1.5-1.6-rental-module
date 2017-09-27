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
        return confirm('{l s='Are you sure want to delete this item?' mod='reviverrental'}');
    });
});
</script>

<form id="form" action="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createTemplate&id={$id|escape:'htmlall':'UTF-8'}" method="post">
<fieldset><legend>{l s='Actions with rental templates' mod='reviverrental'}</legend>

{if $id > 0}

	<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Name' mod='reviverrental'}:</span> <input id="name" name="name" type="text" size="30" value="{$name_tmp|escape:'htmlall':'UTF-8'}"/>
	<input id="edit_id" name="edit_id" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
	<input type="submit" class="button" id="createNewTemplate" name="createNewTemplate" value="{l s='Create new' mod='reviverrental'}"/>
	<input type="submit" class="button" id="cancel" name="cancel" value="{l s='Back' mod='reviverrental'}"/>
	<input type="submit" class="button" id="editTemplate" name="editTemplate" value="{l s='Save' mod='reviverrental'}"/><br /><br />
	<span style="display: inline-block; width:170px; margin-bottom: 10px;">{l s='Include first day to rent' mod='reviverrental'}:</span> 
		<select name="include_same_day">
			<option {if $include_same_day == 0} selected {/if} value="0">{l s='No' mod='reviverrental'}</option>
			<option {if $include_same_day == 1} selected {/if} value="1">{l s='Yes' mod='reviverrental'}</option>
		</select>
	<br>
	<span style="display: inline-block; width:170px; margin-bottom: 10px;">{l s='Discount method' mod='reviverrental'}:</span> 
		<select name="discount_method">
			<option {if $discount_method == 0} selected {/if} value="0">{l s='Global product' mod='reviverrental'}</option>
			<option {if $discount_method == 1} selected {/if} value="1">{l s='Individual' mod='reviverrental'}</option>
		</select>
	<br>
	<br><b>{l s='Template fields' mod='reviverrental'}</b><br><hr style="width:731px; margin: 0; margin-bottom: 10px;">
	
		{if $editFld > 0}
			
			{l s='Field' mod='reviverrental'}: 
				<select name="field_id" id="field_id"> 
                    {if $fields != ""}
					{foreach from=$fields item=field}			
						<option {if $field.id_field == $id_field_find} selected {/if} value='{$field.id_field|escape:'htmlall':'UTF-8'}'>{$field.name|escape:'htmlall':'UTF-8'}</option>"
					{/foreach}
                    {else}
                        <option>{l s='Create fields first' mod='reviverrental'}</option>
                    {/if}
				</select>
                {l s='Position' mod='reviverrental'}: <input id="position_sel" name="position_sel" type="text" size="5" value="{$position_fld|escape:'htmlall':'UTF-8'}"/>
                <input id="template_id" name="template_id" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
                <input id="edit_id" name="edit_id" type="hidden" value="{$editFld|escape:'htmlall':'UTF-8'}"/>
                <input type="submit" class="button" id="editFieldAssign" name="editFieldAssign" value="{l s='Update field' mod='reviverrental'}"/>
		{else}
			{l s='Field' mod='reviverrental'}: 
				<select name="field_id_sel" id="field_id_sel">
					{if $fields != ""}
                    {foreach from=$fields item=field}			
						<option value='{$field.id_field|escape:'htmlall':'UTF-8'}'>{$field.name|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
                    {else}
                        <option>{l s='Create fields first' mod='reviverrental'}</option>
                    {/if}
			    </select>
                {l s='Position' mod='reviverrental'}: <input id="position_sel" name="position_sel" type="text" size="5" value="{$maxpos_fld|escape:'htmlall':'UTF-8'}"/> 
                <input id="id_template" name="id_template" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
                <input type="submit" class="button" id="createFieldAssign" name="createFieldAssign" value="{l s='Add field' mod='reviverrental'}"/>
		{/if}
		
		<br /><br /><table class="table">
			<thead>
				<tr>
					<th style="width:20px;">{l s='ID' mod='reviverrental'}</th>
					<th style="width:200px;">{l s='Field' mod='reviverrental'}</th>
					<th style="width:100px;">{l s='Position' mod='reviverrental'}</th>
					<th style="width:30px;">{l s='Actions' mod='reviverrental'}</th>
				</tr>
			</thead>
		<tbody>
		
			{if $found_tmp > 0}
				{foreach from=$tmp_fields item=tmp_field}
					<tr>
						<td>{$tmp_field.id_template_field_assigned|escape:'htmlall':'UTF-8'}</td>
						<td>{$tmp_field.fieldname|escape:'htmlall':'UTF-8'}</td>
						<td>{$tmp_field.order|escape:'htmlall':'UTF-8'}</td>
						<td><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createTemplate&id={$id|escape:'htmlall':'UTF-8'}&editFld={$tmp_field.id_template_field_assigned|escape:'htmlall':'UTF-8'}"><img src="../img/admin/edit.gif" alt="{l s='Edit' mod='reviverrental'}" /></a> <a class="del_button" href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createTemplate&id={$id|escape:'htmlall':'UTF-8'}&deleteFieldAssign={$tmp_field.id_template_field_assigned|escape:'htmlall':'UTF-8'}"><img src="../img/admin/delete.gif" alt="{l s='Delete' mod='reviverrental'}" /></a></td>
					<tr>
				{/foreach}
			{else}
			
			<tr><td colspan="4" style="text-align:center;">{l s='No items found.' mod='reviverrental'}</td></tr>
			
			{/if}
			
			</tbody></table></p><br>

			<br><b>{l s='Template additional products / services' mod='reviverrental'}</b><br><hr style="width:731px; margin: 0; margin-bottom: 10px;">
			
				{if $editPrd > 0}
					{l s='Product / service' mod='reviverrental'}: 
					<select name="prod_id_sel" id="prod_id_sel">
						{if $tmp_fields2 > 0}
                        {foreach from=$tmp_fields2 item=tmp_field2}
							<option {if $tmp_field2.id_template_product == $id_field_check2} selected {/if} value={$tmp_field2.id_template_product|escape:'htmlall':'UTF-8'}>{$tmp_field2.name|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
                        {else}
                            <option>{l s='Create products / services first' mod='reviverrental'}</option>
                        {/if}
					</select>
					
                    {l s='Position' mod='reviverrental'}: <input id="prod_position_sel" name="prod_position_sel" type="text" size="5" value="{$position_tmp|escape:'htmlall':'UTF-8'}"/>
                    <input id="template_id" name="template_id" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
                    <input id="edit_id" name="edit_id" type="hidden" value="{$editPrd|escape:'htmlall':'UTF-8'}"/>
                    <input type="submit" class="button" id="editProductAssign" name="editProductAssign" value="{l s='Update product / service' mod='reviverrental'}"/>
				{else}
				    {l s='Product / service' mod='reviverrental'}: 
						<select name="prod_id_sel" id="prod_id_sel">
							{if $tmp_fields2 > 0}
                            {foreach from=$tmp_fields2 item=tmp_field2}
								<option value={$tmp_field2.id_template_product|escape:'htmlall':'UTF-8'}>{$tmp_field2.name|escape:'htmlall':'UTF-8'}</option>
							{/foreach}
                            {else}
                                <option>{l s='Create products / services first' mod='reviverrental'}</option>
                            {/if}
						</select>
                    {l s='Position' mod='reviverrental'}: <input id="prod_position_sel" name="prod_position_sel" type="text" size="5" value="{$maxpos_tmp|escape:'htmlall':'UTF-8'}"/>
                    <input id="id_template" name="id_template" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
                    <input type="submit" class="button" id="createProductAssign" name="createProductAssign" value="{l s='Add product / service' mod='reviverrental'}"/>
				{/if}
				
		<br /><br /><table class="table"><thead>
						<tr>
							<th style="width:20px;">{l s='ID' mod='reviverrental'}</th>
							<th style="width:200px;">{l s='Product / service' mod='reviverrental'}</th>
							<th style="width:100px;">{l s='Position' mod='reviverrental'}</th>
							<th style="width:30px;">{l s='Actions' mod='reviverrental'}</th>
						</tr>
		</thead><tbody>
								
				{if $found_tmp2 > 0}
					{foreach from=$tmp_prods item=tmp_prod}  
						<tr>
							<td>{$tmp_prod.id_template_product_assigned|escape:'htmlall':'UTF-8'}</td>
							<td>{$tmp_prod.fieldname|escape:'htmlall':'UTF-8'}</td>
							<td>{$tmp_prod.order|escape:'htmlall':'UTF-8'}</td>
							<td><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createTemplate&id={$id|escape:'htmlall':'UTF-8'}&editPrd={$tmp_prod.id_template_product_assigned|escape:'htmlall':'UTF-8'}"><img src="../img/admin/edit.gif" alt="{l s='Edit' mod='reviverrental'}" /></a> <a class="del_button" href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createTemplate&id={$id|escape:'htmlall':'UTF-8'}&deleteProductAssign={$tmp_prod.id_template_product_assigned|escape:'htmlall':'UTF-8'}"><img src="../img/admin/delete.gif" alt="{l s='Delete' mod='reviverrental'}" /></a></td>
						<tr>
					{/foreach}
				{else}
					<tr><td colspan="4" style="text-align:center;">{l s='No items found.' mod='reviverrental'}</td></tr>
				{/if}
		</tbody></table></p><br>
		
		<br><b>{l s='Order days coefficients' mod='reviverrental'}</b><br><hr style="width:731px; margin: 0; margin-bottom: 10px;">
		
		{if $editCoe > 0}
			{l s='Days from' mod='reviverrental'}: <input id="date_from_coe" name="date_from_coe" type="text" size="1" value="{$days_from|escape:'htmlall':'UTF-8'}"/>
            {l s='Days to' mod='reviverrental'}: <input id="date_to_coe" name="date_to_coe" type="text" size="1" value="{$days_to|escape:'htmlall':'UTF-8'}"/>
            {l s='Coefficient' mod='reviverrental'}: <input id="date_coe" name="date_coe" type="text" size="3" value="{$coefficient|escape:'htmlall':'UTF-8'}"/>
            <input id="template_id" name="template_id" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
            <input id="edit_id" name="edit_id" type="hidden" value="{$editCoe|escape:'htmlall':'UTF-8'}"/>
            <input type="submit" class="button" id="editCoe" name="editCoe" value="{l s='Update coefficient' mod='reviverrental'}"/>
		{else}
			{l s='Days from' mod='reviverrental'}: <input id="date_from_coe" name="date_from_coe" type="text" size="1"/>
            {l s='Days to' mod='reviverrental'}: <input id="date_to_coe" name="date_to_coe" type="text" size="1"/>
            {l s='Coefficient' mod='reviverrental'}: <input id="date_coe" name="date_coe" type="text" size="3"/>
            <input id="id_template" name="id_template" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
            <input type="submit" class="button" id="createCoe" name="createCoe" value="{l s='Add coefficient' mod='reviverrental'}"/>
		{/if}
		
		<br /><br />
			<table class="table">
				<thead>
					<tr>
						<th style="width:100px;">{l s='Days from' mod='reviverrental'}</th>
						<th style="width:100px;">{l s='Days to' mod='reviverrental'}</th>
						<th style="width:120px;">{l s='Coefficient' mod='reviverrental'}</th>
						<th style="width:30px;">{l s='Actions' mod='reviverrental'}</th>
					</tr>
				</thead>
			<tbody>

			{if $found > 0}
				{foreach from=$tmp_prods_list item=tmp_prod_list}
			        <tr>
						<td>{$tmp_prod_list.days_from|escape:'htmlall':'UTF-8'}</td>
						<td>{$tmp_prod_list.days_to|escape:'htmlall':'UTF-8'}</td>
						<td>{$tmp_prod_list.coefficient|escape:'htmlall':'UTF-8'}</td>
						<td><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createTemplate&id={$id|escape:'htmlall':'UTF-8'}&editCoeId={$tmp_prod_list.id_template_coefficient|escape:'htmlall':'UTF-8'}"><img src="../img/admin/edit.gif" alt="{l s='Edit' mod='reviverrental'}" /></a> <a class="del_button" href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createTemplate&id={$id|escape:'htmlall':'UTF-8'}&deleteCoe={$tmp_prod_list.id_template_coefficient|escape:'htmlall':'UTF-8'}"><img src="../img/admin/delete.gif" alt="{l s='Delete' mod='reviverrental'}" /></a></td>
					<tr>
				{/foreach}
			{else}
				<tr><td colspan="4" style="text-align:center;">{l s='No items found.' mod='reviverrental'}</td></tr>
			{/if}
			    </tbody></table></p><br>
{else}
	<span style="display: inline-block; width:85px; margin-bottom: 10px;">{l s='Name' mod='reviverrental'}:</span> <input id="name" name="name" type="text" size="30" value=""/>
	<input id="edit_id" name="edit_id" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
	<input type="submit" class="button" id="cancel" name="cancel" value="{l s='Back' mod='reviverrental'}"/>
	<input type="submit" class="button" id="createTemplate" name="createTemplate" value="{l s='Save' mod='reviverrental'}"/><br /><br />
	{l s='Note: You may add fields and set additional products only after saving template name.' mod='reviverrental'}
{/if}

</fieldset>
</form><br /><br />