<?php
/**
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
 */

$id_product = (int)Tools::getValue('id_product');
$id_template = (int)Tools::getValue('id_template');

$sql='SELECT COUNT(*) AS Number FROM `'._DB_PREFIX_.'reviver_rental_template_assigned` WHERE `id_product`='.(int)$id_product;
$query_result = Db::getInstance()->ExecuteS($sql);
$count = $query_result[0][Number];

if ($id_template != 0) {
    if ($count == 0) {
        $sql2='SELECT COUNT(*) AS Number FROM `'._DB_PREFIX_.'reviver_rental_template_assigned`';
        $query_result2 = Db::getInstance()->ExecuteS($sql2);
        $count2 = $query_result2[0][Number];
        $total_order = $count2 + 1;

        $sql = 'INSERT INTO `'._DB_PREFIX_.'reviver_rental_template_assigned` (id_template_assigned, id_product, id_template, `order`) VALUES ("","'.(int)$id_product.'","'.(int)$id_template.'", "'.(int)$total_order.'")';
        $query_result = Db::getInstance()->ExecuteS($sql);

        $sql2='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name` = "id_attribute" ORDER BY `name` DESC LIMIT 1 ';
        $query_result2 = Db::getInstance()->ExecuteS($sql2);
        foreach ($query_result2 as $row2) {
            $id_attribute = $row2['value'];
        }

        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute` (`id_product_attribute`, `id_product`, `reference`, `supplier_reference`, `location`, `ean13`, `upc`, `wholesale_price`, `price`, `ecotax`, `quantity`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES ("", "'.(int)$id_product.'", "", "", "", "", "", "0.000000", "0.000000", "0.000000", "10000", "0.000000", "0.00", "1", "1", "0.00")');
        $created_id_product_attribute = Db::getInstance()->Insert_ID();
        Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_combination` (`id_attribute`, `id_product_attribute`) VALUES ("'.(int)$id_attribute.'", "'.(int)$created_id_product_attribute.'")');
        $allshop = 'SELECT * FROM `'._DB_PREFIX_.'shop` WHERE `active` = 1';
        foreach (Db::getInstance()->executeS($allshop) as $singleshop) {
            $id_shop = $singleshop['id_shop'];

            // PS 1.6.0.6 db change
            $ps_16_att_change = (int)(version_compare(_PS_VERSION_, '1.6.0.5'));
            if ($ps_16_att_change > 0) {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_shop` (`id_product`, `id_product_attribute`, `id_shop`, `wholesale_price`, `price`, `ecotax`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES ("'.(int)$id_product.'", "'.(int)$created_id_product_attribute.'", "'.(int)$id_shop.'", "0.000000", "0.000000", "0.000000", "0.000000", "0.000000", "1", "1", "0000-00-00")');
            } else {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_shop` (`id_product_attribute`, `id_shop`, `wholesale_price`, `price`, `ecotax`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES ("'.(int)$created_id_product_attribute.'", "'.(int)$id_shop.'", "0.000000", "0.000000", "0.000000", "0.000000", "0.00", "1", "1", "0000-00-00")');
            }
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'stock_available` (`id_stock_available`, `id_product`, `id_product_attribute`, `id_shop`, `id_shop_group`, `quantity`, `depends_on_stock`, `out_of_stock`) VALUES ("", "'.(int)$id_product.'", "'.(int)$created_id_product_attribute.'", "'.(int)$id_shop.'", "0", "10000", "0", "1")');
        }
    } else {
        $sql = 'UPDATE `'._DB_PREFIX_.'reviver_rental_template_assigned` SET `id_template`="'.(int)$id_template.'" WHERE `id_product`='.(int)$id_product;
        $query_result = Db::getInstance()->ExecuteS($sql);

        $sql2='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name` = "id_attribute" ORDER BY `name` DESC LIMIT 1 ';
        $query_result2 = Db::getInstance()->ExecuteS($sql2);
        foreach ($query_result2 as $row2) {
            $id_attribute = $row2['value'];
        }

        $found = "no";

        $sql3='SELECT * FROM `'._DB_PREFIX_.'product_attribute` WHERE `id_product`='.(int)$id_product;
        $query_result3 = Db::getInstance()->ExecuteS($sql3);
        foreach ($query_result3 as $row3) {
            $id_product_attribute = $row3['id_product_attribute'];

            $sql4='SELECT * FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE `id_product_attribute`='.(int)$id_product_attribute;
            $query_result3 = Db::getInstance()->ExecuteS($sql3);
            foreach ($query_result3 as $row3) {
                $id_attribute_check = $row3['id_attribute'];
                if ($id_attribute == $id_attribute_check) {
                    $found = "yes";
                }
            }
        }

        if ($found == "yes") {
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute` (`id_product_attribute`, `id_product`, `reference`, `supplier_reference`, `location`, `ean13`, `upc`, `wholesale_price`, `price`, `ecotax`, `quantity`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES ("", "'.(int)$id_product.'", "", "", "", "", "", "0.000000", "0.000000", "0.000000", "1000", "0.000000", "0.00", "1", "1", "0.00")');
            $created_id_product_attribute = Db::getInstance()->Insert_ID();
            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_combination` (`id_attribute`, `id_product_attribute`) VALUES ("'.(int)$id_attribute.'", "'.(int)$created_id_product_attribute.'")');
            $allshop = 'SELECT * FROM `'._DB_PREFIX_.'shop` WHERE `active` = 1';
            foreach (Db::getInstance()->executeS($allshop) as $singleshop) {
                $id_shop = $singleshop['id_shop'];

                // PS 1.6.0.6 db change
                $ps_16_att_change = (int)(version_compare(_PS_VERSION_, '1.6.0.5'));
                if ($ps_16_att_change > 0) {
                    Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_shop` (`id_product`, `id_product_attribute`, `id_shop`, `wholesale_price`, `price`, `ecotax`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES ("'.(int)$id_product.'", "'.(int)$created_id_product_attribute.'", "'.(int)$id_shop.'", "0.000000", "0.000000", "0.000000", "0.000000", "0.000000", "1", "1", "0000-00-00")');
                } else {
                    Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'product_attribute_shop` (`id_product_attribute`, `id_shop`, `wholesale_price`, `price`, `ecotax`, `weight`, `unit_price_impact`, `default_on`, `minimal_quantity`, `available_date`) VALUES ("'.(int)$created_id_product_attribute.'", "'.(int)$id_shop.'", "0.000000", "0.000000", "0.000000", "0.000000", "0.00", "1", "1", "0000-00-00")');
                }
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'stock_available` (`id_stock_available`, `id_product`, `id_product_attribute`, `id_shop`, `id_shop_group`, `quantity`, `depends_on_stock`, `out_of_stock`) VALUES ("", "'.(int)$id_product.'", "'.(int)$created_id_product_attribute.'", "'.(int)$id_shop.'", "0", "1000", "0", "1")');
            }
        }
    }

} else {
    $sql2='SELECT * FROM `'._DB_PREFIX_.'reviver_rental_options` WHERE `name` = "id_attribute" ORDER BY `name` DESC LIMIT 1 ';
    $query_result2 = Db::getInstance()->ExecuteS($sql2);
    foreach ($query_result2 as $row2) {
        $id_attribute = $row2['value'];
    }

    $sql4='SELECT * FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE `id_attribute`='.(int)$id_attribute;
    $query_result4 = Db::getInstance()->ExecuteS($sql4);
    foreach ($query_result4 as $row4) {
        $id_product_attribute = $row4['id_product_attribute'];

        $sql3='SELECT * FROM `'._DB_PREFIX_.'product_attribute` WHERE `id_product_attribute`='.(int)$id_product_attribute;
        $query_result3 = Db::getInstance()->ExecuteS($sql3);
        foreach ($query_result3 as $row3) {
            $id_product_check = $row3['id_product'];
            if ($id_product == $id_product_check) {
                $id_product_att_real = $id_product_attribute;
            }
        }
    }

    Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute` WHERE `id_product_attribute`='.(int)$id_product_att_real);
    Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_combination` WHERE `id_product_attribute`='.(int)$id_product_att_real);
    Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'product_attribute_shop` WHERE `id_product_attribute`='.(int)$id_product_att_real);
    Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'stock_available` WHERE `id_product_attribute`='.(int)$id_product_att_real);
    Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'reviver_rental_template_assigned` WHERE `id_product`='.(int)$id_product);
}
