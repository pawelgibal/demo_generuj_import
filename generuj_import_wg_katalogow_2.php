
<?php 

/****  PRZY ZMIANIE RODZAJU PRODUKTU PAMIETAJ O ZMIANIE ! 
 * - KATALOGU GRAFIKI
 * - NAZWY PRODUKTU
 * - Nazwy w opisie !
 * - prefixu SKU !!!
 * - CENA
 * - WAGA
 */

$sciezka_pliki='../galerie_test/import_17_03_2015_naszywki_gry_cz';
$zrodlo = "../galleries/gry/naszywki/";

$lcz=0;
$plik_importu=fopen($sciezka_pliki.$lcz.".csv", "w+"); 

$pref_sku= "nasz_";
$grafiki_zrodlo='http://wwwwww/galleries/gry/naszywki/';

$scizka_kat='16,90'; 

$naglowek="Nazwa^KOD^AKTYWNY^KATEGORIE^CENA NETTO^ID_podatku^Producent_wykonawca^WAGA^ILOSC^Krotki_OPIS^ADRESY_grafik^kasuj_grafiki\n";

fputs($plik_importu, $naglowek); 

$i=0;

$dirss=Array();

if ($handle = opendir($zrodlo)) {

while (false !== ($dir = readdir($handle))) {  // dla kazdego KATALOGU ! NIE dla . i .. ! ponizej WARUNEK !
	if ((is_dir("$zrodlo/$dir")) and ($dir<>".") and ($dir<>"..")) {   // jezeli element katalogu nie jest katalogiem) tylko dla glownej struktury

	$i=$i+1;
	$tablica_dir[$i] = $dir;
	// echo($tablica_dir[$i]."-katalog dysk<br>");                                  		 
	 }
	else {
		// echo($zrodlo.$dir."-NIE jestem katalogiem<br>");
	}
} 
}// koniec do czytania katalogu while // wcztanie nazw katalgow ze zrodla wszystkie
// lista kapel do pominiecia przy imporcie
$zakazane = array ('avatar','bal-sagoth','behemoth','bruce_dickinson','arkona','acdc','ac_dc','doom');

// TUTAJ wyczyszczenie z nazw/kapeli zakazanych
$tablica_dir=array_diff($tablica_dir,$zakazane);

if ($tablica_dir !== NULL) { 	       // domyslne sortowanie tablicy
		sort($tablica_dir);
		 }    
                 
// print_r($tablica_dir);
// wczytanie juz tylko poprawnych kapeli
 for($l = 0; $l < sizeof($tablica_dir); ++$l) { 
      // dla kazdego poprawnego katalogu czytaj jego zawartosc !
                                            $zrodlo2=$zrodlo.$tablica_dir[$l];
                                             if ($handle2 = opendir($zrodlo2)) {
                                                while (false !== ($file = readdir($handle2))) {  // dla kazdego KATALOGU 
                                                	if(is_file($zrodlo.$tablica_dir[$l]."/".$file)) {   // kezeli element katalogu nie jest katalogiem) tylko dla glownej struktury                                                                                                    	
                                                           $tablica[] = array ($file,$tablica_dir[$l]);                     
                                                        }
                                                          else {
                                                    	// echo($zrodlo.$tablica_dir[$l]."/".$file."-NIE jestem plikiem<br>");
							//echo($zrodlo.$tablica_dir[$i]."/".$file."-NIE jestem plikiem<br>");
							}
                                                } // koniec do czytania katalogu while

                                             }
     
 }


if ($tablica !== NULL) { 	       // domyslne sortowanie tablicy
		sort($tablica);
		}
                 

$lp=0;


