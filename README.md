# Astro Processor
Astroprocessor on PHP library for calculations in Vedic astrology 

# Architecture
<strong>/public/vendor/devhasta</strong> - ядро (PHP) <br>
<br>
<strong>/public/vendor/devhasta/jyotish</strong> - математические астро расчеты <br>
 jyotish/src/Base/Data.php - рачет необходимых параметров <br>
 jyotish/src/Base/Traits/EnvironmentTrait.php<br>
<strong>/public/vendor/devhasta/jyotish-draw</strong> - Рендеринг фронта <br>
 jyotish-draw/src/Plot/Chakra/Renderer.php - Расчеты для отрисовки<br>
 jyotish-draw/src/Renderer/Svg.php - Расчеты для отрисовки всех пармаметров на фронте<br>
 <strong>Итоговая отрисовка</strong> <br>
 astro/public/data/math.php - Отрисовка таблицы со знаками зодиака <br> 
 astro/public/data/draw.php - Итоговая отрисовка натальной карты<br>
  <strong>Для конечного рендеринга используется jQuery </strong> <br>
/public/assets/js/app.js<br>
<br>
<strong>/public/vendor/devhasta/swetest</strong> - Исполняемый бинарник с помощью которого получаем данные из  Swiss Ephemeris о положении планет в определенное время: /../swetest/sweph/swetest <br>
/jyotish/src/Ganita/Method/Sweetest.php - получение данных от бинарника Swiss Ephemeris<br>

 
## System Requirements
 library requires PHP 7.4 or later
 
## Swiss Ephemeris
The Swiss Ephemeris is the high precision ephemeris developed by [Astrodienst](http://www.astro.com/swisseph/swephinfo_e.htm), largely based upon the DExxx ephemerides from NASA's JPL. The original release in 1997 was based on the DE405/406 ephemeris. Since release 2.00 in February 2014, it is based on the DE431 ephemeris released by JPL in September 2013. The source can also be retrieved [here](http://www.astro.com/ftp/swisseph/).
They have two license options - one, the GPL, which only means that any code you write must also be GPL. And second, their license, which is paid.

## License
The files in this library are released under the GNU General Public License version 2 or later.
 [Source code](https://github.com/kunjara/jyotish ).

# Jyotish Draw North(Front-end)
![jyotish-draw-north](https://i.postimg.cc/yxz7vpxj/13-10-22-11-49-18.png)

# Demo
https://karmision.ru/astroprocessor/ 
