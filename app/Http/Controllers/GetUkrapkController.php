<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class GetUkrapkController extends Controller
{
    public function execute()
    {
        header('Content-type: text/html; charset=utf-8');
        $ads = $this->parseAds();
        $contacts = $this->parseContacts($ads);
    }

    protected function parseAds()
    {
    	$categoriesUrls = [
        'http://ukrapk.com/board/grain', //зерновые
        'http://ukrapk.com/board/beans',
        'http://ukrapk.com/board/oilseeds'];
    	foreach ($categoriesUrls as $categoryUrl) {
    		for ($page = 20; $page<=35700; $page+=20){
                $pageUrl = $categoryUrl . '?page=' . $page;
                // $pageFileName = preg_replace('#^http://#', '', $categoryUrl);
                // $pageFileName = preg_replace('\/', '_', $categoryUrl);
                $pageFileName = md5($pageUrl);
                // pre($pageFileName,1);
                $htmlFilePath = '../storage/agro/' . $pageFileName . $page . '.html';
                if (!file_exists($htmlFilePath)){
                    $html = file_get_contents($pageUrl);
                    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                    file_put_contents($htmlFilePath, $html);
                    usleep(500000);
                }
                else {
                    $html = file_get_contents($htmlFilePath);
                    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                    
                }
                // pre($htmlFilePath);
                $this->crawler = new Crawler($html);
                $items = $this->crawler->filter('div .pro5');
                $items->each(function (Crawler $ad) use (&$ads){
					$url          = $ad->filter('div .title_pro a')->attr('href');
					$url             = 'http://ukrapk.com' . $url;
					// pre($url);
					$title           = $ad->filter('div .title_pro a')->text();
					$adPreviewText   = $ad->filter('.text_pro')->text();
					if ((
						stristr($title, 'продам') or 
						stristr($adPreviewText, 'продам') or 
						stristr($title, 'продаю') or 
						stristr($adPreviewText, 'продаю') or 
						stristr($title, 'продажа') or 
						stristr($adPreviewText, 'продажа')
						) and 
						(
						!stristr($title, 'семена') or 
						!stristr($adPreviewText, 'семена') or 
						!stristr($title, 'насін') or 
						!stristr($adPreviewText, 'насін')
						) 
						) 
					{
					// pre($title);
					// pre($url);
						$ads[]           = [
						'url'            => $url,
						'title'          => $title,
						'adPreviewText'  => $adPreviewText,
                    	];
					}
                });
            }

            
        }
        // pre($ads,1);
        return $ads;
    }//end parseAds()

    protected function parseContacts($ads)
    {
        $phones = [];
        foreach ($ads as $ad) {
             $adUrl  = $ad['url'];
             $adPageFileName = md5($adUrl);
                // pre($pageFileName,1);
                $adHtmlFilePath = '../storage/agro/ads/' . $adPageFileName . '.html';
                if (!file_exists($adHtmlFilePath)){
                    $html = file_get_contents($adUrl);
                    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                    file_put_contents($adHtmlFilePath, $html);
                    pre($adHtmlFilePath);
                    usleep(500000);
                }
                else {
                    $html = file_get_contents($adHtmlFilePath);
                    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                    
                }

                $this->crawler = new Crawler($html);
                $items = $this->crawler->filter('div div.ob');
                $items->each(function (Crawler $ad) use (&$phones, $adUrl){
                    $adText     = $ad->text();
                    $adText     = trim($adText);
                    $phone      = stristr ($adText , 'Телефон: ');
                    $phone      = trim(str_replace('Телефон: ', '', $phone));
                    if (    !stristr($adText, 'диспетчер') 
                        and !stristr($adText, 'гібрид')
                        and !stristr($adText, 'насін')
                        )  
                    {
                        $phones[] = [
                            // 'adUrl' => $adUrl,
                            // 'phone' => 
                        $phone,
                            // 'text' =>  $adText
                        ];
                        
                    }
                });
                        $row = array_unique($phones);



            $fp = fopen('../storage/ukrapk2.csv', 'w');
            // foreach ($csv)
            foreach ($row as $fields) 
             {
                fputcsv($fp, $fields, "\n");
             }
            
            fclose($fp);
         
        }

            pre('end123');
       
    }
}
