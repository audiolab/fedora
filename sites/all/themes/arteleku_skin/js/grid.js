(function ($) {
// Store our function as a property of Drupal.behaviors.
Drupal.behaviors.arteleku_skin_search = {
  attach: function (context, settings) {
    $('.islandora-arteleku-collection').masonry({
      // options
      itemSelector : '.islandora-arteleku-collection-object > div',
      columnWidth : 170,
      gutterWidth: 30,
      isResizable : true
    });
  }
};

}(jQuery));
