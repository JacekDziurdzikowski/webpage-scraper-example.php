<?php

use App\OptionsWebpageParser;
use PHPUnit\Framework\TestCase;

class TestTest extends TestCase
{
    /** @test */
    public function script_scrapes_given_webpage_for_product_options()
    {
        // arrange
        $parser = new OptionsWebpageParser();

        // act
        $json = $parser->parseFromUrl('https://wltest.dns-systems.net/');

        //assert
        $options = json_decode($json);
        $this->assertNotEmpty($options[0]);

        foreach ($options as $option) {
            $option = (array) $option;
            $this->assertNotEmpty($option['title']);
            $this->assertNotEmpty($option['description']);
            $this->assertNotEmpty($option['price']);
            $this->assertArrayHasKey('discount', $option);
        }
    }
}
