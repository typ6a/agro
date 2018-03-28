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
        header('Content-type: text/html; charset=utf-8');
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

// CREATE CSV ARRAY =================================================
        $csv = [];
        $max_properties_count = 0;
        $csv_separator = ';';
 	
        $map = [
            // 'name' => 'Название_позиции',
            // 'price' => 'Цена',
            // 'description' => 'Описание',
            // 'image' => 'Ссылка_изображения',

			'model'                   => 'Код_товара', //Код товара (артикул) необходим для быстрого и удобного поиска нужной позиции на сайте компании и в личном кабинете при телефонном обращении клиента. Длина артикула ― 25 символов (цифры, кириллица, латиница, знаки «-», «_», «.», «/» и пробел).
			'name'                    => 'Название_позиции',
			'categoryName'            => 'Ключевые_слова',	//Ключевые слова через запятую, длина каждого слова не больше 50 символов. По этим словам будет автоматически определен подраздел каталога, в который будет помещен данный товар. При явном указании адреса подраздела в поле category_url, товар будет помещен в указанный подраздел каталога, не анализируя по ключевым словам.
			'description'             => 'Описание',	
			'productType'             => 'Тип_товара',//Поле определяет принадлежность товара к оптовым, розничным или услугам. Для правильного определения рекомендуется задавать название категории и принадлежность товара. продается только в розницу w ― товар продается только оптом u ― товар продается оптом и в розницу s ― услуга
			'price'                   => 'Цена',	
			'currency'                => 'Валюта',	
			'measureUnit'             => 'Единица_измерения',	
			'minOrderVolume'          => 'Минимальный_объем_заказа',	
			'wholesalePrice'          => 'Оптовая_цена',	
			'minOrderVolumeWholesale' => 'Минимальный_заказ_опт',	
			'imageLink'               => 'Ссылка_изображения',	
			'availability'            => 'Наличие',	//Наличие товара на складе "+" — есть в наличии "-" — нет в наличи "@" — услуга цифра, например "9" — кол-во дней на доставку, если товар под заказ Важно! При пустом поле статус вашего товара станет "Нет в наличии".
			'groupNumber'             => 'Номер_группы',//Позволяет помещать товар в конкретную группу у вас на сайте.	
			'subdivisionAddress'      => 'Адрес_подраздела', //	Адрес подраздела каталога. Например, для раздела “Ноутбуки”: http://prom.ua/Noutbuki. Для удобства вы можете воспользоваться поиском или рубрикатором и выбрать конечный раздел товара.
			'deliveryPossibility'     => 'Возможность_поставки',//Возможные объемы поставок	
			'deliveryTime'            => 'Срок_поставки',	
			'packagingMethod'         => 'Способ_упаковки',	
			'unicIdentifier'          => 'Уникальный_идентификатор',	
			'productIdentifier'       => 'Идентификатор_товара',	
			'subdivisionIdentifier'   => 'Идентификатор_подраздела',	
			'groupIdentifier'         => 'Идентификатор_группы',	
			'manufacturer'            => 'Производитель',	
			'warranty'                => 'Гарантийный_срок',	
			'countryManufacturer'     => 'Страна_производитель',	
			'discount'                => 'Скидка',	
			'varietyGroupsID'         => 'ID_группы_разновидностей'
        ];




        $map2 = [
            'Название_Характеристики',
            'Измерение_Характеристики',
            'Значение_Характеристики',
        ];
// CREATE CSV ARRAY =================================================

        // pre($products);
        foreach ($products as $pkey => $product) {
            $productUrl  = $product['url'];
            $productUrl = ' http://bellson-shop.com.ua/all_products/led-lampa-t8-s-datchikom-dvizenia.html';
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
                // $html = htmlspecialchars_decode($html);
            }
            $this->crawler = new Crawler($html);
            $price       = trim($this->crawler->filter('.wrap-col .wrap-item-price')->text());
            $price       = stristr($price, 'Цена');            
            $price = str_replace('Цена:' , '' , $price);
            $price = trim(str_replace(' грн.' , '' , $price));
            $price = preg_replace('/\s+/', '', $price);
            $price = preg_replace("/[^0-9,.]/", "", $price);
            // pre(gettype($price),1);
            $price = $price/27.4;

            $descriptionItems = $this->crawler->filter('.right #tab-description')->text();
			$description = stristr(trim($descriptionItems), "Будем рады", true);

//			pre($description);

			// $description = preg_replace("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u","", $description);

			$description = str_replace(';', '' , $description);
			$description = str_replace(array("\r", "\n"), ".", $description);
			$description = str_replace("....", "", $description);
			$description = str_replace("\t", "", $description);
			$description = str_replace("\f", "", $description);
			$description = str_replace("\v", "", $description);
			$description = str_replace("\s", "", $description);
			$description = trim($description);
			$description = trim($description);
			//$description = trim($description, "\r");
			//$description = trim($description, "\n");
