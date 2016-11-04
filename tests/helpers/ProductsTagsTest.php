<?php
use PHPUnit\Framework\TestCase;

class ProductsTagsTest extends TestCase
{
    public function testGetProductsTags()
    {
        $json_package = file_get_contents(__DIR__.'/../fixtures/package.json');
        $package = json_decode($json_package, true);
        $products_tags = ProductsTags::getProductsTags($package);
        $expected = [
            61 => array("tags" => ['Skydrop'])
        ];
        $this->assertEquals($products_tags, $expected);
    }
}
