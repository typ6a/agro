<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class GetBellsonDataController extends Controller
{
    protected $base_url = 'http://bellson-shop.com.ua/LED_lights/?page=1';

    //protected $base_url = null;

    public function execute()
    {
 // pre("asdxxxx",1);
        $url = 'http://www.rew.ca/properties/R2060885/110-15875-20-avenue-surrey?property_browse=surrey-area-bc';
        $html = file_get_contents($url);
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $this->crawler = new Crawler($html);
        //pre($html,1);
        /*
        $doc = new \DOMDocument();
        $doc->loadHTML($html);
        $xpath = new \DOMXPath($doc);
        $items = $xpath->query('*[@id="m_property_dtl_info_hdr_price"]');
        pre($items,1);
        pre($items->length,1);
        */
        //$property = null;

        $product = [
            'productCode'               => $this->parsePropertyPrice(),//Код_товара
            'positionName'              => $this->parsePropertyListingID(),//Название_позиции
            'keyWords'                  => $this->parsePropertyAddress(),//Ключевые_слова
            'description'               => $this->parsePropertyImages(),//Описание
            'productType'               => $this->parsePropertyDescription(),//Тип_товара
            'price'                     => $this->parsePropertySummary(),//Цена
            'currency'                  => $this->parsePropertySummary(),//Валюта
            'discount'                  => $this->parsePropertyOverview(),//Скидка
            'measureUnit'               => $this->parsePropertyRealtors(),//Единица_измерения
            'minimumOrderQquantity'     => $this->parsePropertyRealtors(),//Минимальный_объем_заказа
            'wholesalePrice'            => $this->parsePropertyRealtors(),//Оптовая_цена
            'minimalWholesaleOrder '    => $this->parsePropertyRealtors(),//Минимальный_заказ_опт
            'imageLink'                 => $this->parsePropertyRealtors(),//Ссылка_изображения
            'availability'              => $this->parsePropertyRealtors(),//Наличие
            'manufacturer'              => $this->parsePropertyRealtors(),//Производитель
            'manufacturerCountry'       => $this->parsePropertyRealtors(),//Страна_производитель
            'groupNumber'               => $this->parsePropertyRealtors(),//Номер_группы
            'subdivisionAddress'        => $this->parsePropertyRealtors(),//Адрес_подраздела
            'deliveryPossibility'       => $this->parsePropertyRealtors(),//Возможность_поставки
            'packagingMethod'           => $this->parsePropertyRealtors(),//Срок_поставки
            'productIdentifier'         => $this->parsePropertyRealtors(),//Идентификатор_товара
            'UniqueIdentifier '         => $this->parsePropertyRealtors(),//Уникальный_идентификатор
            'subdivisionIdentifier'     => $this->parsePropertyRealtors(),//Идентификатор_подраздела
            'groupIdentifier'           => $this->parsePropertyRealtors(),//Идентификатор_группы
            'groupVarietiesID'          => $this->parsePropertyRealtors(),//ID_группы_разновидностей
            'characteristicName'        => $this->parsePropertyRealtors(),//Название_Характеристики
            'characteristicMeasurement' => $this->parsePropertyRealtors(),//Измерение_Характеристики
            'characteristicValue'       => $this->parsePropertyRealtors(),//Значение_Характеристики
            'characteristicName'        => $this->parsePropertyRealtors(),//Название_Характеристики
            'characteristicMeasurement' => $this->parsePropertyRealtors(),//Измерение_Характеристики
            'characteristicValue'       => $this->parsePropertyRealtors(),//Значение_Характеристики
        ];

       

        pre('dine done', 1);
    }

    protected function parsePropertyAddress()
    {
        $propertyAddress = [];
        //$propertyExtra = null;

        $propertyStreetAddresses = $this->crawler->filter('.propertyheader-address span[itemprop="streetAddress"]');
        foreach ($propertyStreetAddresses as $propertyStreetAddress) {
            $propertyStreetAddress = $propertyStreetAddress->nodeValue;
        }
        $propertyStreetAddress = trim($propertyStreetAddress);

        $propertyAddressesLocality = $this->crawler->filter('.propertyheader-secondary.propertyheader-piped_list li span[itemprop="addressLocality"]');
        foreach ($propertyAddressesLocality as $propertyAddressLocality) {
            $propertyAddressLocality = $propertyAddressLocality->nodeValue;
        }
        $propertyAddressLocality = trim($propertyAddressLocality);

        $propertyAddressesRegion = $this->crawler->filter('.propertyheader-secondary.propertyheader-piped_list li span[itemprop="addressRegion"]');
        foreach ($propertyAddressesRegion as $propertyAddressRegion) {
            $propertyAddressRegion = $propertyAddressRegion->nodeValue;
        }
        $propertyAddressRegion = trim($propertyAddressRegion);

        $propertyPostalCodes = $this->crawler->filter('.propertyheader-secondary.propertyheader-piped_list li span[itemprop="postalCode"]');
        foreach ($propertyPostalCodes as $propertyPostalCode) {
            $propertyPostalCode = $propertyPostalCode->nodeValue;
        }
        $propertyPostalCode = trim($propertyPostalCode);

        $propertyExtras = $this->crawler->filter('.propertyheader-secondary.propertyheader-piped_list li')->eq(1);
        foreach ($propertyExtras as $propertyExtra) {
            $propertyExtra = $propertyExtra->nodeValue;
        }
        $propertyExtra = trim($propertyExtra);

        $propertyAddress = [
            'propertyStreetAddress' => $propertyStreetAddress,
            'propertyAddressLocality' => $propertyAddressLocality,
            'propertyAddressRegion' => $propertyAddressRegion,
            'propertyPostalCode' => $propertyPostalCode,
            'propertyExtra' => $propertyExtra
        ];
        //pre($propertyAddress, 1);
        return $propertyAddress;
    }

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