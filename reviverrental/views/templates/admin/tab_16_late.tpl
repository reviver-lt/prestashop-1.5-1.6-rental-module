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

{if $id_product == 0}
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert">×</button>
    {l s='There is 1 warning.' mod='reviverrental'}
    <ul style="display:block;" id="seeMore">
        <li>{l s='You must save this product before setting rental options.' mod='reviverrental'}</li>
    </ul>
</div>
{else}

<input type="hidden" name="id" value="{$id_product|escape:'htmlall':'UTF-8'}"/>
<div id="product-stickersprint" class="panel product-tab">
    <input type="hidden" name="submitted_tabs[]" value="ModuleStickersprint" />
    <h3>{l s='Rental settings' mod='reviverrental'}</h3>

    <div class="form-group">
        <label class="control-label col-lg-3" for="width">{$bullet_common_field|escape:'htmlall':'UTF-8'} {l s='Assigned template' mod='reviverrental'}</label>
        <div class="col-lg-5">
            <select id="id_template" name="id_template">
                <option value="">{l s='None' mod='reviverrental'}</option>
                {if $alltemplates != ""}
                {foreach $alltemplates as $template} 
                <option {if $template.id == $id_template} selected {/if} value="{$template.id|escape:'htmlall':'UTF-8'}">{$template.name|escape:'htmlall':'UTF-8'}</option>
                {/foreach}
                {/if}
            </select>
        </div>
    </div>

    <div class="form-group" id="no-selected-carries-alert">
        <div class="col-lg-offset-3">
            <div class="alert alert-info">{l s='You can assign only one template per product.' mod='reviverrental'} <a href="index.php?controller=AdminModules&configure=reviverrental&token={$token|escape:'htmlall':'UTF-8'}">{l s='Click here' mod='reviverrental'}</a> {l s='in order to edit or create new templates.' mod='reviverrental'}</div>
        </div>
    </div>
    <div class="panel-footer">
        <a href="{$link->getAdminLink('AdminProducts')|escape:'htmlall':'UTF-8'}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='reviverrental'}</a>
        <button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='reviverrental'}</button>
        <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and stay' mod='reviverrental'}</button>
    </div>
</div>
{/if}