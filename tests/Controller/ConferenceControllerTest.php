<?php

namespace App\Tests\Contorller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request("GET", "/");

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Give you feedback');
    }
    public function testCommentSubmission(): void
    {
        $client = static::createClient();
        $client->request('GET', '/conference/amsterdam-2019');
        $client->submitForm('Submit', [
            'comment[author]' => 'Fabien',
            'comment[text]' => 'Some feedback from an automated functional test',
            'comment[email]' => 'me@autotest.de',
            'comment[photo]' => dirname(__DIR__, 2) . '/public/images/under-construction.gif',
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $crawler = $client->getCrawler();
        
        $this->assertStringContainsString('There are',$crawler->filter('div')->text());
    }
    public function testConferencePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(2,$crawler->filter('h4'));

        $client->clickLink('View');
         // Получаем новый объект Crawler после того, как страница перезагрузится
    $crawler = $client->getCrawler();

    // Выводим HTML для отладки
    $html = $crawler->html();
    echo $html;  // Это будет выведено в консоль, если вы запускаете тест через командную строку.

        $this->assertPageTitleContains('Amsterdam');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2','Amsterdam 2019');
        $crawler = $client->getCrawler();
        $this->assertStringContainsString('There are ',$crawler->filter('div')->text());
    }

    
}
