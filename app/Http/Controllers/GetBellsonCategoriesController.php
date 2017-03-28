<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class GetBellsonCategoriesController extends Controller
{
    protected $base_url = 'http://bellson-shop.com.ua/';

    //protected $base_url = null;

    public function execute()
    {
       
        $categories = $this->parseCategories();
        foreach ($categories as $category) {
        	 // pre($category,1);
            $this->parseProducts($category);
        }
        // pre("$categories",1);
        
    }

    protected function parseCategories()
    {
    	$categories = [];
    	$base_url = 'http://bellson-shop.com.ua';
        $htmlFilePath = '../storage/categories.html';
        if (!file_exists($htmlFilePath)){
	        $html = file_get_contents($base_url);
	        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
	        file_put_contents($htmlFilePath, $html);
        }
        else {
        	$html = file_get_contents($htmlFilePath);
	        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        }
        $this->crawler = new Crawler($html);
        $items = $this->crawler->filter('.box .centerbox .XDCategoryGroupsBlocks.autoSpacing a.menu-cat-link');
		if ($items->count()) {
            $items->each(function (Crawler $category) use (&$categories){
                $url = $category->attr('href');
                $name = $category->text();
                // pre($name,1);
                $categories[] = [
                    'url' => $url,
                    'name' => $name
                ];
            });
        }
// pre($categories,1);
        return $categories;
 	}
// 
 	protected function parseProducts($category)
 	{
 		$products = [];
 		$base_url = 'http://bellson-shop.com.ua/LED_lamps/?page=';
 		for ($page = 1; $page <=2; $page++){
	 		$htmlFilePath = '../storage/'. $category['name'] . ' page ' . $page . '.html';
	 		// pre($htmlFilePath,1);
	        if (!file_exists($htmlFilePath)){
		        $html = file_get_contents($base_url . $page);
		        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
		        file_put_contents($htmlFilePath, $html);
	        }
	        else {
	        	$html = file_get_contents($htmlFilePath);
		        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
	        }
	        $products[] = [
	        	'category' => $category['name'],
	        	'categoryPage' => $page,
	         ];

	        $this->crawler = new Crawler($html);
	        $items = $this->crawler->filter('.product-grid div .product');
			if ($items->count()) {
	            $items->each(function (Crawler $product) use (&$products){
					$url   = $product->filter('.imagestik a')->attr('href');
					$name  = $product->filter('.name')->text();
					$model = $product->filter('.model')->text();
					if ($product->filter('#pcr .price') != null){
						$oldPrice = trim($product->filter('#pcr .price')->text());
						$newPrice = trim($product->filter('#pcr .price')->text());
					}
					elseif ($product->filter('#pcr .price')->text()) {
						$oldPrice = trim($product->filter('#pcr .price .price-old')->text());
						$newPrice = trim($product->filter('#pcr .price .price-new')->text());
					}
	                $products[] = [
	                    'url' => $url,
	                    'name' => $name,
	                    'model' => $model,
	                    'oldPrice' => $oldPrice,
	                    'newPrice' => $newPrice,
	                ];
	            });
	        }
        }
					pre($products);
        return $products;
 		
 	}
        
        //pre($propertyAddress, 1);
    

   
    protected function parsePropertySummary()
    {
        $propertySummary = [];
        //$propertyExtra = null;
        $propertyBeds = null;
        $propertyBedsItems = $this->crawler->filter('.summarybar.summarybar--property .col-xs-3.summarybar-item span')->eq(0);
        if ($propertyBedsItems->count()) {
            foreach ($propertyBedsItems as $propertyBeds) {
                $propertyBeds = $propertyBeds->nodeValue;
            }
            $propertyBeds = trim($propertyBeds);
        }

        $propertyBaths = null;
        $propertyBathsItems = $this->crawler->filter('.summarybar.summarybar--property .col-xs-3.summarybar-item span')->eq(1);
        if ($propertyBathsItems->count()) {
            foreach ($propertyBathsItems as $propertyBaths) {
                $propertyBaths = $propertyBaths->nodeValue;
            }
            $propertyBaths = trim($propertyBaths);
        }

        $propertySize = null;
        $propertySizeItems = $this->crawler->filter('.summarybar.summarybar--property .col-xs-3.summarybar-item span')->eq(2);
        if ($propertySizeItems->count()) {
            foreach ($propertySizeItems as $propertySize) {
                $propertySize = $propertySize->nodeValue;
            }
            $propertySize = trim($propertySize);
        }

        $propertyType = null;
        $propertyTypeItems = $this->crawler->filter('.summarybar.summarybar--property .col-xs-3.summarybar-item span')->eq(3);
        if ($propertyTypeItems->count()) {
            foreach ($propertyTypeItems as $propertyType) {
                $propertyType = $propertyType->nodeValue;
            }
            $propertyType = trim($propertyType);
        }

        $propertySummary = [
            'beds' => $propertyBeds,
            'baths' => $propertyBaths,
            'size' => $propertySize,
            'type' => $propertyType,
        ];
        //pre($propertySummary,1);
        return $propertySummary;
    }


    protected function parsePropertyListingID()
    {
        $propertyListingIDs = $this->crawler->filter('.propertyheader-secondary.propertyheader-piped_list li')->last();
        foreach ($propertyListingIDs as $propertyListingID) {
            $propertyListingID = $propertyListingID->nodeValue;
        }
        $propertyListingID = str_replace('Listing ID:', '', preg_replace('|\s+|', ' ', trim($propertyListingID)));
        return $propertyListingID;
    }


    protected function parsePropertyDescription()
    {
        $description = [];
        $propertyDescriptionTitles = $this->crawler->filter('.propertydetails-description_title');
        foreach ($propertyDescriptionTitles as $propertyDescriptionTitle) {
            $propertyDescriptionTitle = $propertyDescriptionTitle->nodeValue;
        }
        $propertyDescriptionTitle = trim($propertyDescriptionTitle);

        $propertyDescriptions = $this->crawler->filter('.propertydetails-description div[itemprop="description"]');
        foreach ($propertyDescriptions as $propertyDescription) {
            $propertyDescription = $propertyDescription->nodeValue;
        }
        $propertyDescription = trim($propertyDescription);
        $description = [
            'propertyDescriptionTitle' => $propertyDescriptionTitle,
            'propertyDescription' => $propertyDescription
        ];

        return $description;

    }


    protected function parsePropertyPrice()
    {
        $items = $this->crawler->filter('.propertyheader-price');
        $price = null;
        foreach ($items as $price) {
            $price = $price->nodeValue;
        }
        $price = trim(preg_replace('/[^\d]/', '', $price));
        //pre($price, 1);
        return $price;
    }


    protected function parsePropertyOverview()
    {
        $features = [];
        $featureRows = $this->crawler->filter('.contenttable tbody tr');
        if ($featureRows->count()) {
            $featureRows->each(function (Crawler $featureRow) use (&$features) {
                $featureName = null;
                $featureValue = null;
                $featureNameEl = $featureRow->filter('th');
                if ($featureNameEl->count()) {
                    $featureName = trim($featureNameEl->text());
                }
                $featureValueEl = $featureRow->filter('td');
                if ($featureValueEl->count()) {
                    $featureValue = trim($featureValueEl->text());
                    //pre($featureValue);
                }
                if ($featureName && $featureValue) {
                    $features[] = [
                        'name' => $featureName,
                        'value' => $featureValue
                    ];
                }
            });

        }
        return $features;
    }

    protected function parsePropertyImages()
    {
        $images = [];
        $items = $this->crawler->filter('.galleria-thumbnails-container .galleria-thumbnails-list .galleria-thumbnails .galleria-image img');
        //pre($items->count(),1);
        if ($items->count()) {
            $items->each(function (Crawler $image) {
                $filename = null;
                $url = $image->attr('src');
                $imageUrl = 'http://' . trim($url, '/');
                $filename = md5($url) . '.jpg';
                $images[] = [
                    'url' => $imageUrl,
                    'filename' => $filename
                ];
                //pre($images->url,1);
                $filepath = 'd:\workspace\crep\public\data\images\\' . $filename;
                $data = file_get_contents($url);
                file_put_contents($filepath, $data);
            });
        }
        //exit('adsad');
        return $images;
    }

    protected function parsePropertyPictures()
    {
        $images = [];
        $items = $this->crawler->filter('.galleria-thumbnails-container  img');// do not works!!!!!!!!!!!!suka
        //pre($items->count(),1);
        if ($items->count()) {
            $items->each(function (Crawler $image) {
                $filename = null;
                $url = $image->attr('src');
                $imageUrl = 'http://' . trim($url, '/');
                $filename = md5($url) . '.jpg';
                $images[] = [
                    'url' => $imageUrl,
                    'filename' => $filename
                ];
                //pre($images->url,1);
                $filepath = 'd:\workspace\crep\public\data\images\\' . $filename;
                $data = file_get_contents($url);
                file_put_contents($filepath, $data);
            });
        }
        //exit('adsad');
        return $images;
    }



    protected function parsePropertyRealtors()
    {
        $realtorInfo = [];
        $realtorCells = $this->crawler->filter('.box--contact .row.contact');
        //pre($realtorCells->count(),1);
        if ($realtorCells->count()) {
            $realtorCells->each(function (Crawler $realtorCell) use (&$realtorInfo) {

                $images =[];
                $realtorPhones=[];

                $realtorPicture = $realtorCell->filter('.contact-logos img');
                //pre ($realtorPicture->count(), 1);
                if ($realtorPicture->count()) {

                        $filename = null;
                        $url = $realtorPicture->attr('src');
                        $imageUrl = 'http://' . trim($url, '/');
                        $filename = md5($url) . '.jpg';
                        $images[] = [
                            'url' => $imageUrl,
                            'filename' => $filename,
                        ];
                        //pre($images->url,1);
                        $filepath = 'd:\workspace\crep\public\data\images\\' . $filename;
                        $data = file_get_contents($url);
                        file_put_contents($filepath, $data);

                }



                $realtorOfficeTitleEl = $realtorCell->filter('.contact-office_name');
                if ($realtorOfficeTitleEl->count()) {
                    $realtorOfficeTitle = trim($realtorOfficeTitleEl->text());
                }


                $realtorCellPhones = $realtorCell->filter('.contact-phonenumbers dd > a');
                //pre($realtorCellPhones->count(),1);
                if ($realtorCellPhones->count()) {
                    $realtorCellPhones->each(function (Crawler $span) use (&$realtorPhones) {
                        $realtorPhones[] = trim($span->text());
                    });
                }



                $realtorNameEl = $realtorCell->filter('.contact-agent_name > a');
                if ($realtorNameEl->count()) {
                    $realtorName = trim($realtorNameEl->text());
                }

                $realtorInfo[] = [
                    'images' => $images,
                    'realtorOfficeTitle' => $realtorOfficeTitle,
                    'realtorPhones' => $realtorPhones,
                    'realtorName' => $realtorName,


                ];

            });

        }
        pre($realtorInfo,1);
        return $realtorInfo;
    }


}