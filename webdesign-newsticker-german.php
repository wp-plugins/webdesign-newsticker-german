<?php
/*
Plugin Name: Webdesign Newsticker (german)
Plugin URI: http://www.serian.de/
Description: Konfigurierbarer Webdesign-Newsticker f&uuml;r die Sidebar Deines Blogs. Es werden aktuelle Nachrichten aus dem Bereich Webdesign und Internet angezeigt. Konfigurierbar und anwendbar &uuml;ber ein Widget.
Version: 1.0
Author: Stefan H&ouml;lzlwimmer
Author URI: http://www.serian.de/
License: GPL3
*/

function webdesignnewsgerman()
{
  $options = get_option("widget_webdesignnewsgerman");
  if (!is_array($options)){
    $options = array(
      'title' => 'Webdesign News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://www.online-artikel.de/rss.php?feed&c=21&n=10&nc=200');
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale Länge, auf die ein Titel, falls notwendig, gekürzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // Länge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel länger als die vorher definierte Maximallänge ist,
    // wird er gekürzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_webdesignnewsgerman($args)
{
  extract($args);
  
  $options = get_option("widget_webdesignnewsgerman");
  if (!is_array($options)){
    $options = array(
      'title' => 'Webdesign News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  webdesignnewsgerman();
  echo $after_widget;
}

function webdesignnewsgerman_control()
{
  $options = get_option("widget_webdesignnewsgerman");
  if (!is_array($options)){
    $options = array(
      'title' => 'Webdesign News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['webdesignnewsgerman-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['webdesignnewsgerman-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['webdesignnewsgerman-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['webdesignnewsgerman-CharCount']);
    update_option("widget_webdesignnewsgerman", $options);
  }
?> 
  <p>
    <label for="webdesignnewsgerman-WidgetTitle">Widget Title: </label>
    <input type="text" id="webdesignnewsgerman-WidgetTitle" name="webdesignnewsgerman-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="webdesignnewsgerman-NewsCount">Max. News: </label>
    <input type="text" id="webdesignnewsgerman-NewsCount" name="webdesignnewsgerman-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="webdesignnewsgerman-CharCount">Max. Characters: </label>
    <input type="text" id="webdesignnewsgerman-CharCount" name="webdesignnewsgerman-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="webdesignnewsgerman-Submit"  name="webdesignnewsgerman-Submit" value="1" />
  </p>
  
<?php
}

function webdesignnewsgerman_init()
{
  register_sidebar_widget(__('Webdesign News'), 'widget_webdesignnewsgerman');    
  register_widget_control('Webdesign News', 'webdesignnewsgerman_control', 300, 200);
}
add_action("plugins_loaded", "webdesignnewsgerman_init");
?>