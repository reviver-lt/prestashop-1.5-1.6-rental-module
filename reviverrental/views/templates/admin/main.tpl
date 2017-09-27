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

<br><fieldset><legend>{l s='Currently assigned products to the rent list' mod='reviverrental'}</legend><p>

{if $editAssignedProd > 0}

	<form id="formprd" action="" method="post">
        {l s='Position' mod='reviverrental'}: <input id="position_prd" name="position_prd" type="text" size="5" value="{$editAssignedProd_position|escape:'htmlall':'UTF-8'}"/>
        <input id="edit_id_prd" name="edit_id_prd" type="hidden" value="{$editAssignedProd|escape:'htmlall':'UTF-8'}"/>
        <input type="submit" class="button" id="cancel" name="cancel" value="{l s='Back' mod='reviverrental'}"/>
        <input type="submit" class="button" id="editFieldAssignPrd" name="editFieldAssignPrd" value="{l s='Update position' mod='reviverrental'}"/><br><br>
    </form>
	
{/if}

<table class="table">
	<thead>
		<tr>
			<th style="width:20px;">{l s='ID' mod='reviverrental'}</th>
			<th style="width:255px;">{l s='Product name' mod='reviverrental'}</th>
			<th style="width:255px;">{l s='Template name' mod='reviverrental'}</th>
			<th style="width:100px;">{l s='Position' mod='reviverrental'}</th>
			<th style="width:30px;">{l s='Actions' mod='reviverrental'}</th>
		</tr>
	</thead>
<tbody>

{if $found_assigned > 0}

	{foreach from=$assigned_prods item=assigned_prod}
		<tr>
			<td>{$assigned_prod.id_template_assigned|escape:'htmlall':'UTF-8'}</td>
			<td>{$assigned_prod.name|escape:'htmlall':'UTF-8'}</td>
			<td>{$assigned_prod.template_name|escape:'htmlall':'UTF-8'}</td>
			<td>{$assigned_prod.order|escape:'htmlall':'UTF-8'}</td>
			<td><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&editAssignedProd={$assigned_prod.id_template_assigned|escape:'htmlall':'UTF-8'}"><img src="../img/admin/edit.gif" alt="{l s='Edit' mod='reviverrental'}" /></a><a href="index.php?controller=AdminProducts&updateproduct&token={$tokenProducts|escape:'htmlall':'UTF-8'}&id_product={$assigned_prod.id_product|escape:'htmlall':'UTF-8'}"><img src="../img/admin/details.gif" alt="{l s='Edit product' mod='reviverrental'}" /></a></td>
		<tr>
	{/foreach}

{else}

<tr><td colspan="5" style="text-align:center;">{l s='No items found.' mod='reviverrental'}</td></tr>

{/if}

</tbody></table><br />{l s='Note: Sorting settings can be changed at the bottom of this page.' mod='reviverrental'}</p></fieldset><br /><br />




<fieldset><legend>{l s='Rental templates' mod='reviverrental'}</legend>
<p><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createTemplate"><img src="../img/admin/add.gif" alt="">{l s='Create new rental template' mod='reviverrental'}</a><br />

<table class="table">
	<thead>
		<tr>
			<th style="width:20px;">{l s='ID' mod='reviverrental'}</th>
			<th style="width:410px;">{l s='Template name' mod='reviverrental'}</th>
			<th style="width:100px;">{l s='Total fields' mod='reviverrental'}</th>
			<th style="width:100px;">{l s='Total products' mod='reviverrental'}</th>
			<th style="width:30px;">{l s='Actions' mod='reviverrental'}</th>
		</tr>
	</thead>
<tbody>

{if $found_templates > 0}

	{foreach from=$templates item=template}
		<tr>
			<td>{$template.id_template|escape:'htmlall':'UTF-8'}</td>
			<td>{$template.name|escape:'htmlall':'UTF-8'}</td>
			<td>{$template.found2|escape:'htmlall':'UTF-8'}</td>
			<td>{$template.found3|escape:'htmlall':'UTF-8'}</td>
			<td><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createTemplate&id={$template.id_template|escape:'htmlall':'UTF-8'}"><img src="../img/admin/edit.gif" alt="{l s='Edit' mod='reviverrental'}" /></a> <a class="del_button" href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&deleteTemplate={$template.id_template|escape:'htmlall':'UTF-8'}"><img src="../img/admin/delete.gif" alt="{l s='Delete' mod='reviverrental'}" /></a></td>
		<tr>
	{/foreach}

{else}

<tr><td colspan="5" style="text-align:center;">{l s='No items found.' mod='reviverrental'}</td></tr>

{/if}

</tbody></table><br />{l s='Note: Rental templates may have the same name.' mod='reviverrental'}</p></fieldset><br /><br />




<fieldset><legend>{l s='Fields' mod='reviverrental'}</legend>
<p><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createField"><img src="../img/admin/add.gif" alt="">{l s='Create new template field' mod='reviverrental'}</a><br />

<table class="table">
	<thead>
		<tr>
			<th style="width:20px;">{l s='ID' mod='reviverrental'}</th>
			<th style="width:256px;">{l s='Field name' mod='reviverrental'}</th>
			<th style="width:256px;">{l s='Title' mod='reviverrental'}</th>
			<th style="width:100px;">{l s='Type' mod='reviverrental'}</th>
			<th style="width:30px;">{l s='Actions' mod='reviverrental'}</th>
		</tr>
	</thead>
<tbody>

