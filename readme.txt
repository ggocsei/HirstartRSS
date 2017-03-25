=== Hírstart Feed ===
Contributors: ggocsei
Tags: RSS, Hirstart.hu
Donate link: http://paypal.me/ggocsei
Requires at least: 3.0
Tested up to: 4.7.3
Stable tag: 1.0
License: GPLv2

RSS Feed a hirstart.hu-hoz

== Description ==
A hirstart.hu aggregátor oldal igényeinek megfelelő speciális RSS feed kategória fordítóval.

https://github.com/ggocsei/HirstartRSS

== Installation ==
Töltsük fel a bővítmény mappáját a WordPress /wp-content/plugins/ mappájába.
A vezérlőpulton a Telepített bővítmények oldalon kapcsoljuk be a kiegészítőt.
A post kategóriáknál beállítható a megfelelő HS kategória ami az RSS feedben fog megjelenni az adott postoknál.
A kategóriáknál kiválaszható hogy a HS kategória helyett az adott kategóriánál a posthoz megadott címkék kerüljenek be. Amennyiben a címke leírásában szerepel a !nohirstart szöveg, úgy az nem kerül megjelenítésre.
A feed elérhető a {odalneve}/feed/hirstart URLen.

== Frequently Asked Questions ==
Q: Hogyan küldhetem el kéréseim, ötleteim, javaslataim a fejlesztőnek?
A: A következő címre küldje el levelét: wphirstartfeed {kukac} gocsei {pont} hu.

Q: Megoldható-e komplex címke-kategória esetleg a sablon saját kiemelései alapján Hírstart kategória összerendelés
A: Igen, kérem keressen az egyedi megoldási javaslatért.


== Changelog ==

= 1.0 =
Tags handling
Category order method changed (tag, hs category, original category)
Security issue fix

= 0.3 =
Term meta handling fix
HS category / Normal category handling fix
Unnecessary tag request remove

= 0.2 =
GMT fix in template

= 0.1 =
Start