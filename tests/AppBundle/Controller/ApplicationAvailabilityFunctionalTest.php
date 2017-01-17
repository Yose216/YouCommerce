<?php
// tests/AppBundle/ApplicationAvailabilityFunctionalTest.php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful('200'));


    }

    public function urlProvider()
    {
        return array(
			array('/'),
            array('/categorie'),
            array('/categorie/1'),
            array('/products/5'),
            array('/products'),
        );
    }
}



///new/categorie
///edit/categorie/{id}
///delete/categorie/{id}


///new/categorie/{id}/products
///edit/products/{id}
///edit/products/{id}
///delete/products/{id}
