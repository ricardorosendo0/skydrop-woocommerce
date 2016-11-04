<?php
use PHPUnit\Framework\TestCase;

function get_the_terms($product_id, $str)
{
    return array((object)["name" => "Skydrop"]);
}

class ProductsTagsTest extends TestCase
{
    public function testGetProductsTags()
    {
        $json_package = file_get_contents(__DIR__.'/../fixtures/package.json');
        $package = json_decode($json_package, true);
        $products_tags = ProductsTags::getProductsTags($package);
        $actual = [
            61 => array("tags" => ['Skydrop'])
        ];
        $this->assertEquals($products_tags, $actual);
    }
}
