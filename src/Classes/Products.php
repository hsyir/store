<?php

namespace Hsy\Shopy\Classes;

use Hsy\Shopy\Models\Product;
use Hsy\Shopy\Traits\QueriesTrait;

class Products
{
    use QueriesTrait;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->with = [];
        $this->withCount = [];
        $productModel = config('shopy.products.model');
        $this->query = $productModel::query();

        return $this;
    }

    /**
     * @param $data
     * @param Product $product
     *
     * @return Product
     */
    public function store($data, $product = null)
    {
        $productModel = config('shopy.products.model');
        $product = ($product instanceof $productModel) ? $product : new $productModel();

        $product->fill($data);
        $product->save();
        if (isset($data['tags']) and is_array($data['tags'])) {
            $product->attachTags($data['tags']);
        }

        if (request()->hasFile('cover_image')) {
            $product->addMediaFromRequest('cover_image')->toMediaCollection('cover_image');
        }

        return $product;
    }

    /**
     * @param Product $product
     * @param string $requestKey
     * @param string $collection
     *
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function changeCoverImageFromRequest($product, $requestKey = 'cover_image', $collection = 'cover_image')
    {
        $product->addMediaFromRequest($requestKey)->toMediaCollection($collection);
    }


    public function priceGreaterThan($price)
    {
        $this->query = $this->query->where('price', '>=', $price);
        return $this;
    }

    public function priceLessThan($price)
    {
        $this->query = $this->query->where('price', '<=', $price);
        return $this;
    }

    public function priceEqual($price)
    {
        $this->query = $this->query->where('price', '=', $price);
        return $this;
    }


    public function filter($term)
    {
        $this->query = $this->query->where(function ($q) use ($term) {
            return $q->where('name', 'LIKE', "%{$term}%")
                ->orWhere('description', 'LIKE', "%{$term}%");
        });
        return $this;
    }
}
