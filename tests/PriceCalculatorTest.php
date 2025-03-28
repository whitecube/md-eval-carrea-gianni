<?php declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase; // Use Laravel's TestCase
use App\Services\PriceCalculator;
use App\Transactions\Clients\ClientType;
use Brick\Money\Money;

final class PriceCalculatorTest extends TestCase
{
    private PriceCalculator $priceCalculator;

    protected function setUp(): void
    {
        parent::setUp(); // Call parent setup to bootstrap Laravel
        $this->priceCalculator = new PriceCalculator();
    }

    public function testNormalClientNoDiscountOrMargin(): void
    {
        $product = [
            'price_selling' => Money::of(100, 'USD'),
            'price_acquisition' => Money::of(80, 'USD'),
            'category' => (object)['slug' => 'general'],
            'quantity' => 1,
        ];
        $clientType = ClientType::Normal; // Correct usage of enum

        $result = $this->priceCalculator->calculateFinalPrice($product, $clientType);

        $this->assertEquals(Money::of(100, 'USD'), $result['basePrice']);
        $this->assertEquals(Money::of(100, 'USD'), $result['discountedPrice']);
        $this->assertFalse($result['hasMargin']);
        $this->assertNull($result['discountLabel']);
    }

    public function testVipClient10PercentDiscount(): void
    {
        $product = [
            'price_selling' => Money::of(200, 'USD'),
            'price_acquisition' => Money::of(150, 'USD'),
            'category' => (object)['slug' => 'general'],
            'quantity' => 1,
        ];
        $clientType = ClientType::Vip; // Correct usage of enum

        $result = $this->priceCalculator->calculateFinalPrice($product, $clientType);

        $this->assertEquals(Money::of(200, 'USD'), $result['basePrice']);
        $this->assertEquals(Money::of(180, 'USD'), $result['discountedPrice']);
        $this->assertFalse($result['hasMargin']);
        $this->assertEquals('10% VIP Discount', $result['discountLabel']);
    }

    public function testWholesalerClient30PercentMargin(): void
    {
        $product = [
            'price_selling' => Money::of(200, 'USD'),
            'price_acquisition' => Money::of(150, 'USD'),
            'category' => (object)['slug' => 'general'],
            'quantity' => 1,
        ];
        $clientType = ClientType::Wholesaler; // Correct usage of enum

        $result = $this->priceCalculator->calculateFinalPrice($product, $clientType);

        $this->assertEquals(Money::of(195, 'USD'), $result['basePrice']);
        $this->assertEquals(Money::of(195, 'USD'), $result['discountedPrice']);
        $this->assertTrue($result['hasMargin']);
        $this->assertNull($result['discountLabel']);
    }

    public function testFrozenCategory5PercentDiscount(): void
    {
        $product = [
            'price_selling' => Money::of(100, 'USD'),
            'price_acquisition' => Money::of(80, 'USD'),
            'category' => (object)['slug' => 'surgeles'],
            'quantity' => 1,
        ];
        $clientType = ClientType::Normal; // Correct usage of enum

        $result = $this->priceCalculator->calculateFinalPrice($product, $clientType);

        $this->assertEquals(Money::of(100, 'USD'), $result['basePrice']);
        $this->assertEquals(Money::of(95, 'USD'), $result['discountedPrice']);
        $this->assertFalse($result['hasMargin']);
        $this->assertEquals('5% Frozen Products Discount', $result['discountLabel']);
    }

    public function testPromotionsCategory15PercentDiscount(): void
    {
        $product = [
            'price_selling' => Money::of(100, 'USD'),
            'price_acquisition' => Money::of(80, 'USD'),
            'category' => (object)['slug' => 'promotions'],
            'quantity' => 1,
        ];
        $clientType = ClientType::Normal; // Correct usage of enum

        $result = $this->priceCalculator->calculateFinalPrice($product, $clientType);

        $this->assertEquals(Money::of(100, 'USD'), $result['basePrice']);
        $this->assertEquals(Money::of(85, 'USD'), $result['discountedPrice']);
        $this->assertFalse($result['hasMargin']);
        $this->assertEquals('15% Promotions Discount', $result['discountLabel']);
    }
}