# Cvičenie 1

Na prvom stretnutí sa nám podarilo rozbehať si `php`, `composer` a vytvoriť si nový laravel projekt.

Nový projekt sme pomocou súboru `.env` pripojili na databázu. Vytvorili sme si v aplikácii novú **entitu** a **migráciu**, po ktorej spustení sme si vyskúšali vytváranie záznamov v databáze bez nutnosti použitia SQL. Vytvorili sme si nový **controller** ktorý zabezpečuje prácu s článkami. V controlleri sme si vytvorili **metódu** ktorou články získame a používateľovi zobrazíme **view** obsahujúci tieto články.

## Pripojenie na databázu

Vytvoríme si v MySQL prázdnu databázu.

Databázu vieme pripojiť pomocou nastavení v súbore `.env`. Tu nastavíme **host**, port, **názov databázy**, **používateľa** a jeho **heslo**. Výsledné nastavenie vyzerá nejako takto:

```dotenv
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=adlerka
DB_USERNAME=adlerka
DB_PASSWORD=
```

Pozor, kedykoľvek zmeníme súbor `.env` musíme aj reštartovať laravel server

```shell script
php artisan serve
```

## Tinker a SQL

Aby sme vedeli čo najjednoduchšie overiť či sme k databáze pripojený, použijeme nástroj **tinker**. Tento nástroj nám umožňuje písať php kód do konzoly pričom ho aj automaticky vykonáva. Nástroj spustíme príkazom:

```shell script
php artisan tinker
```

Na začiatku riadku sa nám zobrazí symbol `>>>` ktorý znaží že sme v prostredí tinkeru. Vyjsť z tohto prostredia môžeme klávesovou skratkou `Ctrl C`.

Pre overenie pripojenia na databázu spustíme v tinkeri nasledujúci príkaz:

```php
DB::connection()->getPdo();
```

Ak nám príkaz nevrátil chybu, sme úspešne pripojený a môžeme zatvoriť tinker (`Ctrl C`)

## Model a migrácia

Tabuľky v databáze nevytvárame pomocou PHPMyAdmin ale pomocou laravel **migrácií**, ktoré vieme jednoducho kdekoľvek spustiť a vytvoriť tak štruktúru databázy ktorú si špecifikujeme. Tabuľka je vždy viazaná na **model**, preto aby sme vytvorili prvú tabuľku, musíme najprv vytvoriť prvý model.

Keďže chceme aby sa model vytvoril aj s migráciou, spustíme nasledujúci príkaz:

```shell script
php artisan make:model Article -m
```

Týmto príkazom sa nám v zložke `app` vytvorí súbor `Article.php` reprezentujúci náš model a v zložke `database/migrations` súbor `...._create_articles_table.php` predstavujúci migráciu.

Názov modelu uvádzame vždy v jednotnom čísle. Laravel si z neho automaticky pri vytváraní tabuľky vytvorí množné. Ako môžeme vidieť vyššie model `Article` tabuľka `articles`.

V súbore s migráciou sa nachádzajú 2 funkcie: `up` ktorá sa spúšťa keď spúšťame migrácie a `down` ktorá sa spúšťa keď chceme migrácie vrátiť späť. Inak povedané v prvej tabuľku/stĺpce vytvárame, v druhej ich mažeme.

Funkcia `up` už obsahuje nasledujúci kód:

```php
Schema::create('articles', function (Blueprint $table) { // Názov tabuľky a funkcia na jej vytvorenie
    $table->bigIncrements('id'); // Stĺpec ID autoincrement
    $table->timestamps(); // Stĺpce created_at a updated_at o ktoré sa stará automaticky laravel
});
```

K tomuto kódu si následne pridáme naše vlastné stĺpce `name`, reprezentujúci názov článku a `content` reprezentujúci jeho obsah. Výsledný obsah funkcie `up` vyzerá takto:

```php
Schema::create('articles', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('name'); // Stĺpec name typu string (varchar)
    $table->text('content'); // Stĺpec content typu text
    $table->timestamps();
});
```

Aby sme migrácie dostali do databázy musíme ich spustiť. To urobíme nasledujúcim príkazom:

```shell script
php artisan migrate
```

Následne sa nám v databáze vytvorí tabuľka articles so stĺpcami, ktoré sme si definovali v migrácii. Laravel si automaticky v databáze drží stav spustených migrácií. Po opätovnom spustení predchádzajúceho príkazu sa teda nič nestane.

**Poznámka:** v starších verziách laravelu sa pri migrácii vyskytne chyba hovoriaca o maximálnej dĺžke stĺpcov typu string. Túto vieme opraviť úpravou súboru `AppServiceProvider.php` ktorý sa nachádza v zložke `App/Providers`. Funkciu `boot()` upravíme nasledovne:

```php
public function boot()
{
    Schema::defaultStringLength(191);
}
```

Model a tabuľku v databáze máme hotovú. Môžeme sa pustiť do ukladania údajov.

