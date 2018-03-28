<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class GetHorozController extends Controller
{
    protected $base_url = '';
    
    //protected $base_url = null;
    
    public function execute()
    {
        // header('Content-type: text/html; charset=windows-1251');
        header('Content-type: text/html; charset=utf-8');
        $mainCategories = $this->parseMainCategories();
        $categories = $this->parseCategories($mainCategories);
        $products = $this->parseProducts($categories);
        $csv = $this->makeCsv($products);
        
    }
    
   
    protected function parseMainCategories() 
    {
        $mainCategories = [];
        $mainUrl = 'http://horozua.com/';
        $htmlFilePath = '../storage/horoz/' . 'horozua' . '.html';
        if (!file_exists($htmlFilePath)){
                $html = file_get_contents($mainUrl);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                file_put_contents($htmlFilePath, $html);
                usleep(rand(5000000, 10000000));
            }
        else {
            $html = file_get_contents($htmlFilePath);
            $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
            }
        $this->crawler = new Crawler($html);
            $items = $this->crawler->filter('#column-left .box .box-content ul.box-category li');
            $items->each(function (Crawler $mainCategory) use (&$mainCategories)
            {
                $name       = $mainCategory->filter('a')->text();
                $url        = $mainCategory->filter('a')->attr('href');
                $mainCategories[] = [
                'name'         => $name,
                'url'          => $url,
                ];
            });
        return $mainCategories;
        
    }
    protected function parseCategories($mainCategories) 
    {
        $categories = [];
        foreach ($mainCategories as $mainCategory) {
            $mainCategoryName = $mainCategory['name'];
            $mainCategoryUrl = $mainCategory['url'];
            $htmlFilePath = '../storage/horoz/' . $mainCategoryName . '.html';
            // pre($htmlFilePath);
            if (!file_exists($htmlFilePath)){
                $html = file_get_contents($mainCategoryUrl);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                file_put_contents($htmlFilePath, $html);
                usleep(rand(5000000, 10000000));
            }
            else {
                $html = file_get_contents($htmlFilePath);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
            }
            $this->crawler = new Crawler($html);
            $items = $this->crawler->filter('#column-left .box .box-content ul.box-category li ul li');
            if ($items->count()){
                $items->each(function (Crawler $сategory) use (&$categories, $mainCategoryName, $mainCategoryUrl)
                {
                    $name       = $сategory->filter('a')->text();
                    $url        = $сategory->filter('a')->attr('href');
                    $categories[] = [
                    'name'         => $name,
                    'url'          => $url,
                    'mainCategoryName' => $mainCategoryName,
                    'mainCategoryUrl' => $mainCategoryUrl,
                    ];
                });
            }
            else{
                $categories[] = [
                    'name'         => $mainCategoryName,
                    'url'          => $mainCategoryUrl,
                    'mainCategoryName' => $mainCategoryName,
                    'mainCategoryUrl' => $mainCategoryUrl,
                    ];
            }

        }
        // pre($categories);
        return $categories;
                
    }
    protected function parseProducts($categories)
    {
        $products = [];
        foreach ($categories as $category) {
            $mainCategoryName = $category['mainCategoryName'];
            $mainCategoryUrl  = $category['mainCategoryUrl'];
            $categoryName     = $category['name'];
            $categoryUrl      = $category['url'] . '?page=0&limit=1000';
            // pre($categoryUrl,1);
            $htmlFilePath     = '../storage/horoz/' . $categoryName . '.html';
            if (!file_exists($htmlFilePath)){
                $html = file_get_contents($categoryUrl);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                file_put_contents($htmlFilePath, $html);
                // usleep(rand(500000, 1000000));
            }
            else {
                $html = file_get_contents($htmlFilePath);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
            }
            $this->crawler = new Crawler($html);
            $items = $this->crawler->filter('#content .product-list>div');
            if ($items->count()){
                $items->each(function (Crawler $product) use (&$products, $mainCategoryName, $mainCategoryUrl, $categoryName, $categoryUrl)
                {
                    $name       = $product->filter('div.name')->text();
            // pre($name);
                    $url        = $product->filter('div.name a')->attr('href');
                    $products[] = [
                    'name'         => $name,
                    'url'          => $url,
                    'categoryName'     => $categoryName,
                    'categoryUrl'      => $categoryUrl,
                    'mainCategoryName' => $mainCategoryName,
                    'mainCategoryUrl'  => $mainCategoryUrl,
                    ];
                });
            }
        }
        // !!!
        // pre($products);
        // pre('$products',1);
        foreach ($products as $pkey => &$product) {
            $productUrl              = $product['url'];
            $productName             = $product['name'];
            $productMainCategory        = $product['mainCategoryName'];
            $productMainCategoryUrl         = $product['mainCategoryUrl'];
            $productCategory         = $product['categoryName'];
            $productCategoryUrl      = $product['categoryUrl'];
            $htmlFilePath     = '../storage/horoz/products/' . md5($productUrl) . '.html';
            if (!file_exists($htmlFilePath)){
                $html = file_get_contents($productUrl);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                file_put_contents($htmlFilePath, $html);
                // usleep(rand(500000, 1000000));
            }
            else {
                $html = file_get_contents($htmlFilePath);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
            }
            $this->crawler = new Crawler($html);
            
            $price = trim($this->crawler->filter('.price')->text());
            // $price = $productData->filter('.price')->text();
            $price = str_replace('Цена:', '', $price);
            $price = str_replace('грн.', '', $price);
            $price = trim(str_replace(' ' , '' , $price));
            $price = preg_replace('/\s+/', '', $price);
            $price = preg_replace("/[^0-9,.]/", "", $price);
            $price = str_replace('.', ',', $price);
            $price = trim($price);
            // pre($price);
           
            $description = str_replace('енер.', 'енергосберегающая', $product['categoryName'] . '. ' . $product['name']);
            $description = str_replace(';', ',', $description);
            
            $keyWords = str_replace('  ', ' ', $product['categoryName'] . ' ' . $product['name']);
            $keyWords = str_replace(' ', ', ', $keyWords);
            $keyWords = str_replace('енер.', 'енергосберегающая', $keyWords);
            $keyWords = str_replace('с, ', '', $keyWords);
            $keyWords = str_replace('под, ', '', $keyWords);
            $keyWords = str_replace('без, ', '', $keyWords);
            $keyWords = str_replace('в асортименті: ', '', $keyWords);
            $keyWords = str_replace(';', ',', $keyWords);
            
            $manufacturer = trim($this->crawler->filter('.product-info .right .description a')->text());
            
            $warranty = '';
            
            $properties = [];
            $propertyRows = $this->crawler->filter('.attribute tbody tr');
                 // pre($propertyRows,1);
                
            if ($propertyRows->count()) {
                $propertyRows->each(function (Crawler $propertyRow) use (&$properties) {
                    $propertyName = trim($propertyRow->filter('td')->eq(0)->text());
                    $propertyName = str_replace(';', ',', $propertyName);
                    $propertyName = preg_replace('/\s+/', '', $propertyName);
                    $propertyValue = trim($propertyRow->filter('td')->eq(1)->text());
                    $propertyValue = str_replace(';', ',', $propertyValue);
                    $propertyValue = preg_replace('/\s+/', '', $propertyValue);
                    $propertyValue = preg_replace('/\r\n|\r|\n/u', '', $propertyValue);
                    $propertyValue = trim($propertyValue);

                    if ($propertyValue = 'Защита от короткого замыкания,Защита от перегрузки Защита от перегрузки') {
                        $propertyValue = 'Защита от короткого замыкания,Защита от перегрузки';
                    }
                    $properties[] = [
                    'propertyName'  => $propertyName,
                    'propertyValue' => $propertyValue,
                    ];
                    
                });
            }
            // $images = []; // смотри кенгу
            $imageLink = trim($this->crawler->filter('.image a')->attr('href'));
               
            $row          = $this->crawler->filter('.product-info .right .description')->text();
            
            $pN           = stristr ($row, 'Модель: ', FALSE);
            $pN           = stristr ($pN, 'Наличие: ', true);
            $pN           = trim(str_replace('Модель:', '', $pN));
            $pN           = trim(str_replace('-', ' ', $pN));
            $pN = substr($pN, 0, 13);
            // $pN = 'hz' . $pkey . ' ' . $pN;

            if (preg_match("/Есть в наличии/i", $row)) 
            {
                $available    = '+';
            } 
            else {
                $available    = '-';
            }

            
            $product['properties'] = $properties;
    
            $product['model']                   = $pN;
            $product['price']                   = trim($price);
            $product['description']             = $description;
            $product['warranty']                = $warranty;
            $product['currency']                = 'uah';
            $product['measureUnit']             = 'шт.';
            $product['minOrderVolume']          = '';
            $product['wholesalePrice']          = trim($price);
            $product['minOrderVolumeWholesale'] = '10';
            $product['imageLink']               = $imageLink;
            $product['availability']            = $available;
            $product['groupNumber']             = '';
            $product['subdivisionAddress']      = '';//http://prom.ua/Lampochki
            $product['deliveryPossibility']     = '';
            $product['deliveryTime']            = '';
            $product['packagingMethod']         = '';
            $product['productIdentifier']       = $pN;
            $product['subdivisionIdentifier']   = '';//Техническая информация об идентификаторе подраздела. Не рекомендуется менять этот параметр никогда. Этот параметр заполняется автоматически при экспорте данных из нашей системы.
            $product['groupIdentifier']         = '';//Техническая информация об идентификаторе группы товаров в вашей системе. Указывает на категорию товара/услуги на внешнем сайте вашей компании или в другой системе (например, в Яндекс.Маркет). Используется в системе импорта из XML и YML файлов. Это поле будет использоваться при выгрузке данных из каталога портала и импорте обратно в вашу систему.
            $product['manufacturer']            = $manufacturer;
            $product['countryManufacturer']     = '';
            $product['varietyGroupsID']         = '';//Идентификатор разновидности товара. Все товары с одним и тем же номером "ID_группы_разновидностей" и заполненными полями "Название_Характеристики" и "Значение_Характеристики" считаются разновидностями основного товара. Основной товар при этом имеет тот же номер "ID_группы_разновидностей", что и разновидности, но поля "Название_Характеристики" и "Значение_Характеристики" у основного товара не заполнены.
            $product['keyWords']                = $keyWords;
            $product['productType']             = 'u';
            $product['discount']                = '';
            $product['unicIdentifier']          = '';//Уникальный идентификатор товара ― служебная информация, которая используется для идентификации товара или услуги исключительно на нашем портале. Данный идентификатор заполняется автоматически при экспорте и не должен изменяться вручную. Значение уникального идентификатора ― число, которое можно найти в ссылке на товар на портале или на сайте компании.

        }//end foreach
            pre($product['properties']);
        return($products);
    }//end parseProducts() 

    protected function makeCsv($products)
    {
        $csv = [];
        $max_properties_count = 0;
        $csv_separator = ';';
    
        $map = [
            'model'                   => 'Код_товара', //Код товара (артикул) необходим для быстрого и удобного поиска нужной позиции на сайте компании и в личном кабинете при телефонном обращении клиента. Длина артикула ― 25 символов (цифры, кириллица, латиница, знаки «-», «_», «.», «/» и пробел).
            'name'                    => 'Название_позиции',
            'keyWords'                => 'Ключевые_слова',  //Ключевые слова через запятую, длина каждого слова не больше 50 символов. По этим словам будет автоматически определен подраздел каталога, в который будет помещен данный товар. При явном указании адреса подраздела в поле category_url, товар будет помещен в указанный подраздел каталога, не анализируя по ключевым словам.
            'description'             => 'Описание',    
            'productType'             => 'Тип_товара',//Поле определяет принадлежность товара к оптовым, розничным или услугам. Для правильного определения рекомендуется задавать название категории и принадлежность товара. продается только в розницу w ― товар продается только оптом u ― товар продается оптом и в розницу s ― услуга
            'price'                   => 'Цена',    
            'currency'                => 'Валюта',  
            'measureUnit'             => 'Единица_измерения',   
            'minOrderVolume'          => 'Минимальный_объем_заказа',    
            'wholesalePrice'          => 'Оптовая_цена',    
            'minOrderVolumeWholesale' => 'Минимальный_заказ_опт',   
            'imageLink'               => 'Ссылка_изображения',  
            'availability'            => 'Наличие', //Наличие товара на складе "+" — есть в наличии "-" — нет в наличи "@" — услуга цифра, например "9" — кол-во дней на доставку, если товар под заказ Важно! При пустом поле статус вашего товара станет "Нет в наличии".
            'groupNumber'             => 'Номер_группы',//Позволяет помещать товар в конкретную группу у вас на сайте.  
            'subdivisionAddress'      => 'Адрес_подраздела', // Адрес подраздела каталога. Например, для раздела “Ноутбуки”: http://prom.ua/Noutbuki. Для удобства вы можете воспользоваться поиском или рубрикатором и выбрать конечный раздел товара.
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
            $max_properties_count = 0;
        foreach ($products as $pkey => $product) {
        pre($product['url']);
        pre($product['model']);
            $properties_count = count($product['properties']);
        pre($properties_count);
            if($properties_count > $max_properties_count){
                $max_properties_count = $properties_count;

            }
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
        }//end foreach
        $csv_header = array_values($map);
        $i = 0;
        pre($max_properties_count);
        while($i < $max_properties_count){
            $csv_header = array_merge($csv_header, $map2);
            $i++;
        }
        
        array_unshift($csv, join($csv_separator, $csv_header));
        $fp = fopen('../storage/horoz/horoz.csv', 'w');
        // foreach ($csv)
        fputcsv($fp, $csv, "\n");
        fclose($fp);
        pre('+++');
    }   
}