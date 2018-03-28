<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class getTripoliFermersController extends Controller
{
    public function execute()
    {
        header('Content-type: text/html; charset=utf-8');
        $fermers = $this->parseFermersUrls();
        $contacts = $this->parseContacts($fermers);
    }

    protected function parseFermersUrls()
    {	
    	$fermers = [];
    	$mainUrl = 'https://tripoli.land';
    	for ($page = 1; $page<=2144; $page++){
            $pageUrl = $mainUrl . '/farmers?page=' . $page;
            $htmlFilePath = '../storage/tripoli/' . 'page' . $page . '.html';
            if (!file_exists($htmlFilePath)){
                $html = file_get_contents($pageUrl);
                usleep(10000);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                file_put_contents($htmlFilePath, $html);
            }
            else {
                $html = file_get_contents($htmlFilePath);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
            }
            $this->crawler = new Crawler($html);
            $items = $this->crawler->filter('.tripoli.ftable tbody .ng-cloak');
            $items->each(function (Crawler $fermer) use (&$fermers, $mainUrl){
                $url        = $fermer->filter('td div')->attr('data-url');
                $url        = trim($mainUrl . $url);
                // pre($url);
                $name       = $fermer->filter('td span')->text();
                // pre($name);
                // $address    = $fermer->filter('.ng-click p')->text();
                // pre($address,1);
         

                $fermers[] = [
                'url'          => $url,
                'name'         => $name,
                // 'address' 	   => $address,
                ];
            // pre($fermers,1);
            });
        }

    	
    	
        // pre($fermers,1);
        return $fermers;
    }//end parseAds()

    protected function parseContacts($fermers)
    {
    	$fermersInfo = [];
    	foreach ($fermers as $fermer) {
    		$fermerUrl = $fermer['url'];
    		// pre($fermerUrl);
    		$fermerName = $fermer['name'];
    		// $fermerAddress = $fermer['address'];
    		$fermerPageFileName = md5($fermerUrl);
            // pre($pageFileName,1);
            $fermerHtmlFilePath = '../storage/tripoli/fermers/' . $fermerPageFileName . '.html';
                // pre($fermerHtmlFilePath);
            if (!file_exists($fermerHtmlFilePath)){
                $html = file_get_contents($fermerUrl);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                file_put_contents($fermerHtmlFilePath, $html);
                usleep(10000);
            }
            else {
                $html = file_get_contents($fermerHtmlFilePath);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                    
            }

            $this->crawler = new Crawler($html);
                $items = $this->crawler->filter('.org-show-table');
                $items->each(function (Crawler $fermer) use (&$fermersInfo, $fermerUrl){
                    $text     = $fermer->text();
                    preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $text, $fermerEmail);
                    $fermerEmail = implode("", $fermerEmail[0]);
                    if(stristr($fermerEmail, '@')){
                    // $email = implode("", $email[0]);
                    $email = trim($fermerEmail);
                    $email = mb_strtolower($fermerEmail);
                    // pre($email);


                    	// $fermerEmail = trim($fermerEmail[0]);
                    	$fermersInfo[] = [
                    		$email,
                    	];
                	}
                   
            });
        	// $row = array_unique($fermersInfo);
        }
            $fp = fopen('../storage/tripoli/tripoliFermers1.csv', 'w');
            // foreach ($csv)
            foreach ($fermersInfo as $fields) 
             {
                fputcsv($fp, $fields, "\n");
             }
            
            fclose($fp);
            // pre($fermersInfo,1);
    pre($fermersInfo);
    return($fermersInfo);
    }
    
}
