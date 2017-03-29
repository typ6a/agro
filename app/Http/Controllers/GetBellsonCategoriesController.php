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
        $products = $this->parseProducts($categories);
        
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
 	protected function parseProducts($categories)
 	{
 		$products = [];
        // pre($categories,1);
        foreach ($categories as $category) {
            $categoryUrl = $category['url'];
            $categoryName = $category['name'];
            for ($page = 1; $page<=4; $page++){
                $pageUrl = $categoryUrl . '?page=' . $page;
                $htmlFilePath = '../storage/' . $categoryName . $page . '.html'; 
                if (!file_exists($htmlFilePath)){
                    $html = file_get_contents($pageUrl);
                    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                    file_put_contents($htmlFilePath, $html);
                }
                else {
                    $html = file_get_contents($htmlFilePath);
                    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                }
                $this->crawler = new Crawler($html);
                $items = $this->crawler->filter('.product-grid div .product');
                $items->each(function (Crawler $product) use (&$products, $categoryName){
                    $url        = $product->filter('.imagestik a')->attr('href');
                    $name       = $product->filter('.name')->text();
                    $model      = $product->filter('.model')->text();
                    $products[] = [
                        'url'          => $url,
                        'name'         => $name,
                        'model'        => str_replace ('модель ', '', $model),
                        'categoryName' => $categoryName,
                    ];
                });
            }
        }

                 // pre($products);
        foreach ($products as $product) {
            $productUrl  = $product['url'];
            $productName = $product['name'];
            $productCategory = $product['categoryName'];
            $htmlFilePath = '../storage/' . md5($productCategory) . md5($productName) . '.html'; 
            // pre($htmlFilePath);
            if (!file_exists($htmlFilePath)){
                    $html = file_get_contents($productUrl);
                    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                    file_put_contents($htmlFilePath, $html);
                }
                else {
                    $html = file_get_contents($htmlFilePath);
                    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                }
                $this->crawler = new Crawler($html);
                $price       = trim($this->crawler->filter('.wrap-col .wrap-item-price')->text());
                $price       = stristr($price, 'Цена');

                $price = str_replace ('Цена' , '' , $price);
                $price = trim(str_replace (' грн.' , '' , $price));
                $description = trim($this->crawler->filter('.right #tab-description p')->siblings()->text());
                

                $properties = [];
		        $propertyRows = $this->crawler->filter('#tab-attribute .attribute tbody tr');
		        // pre($propertyRows,1);

		        if ($propertyRows->count()) {
		            $propertyRows->each(function (Crawler $propertyRow) use (&$properties) {
		                $propertyName = trim($propertyRow->filter('td:first-child')->text());
		                $propertyValue = trim($propertyRow->filter('td:last-child')->text());   
		                $properties[] = [
								'propertyName'  => $propertyName,
								'propertyValue' => $propertyValue,
		                    ];
		                
		            });
		        }
		        $product['price'] = $price;
		        $product['properties'] = $properties;

		        $image = [];
		        $imageUrl = $this->crawler->filter('.product-info .left .image a')->attr('href');
		        $imageFileName = $product['name'];
		        $imageFilePath = '../storage/imagesBellson/' . md5($imageFileName) . '.jpg';
		        if(!file_exists($imageFileName)){
		        	file_put_contents($imageFilePath, file_get_contents($imageUrl));
		        }
		        $product['image'] = [
		        	'imageUrl' => $imageUrl,
		        	'imageFile' => $imageFilePath,
		        ];
		        // pre($image);
                // pre($imageUrl);
                pre($product);
                // pre($product['url'] . $description . '<hr>');
        }
		       // pre($products);
        
        //pre($propertyAddress, 1);
    

   }
    


