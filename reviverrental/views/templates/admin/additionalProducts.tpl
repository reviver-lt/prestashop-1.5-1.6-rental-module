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
        return confirm('{l s='Are you sure want to delete this image?' mod='reviverrental'}');
    });
});
</script>

<form id="form" action="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&page=createField&id={$id|escape:'htmlall':'UTF-8'}" method="post" enctype="multipart/form-data">
<fieldset><legend>{l s='Actions with additional products / services' mod='reviverrental'}</legend>

{if $id > 0}
	<p>
		<span style="display: inline-block; width:120px; margin-bottom: 10px;">{l s='Name' mod='reviverrental'}:</span> <input id="name" name="name" type="text" size="30" value="{$name|escape:'htmlall':'UTF-8'}"/><br>
		<span style="display: inline-block; width:120px; margin-bottom: 10px;">{l s='Title' mod='reviverrental'}:</span> <input id="title" name="title" type="text" size="30" value="{$title|escape:'htmlall':'UTF-8'}"/><br>
		<span style="display: inline-block; width:120px; margin-bottom: 10px; vertical-align: top;">{l s='Description' mod='reviverrental'}:</span> <textarea name="description" id="description" rows="4" cols="30">{$description|escape:'htmlall':'UTF-8'}</textarea><br>
		<span style="display: inline-block; width:120px; margin-bottom: 10px;">{l s='Old price' mod='reviverrental'}:</span> <input id="price_discount" name="price_discount" type="text" size="5" value="{$price_discount|escape:'htmlall':'UTF-8'}"/><br>
		<span style="display: inline-block; width:120px; margin-bottom: 10px;">{l s='Price' mod='reviverrental'}:</span> <input id="price" name="price" type="text" size="5" value="{$price|escape:'htmlall':'UTF-8'}"/><br><br>
		
			{if $image_path != ""}
			    <span style="display: inline-block; width:120px; margin-bottom: 10px; vertical-align: top;"">{l s='Image' mod='reviverrental'}:</span> 
				<img height="100" src="/modules/reviverrental/products/{$image_path|escape:'htmlall':'UTF-8'}"><br>
				<span style="width:300px; padding-top: 5px; text-align:center; display:block;"><a class="del_button" href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}&deleteProductImage={$id|escape:'htmlall':'UTF-8'}">{l s='Delete' mod='reviverrental'}</a></span><br>
			{else}
				<span style="display: inline-block; width:120px; margin-bottom: 10px;">{l s='Image' mod='reviverrental'}:</span> <input type="file" name="file" id="file"/><br /><br />
			{/if}
			
		<input id="edit_id" name="edit_id" type="hidden" value="{$id|escape:'htmlall':'UTF-8'}"/>
		<input type="submit" class="button" id="createNewProduct" name="createNewProduct" value="{l s='Create new' mod='reviverrental'}"/>
		<input type="submit" class="button" id="cancel" name="cancel" value="{l s='Back' mod='reviverrental'}"/>
		<input type="submit" class="button" id="editGallery" name="editProduct" value="{l s='Save' mod='reviverrental'}"/><br /><br />
		{l s='Note: To show product price as discount enter old price field, otherwise leave it empty.' mod='reviverrental'}
	</p>
{else}
	<p>
		<span style="display: inline-block; width:120px; margin-bottom: 10px;">{l s='Name' mod='reviverrental'}:</span> <input id="name" name="name" type="text" size="30" value=""/><br>
		<span style="display: inline-block; width:120px; margin-bottom: 10px;">{l s='Title' mod='reviverrental'}:</span> <input id="title" name="title" type="text" size="30" value=""/><br>
		<span style="display: inline-block; width:120px; margin-bottom: 10px; vertical-align: top;">{l s='Description' mod='reviverrental'}:</span> <textarea name="description" id="description" rows="4" cols="30"></textarea><br>
		<span style="display: inline-block; width:120px; margin-bottom: 10px;">{l s='Old price' mod='reviverrental'}:</span> <input id="price_discount" name="price_discount" type="text" size="5" value=""/><br>
		<span style="display: inline-block; width:120px; margin-bottom: 10px;">{l s='Price' mod='reviverrental'}:</span> <input id="price" name="price" type="text" size="5" value=""/><br><br>

        <input type="submit" class="button" id="cancel" name="cancel" value="{l s='Back' mod='reviverrental'}"/>
		<input type="submit" class="button" id="createProduct" name="createProduct" value="{l s='Save' mod='reviverrental'}"/> <br /><br /> 
		{l s='Note: To show product price as discount enter old price field, otherwise leave it empty.' mod='reviverrental'} <br><br> {l s='Note: You may add image only after saving product / service.' mod='reviverrental'}
	</p>
{/if}

</fieldset>
</form><br /><br />