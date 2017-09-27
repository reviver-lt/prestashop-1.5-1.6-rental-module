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

<div class="rental_page">
    <div class="rvr">
        <div class="container-fluid">

            <div class="row">


                {foreach from=$products_arr item=prod_id}
                {$n = 0}

                <div id="rentalprod-{$prod_id|escape:'htmlall':'UTF-8'}" class="rent_product col-xs-12">

                    <form id="rental_page_form-{$prod_id|escape:'htmlall':'UTF-8'}" action="http://{$smarty.server.HTTP_HOST|escape:'htmlall':'UTF-8'}/index.php?controller=cart" method="post">

                        <input type="hidden" name="type" value="rental" />
                        <input type="hidden" name="token" value="{$token|escape:'htmlall':'UTF-8'}" />
                        <input type="hidden" name="id_product" value="{$prod_id|escape:'htmlall':'UTF-8'}" id="product_page_product_id" />
                        <input type="hidden" name="id_template" value="{$product_info[$prod_id]["id_template"]|escape:'htmlall':'UTF-8'}" />
                        <input type="hidden" name="add" value="1" />
                        <input type="hidden" name="id_product_attribute" id="idCombination" value="" />

                        <div class="rental_summary row">
                            <div class="rental_image col-xs-12 col-sm-6"><img src="{$product_info[$prod_id]["image"]|escape:'htmlall':'UTF-8'}"></div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="row">
                                    <div class="rental_name col-xs-12"><h3>{$product_info[$prod_id]["name"]|escape:'htmlall':'UTF-8'}</h3></div>
                                    {if $columnsset == 1}<div class="rental_price col-xs-12 col-sm-6 text-right">{else}<div class="rental_price col-xs-12 col-sm-8 text-right">{/if}
                                    <div class="days-container"><span class="days-text">{l s='Price for' mod='reviverrental'}</span> <span class="days-{$prod_id|escape:'htmlall':'UTF-8'}">1</span> <span class="days-text">{l s='day(s)' mod='reviverrental'}</span></div>
                                    {if $product_info[$prod_id]["price_sale"] != ""}<span data-price-old="{convertPrice price=$product_info[$prod_id]["price"]|escape:'htmlall':'UTF-8'}" style="text-decoration: line-through;" class="oldprice-{$prod_id|escape:'htmlall':'UTF-8'} oldprice exclusive_large">{convertPrice price=$product_info[$prod_id]["price"]|escape:'htmlall':'UTF-8'}</span><span data-price="{convertPrice price=$product_info[$prod_id]["price_sale"]|escape:'htmlall':'UTF-8'}" class="price-{$prod_id|escape:'htmlall':'UTF-8'} newprice exclusive_large">{convertPrice price=$product_info[$prod_id]["price_sale"]|escape:'htmlall':'UTF-8'}</span> {else} <span data-price="{convertPrice price=$product_info[$prod_id]["price"]|escape:'htmlall':'UTF-8'}" class="price-{$prod_id|escape:'htmlall':'UTF-8'} regularprice exclusive_large">{convertPrice price=$product_info[$prod_id]["price"]|escape:'htmlall':'UTF-8'}</span> {/if}
                                    </div>
                                    {if $columnsset == 1}<div class="col-xs-12 col-sm-6 rental_button">{else}<div class="col-xs-12 col-sm-4 rental_button">{/if}
                                    <a class="button lnk_view rental_expand" data-toggle="collapse" data-target="#collapseExample{$prod_id|escape:'htmlall':'UTF-8'}" aria-expanded="false" aria-controls="collapseExample"><span>{l s='Show details' mod='reviverrental'}</span></a>
                                    </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="rent_container" id="collapseExample{$prod_id|escape:'htmlall':'UTF-8'}">
                                    <div class="row">
                                        {* <div class="rental_desc col-xs-12">{$product_info[$prod_id]["description"]|escape:'htmlall':'UTF-8'}</div> *}
                                        <div class="col-xs-12 rental_fields">
                                            <div class="row">
                                                <!----- Fields ----->
                                                {if $product_flds != ""}
                                                {foreach from=$product_flds[$prod_id] item=prod_fld}
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="fields_prod form-group">
                                                        {if $prod_fld["type"] == "date"}
                                                        {$n = $n + 1}
                                                        <label for="fieldid-{$prod_fld["id_field"]|escape:'htmlall':'UTF-8'}">{$prod_fld["title"]|escape:'htmlall':'UTF-8'}</label>
                                                        <input data-prod="{$prod_id|escape:'htmlall':'UTF-8'}" class="rental_input form-control datepicker required date{$n|escape:'htmlall':'UTF-8'} date{$n|escape:'htmlall':'UTF-8'}-{$prod_id|escape:'htmlall':'UTF-8'}" type="text" id="fieldid-{$prod_fld["id_field"]|escape:'htmlall':'UTF-8'}-{$prod_id|escape:'htmlall':'UTF-8'}" {if $n < 3} name="date{$n|escape:'htmlall':'UTF-8'}-{$prod_fld["id_field"]|escape:'htmlall':'UTF-8'}" {else} name="fieldid-{$prod_fld["id_field"]|escape:'htmlall':'UTF-8'}" {/if} value="{$prod_fld["value"]|escape:'htmlall':'UTF-8'}" placeholder="{$prod_fld["placeholder"]|escape:'htmlall':'UTF-8'}"><img class="ui-datepicker-trigger" src="http://{$smarty.server.HTTP_HOST|escape:'htmlall':'UTF-8'}/modules/reviverrental/views/img/calendar.gif" alt="..." title="...">
                                                        {/if}
                                                        {if $prod_fld["type"] == "input"}
                                                        <label for="fieldid-{$prod_fld["id_field"]|escape:'htmlall':'UTF-8'}">{$prod_fld["title"]|escape:'htmlall':'UTF-8'}</label>
                                                        <input class="rental_input form-control required" type="text" id="fieldid-{$prod_fld["id_field"]|escape:'htmlall':'UTF-8'}" name="fieldid-{$prod_fld["id_field"]|escape:'htmlall':'UTF-8'}" value="{$prod_fld["value"]|escape:'htmlall':'UTF-8'}" placeholder="{$prod_fld["placeholder"]|escape:'htmlall':'UTF-8'}">
                                                        {/if}
                                                        {if $prod_fld["type"] == "select"}
                                                        <label for="fieldid-{$prod_fld["id_field"]|escape:'htmlall':'UTF-8'}">{$prod_fld["title"]|escape:'htmlall':'UTF-8'}</label>
                                                        <select class="rental_input form-control" id="fieldid-{$prod_fld["id_field"]|escape:'htmlall':'UTF-8'}" name="fieldid-{$prod_fld["id_field"]|escape:'htmlall':'UTF-8'}">
                                                            {if $product_flds_sel != ""}
                                                            {foreach from=$product_flds_sel[$prod_id][$prod_fld["id_field"]] item=prod_sel}
                                                            {if $prod_sel["title"] != ""}
                                                            <option value="{$prod_sel["value"]|escape:'htmlall':'UTF-8'}">{$prod_sel["title"]|escape:'htmlall':'UTF-8'}</option>
                                                            {/if}
                                                            {/foreach}
                                                            {/if}
                                                        </select>
                                                        {/if}
                                                    </div>
                                                </div>
                                                {/foreach}
                                                {/if}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row additional_prods">

                                        <!----- Additional products ----->
                                        {if $product_prods != ""}
                                        {foreach from=$product_prods[$prod_id] item=prod_add}
                                        {if $prod_add["id_template_product"] > 0}
                                        <div class="col-xs-3">
                                            <div class="additional_prod">
                                                <div class="add_product_name">
                                                {if $prod_add["image_path"] != ""}
                                                <img src="http://{$smarty.server.HTTP_HOST|escape:'htmlall':'UTF-8'}/modules/reviverrental/products/{$prod_add["image_path"]|escape:'htmlall':'UTF-8'}">{/if}
                                                </div>
                                                <div class="additional_prod_details">

                                                    {if $product_info[$prod_id]["discount_method"] == 0}
                                                    <input class="add_product_chk" type="checkbox" {if $product_info[$prod_id]["reduction"] != ""} {if $product_info[$prod_id]["reduction_type"] == "percentage"} {math equation="x-x*y" x=$prod_add["price"]|escape:'htmlall':'UTF-8' y=$product_info[$prod_id]["reduction"]|escape:'htmlall':'UTF-8' assign="add_prod_new_price"} {else} {math equation="x-y" x=$prod_add["price"]|escape:'htmlall':'UTF-8' y=$product_info[$prod_id]["reduction"]|escape:'htmlall':'UTF-8' assign="add_prod_new_price"} {/if} data-price2="{$prod_add["price"]|escape:'htmlall':'UTF-8'}" data-price="{$add_prod_new_price|escape:'htmlall':'UTF-8'}" {else} data-price2="{$prod_add["price"]|escape:'htmlall':'UTF-8'}" data-price="{$prod_add["price"]|escape:'htmlall':'UTF-8'}" {/if} data-prod="{$prod_id|escape:'htmlall':'UTF-8'}" data-add-prod="{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}" name="productid-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}" id="productid-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}" value="{$prod_add["title"]|escape:'htmlall':'UTF-8'}"> 
                                                    {elseif $product_info[$prod_id]["discount_method"] == 1}
                                                    <input class="add_product_chk" type="checkbox" {if $prod_add["price_discount"] > 0} data-price="{$prod_add["price"]|escape:'htmlall':'UTF-8'}" data-price2="{$prod_add["price_discount"]|escape:'htmlall':'UTF-8'}" {else} data-price2="{$prod_add["price"]|escape:'htmlall':'UTF-8'}" data-price="{$prod_add["price"]|escape:'htmlall':'UTF-8'}" {/if} data-prod="{$prod_id|escape:'htmlall':'UTF-8'}" data-add-prod="{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}" name="productid-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}" id="productid-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}" value="{$prod_add["title"]|escape:'htmlall':'UTF-8'}"> 
                                                    {/if}
                                                    <label for="productid-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}">
                                                        <span class="add_product_title"><h3>{$prod_add["title"]|escape:'htmlall':'UTF-8'}</h3></span>
                                                        {if $product_info[$prod_id]["discount_method"] == 0}
                                                        <span class="add_product_price">{if $product_info[$prod_id]["reduction"] != ""} {if $product_info[$prod_id]["reduction_type"] == "percentage"} {math equation="x-x*y" x=$prod_add["price"]|escape:'htmlall':'UTF-8' y=$product_info[$prod_id]["reduction"]|escape:'htmlall':'UTF-8' assign="add_prod_new_price"} {else} {math equation="x-y" x=$prod_add["price"]|escape:'htmlall':'UTF-8' y=$product_info[$prod_id]["reduction"]|escape:'htmlall':'UTF-8' assign="add_prod_new_price"} {/if}<span style="text-decoration: line-through;" class="add_product_price_old add_product_old-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}">{convertPrice price=$prod_add["price"]|escape:'htmlall':'UTF-8'}</span> <span class="add_product_price_new add_product-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}">{convertPrice price=$add_prod_new_price|escape:'htmlall':'UTF-8'}</span> {else} <span class="add_product_price_new add_product-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}">{convertPrice price=$prod_add["price"]|escape:'htmlall':'UTF-8'}</span> {/if}</span><br>
                                                        {elseif $product_info[$prod_id]["discount_method"] == 1}
                                                        <span class="add_product_price">{if $prod_add["price_discount"] > 0}<span style="text-decoration: line-through;" class="add_product_price_old add_product_old-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}">{convertPrice price=$prod_add["price_discount"]|escape:'htmlall':'UTF-8'}</span> <span class="add_product_price_new add_product-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}">{convertPrice price=$prod_add["price"]|escape:'htmlall':'UTF-8'}</span> {else} <span class="add_product_price_new add_product-{$prod_add["id_template_product"]|escape:'htmlall':'UTF-8'}">{convertPrice price=$prod_add["price"]|escape:'htmlall':'UTF-8'}</span> {/if}</span><br>
                                                        {/if}
                                                    </label>
                                                </div>
                                                <div class="clearfix"></div>
                                                {* <div class="add_product_desc">{$prod_add["description"]|escape:'htmlall':'UTF-8'}</div> *}
                                            </div>
                                        </div>
                                        {/if}
                                        {/foreach}
                                        {/if}
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 pull-right">
                                            <div class="row">
                                                {if $columnsset == 1}<div class="rental_price col-xs-12 col-sm-6 text-right">{else}<div class="rental_price col-xs-12 col-sm-8 text-right">{/if}
                                                <div class="days-container"><span class="days-text">{l s='Price for' mod='reviverrental'}</span> <span class="days-{$prod_id|escape:'htmlall':'UTF-8'}">1</span> <span class="days-text">{l s='day(s)' mod='reviverrental'}</span></div>
                                                {if $product_info[$prod_id]["price_sale"] != ""}<span style="text-decoration: line-through;" class="oldprice-{$prod_id|escape:'htmlall':'UTF-8'} oldprice exclusive_large">{convertPrice price=$product_info[$prod_id]["price"]|escape:'htmlall':'UTF-8'}</span><span class="price-{$prod_id|escape:'htmlall':'UTF-8'} newprice exclusive_large">{convertPrice price=$product_info[$prod_id]["price_sale"]|escape:'htmlall':'UTF-8'}</span> {else} <span style="text-decoration: line-through; display: none;" class="oldprice-{$prod_id|escape:'htmlall':'UTF-8'} oldprice exclusive_large">{convertPrice price=$product_info[$prod_id]["price"]|escape:'htmlall':'UTF-8'}</span> <span class="price-{$prod_id|escape:'htmlall':'UTF-8'} regularprice exclusive_large">{convertPrice price=$product_info[$prod_id]["price"]|escape:'htmlall':'UTF-8'}</span> {/if}
                                                </div>
                                                {if $columnsset == 1}<div class="col-xs-12 col-sm-6 rental_button">{else}<div class="col-xs-12 col-sm-4 rental_button">{/if}
                                                <input data-id-product="{$prod_id|escape:'htmlall':'UTF-8'}" data-id="rental_page_form-{$prod_id|escape:'htmlall':'UTF-8'}" class="button exclusive button_rental button_rental_order" type="submit" name="Submit" value="{l s='Add to Cart' mod='reviverrental'}">
                                                </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div><!-- .collapse -->
                                        </form>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                        </div>
                </div>

                {* ----------------- *}
                {* DO NOT EDIT BELOW *}
                {* ----------------- *}

                <script language="javascript" type="text/javascript">
                    $(document).ready(function(event) {
                        $(".rental_page").appendTo(".rte");
                        $('.rent_product input:checkbox').removeAttr('checked');
                    });
                    $(document).ready(function(){

                        if({$currency_spacing|escape:'htmlall':'UTF-8'} == 1) { var space_between = " "; } else { var space_between = ""; }
                        var d = new Date();
                        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                        today = monthNames[d.getMonth()] + ' ' + d.getDate() + ' ' + d.getFullYear();

                        $('.rent_container').hide();
                        $('.rent_container').each(function(){
                            $('.datepicker', this).first().datepicker({
                                defaultDate: "+0d",
                                minDate: 0,
                                maxDate: "+12M",
                                dateFormat: 'yy-mm-dd',
                                showOtherMonths: true,
                                selectOtherMonths: true,
                                required: true,
                                showOn: "focus",
                                numberOfMonths: 1,
                                firstDay: 1
                            });
                            $('.datepicker', this).last().attr('disabled', 'disabled');
                        });

                        $('.date1').change(function () {
                            var product = $(this).attr("data-prod");
                            var from = $(this).datepicker('getDate');
                            var date_diff = Math.ceil((from.getTime() - Date.parse(today)) / 86400000);
                            var maxDate_d = date_diff+365+'d';
                            date_diff = date_diff + 'd';
                            $('.date2-'+product+'').val('').removeAttr('disabled').removeClass('hasDatepicker').datepicker({
                                dateFormat: 'yy-mm-dd',
                                minDate: date_diff,
                                maxDate: maxDate_d
                            });

                            $('.days-'+product).html( 1 );

                            var currentpriceel = $('.price-'+product).attr("data-price");
                            if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                var pos = currentpriceel.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                if(pos == 0) { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                            var currency = currentpriceel.split(' ');
                            var currency1 = parseFloat(currency[0].replace(',','.').replace(' ',''));
                            var currency2 = parseFloat(currency[1].replace(',','.').replace(' ',''));
                            if(isNaN(currency1)) { var curr_place = 1; } else { var curr_place = 2;  }
                            var currentpriceel2 = $('.oldprice-'+product).attr("data-price-old");
                            if(typeof currentpriceel2 != 'undefined') {
                                if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                    var pos = currentpriceel2.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                    if(pos == 0) { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                var currency_old = currentpriceel2.split(' ');
                                var currency_old1 = parseFloat(currency_old[0].replace(',','.').replace(' ',''));
                                var currency_old2 = parseFloat(currency_old[1].replace(',','.').replace(' ','')); }
                            if(curr_place == 1) {
                                var newprice = currency2 * 1;
                                $('.price-'+product).html( currency[0]+""+space_between+""+newprice.toFixed(2) );
                                if(typeof currentpriceel2 != 'undefined') { var newprice2 = currency_old2 * 1; $('.oldprice-'+product).html( currency[0]+""+space_between+""+newprice2.toFixed(2) ); }
                            } else {
                                var newprice = currency1 * 1;
                                $('.price-'+product).html( newprice.toFixed(2)+""+space_between+""+currency[1] );
                                if(typeof currentpriceel2 != 'undefined') { var newprice2 = currency_old1 * 1; $('.oldprice-'+product).html( newprice2.toFixed(2)+""+space_between+""+currency[1] ); }
                            }

                            $('#rentalprod-'+product+' .additional_prods :checked').each(function() {
                                var thisprice = $(this).attr("data-price");
                                var thisprice = parseFloat(thisprice.replace(',','.').replace(' ',''));
                                var thisprice2 = $(this).attr("data-price2");
                                if(thisprice2 > 0) {
                                    var thisprice2 = parseFloat(thisprice2.replace(',','.').replace(' ','')); }
                                var thisprodid = $(this).attr("data-prod");
                                var currentpriceel = $('.price-'+product).html();
                                if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                    var pos = currentpriceel.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                    if(pos == 0) { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                var currency = currentpriceel.split(' ');
                                var currency1 = parseFloat(currency[0].replace(',','.').replace(' ',''));
                                var currency2 = parseFloat(currency[1].replace(',','.').replace(' ',''));			
                                if(isNaN(currency1)) { var curr_place = 1; } else { var curr_place = 2;  }
                                if(thisprice2 > 0) {
                                    var currentpriceel2 = $('.oldprice-'+thisprodid).text(); 
                                    if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                        var pos = currentpriceel2.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                        if(pos == 0) { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                    var currency_old = currentpriceel2.split(' ');
                                    var currency_old1 = parseFloat(currency_old[0].replace(',','.').replace(' ',''));
                                    var currency_old2 = parseFloat(currency_old[1].replace(',','.').replace(' ',''));
                                }

                                if(curr_place == 1) {
                                    var newprice = currency2 + thisprice;
                                    $('.price-'+thisprodid).html( currency[0]+""+space_between+""+newprice.toFixed(2) );
                                    if(thisprice2 > 0) { var newprice2 = currency_old2 + thisprice2; $('.oldprice-'+thisprodid).html( currency[0]+""+space_between+""+newprice2.toFixed(2) ); }
                                } else {
                                    var newprice = currency1 + thisprice;
                                    $('.price-'+thisprodid).html( newprice.toFixed(2)+""+space_between+""+currency[1] );
                                    if(thisprice2 > 0) { var newprice2 = currency_old1 + thisprice2; $('.oldprice-'+thisprodid).html( newprice2.toFixed(2)+""+space_between+""+currency[1] ); }
                                }
                            });

                            var days = parseInt(1);

                            $('#rentalprod-'+product+' .additional_prods input[type=checkbox]').each(function() {
                                var thisprice = $(this).attr("data-price");
                                var thisprice = parseFloat(thisprice.replace(',','.').replace(' ',''));
                                var thisprice2 = $(this).attr("data-price2");
                                if(thisprice2 > 0) {
                                    var thisprice2 = parseFloat(thisprice2.replace(',','.').replace(' ','')); }
                                var thisprodid = $(this).attr("data-prod");
                                var thisaddprodid = $(this).attr("data-add-prod");
                                var currentpriceel = $('.price-'+thisprodid+':first').text();
                                if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                    var pos = currentpriceel.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                    if(pos == 0) { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                var currency = currentpriceel.split(' ');
                                var currency1 = parseFloat(currency[0].replace(',','.').replace(' ',''));
                                var currency2 = parseFloat(currency[1].replace(',','.').replace(' ',''));
                                if(isNaN(currency1)) { var curr_place = 1; } else { var curr_place = 2;  }
                                if(thisprice2 > 0) {
                                    var currentpriceel2 = $('.oldprice-'+thisprodid).text();
                                    if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                        var pos = currentpriceel2.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                        if(pos == 0) { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                    var currency_old = currentpriceel2.split(' ');
                                    var currency_old1 = parseFloat(currency_old[0].replace(',','.').replace(' ',''));
                                    var currency_old2 = parseFloat(currency_old[1].replace(',','.').replace(' ',''));
                                }			

                                if(curr_place == 1) {
                                    var newprice = thisprice * days;
                                    $('#rentalprod-'+product+' .add_product-'+thisaddprodid).html( currency[0]+""+space_between+""+newprice.toFixed(2) );
                                    if(thisprice2 > 0) { var newprice2 = thisprice2 * days; $('#rentalprod-'+product+' .add_product_old-'+thisaddprodid).html( currency[0]+""+space_between+""+newprice2.toFixed(2) ); }
                                } else {
                                    var newprice = thisprice * days;
                                    $('#rentalprod-'+product+' .add_product-'+thisaddprodid).html( newprice.toFixed(2)+""+space_between+""+currency[1] );
                                    if(thisprice2 > 0) { var newprice2 = thisprice2 * days; $('#rentalprod-'+product+' .add_product_old-'+thisaddprodid).html( newprice2.toFixed(2)+""+space_between+""+currency[1] ); }
                                }
                            });


                        });

                        $('.date2').change(function () {
                            var product = $(this).attr("data-prod");
                            var date1 = $('.date1-'+product+'').val();
                            var date2 = $('.date2-'+product+'').val();
                            var diff = new Date(Date.parse(date2) - Date.parse(date1));
                            var days = diff/1000/60/60/24;
                            var days = parseInt(days);
                            var days = days + {$include_same_day|escape:'htmlall':'UTF-8'};

                            var days_show = days;
                            if (days_show == 0) { var days_show = 1; }

                            $('.days-'+product).html( days_show );

                            var arr_coe = {$product_coe|json_encode};

                            if (arr_coe == null || arr_coe[product] === undefined || arr_coe[product] === null) { 
                                var coef = 1;
                            } else {
                                var length = Object.keys(arr_coe[product]).length;


                                var from = 0;
                                var to = 0;
                                var coef = 0;
                                var coef_temp = 0;

                                for (var i = 0; i < length; i++) {
                                    var date_from = arr_coe[product][i]["date_from"];
                                    var date_to = arr_coe[product][i]["date_to"];
                                    var coef_temp = arr_coe[product][i]["coef"];
                                    if((days >= date_from) && (days <= date_to)) { var coef = coef_temp; }
                                }
                            }

                            if(coef == 0) { var coef = 1; }

                            if(days == 0) { 
                                var days = 1 * coef;
                            } else {
                                var days = days * coef;	
                            }

                            if(days > 0) {
                                var currentpriceel = $('.price-'+product).attr("data-price");
                                if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                    var pos = currentpriceel.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                    if(pos == 0) { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                var currency = currentpriceel.split(' ');
                                var currency1 = parseFloat(currency[0].replace(',','.').replace(' ',''));
                                var currency2 = parseFloat(currency[1].replace(',','.').replace(' ',''));
                                if(isNaN(currency1)) { var curr_place = 1; } else { var curr_place = 2;  }
                                var currentpriceel2 = $('.oldprice-'+product).attr("data-price-old");
                                if(typeof currentpriceel2 != 'undefined') {
                                    if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                        var pos = currentpriceel2.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                        if(pos == 0) { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                    var currency_old = currentpriceel2.split(' ');
                                    var currency_old1 = parseFloat(currency_old[0].replace(',','.').replace(' ',''));
                                    var currency_old2 = parseFloat(currency_old[1].replace(',','.').replace(' ','')); }
                                if(curr_place == 1) {
                                    var newprice = currency2 * days;
                                    $('.price-'+product).html( currency[0]+""+space_between+""+newprice.toFixed(2) );
                                    if(typeof currentpriceel2 != 'undefined') { var newprice2 = currency_old2 * days; $('.oldprice-'+product).html( currency[0]+""+space_between+""+newprice2.toFixed(2) ); }
                                } else {
                                    var newprice = currency1 * days;
                                    $('.price-'+product).html( newprice.toFixed(2)+""+space_between+""+currency[1] );
                                    if(typeof currentpriceel2 != 'undefined') { var newprice2 = currency_old1 * days; $('.oldprice-'+product).html( newprice2.toFixed(2)+""+space_between+""+currency[1] ); }
                                }

                                $('#rentalprod-'+product+' .additional_prods :checked').each(function() {
                                    var thisprice = $(this).attr("data-price");
                                    var thisprice = parseFloat(thisprice.replace(',','.').replace(' ',''));
                                    var thisprice2 = $(this).attr("data-price2");
                                    if(thisprice2 > 0) {
                                        var thisprice2 = parseFloat(thisprice2.replace(',','.').replace(' ','')); }
                                    var thisprodid = $(this).attr("data-prod");
                                    var currentpriceel = $('.price-'+product).html();
                                    if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                        var pos = currentpriceel.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                        if(pos == 0) { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                    var currency = currentpriceel.split(' ');
                                    var currency1 = parseFloat(currency[0].replace(',','.').replace(' ',''));
                                    var currency2 = parseFloat(currency[1].replace(',','.').replace(' ',''));
                                    if(isNaN(currency1)) { var curr_place = 1; } else { var curr_place = 2;  }
                                    if(thisprice2 > 0) {
                                        var currentpriceel2 = $('.oldprice-'+thisprodid).text(); 
                                        if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                            var pos = currentpriceel2.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                            if(pos == 0) { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                        var currency_old = currentpriceel2.split(' ');
                                        var currency_old1 = parseFloat(currency_old[0].replace(',','.').replace(' ',''));
                                        var currency_old2 = parseFloat(currency_old[1].replace(',','.').replace(' ',''));
                                    }
                                    if(curr_place == 1) {
                                        var newprice = currency2 + thisprice * days;
                                        $('.price-'+thisprodid).html( currency[0]+""+space_between+""+newprice.toFixed(2) );
                                        if(thisprice2 > 0) { var newprice2 = currency_old2 + thisprice2 * days; $('.oldprice-'+thisprodid).html( currency[0]+""+space_between+""+newprice2.toFixed(2) ); }
                                    } else {
                                        var newprice = currency1 + thisprice * days;
                                        $('.price-'+thisprodid).html( newprice.toFixed(2)+""+space_between+""+currency[1] );
                                        if(thisprice2 > 0) { var newprice2 = currency_old1 + thisprice2 * days; $('.oldprice-'+thisprodid).html( newprice2.toFixed(2)+""+space_between+""+currency[1] ); }
                                    }
                                });

                                $('#rentalprod-'+product+' .additional_prods input[type=checkbox]').each(function() {
                                    var thisprice = $(this).attr("data-price");
                                    var thisprice = parseFloat(thisprice.replace(',','.').replace(' ',''));
                                    var thisprice2 = $(this).attr("data-price2");
                                    if(thisprice2 > 0) {
                                        var thisprice2 = parseFloat(thisprice2.replace(',','.').replace(' ','')); }
                                    var thisprodid = $(this).attr("data-prod");
                                    var thisaddprodid = $(this).attr("data-add-prod");
                                    var currentpriceel = $('.price-'+thisprodid+':first').text();
                                    if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                        var pos = currentpriceel.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                        if(pos == 0) { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                    var currency = currentpriceel.split(' ');
                                    var currency1 = parseFloat(currency[0].replace(',','.').replace(' ',''));
                                    var currency2 = parseFloat(currency[1].replace(',','.').replace(' ',''));
                                    if(isNaN(currency1)) { var curr_place = 1; } else { var curr_place = 2;  }
                                    if(thisprice2 > 0) {
                                        var currentpriceel2 = $('.oldprice-'+thisprodid).text();
                                        if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                            var pos = currentpriceel2.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                            if(pos == 0) { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                        var currency_old = currentpriceel2.split(' ');
                                        var currency_old1 = parseFloat(currency_old[0].replace(',','.').replace(' ',''));
                                        var currency_old2 = parseFloat(currency_old[1].replace(',','.').replace(' ',''));
                                    }			

                                    if(curr_place == 1) {
                                        var newprice = thisprice * days;
                                        $('#rentalprod-'+product+' .add_product-'+thisaddprodid).html( currency[0]+""+space_between+""+newprice.toFixed(2) );
                                        if(thisprice2 > 0) { var newprice2 = thisprice2 * days; $('#rentalprod-'+product+' .add_product_old-'+thisaddprodid).html( currency[0]+""+space_between+""+newprice2.toFixed(2) ); }
                                    } else {
                                        var newprice = thisprice * days;
                                        $('#rentalprod-'+product+' .add_product-'+thisaddprodid).html( newprice.toFixed(2)+""+space_between+""+currency[1] );
                                        if(thisprice2 > 0) { var newprice2 = thisprice2 * days; $('#rentalprod-'+product+' .add_product_old-'+thisaddprodid).html( newprice2.toFixed(2)+""+space_between+""+currency[1] ); }
                                    }

                                });

                            } else {
                                alert("{l s='Error: The end date may not be earlier than the beginning.' mod='reviverrental'}");
                            }
                        });


                        $('.datepicker').keyup(function () {
                            $(this).val('');
                            alert("{l s='Error: you must select date from the calendar.' mod='reviverrental'}");
                        });

                        {foreach from=$products_arr item=prod_id}
                        $("#rental_page_form-{$prod_id|escape:'htmlall':'UTF-8'}").validate();
                        {/foreach}
                        {if $show_error == "yes"}
                        alert("{l s='Error: reservation could not be properly completed wrong data in booking form.' mod='reviverrental'}");
                         {/if}

                         });
                         $(".rental_expand").click(function(){
                             $($(this).data("target")).toggle(500);
                         });
                         $('a').click(function(){   
                             if($('span', this).text().trim() == '{l s='Show details' mod='reviverrental'}' ){
                                 $('span', this).text('{l s='Close' mod='reviverrental'}');
                             }else{
                                 $('span', this).text('{l s='Show details' mod='reviverrental'}');
                             }
                         });
                         $('.add_product_chk').click(function(event) {

                             if({$currency_spacing|escape:'htmlall':'UTF-8'} == 1) { var space_between = " "; } else { var space_between = ""; }
                             var thisprice = $(this).attr("data-price");
                             var thisprice = parseFloat(thisprice.replace(',','.').replace(' ',''));
                             var thisprice2 = $(this).attr("data-price2");
                             if(thisprice2 > 0) {
                                 var thisprice2 = parseFloat(thisprice2.replace(',','.').replace(' ','')); }
                             var thisprodid = $(this).attr("data-prod");
                             var thisch = $(this).attr("checked");
                             var currentpriceel = $('.price-'+thisprodid+':first').text();
                             if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                 var pos = currentpriceel.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                 if(pos == 0) { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel = currentpriceel.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                             var currency = currentpriceel.split(' ');
                             var currency1 = parseFloat(currency[0].replace(',','.').replace(' ',''));
                             var currency2 = parseFloat(currency[1].replace(',','.').replace(' ',''));
                             if(isNaN(currency1)) { var curr_place = 1; } else { var curr_place = 2;  }
                             if(thisprice2 > 0) {
                                 var currentpriceel2 = $('.oldprice-'+thisprodid).text();
                                 if({$currency_spacing|escape:'htmlall':'UTF-8'} == 0) {
                                     var pos = currentpriceel2.indexOf("{$currency_sign|escape:'htmlall':'UTF-8'}");
                                     if(pos == 0) { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}','{$currency_sign|escape:'htmlall':'UTF-8'} '); } else { var currentpriceel2 = currentpriceel2.replace('{$currency_sign|escape:'htmlall':'UTF-8'}',' {$currency_sign|escape:'htmlall':'UTF-8'}'); } }
                                 var currency_old = currentpriceel2.split(' ');
                                 var currency_old1 = parseFloat(currency_old[0].replace(',','.').replace(' ',''));
                                 var currency_old2 = parseFloat(currency_old[1].replace(',','.').replace(' ',''));
                             }

                             var product = $(this).attr("data-prod");
                             var date1 = $('.date1-'+thisprodid+'').val();
                             var date2 = $('.date2-'+thisprodid+'').val();
                             var diff = new Date(Date.parse(date2) - Date.parse(date1));
                             var days = diff/1000/60/60/24;
                             var days = parseInt(days);
                             var days = days + {$include_same_day|escape:'htmlall':'UTF-8'};

                             var arr_coe = {$product_coe|json_encode};
                             if (arr_coe == null || arr_coe[product] === undefined || arr_coe[product] === null) { 
                                 var coef = 1;
                             } else {
                                 var length = Object.keys(arr_coe[product]).length;
                             }

                             var from = 0;
                             var to = 0;
                             var coef = 0;
                             var coef_temp = 0;

                             for (var i = 0; i < length; i++) {
                                 var date_from = arr_coe[product][i]["date_from"];
                                 var date_to = arr_coe[product][i]["date_to"];
                                 var coef_temp = arr_coe[product][i]["coef"];
                                 if((days >= date_from) && (days <= date_to)) { var coef = coef_temp; }
                             }

                             if(coef == 0) { var coef = 1; }

                             if(days == 0) { 
                                 var days = 1 * coef;
                             } else {
                                 var days = days * coef;	
                             }


                             if(days > 0) {
                                 var thisprice = thisprice * days;
                                 if(thisprice2 > 0) { var thisprice2 = thisprice2 * days; }
                             }

                             if (thisch == "checked") {
                                 if(curr_place == 1) {
                                     var newprice = currency2 + thisprice;
                                     $('.price-'+thisprodid).html( currency[0]+""+space_between+""+newprice.toFixed(2) );
                                     if(thisprice2 > 0) { var newprice2 = currency_old2 + thisprice2; $('.oldprice-'+thisprodid).html( currency[0]+""+space_between+""+newprice2.toFixed(2) ); }
                                 } else {
                                     var newprice = currency1 + thisprice;
                                     $('.price-'+thisprodid).html( newprice.toFixed(2)+""+space_between+""+currency[1] );
                                     if(thisprice2 > 0) { var newprice2 = currency_old1 + thisprice2; $('.oldprice-'+thisprodid).html( newprice2.toFixed(2)+""+space_between+""+currency[1] ); }
                                 }
                             } else {
                                 if(curr_place == 1) {
                                     var newprice = currency2 - thisprice;
                                     $('.price-'+thisprodid).html( currency[0]+""+space_between+""+newprice.toFixed(2) );
                                     if(thisprice2 > 0) { var newprice2 = currency_old2 - thisprice2; $('.oldprice-'+thisprodid).html( currency[0]+""+space_between+""+newprice2.toFixed(2) ); }
                                 } else {
                                     var newprice = currency1 - thisprice;
                                     $('.price-'+thisprodid).html( newprice.toFixed(2)+""+space_between+""+currency[1] );
                                     if(thisprice2 > 0) { var newprice2 = currency_old1 - thisprice2; $('.oldprice-'+thisprodid).html( newprice2.toFixed(2)+""+space_between+""+currency[1] ); }
                                 }
                             }
                         });


                         $('.button_rental_order').click(function(event) {
                             var thisform = $(this).attr("data-id");
                             $("#thisform").validate();
                         });

                </script>