jQuery(document).ready(function () {
    jQuery("#responseOptions tbody").sortable().disableSelection();
    jQuery(".submenusoptions tbody").sortable().disableSelection();
    var containString;

    jQuery('.menu-items').click(function(){
      jQuery(containString).hide();
    	var id = jQuery(this).attr('id');
    	var iterString = id.toString();
      containString= "#submenus-" + iterString;
      jQuery(containString).show();
  	  jQuery(containString).addClass('display-block');
    });

    jQuery('.menu_edit').click(function(){
      jQuery('.loader-icon').show();
        var subids  =   new Object;
        var ids     =   jQuery('#responseOptions .menu-editor-rows[id]').map(function() {
        var mid     = jQuery( this ).find(".submenusoptions").attr('id');
        var i = this.id;
        if( typeof mid !== "undefined" ) {
            var smid = '#'+ mid + ' .submenu-editor-rows';
            subids[i] = jQuery( smid ).map(function() {
                return this.id;
            }).get();
        }
        return this.id;
      }).get();
      var arg     =   {action:'custom_order',id:ids,subid:subids};
      jQuery.ajax({
          type: "post",
          url: ajax_object.ajax_url,
          dataType:'text',
          data: arg,
          success: function() {
            jQuery('.loader-icon').hide();
            location.reload();

          }
      });
    });

    jQuery('.menu_default').on('click',function(){
      var arg     =   {action:'reorder'};
      jQuery('.loader-icon-reset').show();
      jQuery.ajax({
          type: "post",
          url: ajax_object.ajax_url,
          dataType:'text',
          data: arg,
          success: function() {
            jQuery('.loader-icon-reset').hide();
            location.reload();
          }
      });
    });
});
