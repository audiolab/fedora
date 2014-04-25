<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
?>
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
      <?php
      $current = current_path();
      if (!user_is_logged_in()){
	      print l(t('LOG IN'), "user", array('query'=>array('current'=>$current)));
	}else{
	      print l(t('LOG OUT'), "user/logout", array('query'=>array('current'=>$current)));
	    };
      ?>

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

  <?php if ($page['sidebar_first']): ?>
    <div id="sidebar" class="column sidebar">
	    <div id="logotipo">
	    	<a href="<?php print $front_page; ?>"><img src="<?php print file_create_url(drupal_get_path('theme', 'arteleku_skin') . '/images/arteleku.png') ?>" alt="arteleku.net"/></a>
	    </div>

	    <div class="section">
	      <?php print render($page['sidebar_first']); ?>
	    </div>
	    <div class="clear"></div>
        <div class="section">
            <div id="admin-block">

                <?php if ($tabs): ?>
                    <h3 class="block-title"><?php print t('Acciones')?></h3>
                    <?php print render($tabs); ?>
                <?php endif; ?>
                <?php print render($page['help']); ?>
                <?php if ($action_links): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
            </div>
        </div>
	</div> <!-- /.section, /#sidebar -->
  <?php endif; ?>



      <div id="content" class="column"><div class="section">
              <?php print $messages; ?>
        <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']); ?></div><?php endif; ?>
        <a id="main-content"></a>
        <?php print render($page['content']); ?>
        <?php print $feed_icons; ?>
        <div class="clear"></div>


  <?php if ($page['search_facets']): ?>
    <div id="" class="column sidebar">
	    <div class="section">
	      <?php print render($page['search_facets']); ?>
	    </div>
	    <div class="clear"></div>
	</div> <!-- /.section, /#sidebar -->
  <?php endif; ?>

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
<div id="grid"></div>

  </div> <!-- /#page, /#page-wrapper -->
