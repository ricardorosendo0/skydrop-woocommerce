<?php

class ProductsTags
{
    public static function getProductsTags($package)
    {
        $products = [];
        foreach($package['contents'] as $key => $val) {
            $product_tags = get_the_terms($val['product_id'], 'product_tag');
            $tags = [];
            foreach($product_tags as $tag) {
                $tags[] = $tag->name;
            }
            $products[$val['product_id']]['tags'] = $tags;
        }
        return $products;
    }
}
