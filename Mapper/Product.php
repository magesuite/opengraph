<?php

namespace MageSuite\Opengraph\Mapper;

class Product implements MapperInterface
{
    protected $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItems($product)
    {
        $productData = array_filter($product->getData());

        $tags = [];
        foreach($this->items as $tagName => $item)
        {
            $tagValue = null;

            if($item['type'] == 'value'){
                $tagValue = $item['value'];
            }elseif ($item['type'] == 'attribute' and $product->hasData($item['attribute'])){
                $tagValue = $this->getAttributeValue($product, $item);
            }elseif($item['type'] == 'class'){
                $data = $item['parameter'] == 'object' ? $product : $productData;
                $tagValue = $item['class']->getTagValue($data);
            }

            if(empty($tagValue)){
                continue;
            }

            if(is_array($tagValue)){
                foreach($tagValue as $key => $value){
                    $name = $tagName . ':' . $key;
                    $tags[$name] = $value;
                }
            }else{
                $tags[$tagName] = $tagValue;
            }
        }

        return $tags;
    }

    protected function getAttributeValue($product, $item)
    {
        if($item['attribute_type'] == 'select'){
            return $product->getAttributeText($item['attribute']);
        }else{
            return $product->getData($item['attribute']);
        }
    }
}
