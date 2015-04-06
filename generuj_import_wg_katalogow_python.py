#-*- coding: utf-8 -*-
## wersja generatora importu w PYTHON 
import os,string

print os.getcwd()

sciezka_pliki='./import_python_05_04_2015_cz'
zrodlo = "/home/pawel/as_2013/rocktopia/grafika_czerwiec_2014/piorniki_podkatalogi_02/"

lcz=0

plik_importu=open(sciezka_pliki+str(lcz)+".csv", "w")
#plik_importu=open('import_test.csv',"r")
#plik_importu.read()
#for line in plik_importu:
#  print line
#print plik_importu.readlines()
pref_sku= "nasz_"
grafiki_zrodlo='http://bemolik.webd.pl/galleries/gry/naszywki/'

scizka_kat='16,90'
#='/home/pawel/as_2013/rocktopia/prstashop_rocktopia/'
# //$katalog_lokalny=substr($prefiks_katalog_lokalny,0,-1)

naglowek="Nazwa^KOD^AKTYWNY^KATEGORIE^CENA NETTO^ID_podatku^Producent_wykonawca^WAGA^ILOSC^Krotki_OPIS^ADRESY_grafik^kasuj_grafiki\n"
plik_importu.write(naglowek)

lista_katalogow= os.listdir(zrodlo)
lista_katalogow.sort()

  
#tablica zakazane
zakazane=['30_seconds','30_seconds_to_mars','3_inches_of_blood','3_inches','aborted','aion','arakain','angel_dust','arathyr',
    'arch_enemy','artrosis','avatar','bal_sagoth','bal-sagoth','behemoth','bruce_dickinson','bloodbath','cryptopsy','dark_angel','dark_fortress',
    'decapitated','deicide','demolition_hammer','fading_colours','ghost','grave_land','hades','harley_davidson','hunter','iced_earth','kat',
    'katatonia','krisiun','moonspell','nocturnal_rites','opeth','paradise_lost','pink_floyd','possessed','rotting_christ','rolling_stones',
    'sabaton','sentenced','terrorizer','theiron','ulver','unleashed','vader','violetta','fender','bayern','messi','ronaldo','legendary_pink_dots',
    'unknown','arkona','acdc','ac_dc','aerosmith','amy_winehouse','apocalyptica','beatles','black_label_society','blink_182','bullet_for','bullet_for_my',
    'bullet_for_my_valentine','depeche_mode','doors','the_doors','eminem','green_day','guns_n_roses','jimi_hendrix','kanye_west',
    'korn','kurt_cobain','lady_gaga','led_zeppelin','linkin_park','marilyn_manson','megadeth','metallica','michael_jackson',
    'motorhead','nirvana','offspring','pearl_jam','queen','queens_of','queens_of_the_stone_age','queensryche','rage_against_the_machine','rage_against',
    'rammstein','red_hot_chili','red_hot','red_hot_chili_peppers','sepultura','sex_pistols','slayer','slipknot','tokio_hotel','trivium','doom','sixpounder']
#print lista_plikow
#czysta lista -> wczytane - zakazane
#czysta_lista=set(lista_plikow)-set(zakazane)
czysta_lista =[item for item in lista_katalogow if item not in zakazane]

#print lista_plikow
print czysta_lista
i=0
nowa_sciezka=zrodlo+czysta_lista[i]
print nowa_sciezka

## usuniecie z listy ewentualnych nazw plikow, nieistniejacych katalogow
for i in range(len(czysta_lista)): 
  nowa_sciezka=zrodlo+czysta_lista[i]
  if not os.path.isdir(nowa_sciezka):
    czysta_lista.pop(i)
    
#while os.path.isdir(czysta_lista):
print czysta_lista
lista_plikow=[]
lista_razem=[]
nazwa_kap_czysta=''
## dla kazdego katalogu wczytaj liste plikow i dodaj do listy_razem [nazwa_pliku.jpg, nazwa_katalogu]
for i in range(len(czysta_lista)): 
  nowa_sciezka=zrodlo+czysta_lista[i]
#  lista_plikow.append(['a',nowa_sciezka])
#  lista_plikow=lista_plikow+os.listdir(nowa_sciezka)
  lista_plikow=os.listdir(nowa_sciezka)
  for j in range(len(lista_plikow)):
    lista_razem.append([czysta_lista[i],lista_plikow[j]])
    
lista_razem.sort()
lp=0
# dla wszystkich plikow wczytanych z katalogow
for k in range(len(lista_razem)):
  #tworzenie nowego pliku co lp wpisow-linijek
  lp+=1
  if lp==200:
    plik_importu.close()
    lcz+=1
    plik_importu=open(sciezka_pliki+str(lcz)+".csv", "w")
    plik_importu.write(naglowek)
    lp=0
  brudna_nazwa=lista_razem[k][1][0:len(lista_razem[k][1])-4]
#  plik_importu.write(brudna_nazwa+'^')
  
  numer=brudna_nazwa[-3:]
  if numer[0]=='_':
    numer=numer[-2:]
    nazwa_kap_czysta=brudna_nazwa[:-3]
#  elif numer[0]<>'_':
  else:
    nazwa_kap_czysta=brudna_nazwa[:-4]
# numer = 3 ostatnie cyfry tak jak zostal wczytany na poczatku   
  
# poprawiacz nazw kapel
  if nazwa_kap_czysta=='avril':
    nazwa_kap_czysta=='avril_lavigne'
  elif nazwa_kap_czysta=='avenged':
   nazwa_kap_czysta=='avenged_sevenfold'
   
  nazwa_kap_czysta=nazwa_kap_czysta.replace('_',' ')
  nazwa_kap_czysta=nazwa_producent=string.capwords(nazwa_kap_czysta)
  
  nazwa_produktu='Naszywka ' + nazwa_kap_czysta +' '+ numer
  print nazwa_produktu 
  plik_importu.write(nazwa_produktu+'^')
  
  kod=pref_sku+brudna_nazwa
  if len(kod)>32:
    poczatek=brudna_nazwa[0:19]
    kod=pref_sku+poczatek+'_'+numer
  plik_importu.write(kod+'^')    
  
  aktywny='1'
  plik_importu.write(aktywny+'^') 
  
#numery kategorii
  nr_kat='40,41'
  plik_importu.write(nr_kat+'^')
#cena
  cena='6.33'
  plik_importu.write(cena+'^') 
#podatek id 
  tax_id='1'
  plik_importu.write(tax_id+'^') 
#nazwa producenta
  plik_importu.write(nazwa_producent+'^') 
#waga  
  waga='0.01'
  plik_importu.write(waga+'^') 
#ilosc  
  ilosc='100'
  plik_importu.write(ilosc+'^')
  
#krotki Krotki OPIS
  krotki_opis="""Naszywka %s<br>Wymiary naszywki: wpisana w kwadrat 10x10cm. Nadruk niespieralny, odporny na kruszenie i ścieranie. Całość usztywniona i obszyta czarną mereżką ułatwiającą przyszycie lub przypięcie do odzieży, torby, plecaka itp.""" % (nazwa_kap_czysta)
  plik_importu.write(krotki_opis+'^')
#for i in range(len(lista_plikow)):
#  plik=lista_plikow[i]
#  lista_plikow[i]=plik[0:len(lista_plikow[i])-7]
#print lista_razem
#linki do grafiki produktow
  grafiki_produktu=grafiki_zrodlo+lista_razem[k][1]
  plik_importu.write(grafiki_produktu+'^')
# kasowac / nie kasowac poprzednich grafika_czerwiec_2014  
  plik_importu.write('1\n')
plik_importu.close()