## Práca s databázou pomocou tinkera

Najjednoduchšie si môžeme vyskúšať prácu s databázou pomocou nástroja **tinker**, ktorý sme už používali. Spustíme si ho.

Aby sme získali všetky články čo máme v databáze, použijeme metódu `all()` nad našim modelom. Do tinkera teda napíšeme príkaz (pozor na lomítko):

```php
App\Article::all()
```

Keďže máme databázu prázdnu, príkaz nám vráti prázdnu kolekciu.

Vytvoríme nový záznam v databáze jednoduchou postupnosťou krokov. Z modelu `Article` vytvoríme objekt ktorý uložíme do premennej:

```php
$article = new App\Article()
```

Následne tomuto objektu nastavíme hodnoty:

```php
$article->name = "Meno článku"
$article->content = "Obsah článku"
```

Článok uložíme zavolaním metódy `save()` nad týmto objektom:

```php
$article->save()
```

Keď teraz opäť načítame všetky články uvidíme pole (Kolekciu) článkov v ktorých sa nachádza náš novo pridaný článok.

Zatvoríme tinker a pokračujeme ďalej.

## Nový controller a view

Aby si naše články mohol pozrieť aj používateľ aplikácie musíme mu ich ukázať. Používateľ vidí `view` ale pracuje s `controllerom`. Preto aby sme mohli získať články, musíme najprv vytvoriť nový controller:

```shell script
php artisan make:controller ArticlesController
```

Príkaz nám vytvoril prázny controller `ArticlesController` v priečinku `app\Http\Controllers`.

V tomto prázdnom controlleri si následne vytvoríme novú funkciu:

```php
function index() { // Funkcia index

    $articles = Article::all(); // Nájdeme všetky články a uložíme ich do premennej articles

    return view('welcome') // Vrátime view s názvom welcome
        ->with(['articles' => $articles]); // Do view pridáme naše články a nazveme ich rovnako articles
}
``` 

Funkcia vracia (`return`) view. Pod view sa rozumie súbor ktorý sa nachádza kdekoľvek v zložke `resources/views`. Do úvodzoviek píšeme iba jeho názov bez prípon `.blade.php`.

Články do view pridáme aj naše článku pomocou `with` kde pošleme pole. Kľúč (v úvodzovkách) reprezentuje názov premennej vo view a hodnota (premenná za šípkou) reprezentuje dáta.

Keď refreshneme stránku nevidíme žiadne zmeny. Preto lebo laravel náš controller a funkciu nevolá. Aby ju zavolal musíme vytvoriť/upraviť routu (cestu URL). Routy sa nachádzajú v priečinku `routes` v súbore `web.php`.

Jeho obsah vyzerá nasledovne:

```php
Route::get('/', function () { // Cesta ktorá sa má použiť
    return view('welcome'); // View ktorý sa má vrátiť
});
```

Aby sme použili náš nový controller musíme zmeniť routu nasledovne:

```php
Route::get('/', 'ArticlesController@index');
```

Pričom druhý parameter obsahuje informáciu `NazovControllera@nazovfunkcie`

Teraz keď skúsime refreshnúť stránku sa stále nič nedeje. Ešte musíme upraviť view.

## Úprava view
Aby sme mohli naše články aj vidieť, musíme upraviť view aby ich zobrazoval. Ako sme si povedali vyššie, view sa nachádza v zložke `resources/views`. Náš view má názov `welcome.blade.php`. Otvoríme si ho.

Obsah view je klasické HTML obsahujúce rozšírenia umožňujúce výpis a prácu s PHP premennými.

Keďže vieme že obsah našej premennej `articles` je pole môžeme ním iterovať napríklad for cyklom. Vytvoríme si teda na začiatku `<body>` nový cyklus:

```blade
...
<body>
    @foreach($articles as $article)

    @endforeach
    ...
</body>
```

Vysvetlenie funkcie: `@foreach($articles as $article)` = každý článok z poľa `$articles` ďalej ako `$article` (takže ďalej pre každý článok použijeme iba premennú `$article`)

V cykle môžeme následne vypísať tie dáta z článku ktoré chceme, teda `name` a `content`. V blade vypisujeme premenné tak, že ich obalíme dvoma zátvorkami: `{{ $article }}`. Vypísať ale môžeme iba jednoduché premenné a nie objekty. Takže článok ako taký vypísať nemôžeme, môžeme vypísať iba jeho vlastnosti:

```blade
...
<body>
    @foreach($articles as $article)
        {{ $article->name }} <br/>
        {{ $article->content }} <br/><br/>
    @endforeach
    ...
</body>
```

Tak a po refreshi stránky vidíme náš článok na vrchu nášho view. Môžeme skúsiť pridať ďalší článok pomocou **tinkera** a overiť či sa nám aj ten zobrazí na stránke.

Link na verziu repozitára po prvom cvičení:
https://github.com/mnagy112/Adlerka/tree/99554ec15ceb81410e32a379792199b6721f4e73