//pre($description,1);

            // $warranty = trim($this->crawler->filter('.d-t-warranty #warranty-link')->text());
            // $warranty = str_replace("i", '', $warranty);
            $warranty = 36;

            
            // exit('END');

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
            
// pre($product['warranty'],1);
            $image = [];
            $imageUrl = $this->crawler->filter('.product-info .left .image a')->attr('href');
// pre($imageUrl);
            // $imageFileName = $product['name'];
            $imageFileName = strrchr($imageUrl, '//');
            $imageFilePath = '../storage/imagesBellson' . $imageFileName;
            if(!file_exists($imageFileName)){
                file_put_contents($imageFilePath, file_get_contents($imageUrl));
            }
            $product['image'] = [
            'imageUrl' => $imageUrl,
            'imageFile' => $imageFilePath,
            ];
pre($product['image']);
            $product['properties'] = $properties;


	
			$product['price']                   = $price;
			$product['description']             = $description;
			$product['warranty']                = $warranty;
			$product['currency']                = 'usd';
			$product['measureUnit']             = 'шт.';
			$product['minOrderVolume']          = '';
			$product['wholesalePrice']          = $price;
			$product['minOrderVolumeWholesale'] = '10';
			$product['imageLink']               = $product['image']['imageUrl'];
			$product['availability']            = '+';
			$product['groupNumber']             = '';
			$product['subdivisionAddress']      = '';//http://prom.ua/Lampochki
			$product['deliveryPossibility']     = '';
			$product['deliveryTime']            = '';
			$product['packagingMethod']         = '';
			$product['productIdentifier']       = '';
			$product['subdivisionIdentifier']   = '';//Техническая информация об идентификаторе подраздела. Не рекомендуется менять этот параметр никогда. Этот параметр заполняется автоматически при экспорте данных из нашей системы.
			$product['groupIdentifier']         = '';//Техническая информация об идентификаторе группы товаров в вашей системе. Указывает на категорию товара/услуги на внешнем сайте вашей компании или в другой системе (например, в Яндекс.Маркет). Используется в системе импорта из XML и YML файлов. Это поле будет использоваться при выгрузке данных из каталога портала и импорте обратно в вашу систему.
			$product['manufacturer']            = 'Bellson';
			$product['countryManufacturer']     = '';
			$product['varietyGroupsID']         = '';//Идентификатор разновидности товара. Все товары с одним и тем же номером "ID_группы_разновидностей" и заполненными полями "Название_Характеристики" и "Значение_Характеристики" считаются разновидностями основного товара. Основной товар при этом имеет тот же номер "ID_группы_разновидностей", что и разновидности, но поля "Название_Характеристики" и "Значение_Характеристики" у основного товара не заполнены.
			$product['keyWords']                = $productCategory . ' светодиодное освещение, светодиодный, светодиодная';
			$product['productType']             = 'u';
			$product['discount']                = '';
			$product['unicIdentifier']          = '';//Уникальный идентификатор товара ― служебная информация, которая используется для идентификации товара или услуги исключительно на нашем портале. Данный идентификатор заполняется автоматически при экспорте и не должен изменяться вручную. Значение уникального идентификатора ― число, которое можно найти в ссылке на товар на портале или на сайте компании.


// CREATE CSV ARRAY =================================================

            $max_properties_count = count($product['properties']);
            $csv_row = []; // reset row
            foreach($map as $ourKey => $promUaKey){
                if($ourKey === 'image'){
                    $csv_row[] = isset($product[$ourKey]['imageUrl']) ? $product[$ourKey]['imageUrl'] : '';
                }else{
                    $csv_row[] = $product[$ourKey];
                }
                
            }

            foreach($product['properties'] as $property){
                $csv_row[] = $property['propertyName'];
                $csv_row[] = '';
                $csv_row[] = $property['propertyValue'];
            }
            
            $csv[] = join($csv_separator, $csv_row);

            // if($pkey >= 5){
            //     break;
            // }
        }

        $csv_header = array_values($map);

        $i = 0;
        while($i < $max_properties_count){
            $csv_header = array_merge($csv_header, $map2);
            $i++;
        }
        
        array_unshift($csv, join($csv_separator, $csv_header));

// CREATE CSV ARRAY ================================================= END
        
        $fp = fopen('../storage/promUa.csv', 'w');
        // foreach ($csv)
	    fputcsv($fp, $csv, "\n");
		fclose($fp);
		pre('+++');


    }
}