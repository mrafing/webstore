<?php

namespace App\Livewire;

use Livewire\Component;
use App\Data\ProductData;
use App\Data\CartItemData;
use App\Contract\CartServiceInterface;

class AddToCart extends Component
{
    public int $quantity;

    public string $sku;

    public float $price;

    public int $stock;

    public int $weight;

    public string $label = 'Add To Cart';

    public function mount(ProductData $product, CartServiceInterface $cart)
    {
        $this->sku = $product->sku;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->weight = $product->weight;
        $this->quantity = $cart->getItemBySku($product->sku)->quantity ?? 1;
    }

    protected function rules() : array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', "max:{$this->stock}"]
        ];
    }

    public function addToCart(CartServiceInterface $cart)
    {
        $this->validate();
        $cart->addOrUpdate(new CartItemData(
            sku: $this->sku,
            quantity: $this->quantity,
            price: $this->price,
            weight: $this->weight
        ));

        session()->flash('success', 'Product Added To Cart');

        $this->dispatch('cart-updated');

        return redirect()->route('cart');
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
