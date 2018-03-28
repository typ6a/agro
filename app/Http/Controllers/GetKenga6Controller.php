<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class GetKenga6Controller extends Controller
{
    protected $base_url = '';
    
    //protected $base_url = null;
    
    public function execute()
    {
        // header('Content-type: text/html; charset=windows-1251');
        header('Content-type: text/html; charset=utf-8');
        // $categories = $this->parseCategories();
        $products = $this->parseProducts();
        
    }
    
   
    protected function parseProducts()
    {
        // pre('test3221',1);
        $products = [];
        $categoryUrl = 'http://kinga.com.ua/index.php?categoryID=600000&offset=';
        $categoryName = 'Домашняя одежда';
        // pre($categoryName,1);
        for ($offset = 0; $offset<=128; $offset+=16){
            $pageUrl = $categoryUrl . $offset;
            $htmlFilePath = '../storage/kenga/' . $categoryName . $offset . '.html';
            if (!file_exists($htmlFilePath)){
                $html = file_get_contents($pageUrl);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                file_put_contents($htmlFilePath, $html);
                usleep(rand(5000000, 10000000));
            }
            else {
                $html = file_get_contents($htmlFilePath);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
            }
            // pre($html,1);
            $this->crawler = new Crawler($html);
            $items = $this->crawler->filter('.main_bg_block.sub_fix_page td[width="50%"]');
            $items->each(function (Crawler $product) use (&$products, $categoryName){
                $url        = 'http://kinga.com.ua/' . $product->filter('a')->attr('href');
                $url        = trim($url);
                $pN         = str_replace('http://kinga.com.ua/product_', '', $url);
                $pN         = trim(str_replace('.html', '', $pN));
            // pre($pN);
                $name       = $product->filter('.name_sm .right_bl_title a')->text();
                // $model      = trim($product->filter('form')->text());;
                // $model      = stristr($model, 'Размер:', true);
                // $model      = trim(str_replace('Модель: ', '', $model));
                $model      = $pN;
                $products[] = [
                'url'          => $url,
                'name'         => $name,
                'model'        => str_replace ('модель ', '', $model),
                'categoryName' => $categoryName,
                ];
            });
        }
        // pre($products);
        

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
// CREATE CSV ARRAY =================================================

        // pre($products);
        foreach ($products as $pkey => $product) {
            // pre($product,1);
            $productUrl  = $product['url'];
            pre($productUrl);
            // $productUrl = ' http://bellson-shop.com.ua/all_products/led-lampa-t8-s-datchikom-dvizenia.html';
            $productName = $product['name'];
            // $productModel = $this->crawler->filter('.sku_wrapper span')->text();
            // pre($productModel . 'model');
            $productCategory = $product['categoryName'];
            $htmlFilePath = '../storage/kenga/products/' . md5($productCategory) . md5($productUrl) . '.html';
            // pre($htmlFilePath);
            if (!file_exists($htmlFilePath)){
                $html = file_get_contents($productUrl);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                file_put_contents($htmlFilePath, $html);
                usleep(rand(5000000, 10000000));
            }
            else {
                $html = file_get_contents($htmlFilePath);
                $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
                // $html = htmlspecialchars_decode($html);
            }
            $this->crawler = new Crawler($html);
            $price       = trim($this->crawler->filter('#optionPrice')->text());
            // $price       = stristr($price, 'Цена');            
            $price = str_replace('Цена:' , '' , $price);
            $price = trim(str_replace('.00' , '' , $price));
            $price = trim(str_replace('грн.' , '' , $price));
            $price = trim(str_replace(' ' , '' , $price));
            $price = preg_replace('/\s+/', '', $price);
            $price = preg_replace("/[^0-9,.]/", "", $price);
            // $price = $price/27.4;
            $price = trim($price);
            pre($price);

//             $description = $this->crawler->filter('.summary.entry-summary .short-description')->text();
//             // $description = stristr(trim($descriptionItems), "Будем рады", true);

// //          pre($description);

//             // $description = preg_replace("/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u","", $description);

//             $description = trim($description);
//             $description = str_replace(';', '' , $description);
//             $description = str_replace(array("\r", "\n"), ". ", $description);
//             // $description = str_replace("....", "", $description);
//             $description = str_replace("\t", "", $description);
//             $description = str_replace("\f", "", $description);
//             $description = str_replace("\v", "", $description);
//             $description = str_replace("\s", "", $description);
//             $description = trim($description);
//             $description = $description . '.';
//             //$description = trim($description, "\r");
            $description = $product['categoryName'] . '. ' . $product['name'];
             pre($description);

            // $warranty = trim($this->crawler->filter('.d-t-warranty #warranty-link')->text());
            // $warranty = str_replace("i", '', $warranty);
            $warranty = '';

            
            // exit('END');

            $properties = [];
            $color = trim($this->crawler->filter('.hdbtop form')->eq(2)->text());
            $color = stristr($color, 'Цвет:');
            $color = preg_replace('/\s+/', '', $color);
            $color = str_replace('Цвет:', '', $color);
            $properties[] = [
                    'propertyName'  => 'Цвет',
                    'propertyValue' => $color,
                    ];

            $sizes = $this->crawler->filter('.hdbtop form')->eq(2);
            $propertyValue = '';
            $propertyName = 'Размер';
                        $sizesBox = $sizes->filter('select option');
                $sizesBox->each(function (Crawler $sizesBox) use (&$properties, &$propertyValue) {
                    $value = $sizesBox->text();
                    $propertyValue = $propertyValue . $value . ', ';
                    
                });
            
            if (!$propertyValue) 
            {
                $size = $sizes->filter('b')->eq(2);
                $value = $size->text();
                $propertyValue = trim($value);
            }
         
           
                $properties[] = [
                'propertyName'  => $propertyName,
                'propertyValue' => $propertyValue,
                ];
            // pre($properties,1);


            $propertyRows = $this->crawler->filter('#tab-additional_information .shop_attributes tr');
             // pre($propertyRows,1);
            
            if ($propertyRows->count()) {
                $propertyRows->each(function (Crawler $propertyRow) use (&$properties) {
                    $propertyName = trim($propertyRow->filter('th')->text());
                    $propertyValue = trim($propertyRow->filter('td')->text());
                    $properties[] = [
                    'propertyName'  => $propertyName,
                    'propertyValue' => $propertyValue,
                    ];
                    
                });
             // pre($properties,1);
            }






          
            
            $manufacturerRow = $this->crawler->filter('.hdbtop form')->eq(2);
            if ($manufacturerRow->count()){
                $manufacturer = $manufacturerRow->text();
                $manufacturer = trim($manufacturer);
                $manufacturer = trim(stristr($manufacturer, 'Модель:', true));
                $manufacturer = str_replace('Марка: ' , '' , $manufacturer);
                // $manufacturer = stristr($manufacturer, 'Размер:', true);
            }
            pre($manufacturer);
            
// pre($product['warranty'],1);
            $images = [];
            $imagesUrl = $this->crawler->filter('.imboxr a');
            $imageLink ='';
            if ($imagesUrl->count()) {
                $imagesUrl->each(function (Crawler $imageUrl) use (&$imageLink) {
                    $imageUrl = $imageUrl->attr('href');
                    $imageUrl = 'http://kinga.com.ua/' . $imageUrl;
                    $imageLink = $imageLink . ',' . $imageUrl;
                    $imageLink = trim($imageLink, ",");
                });
            }
// pre($imageLink,1);                    
            // $imageFileName = $product['name'];
            // $imageFileName = strrchr($imageUrl, '//');
            // $imageFilePath = '../storage/imagesBellson' . $imageFileName;
            // if(!file_exists($imageFileName)){
            //     file_put_contents($imageFilePath, file_get_contents($imageUrl));
            // }
            // $product['image'] = [
            // 'imageUrl' => $imageUrl,
            // // 'imageFile' => $imageFilePath,
            // ];

            $product['properties'] = $properties;
    
            // $product['model']                   = $productModel;
            $product['price']                   = trim($price);
            $product['description']             = $description;
            $product['warranty']                = $warranty;
            $product['currency']                = 'uah';
            $product['measureUnit']             = 'шт.';
            $product['minOrderVolume']          = '';
            $product['wholesalePrice']          = trim($price);
            $product['minOrderVolumeWholesale'] = '10';
            $product['imageLink']               = $imageLink;
            $product['availability']            = '+';
            $product['groupNumber']             = '';
            $product['subdivisionAddress']      = '';//http://prom.ua/Lampochki
            $product['deliveryPossibility']     = '';
            $product['deliveryTime']            = '';
            $product['packagingMethod']         = '';
            $product['productIdentifier']       = $product['model'];
            $product['subdivisionIdentifier']   = '';//Техническая информация об идентификаторе подраздела. Не рекомендуется менять этот параметр никогда. Этот параметр заполняется автоматически при экспорте данных из нашей системы.
            $product['groupIdentifier']         = '';//Техническая информация об идентификаторе группы товаров в вашей системе. Указывает на категорию товара/услуги на внешнем сайте вашей компании или в другой системе (например, в Яндекс.Маркет). Используется в системе импорта из XML и YML файлов. Это поле будет использоваться при выгрузке данных из каталога портала и импорте обратно в вашу систему.
            $product['manufacturer']            = $manufacturer;
            $product['countryManufacturer']     = '';
            $product['varietyGroupsID']         = '';//Идентификатор разновидности товара. Все товары с одним и тем же номером "ID_группы_разновидностей" и заполненными полями "Название_Характеристики" и "Значение_Характеристики" считаются разновидностями основного товара. Основной товар при этом имеет тот же номер "ID_группы_разновидностей", что и разновидности, но поля "Название_Характеристики" и "Значение_Характеристики" у основного товара не заполнены.
            $product['keyWords']                = 'одежда, белье, леггинсы, футболка, футболки, платье, халат, халаты, домашняя одежда';
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
        
        $fp = fopen('../storage/kenga6.csv', 'w');
        // foreach ($csv)
        fputcsv($fp, $csv, "\n");
        fclose($fp);
        pre('+++');


    }
}