// OD TEGO MOMENTU wszystkie dane w tablicy
for($k = 0; $k < sizeof($tablica); ++$k) { // petla na tablicy  z nazwami plikow
	    //dzielenie pliku na czesci
	    $lp=$lp+1;
      
            if ($lp==200) {  // co ile DZIELIC plik na kolejna czesc
                fclose($plik_importu);
                $lcz=$lcz+1;
                echo $lcz.('<-Licznik czesci plikow<br>');
                $plik_importu=fopen($sciezka_pliki.$lcz.".csv", "w+"); 
                fputs($plik_importu, $naglowek); 
                $lp=0;
            }       
	
	$nazw_kap_brud=substr($tablica[$k][0],0,-4); //wczytanie nazwy pliku do zmiany,obciecie rozszerzenia
		
	$dl=strlen($nazw_kap_brud); 
	$numer= substr  ($nazw_kap_brud,$dl-3,3); // pobiera typu  XXL, aXL,aaM
	if (substr($numer,0,1)<>'_')  // jezeli na 3 od konca nie ma _ to znaczy ze jest 3 cyfry
	   {  $numer=$numer;
		//$nazw_kap_brud=substr($nazw_kap_brud,0,-1); // obciecie zbednego _
		$nazw_kap_czy=substr($nazw_kap_brud,0,-4);
	    }
       		else   { 
			$numer=substr($numer,1,2); // obciecie pierwszego _
			$nazw_kap_czy=substr($nazw_kap_brud,0,-3);
 			}
              // w tym miejscu nazwa kapeli $nazw_kap_czy:  30_seconds          
         $nazwa_podkatalogu=$tablica[$k][1].'/';     // tutaj nazwa katalogu przypisna do nazwy pliku          
              // tutaj FILTR nazw do zmian
                    switch ($nazw_kap_czy) {
		case "avril":
			echo "avril_lavigne";
			$nazw_kap_czy="avril_lavigne";
			break;
		case "avenged":
			echo "avenged_sevenfold";
			$nazw_kap_czy="avenged_sevenfold";
			break;
		case "elvis":  
			echo "elvis_presley";
			$nazw_kap_czy="elvis_presley";
			break;			
		case "ozzy":
			echo "ozzy_osbourne";
			$nazw_kap_czy="ozzy_osbourne";
			break;
		// ITD
	       
		}
         
        $nazw_kap_czy=str_replace("_"," ",$nazw_kap_czy);// pod nazwa kategorii usuniecie _
        
	$nazwa_prod_bn=ucwords(strtolower($nazw_kap_czy));    // pierwsze litery slow z Duzej Litera
        // Nazwa produktu
        $nazwa_prod='Naszywka '.$nazwa_prod_bn." ".$numer.""; //."";
	fputs($plik_importu,"".$nazwa_prod."^");
        // index - kod
        $pr_sku=str_replace(" ","_",$nazw_kap_brud); //KOD
	//echo ("<br>pr_sku:".$pref_sku.$pr_sku."<br>");
		$kod=$pref_sku.$pr_sku;
		if ((strlen($kod)) > 32) {			
                                $poczatek=substr($nazw_kap_brud,0,19); // max7+1(prefix)_19+1(nazwa)_3(numer)
                                $kod=$pref_sku.$poczatek.'_'.$numer;
                                echo('kod_skrocony:'.$kod.'<br> ');				
			}
				// echo ("<br>kod:".$kod."dl kodu:".$dl_kod."<br>");
	fputs($plik_importu,"".$kod."^");

	
	$aktywny=1;
	fputs($plik_importu,"".$aktywny."^");

	// numery kategorii
	fputs($plik_importu,"".$scizka_kat."^");
	
	$cena=6.504065;  // NETTO !
	fputs($plik_importu,"".$cena."^");
	//id podatku	
	$tax_id=1;
	fputs($plik_importu,"".$tax_id."^");
	
	//$producent_wykonawca=$nazwa_prod_bn;
	//fputs($plik_importu,"".$producent_wykonawca."^");
	fputs($plik_importu,"".$nazwa_prod_bn."^");
        
	$waga="0.01";
	fputs($plik_importu,"".$waga."^");
	
	$ilosc=100;  
	fputs($plik_importu,"".$ilosc."^");

	$krotki_opis='Naszywka '.$nazwa_prod_bn.'<br>Wymiary naszywki: wpisana w kwadrat 10x10cm. Nadruk niespieralny, odporny na kruszenie i ścieranie. Całość usztywniona i obszyta czarną mereżką ułatwiającą przyszycie lub przypięcie do odzieży, torby, plecaka itp.';
        fputs($plik_importu,"".$krotki_opis."^");
        
       // $opis='<img alt="piórnik 3 pogląd" title="piórnik 3 pogląd" src="/images/piornik_3_450.jpg">'  ;
	// fputs($plik_importu,"".$opis."^"); // w opisie glownym tylko grafika pogladowa
	/*$tagi='piórnik '.$nazwa_prod_bn.', szkolny piórnik '.$nazwa_prod_bn.', '.$nazwa_prod_bn.', piórnik';
	fputs($plik_importu,"".$tagi."^"); // te tagi do wyszukiwania w sklepie - ew chmura tagow
	
	$meta_tytul='Piórnik szkolny z motywem '.$nazwa_prod_bn.' ';
	fputs($plik_importu,"".$meta_tytul."^");
	yjgyjy
	$meta_slowa='piórnik '.$nazwa_prod_bn.', piórnik szkolny '.$nazwa_prod_bn.', piórnik, szkolny, '.$nazwa_prod_bn.'';
	fputs($plik_importu,"".$meta_slowa."^");
	
	$meta_opis='Piórnik szkolny z motywem '.$nazwa_prod_bn. ' bardzo dobrej jakości';
	fputs($plik_importu,"".$meta_opis."^"); */
	
	// link do gerafiki
	$grafiki=$grafiki_zrodlo.''.$nazwa_podkatalogu.''.$tablica[$k][0].'';  //ewentuanie pozniej dodatkowy obrazek damskiej      
	fputs($plik_importu,"".$grafiki."^");
	
	//txt_gdy_na_stanie;txt_gdy_brak
	//fputs($plik_importu,"Do 3 dni"."^");
	//fputs($plik_importu,"Chwilowo niedostępny"."^");
	
	// kasowac poprzednie grafiki ?
	fputs($plik_importu,"1"."");
	fputs($plik_importu," \n"); 
} // koniec FOR tablica  
  
                 
// KONIEC
echo('tablica katalogow<br><br>');
// var_dump($tablica_dir);
echo('tablica plikow<br><br>');
 var_dump($tablica);

 
// ZEROWANIE tablicy plikow !!
	unset($tablica);
	unset($tablica_dir);

//echo "<br>\n"; 

fclose ($plik_importu);

?>
