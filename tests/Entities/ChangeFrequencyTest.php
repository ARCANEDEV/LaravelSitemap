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

        static::assertInstanceOf(Collection::class, $frequencies);

        $expected = [
            ChangeFrequency::ALWAYS,
            ChangeFrequency::HOURLY,
            ChangeFrequency::DAILY,
            ChangeFrequency::WEEKLY,
            ChangeFrequency::MONTHLY,
            ChangeFrequency::YEARLY,
            ChangeFrequency::NEVER,
        ];

        static::assertSame($expected, $frequencies->toArray());
    }

    /** @test */
    public function it_can_get_all_translated_frequencies()
    {
        $frequencies = ChangeFrequency::all();

        static::assertInstanceOf(Collection::class, $frequencies);

        $expected = [
            'always'  => 'Always',
            'hourly'  => 'Hourly',
            'daily'   => 'Daily',
            'weekly'  => 'Weekly',
            'monthly' => 'Monthly',
            'yearly'  => 'Yearly',
            'never'   => 'Never',
        ];

        static::assertSame($expected, $frequencies->toArray());
    }

    /** @test */
    public function it_can_get_all_translated_frequencies_with_a_given_locale()
    {
        $frequencies = ChangeFrequency::all('fr');

        static::assertInstanceOf(Collection::class, $frequencies);

        $expected = [
            'always'  => 'Toujours',
            'hourly'  => 'Une fois par heure',
            'daily'   => 'Quotidien',
            'weekly'  => 'Hebdomadaire',
            'monthly' => 'Mensuel',
            'yearly'  => 'Annuel',
            'never'   => 'Jamais',
        ];

        static::assertSame($expected, $frequencies->toArray());
    }

    /** @test */
    public function it_can_get_one_translated_frequency()
    {
        $key = ChangeFrequency::ALWAYS;

        static::assertSame('Always', ChangeFrequency::get($key));

        // With invalid key
        static::assertNull(ChangeFrequency::get('infinity'));
        static::assertSame('Fallback', ChangeFrequency::get('infinity', 'Fallback'));

        // With locale
        static::assertSame('Always', ChangeFrequency::get($key, null, 'en'));
        static::assertSame('Toujours', ChangeFrequency::get($key, null, 'fr'));

        // With invalid locale
        static::assertSame('Always', ChangeFrequency::get($key, null, 'ar'));
    }

    /** @test */
    public function it_can_check_if_frequency_exists()
    {
        foreach (ChangeFrequency::keys() as $key) {
            static::assertTrue(ChangeFrequency::has($key));
        }

        static::assertFalse(ChangeFrequency::has('infinity'));
    }
}