{if $found_fields > 0}

	{foreach from=$fields item=field}
		<tr>
			<td>{$field.id_field|escape:'htmlall':'UTF-8'}</td>
			<td>{$field.name|escape:'htmlall':'UTF-8'}</td>
			<td>{$field.title|escape:'htmlall':'UTF-8'}</td>
			<td>{$field.type|escape:'htmlall':'UTF-8'}</td>
			<td><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createField&id={$field.id_field|escape:'htmlall':'UTF-8'}"><img src="../img/admin/edit.gif" alt="{l s='Edit' mod='reviverrental'}" /></a> <a class="del_button" href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&deleteField={$field.id_field|escape:'htmlall':'UTF-8'}"><img src="../img/admin/delete.gif" alt="{l s='Delete' mod='reviverrental'}" /></a></td>
		<tr>
	{/foreach}

{else}

<tr><td colspan="5" style="text-align:center;">{l s='No items found.' mod='reviverrental'}</td></tr>

{/if}

</tbody></table><br />{l s='Note: All created fields could be used in multiple templates.' mod='reviverrental'}</p></fieldset><br /><br />




<fieldset><legend>{l s='Additional products / services' mod='reviverrental'}</legend>
<p><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createProduct"><img src="../img/admin/add.gif" alt="">{l s='Create new template product / service' mod='reviverrental'}</a><br />

<table class="table">
	<thead>
		<tr>
			<th style="width:20px;">{l s='ID' mod='reviverrental'}</th>
			<th style="width:256px;">{l s='Field name' mod='reviverrental'}</th>
			<th style="width:256px;">{l s='Title' mod='reviverrental'}</th>
			<th style="width:100px;">{l s='Type' mod='reviverrental'}</th>
			<th style="width:30px;">{l s='Actions' mod='reviverrental'}</th>
		</tr>
	</thead>
<tbody>

{if $found_prods > 0}

	{foreach from=$add_products item=add_product}
		<tr>
			<td>{$add_product.id_template_product|escape:'htmlall':'UTF-8'}</td>
			<td>{$add_product.name|escape:'htmlall':'UTF-8'}</td>
			<td>{$add_product.title|escape:'htmlall':'UTF-8'}</td>
			<td>{$add_product.price|escape:'htmlall':'UTF-8'}</td>
			<td><a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createProduct&id={$add_product.id_template_product|escape:'htmlall':'UTF-8'}"><img src="../img/admin/edit.gif" alt="{l s='Edit' mod='reviverrental'}" /></a><a class="del_button" href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&deleteProduct='{$add_product.id_template_product|escape:'htmlall':'UTF-8'}"><img src="../img/admin/delete.gif" alt="{l s='Delete' mod='reviverrental'}" /></a></td>
		<tr>
	{/foreach}

{else}

<tr><td colspan="5" style="text-align:center;">{l s='No items found.' mod='reviverrental'}</td></tr>

{/if}

</tbody></table><br />{l s='Note: All created products / services could be used in multiple templates.' mod='reviverrental'}</p></fieldset><br /><br />




<fieldset><legend>{l s='Rental options' mod='reviverrental'}</legend>
<p><form id="form" action="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=updateSettings" method="post" enctype="multipart/form-data">

{l s='CMS page for rental list' mod='reviverrental'}: 
<select name="id_cms" id="id_cms">
	<option selected value="0">{l s='Not selected' mod='reviverrental'}</option>
		{foreach from=$cms_pages item=cms_page}
			<option {if $cms_page.id_cms_check == $id_current_cms} selected {/if} value="{$cms_page.id_cms_check|escape:'htmlall':'UTF-8'}">{$cms_page.name|escape:'htmlall':'UTF-8'}</option>";
		{/foreach}
</select>
<br><br>

{l s='Email recipients (separate with comma)' mod='reviverrental'}: <input type="text" name="email_rec" value="{$email_rec|escape:'htmlall':'UTF-8'}"/>
<br><br>

{l s='Order by' mod='reviverrental'}: 
<select name="orderby" id="orderby">
	<option {if $orderbysett == 1} selected {/if} value="1">{l s='Order by product name ascending' mod='reviverrental'}</option>
	<option {if $orderbysett == 2} selected {/if} value="2">{l s='Order by product name descending' mod='reviverrental'}</option>
	<option {if $orderbysett == 3} selected {/if} value="3">{l s='Order by assigned product id ascending' mod='reviverrental'}</option>
	<option {if $orderbysett == 4} selected {/if} value="4">{l s='Order by assigned product id  descending' mod='reviverrental'}</option>
	<option {if $orderbysett == 5} selected {/if} value="5">{l s='Order by assigned product position ascending' mod='reviverrental'}</option>
	<option {if $orderbysett == 6} selected {/if} value="6">{l s='Order by assigned product position descending' mod='reviverrental'}</option>
</select>
<br><br>

{l s='Compatibility for columns' mod='reviverrental'}: 
<select name="columns" id="columns">
	<option {if $columnsset == 0} selected {/if} value="0">{l s='No' mod='reviverrental'}</option>
	<option {if $columnsset == 1} selected {/if} value="1">{l s='Yes' mod='reviverrental'}</option>
</select>
<br><br>

{l s='Force add tax' mod='reviverrental'}: 
<select name="taxesset" id="taxesset">
	<option {if $taxesset == 0} selected {/if} value="0">{l s='No' mod='reviverrental'}</option>
	<option {if $taxesset == 1} selected {/if} value="1">{l s='Yes' mod='reviverrental'}</option>
</select>
<br><br>

<input type="submit" class="button" id="updateSettingsButton" name="updateSettingsButton" value="{l s='Udate settings' mod='reviverrental'}"/></form>
</p></fieldset>