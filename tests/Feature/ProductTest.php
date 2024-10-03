<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase; // Veritabanını her testten önce sıfırlamak için

    /** @test */
    public function it_can_create_a_product()
    {
        $productData = [
            'name' => 'Test Product',
            'price' => 100.00,
        ];

        $response = $this->post('/products', $productData); // Ürün ekleme isteği yap

        $response->assertRedirect('/products'); // Yönlendirme kontrolü
        $this->assertDatabaseHas('products', $productData); // Veritabanında ürünün var olup olmadığını kontrol et
    }

    /** @test */
    public function it_requires_name_and_price()
    {
        $response = $this->post('/products', []); // Eksik veri ile ürün ekleme isteği yap

        $response->assertSessionHasErrors(['name', 'price']); // Hatalı alanları kontrol et
    }

    /** @test */
    public function test_it_can_list_products()
    {
        // Test verisi oluştur
        $productData1 = [
            'name' => 'Product 1',
            'price' => 50.00,
        ];

        $productData2 = [
            'name' => 'Product 2',
            'price' => 100.00,
        ];

        // Ürünleri veritabanına ekleyelim
        \App\Models\Product::create($productData1);
        \App\Models\Product::create($productData2);

        // Ürün listeleme isteği yap
        $response = $this->get('/products');

        // Başarılı yanıt kontrolü
        $response->assertStatus(200);

        // Sayfada ürün adlarını kontrol et
        $response->assertSee('Product 1');
        $response->assertSee('Product 2');
    }

    /** @test */
    public function test_it_can_update_a_product()
    {
        // Eski ürün verisi
        $product = \App\Models\Product::create([
            'name' => 'Old Product',
            'price' => 100.00
        ]);

        // Güncellenmiş veri
        $updatedData = [
            'name' => 'Updated Product',
            'price' => 150.00,
        ];

        // Ürün güncelleme isteği
        $response = $this->put('/products/' . $product->id, $updatedData);

        // Yönlendirme kontrolü
        $response->assertRedirect('/products');

        // Veritabanında güncellenmiş veriyi kontrol et
        $this->assertDatabaseHas('products', $updatedData);
    }


    /** @test */
    public function test_it_can_delete_a_product()
    {
        // Silinecek ürün verisi
        $product = \App\Models\Product::create([
            'name' => 'Product to delete',
            'price' => 50.00
        ]);

        // Ürün silme isteği
        $response = $this->delete('/products/' . $product->id);

        // Yönlendirme kontrolü
        $response->assertRedirect('/products');

        // Veritabanında ürünün olmadığını kontrol et
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }


    /** @test */
    public function test_it_requires_name_and_price_when_updating()
    {
        // Geçerli ürün verisi
        $product = \App\Models\Product::create([
            'name' => 'Valid Product',
            'price' => 100.00
        ]);

        // Geçersiz veri ile JSON isteği yaparak güncelleme isteği
        $response = $this->putJson('/products/' . $product->id, []);

        // 422 durum kodu, doğrulama hatası
        $response->assertStatus(422);

        // Yanıtın içinde doğrulama hatalarının olup olmadığını kontrol et
        $response->assertJsonValidationErrors(['name', 'price']);

        // Veritabanında ürünün değişmediğini kontrol et
        $this->assertDatabaseHas('products', ['name' => 'Valid Product', 'price' => 100.00]);
    }


    
    /** @test */
    public function it_requires_valid_price_when_creating()
    {
        $productData = [
            'name' => 'Test Product',
            'price' => 'not_a_number', // Geçersiz fiyat
        ];

        $response = $this->post('/products', $productData); // Ürün ekleme isteği yap

        $response->assertSessionHasErrors(['price']); // Hatalı alanı kontrol et
    }





}
