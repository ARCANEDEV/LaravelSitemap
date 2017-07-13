<?php namespace Arcanedev\LaravelSitemap\Tests\Entities;

use Arcanedev\LaravelSitemap\Entities\ChangeFrequency;
use Arcanedev\LaravelSitemap\Tests\TestCase;
use Illuminate\Support\Collection;

/**
 * Class     ChangeFrequencyTest
 *
 * @package  Arcanedev\LaravelSitemap\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ChangeFrequencyTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_get_all_keys()
    {
        $frequencies = ChangeFrequency::keys();

        $this->assertInstanceOf(Collection::class, $frequencies);

        $expected = [
            ChangeFrequency::ALWAYS,
            ChangeFrequency::HOURLY,
            ChangeFrequency::DAILY,
            ChangeFrequency::WEEKLY,
            ChangeFrequency::MONTHLY,
            ChangeFrequency::YEARLY,
            ChangeFrequency::NEVER,
        ];

        $this->assertSame($expected, $frequencies->toArray());
    }

    /** @test */
    public function it_can_get_all_translated_frequencies()
    {
        $frequencies = ChangeFrequency::all();

        $this->assertInstanceOf(Collection::class, $frequencies);

        $expected = [
            'always'  => 'Always',
            'hourly'  => 'Hourly',
            'daily'   => 'Daily',
            'weekly'  => 'Weekly',
            'monthly' => 'Monthly',
            'yearly'  => 'Yearly',
            'never'   => 'Never',
        ];

        $this->assertSame($expected, $frequencies->toArray());
    }

    /** @test */
    public function it_can_get_all_translated_frequencies_with_a_given_locale()
    {
        $frequencies = ChangeFrequency::all('fr');

        $this->assertInstanceOf(Collection::class, $frequencies);

        $expected = [
            'always'  => 'Toujours',
            'hourly'  => 'Une fois par heure',
            'daily'   => 'Quotidien',
            'weekly'  => 'Hebdomadaire',
            'monthly' => 'Mensuel',
            'yearly'  => 'Annuel',
            'never'   => 'Jamais',
        ];

        $this->assertSame($expected, $frequencies->toArray());
    }

    /** @test */
    public function it_can_get_one_translated_frequency()
    {
        $key = ChangeFrequency::ALWAYS;

        $this->assertSame('Always', ChangeFrequency::get($key));

        // With invalid key
        $this->assertNull(ChangeFrequency::get('infinity'));
        $this->assertSame('Fallback', ChangeFrequency::get('infinity', 'Fallback'));

        // With locale
        $this->assertSame('Always', ChangeFrequency::get($key, null, 'en'));
        $this->assertSame('Toujours', ChangeFrequency::get($key, null, 'fr'));

        // With invalid locale
        $this->assertSame('Always', ChangeFrequency::get($key, null, 'ar'));
    }

    /** @test */
    public function it_can_check_if_frequency_exists()
    {
        foreach (ChangeFrequency::keys() as $key) {
            $this->assertTrue(ChangeFrequency::has($key));
        }

        $this->assertFalse(ChangeFrequency::has('infinity'));
    }
}
