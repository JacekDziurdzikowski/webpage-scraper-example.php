<?php

namespace App;

use PHPHtmlParser\Dom;

class OptionsWebpageParser
{
    const SOURCE_STRATEGY = [
        'https://wltest.dns-systems.net/' => 'fromDnsSystems'
    ];

    public function parseFromUrl($url): string
    {
        if (empty(self::SOURCE_STRATEGY[$url])) {
            throw new \Exception('Url mapping not found.');
        }

        $function = self::SOURCE_STRATEGY[$url];
        return $this->{$function}($url);
    }

    private function fromDnsSystems(string $url): string
    {
        $content = file_get_contents($url);
        $document = (new DOM)->loadStr($content);
        $products = $document->find('.package');
        $json = [];

        foreach ($products as $i => $product) {

            $title = trim(strip_tags($product->find('.header')->innerHtml));
            $description = $product->find('.package-name')->innerHtml;
            $price = $product->find('.package-price .price-big')->innerHtml;
            $discount = $product->find('.package-price p[style*="color: red"]');

            preg_match('/[$€£](\d+.\d{1,2})/', $price, $matches);
            $price = $matches[1];

            $json[$i]['discount'] = null;
            if (count($discount)) {
                preg_match('/[$€£](\d+.\d{1,2})/', $discount->innerHtml, $matches);
                $discount = $matches[1];
                $json[$i]['discount'] = $discount;
            }

            $json[$i]['title'] = $title;
            $json[$i]['description'] = $description;
            $json[$i]['price'] = $price;
        }

        // sort
        usort($json, fn ($i, $j) => ($i['price'] > $j['price']) ? -1 : 1);

        $json = json_encode($json, JSON_PRETTY_PRINT);
        return $json;
    }
}