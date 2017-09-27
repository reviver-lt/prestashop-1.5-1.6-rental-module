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

{foreach from=$allproducts item=product}
    
    {if in_array($product.product_id, $rental_products)}
        
        <tr style="border: 1px #D6D4D4 solid;">
            <td style="border-left: 1px #D6D4D4 solid; padding:0.6em 0.4em;">{$product.product_reference|escape:'htmlall':'UTF-8'}</td>
            <td style="border-left: 1px #D6D4D4 solid; padding:0.6em 0.4em;"><strong>{$product.product_name|escape:'htmlall':'UTF-8'}
            <br><br>{l s='Rental information:' mod='reviverrental'}</strong><br>
                {foreach from=$rent_fields item=rent_field}
                    {if $rent_field.product_id == $product.product_id}
                        {$rent_field.title|escape:'htmlall':'UTF-8'} - {$rent_field.value|escape:'htmlall':'UTF-8'} <br>
                    {/if}
                {/foreach}
            {if $rent_prods != ""}<strong><br>{l s='Additional products / services:' mod='reviverrental'}</strong><br>
                {foreach from=$rent_prods item=rent_prod}
                    {if $rent_prod.product_id == $product.product_id}
                        {$rent_prod.title|escape:'htmlall':'UTF-8'} - {$rent_prod.value|escape:'htmlall':'UTF-8'} <br>
                    {/if}
                {/foreach}
            {/if}
            </td>
            <td style="border-left: 1px #D6D4D4 solid; padding:0.6em 0.4em; text-align:right;">{$product.price|escape:'htmlall':'UTF-8'}</td>
            <td style="border-left: 1px #D6D4D4 solid; padding:0.6em 0.4em; text-align:center;">{$product.product_quantity|escape:'htmlall':'UTF-8'}</td>
            <td style="border-left: 1px #D6D4D4 solid; border-right: 1px #D6D4D4 solid; padding:0.6em 0.4em; text-align:right;">{$product.sum|escape:'htmlall':'UTF-8'}</td>
        </tr>
        
    {else}
    
        <tr style="border: 1px #D6D4D4 solid;">
            <td style="border-left: 1px #D6D4D4 solid; padding:0.6em 0.4em;">{$product.product_reference|escape:'htmlall':'UTF-8'}</td>
            <td style="border-left: 1px #D6D4D4 solid; padding:0.6em 0.4em;"><strong>{$product.product_name|escape:'htmlall':'UTF-8'}</strong></td>
            <td style="border-left: 1px #D6D4D4 solid; padding:0.6em 0.4em; text-align:right;">{$product.price|escape:'htmlall':'UTF-8'}</td>
            <td style="border-left: 1px #D6D4D4 solid; padding:0.6em 0.4em; text-align:center;">{$product.product_quantity|escape:'htmlall':'UTF-8'}</td>
            <td style="border-left: 1px #D6D4D4 solid; border-right: 1px #D6D4D4 solid; padding:0.6em 0.4em; text-align:right;">{$product.sum|escape:'htmlall':'UTF-8'}</td>
        </tr>
    
    {/if}
    
{/foreach}

{if $vouchers != ""}
{foreach from=$vouchers item=voucher}
<tr style="background-color:#EBECEE;">
    <td colspan="4" style="padding:0.6em 0.4em; text-align:right;">{l s='Voucher code:' mod='reviverrental'} {$voucher.name|escape:'htmlall':'UTF-8'}</td>
    <td style="padding:0.6em 0.4em; text-align:right;">{$voucher.value|escape:'htmlall':'UTF-8'}</td>
</tr>
{/foreach}
{/if}