<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page while offline.
 *
 * All the available variables are mirrored in html.tpl.php and page.tpl.php.
 * Some may be blank but they are provided for consistency.
 *
 * @see template_preprocess()
 * @see template_preprocess_maintenance_page()
 *
 * @ingroup themeable
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0" dir="<?php print $language->dir; ?>">

<head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <script type="text/javascript">
  WebFontConfig = {
    google: { families: [ 'Droid+Sans:400,700:latin', 'Droid+Sans+Mono::latin' ] }
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
      '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })(); </script>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-30397593-1']);
    _gaq.push(['_trackPageview']);

    (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  </script>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <?php
  $images_url = file_create_url(drupal_get_path('theme', 'arteleku_skin'));
?>
    <header>
    <div id="bloque-tools">
      <h1 id="titulo"><a href="<?php print $front_page; ?>">ARTELEKU</a></h1>
          <?php print render($page['header']); ?>
    </div><!-- bloque-tools-->
    <div id="bloque-login" class="no-mobile">
      <div id="bloque-login-link">
       </div>
       <div id="bloque-social">
          <ul>
              <li><a href="http://twitter.com/arteleku"><img src="<?php print $images_url;?>/images/twitter.png"></a></li>
              <li><a href="http://www.facebook.com/arteleku"><img src="<?php print $images_url;?>/images/facebook.png"></a></li>
              <li><a href="<?php print url('feed') ?>"><img src="<?php print $images_url;?>/images/rss.png"></a></li>
          </ul>
       </div>
    </div><!-- bloque-login-->
    </header> <!-- /.section, /#header -->
<div id="white-space"></div>
  <div id="page">


    <div id="sidebar" class="column sidebar">
      <div id="logotipo">
        <a href="<?php print $front_page; ?>"><img src="<?php print file_create_url(drupal_get_path('theme', 'arteleku_skin') . '/images/arteleku.png') ?>" alt="arteleku.net"/></a>
      </div>
      <div class="clear"></div>
  </div> <!-- /.section, /#sidebar -->

    <div id="content" class="column"><div class="section">

    <a id="main-content"></a>

    <?php if ($title): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
    <p></p>
    <?php print $content; ?>

    <div class="clear"></div>
  </div></div> <!-- /.section, /#content -->


   <div id="footer">
    <div id="footer-logo">
      <img src="<?php print file_create_url(drupal_get_path('theme', 'arteleku_skin') . '/images/logo.png'); ?>">
    </div>
    <div class="footer-panel" id="footer-dir">
      <p>Arteleku<br /><br />
      Kristobaldegi, 14<br />
      Donostia - San Sebastián<br />
      20014</p>
    </div>
    <div class="footer-panel" id="footer-info">
      <p><?php print t("Information - Administration") ?><br /><br />
      <?php print t('Monday to Friday') ?><br />
      9:00 - 15:00 <br />
      <br />
      T 00 34 943 453 662<br />
      F 00 34 943 462 256<br />
      <br />
      <a href="mailto:info@arteleku.net">info@arteleku.net</a></p>
    </div>
    <div class="footer-panel" id="footer-document">
      <p>Mediateka<br /><br />
      <?php print t('Monday to Friday') ?><br />
      10:00 - 14:00 / 15:00 - 19:00<br /><br />
      T 00 34 943 444 667<br />
      F 00 34 943 462 256<br /><br />
      <a href="mailto:mediateka@arteleku.net">mediateka@arteleku.net</a></p>
    </div>
    <div class="footer-panel last" id="footer-links">
      <?php print theme('links', array('links' => menu_navigation_links('menu-footer'))); ?>
    </div>
    <div class="clear"></div>
    <div id="footer-credits">
      <div class="float-left">2012 Gipuzkoako Foru Aldundia — T 00 34 943 112 760 — <?php print l(t('Legal Information'), 'legal'); ?></div>
      <div class="float-right"><a href="http://gipuzkoa.net/"><img src="<?php print file_create_url(drupal_get_path('theme', 'arteleku_skin') . '/images/gfa.png'); ?>"></a></div>
          <div class="clear"></div>
    </div><!--footer-credits-->
    <div class="clear"></div>
   </div><!-- footer -->

  </div> <!-- /page -->

</body>
</html>
