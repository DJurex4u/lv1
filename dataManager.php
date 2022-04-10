<?php

include 'simple_html_dom.php';
include 'diplomskiRadovi.php';

class dataManager
{
    private $url;
    private $data;
    private $htmlParser;
    private $diplomskiRadovi;

    public function __construct($url)
    {
        $this->url = $url;
        $this->htmlParser = new simple_html_dom();
        $this->diplomskiRadovi = new DiplomskiRadovi();
    }

    public function fetchData()
    {
        //$data = [];
        
        //Pokrećemo cURL spoj
        $curl = curl_init($this->url);
        //Zaustavi ako se dogodi pogrešk
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        //Dozvoli preusmjeravanja
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        //Spremi vraćene podatke u varijablu
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //Postavi timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        // //Izvrši transakciju
        $webpageString = curl_exec($curl);
        curl_close($curl);      

        $this->parseData($webpageString);
    }

    private function parseData($webpageString)
    {      
        $oibs = [];
        $hrefs = [];
        $titles = [];
        $texts = [];
        $count = 0;

        $html = $this->htmlParser->load($webpageString);
        
        foreach($html->find('img') as $img) {  
            if (strpos($img, "logos") !== false) {    
            $urlToBeParsed = $img->src;
            $urlExploded = explode('/', $urlToBeParsed);
            $oibDotPng = end($urlExploded);
            $oib = current(explode('.', $oibDotPng));
            array_push($oibs, $oib);
            }  
        }

        foreach($html->find('a') as $element)
        {
            if($count > 26 and $count < 51)
            {
                if(!in_array($element->href, $hrefs))
                {
                    array_push($hrefs, $element->href);        
                    array_push($titles, $element->plaintext);       
                }
            }
            $count = $count + 1;
        }      

        foreach($hrefs as $href)
        {
            //Pokrećemo cURL spoj
            $curl = curl_init($href);
            //Zaustavi ako se dogodi pogrešk
            curl_setopt($curl, CURLOPT_FAILONERROR, 1);
            //Dozvoli preusmjeravanja
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            //Spremi vraćene podatke u varijablu
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            //Postavi timeout
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);
            // //Izvrši transakciju
            $result = curl_exec($curl);
            curl_close($curl);
        
            $html->load($result);
        
            foreach($html->find("div.post-content") as $div)
            {
                $paragraphs = array();
                $tekst = '';
        
                foreach($div->find('p') as $paragraph)
                {
                    array_push($paragraphs, strip_tags($paragraph->innertext));                     
                }
        
                $tekst = implode('<br>', $paragraphs);            
                array_push($texts, $tekst);  
            }
             
        }    

        for ($i = 0; $i < count($hrefs); $i++) 
        {
            $this->diplomskiRadovi->create($titles[$i], $texts[$i], $hrefs[$i], $oibs[$i]);
            $this->diplomskiRadovi->save();         
        }
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getDiplomskiRadovi()
    {
        return $this->diplomskiRadovi;
    }        
}
