jQuery(document).ready(function(){
	jQuery( ".menu_appear_date" ).datepicker({
    dateFormat : "yy-mm-dd",
    /*numberOfMonths: [ 1, 2 ],*/
    showOn: "button",
  	buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
  	buttonImageOnly: true,
  	buttonText: "Select date",
  	//minDate: 0, 
  	maxDate: "+30D",
    firstDay: 0,
    beforeShowDay: function (date) {
	    if (date.getDay() == 0) {
	        return [true, ''];
	    } else {
	        return [false, ''];
	    }
	  },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() + 6);
      jQuery(".menu_expire_date").datepicker("option", "minDate", dt);
    }
  });

  jQuery( ".menu_expire_date" ).datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 8, 
    firstDay: 0,
    //maxDate: "+30D",
    beforeShowDay: function (date) {
      if (date.getDay() == 6) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() - 1);
      //jQuery(".menu_appear_date").datepicker("option", "maxDate", dt);
    }
  });

  jQuery("#menu_appear_date_33").datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 0, 
    maxDate: "+30D",
    firstDay: 0,
    beforeShowDay: function (date) {
      if (date.getDay() == 0) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() + 1);
      jQuery("#menu_expire_date_33").datepicker("option", "minDate", dt);
    }
  });

  jQuery( "#menu_expire_date_33" ).datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 8, 
    firstDay: 0,
    beforeShowDay: function (date) {
      if (date.getDay() == 6) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() - 1);
      jQuery("#menu_appear_date_33").datepicker("option", "maxDate", dt);
    }
  });

  jQuery("#menu_appear_date_34").datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 0, 
    maxDate: "+30D",
    firstDay: 0,
    beforeShowDay: function (date) {
      if (date.getDay() == 0) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() + 1);
      jQuery("#menu_expire_date_34").datepicker("option", "minDate", dt);
    }
  });

  jQuery( "#menu_expire_date_34" ).datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 8, 
    firstDay: 0,
    beforeShowDay: function (date) {
      if (date.getDay() == 6) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() - 1);
      jQuery("#menu_appear_date_34").datepicker("option", "maxDate", dt);
    }
  });

  jQuery("#menu_appear_date_35").datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 0, 
    maxDate: "+30D",
    firstDay: 0,
    beforeShowDay: function (date) {
      if (date.getDay() == 0) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() + 1);
      jQuery("#menu_expire_date_35").datepicker("option", "minDate", dt);
    }
  });

  jQuery( "#menu_expire_date_35" ).datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 8, 
    firstDay: 0,
    beforeShowDay: function (date) {
      if (date.getDay() == 6) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() - 1);
      jQuery("#menu_appear_date_35").datepicker("option", "maxDate", dt);
    }
  });

  jQuery("#menu_appear_date_36").datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 0, 
    maxDate: "+30D",
    firstDay: 0,
    beforeShowDay: function (date) {
      if (date.getDay() == 0) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() + 1);
      jQuery("#menu_expire_date_36").datepicker("option", "minDate", dt);
    }
  });

  jQuery( "#menu_expire_date_36" ).datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 8, 
    firstDay: 0,
    beforeShowDay: function (date) {
      if (date.getDay() == 6) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() - 1);
      jQuery("#menu_appear_date_36").datepicker("option", "maxDate", dt);
    }
  });

  jQuery("#menu_appear_date_37").datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 0, 
    maxDate: "+30D",
    firstDay: 0,
    beforeShowDay: function (date) {
      if (date.getDay() == 0) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() + 1);
      jQuery("#menu_expire_date_37").datepicker("option", "minDate", dt);
    }
  });

  jQuery( "#menu_expire_date_37" ).datepicker({
    dateFormat : "yy-mm-dd",
    showOn: "button",
    buttonImage: "../wp-content/plugins/toughcookies/images/calendar.gif",
    buttonImageOnly: true,
    buttonText: "Select date",
    minDate: 8, 
    firstDay: 0,
    beforeShowDay: function (date) {
      if (date.getDay() == 6) {
          return [true, ''];
      } else {
          return [false, ''];
      }
    },
    onSelect: function (selected) {
      var dt = new Date(selected);
      dt.setDate(dt.getDate() - 1);
      jQuery("#menu_appear_date_37").datepicker("option", "maxDate", dt);
    }
  });

  /*
  * Date: 02-Jan-2018
  * Method: function for save customer's account info
  */
  jQuery("#save_zipcode").click(function(){
    jQuery("form#zipcode_frm input").removeClass("error");
    jQuery("form#zipcode_frm p.err-txt").remove();
    var data = {
      'action': 'save_zipcode_info',
      'fdata' : jQuery("#zipcode_frm").serialize()
    };          
    jQuery.post(ajaxurl, data, function(response) {
      var obj = jQuery.parseJSON(response);
      if(obj.error == 0 && obj.msg != ""){
        if(obj.redirecturl){
          window.location.href = obj.redirecturl;
        }
      }else{
        jQuery.each(obj.msg, function( key, value ) {
          jQuery("#"+key).addClass("error");
          jQuery("input#"+key).after("<p class='err-txt'>"+value+"<p>");
        });
      }
    });
  });

  /*
  * Method: function for show/hide coupon billing cycles field
  */

  jQuery("input[name='coupon_one_time_or_recurring']").change(function(){
    if(this.value && this.value == 'recurring'){
      jQuery("tr#coupon_billing_cycles").removeClass("dnone");
    }else{
      jQuery("tr#coupon_billing_cycles").addClass("dnone");
    }
  });

  /*
  * Method: function for show coupon expiry date datepicker
  */

  jQuery( "#coupon_expiry_date" ).datepicker({
    dateFormat : "yy-mm-dd",
    minDate: 0
  });

  /*
  * Method: function for check integer value
  */

  jQuery(".isonlyinteger").keypress(function(e){
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      jQuery("#coupon_meta_msg").addClass('txt-color-red').html("Please enter only digits like 1, 2, 3 etc.").show('slow');
      return false;
    }else{
      jQuery("#coupon_meta_msg").hide('slow');
    }
  });

  /*
  * Method: function for check numeric value
  */

  jQuery(".isonlynumeric").keypress(function(e){
    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
      jQuery("#coupon_meta_msg").addClass('txt-color-red').html("Please enter only numeric like 5, 5.5 etc.").show('slow');
      return false;
    }else{
      jQuery("#coupon_meta_msg").hide('slow');
    }
  });

  /*
  * Method: function for check unit abbreviation value
  */

  jQuery("#iu_abbreviation").focusout(function(){
    jQuery.ajax({
      type: 'POST',
      url: ajaxurl,
      data:{
        action : "check_unit_abbreviation",
        val : this.value,
        termid : jQuery("input[name=tag_ID]").val(),
        group : jQuery("#iu_group").val()
      },
      success: function( data ) {
        var obj = JSON.parse(data);
        if(obj.error == 1){
          jQuery("#abbr_unit_error").empty().html(obj.msg).show('slow');
        }else{
          jQuery("#abbr_unit_error").hide();
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        jQuery("#abbr_error").empty().html(xhr.status+' '+thrownError).show('slow');
      }
    });
  });

  /*
  * Method: function for add more fields to select more ingredients with meal
  */

  slctedIngjsonObj = [];
  if(jQuery('#slcted_ingredients').val()){
    slctedIngjsonObj.push(jQuery('#slcted_ingredients').val());
  }
  jQuery('.add_more_button').click(function(e){
    e.preventDefault();
    var fraction_list = '';
    $.each(tc_adm_data.fractions_list, function( key, value ) {
      fraction_list += '<option value=' +key+ '>' +value+ '</option>';     
    });
    jQuery('.input_fields_container').append('<div><input type="text" name="ingredients[]" id="ingredients" class="ingredients" placeholder="Ingredients"/><input type="text" name="quantity[]" id="quantity" placeholder="Quantity"><select name="fractional_qty[]" id="fractional_qty"><option value="">Select fraction</option>'+fraction_list+'</select><select name="unit[]" class="unit" id="unit"><option value="">Select unit</option></select><a href="javascript:void(0);" id="" title="Remove" class="remove_field"><img class="cursor-point" alt="Remove" src="'+tc_adm_data.adm_site_url+'/wp-content/plugins/toughcookies/images/cross.jpeg"></a></div>');
    jQuery('.input_fields_container').find('input[name="ingredients[]"]:last').autocomplete({
      source: function( request, response ) {
        jQuery("input[name='ingredients[]']").keyup(function(e){
          if(this.value){
            jQuery('#ingredient_val').val(this.value);
          }else{
            var rmed_ingid = jQuery(this).siblings('a.remove_field').attr('id');
            var slcted_ing_ids = jQuery('#slcted_ingredients').val();
            if(rmed_ingid && slcted_ing_ids){
              var ing_ids_arr = slcted_ing_ids.split(',');
              /*var ing_arr_index = ing_ids_arr.indexOf(rmed_ingid);
              if (ing_arr_index > -1) {
                ing_ids_arr.splice(ing_arr_index, 1);
              }*/
              var ids_without_removed = ing_ids_arr.filter(function(value) { return value != rmed_ingid; });
              jQuery('#slcted_ingredients').val(ids_without_removed);
              jQuery(this).siblings('a.remove_field').attr('id','');
              var iu_slct = jQuery(this).closest('div').children('.unit');
              iu_slct.empty();
              iu_slct.append('<option value="">Select unit</option>');
            }
          }
        });
        
        $.ajax({
          type: 'POST',
          dataType: "json",
          url: ajaxurl,
          data: {
            action: "get_ingredients_list",
            enter_val: jQuery('#ingredient_val').val(),
            slcted_items: jQuery('#slcted_ingredients').val()
          },
          success: function (data) {
            if(data.ingData){
              response($.map(data.ingData, function (rslt) {
                return {
                  label: rslt.ing_name,
                  value: rslt.ing_id
                };
              }));
            }
          }
        });
      },
      minLength: 1,
      select: function( event, ui ) {
        event.preventDefault();
        jQuery(this).val(ui.item.label);
        slctedIngjsonObj.push(ui.item.value);
        jQuery('#slcted_ingredients').val(slctedIngjsonObj);
        jQuery(this).siblings('a.remove_field').attr('id',ui.item.value);
        var iu_slct = jQuery(this).closest('div').children('.unit');
        $.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
            action: "get_ing_unit_list",
            ing_val: ui.item.value
          },
          success: function (udata) {
            var obj = JSON.parse(udata);
            if(obj.error == 0){
              iu_slct.empty();
              $.each( obj.rslt, function( key, value ) {
                iu_slct.append('<option value='+value.unit+'>'+value.iu_name+'</option>');     
              });
            }
          }
        });
      },
      change: function(event, ui) {
        if (ui.item == null) {
          jQuery(this).val('');
          jQuery(this).focus();
        }
      }
    });
  });

  /*
  * Method: function for remove field from ingredients block on meal page
  */

  jQuery('.input_fields_container').on("click","a.remove_field", function(e){ //user click on remove text links
    e.preventDefault();
    var rmed_ingid = jQuery(this).attr('id');
    var slcted_ing_ids = jQuery('#slcted_ingredients').val();
    if(slcted_ing_ids){
      var ing_ids_arr = slcted_ing_ids.split(',');
      var ing_arr_index = ing_ids_arr.indexOf(rmed_ingid);
      if (ing_arr_index > -1) {
        ing_ids_arr.splice(ing_arr_index, 1);
      }
      jQuery('#slcted_ingredients').val(ing_ids_arr);
    }
    jQuery(this).parent('div').remove();
  });
 
  /*
  * Method: function for display autocomplete list of ingredients on meal page
  */
  
  if(jQuery('input[name^="ingredients"]').length){
    jQuery('input[name^="ingredients"]').autocomplete({
      source: function( request, response ) {
        jQuery("input[name='ingredients[]']").keyup(function(e){
          if(this.value){
            jQuery('#ingredient_val').val(this.value);
          }else{
            var rmed_ingid = jQuery(this).siblings('a.remove_field').attr('id');
            var slcted_ing_ids = jQuery('#slcted_ingredients').val();
            if(rmed_ingid && slcted_ing_ids){
              var ing_ids_arr = slcted_ing_ids.split(',');
              var ids_without_removed = ing_ids_arr.filter(function(value) { return value != rmed_ingid; });
              //var ing_arr_index = ing_ids_arr.indexOf(rmed_ingid);
              /*if (ing_arr_index > -1) {
                ing_ids_arr.splice(ing_arr_index, 1);
              }*/
              jQuery('#slcted_ingredients').val(ids_without_removed);
              jQuery(this).siblings('a.remove_field').attr('id','');
              var iu_slct = jQuery(this).closest('div').children('.unit');
              iu_slct.empty();
              iu_slct.append('<option value="">Select unit</option>');
            }
          }
        });
        /*jQuery("input[name='ingredients[]']").keyup(function(e){
          jQuery('#ingredient_val').val(this.value);
        });*/
        $.ajax({
          type: 'POST',
          dataType: "json",
          url: ajaxurl,
          data: {
            action: "get_ingredients_list",
            enter_val: jQuery('#ingredient_val').val(),
            slcted_items: jQuery('#slcted_ingredients').val()
          },
          success: function (data) {
            if(data.ingData){
              response($.map(data.ingData, function (rslt) {
                return {
                  label: rslt.ing_name,
                  value: rslt.ing_id
                };
              }));
            }
          }
        });
      },
      minLength: 1,
      select: function( event, ui ) {
        event.preventDefault();
        jQuery(this).val(ui.item.label);
        slctedIngjsonObj.push(ui.item.value);
        jQuery('#slcted_ingredients').val(slctedIngjsonObj);
        jQuery(this).siblings('a.remove_field').attr('id',ui.item.value);
        var iu_slct = jQuery(this).closest('div').children('.unit');
        $.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
            action: "get_ing_unit_list",
            ing_val: ui.item.value
          },
          success: function (udata) {
            var obj = JSON.parse(udata);
            if(obj.error == 0){
              iu_slct.empty();
              $.each( obj.rslt, function( key, value ) {
                iu_slct.append('<option value='+value.unit+'>'+value.iu_name+'</option>');
              });
            }
          }
        });
      },
      change: function(event, ui) {
        if (ui.item == null) {
          jQuery(this).val('');
          jQuery(this).focus();
        }
      }
    });
  }

  
  /*
  * Method: function for display autocomplete list of Registered Affiliate User on Membership Coupon add/edit page
  */

  if(jQuery('#affiliate_discount').length){
    jQuery('#affiliate_discount').autocomplete({
      source: function( request, response ) {
        $.ajax({
          type: 'POST',
          dataType: "json",
          url: ajaxurl,
          data: {
            action: "get_affiliate_users",
            srch_val: jQuery('#affiliate_discount').val()
          },
          success: function (data) {
            if(data.afusers){
              response($.map(data.afusers, function (rslt) {
                return {
                  label: rslt.lel,
                  value: rslt.vl,
                  usrid: rslt.usr_id
                };
              }));
            }
          }
        });
      },
      delay: 500,
      minLength: 2,
      position: { offset: '0, -1' },
      select: function( event, ui ) {
        event.preventDefault();
        jQuery(this).val(ui.item.label);
        jQuery('#slcted_affiliate_usr').val(ui.item.usrid);
      },
      change: function(event, ui) {
        if (ui.item == null) {
          jQuery(this).val('');
          jQuery(this).focus();
          jQuery('#slcted_affiliate_usr').val('');
        }
      }
    });
  }

  /*
  * Method: function for display autocomplete list of Registered User who puchase meal from our website
  */

  if(jQuery('#flr_by_customer').length){
    jQuery('#flr_by_customer').autocomplete({
      source: function( request, response ) {
        jQuery.ajax({
          type: 'POST',
          dataType: "json",
          url: ajaxurl,
          data: {
            action: "get_locked_orders_users",
            srch_val: jQuery('#flr_by_customer').val()
          },
          success: function (data) {
            if(data.lo_users){
              response(jQuery.map(data.lo_users, function (rslt) {
                return {
                  label: rslt.lel,
                  value: rslt.vl,
                  usrid: rslt.usr_id
                };
              }));
            }
          }
        });
      },
      delay: 500,
      minLength: 2,
      position: { offset: '0, -1' },
      select: function( event, ui ) {
        event.preventDefault();
        jQuery(this).val(ui.item.label);
        jQuery('#filter_by_customer').val(ui.item.usrid);
        jQuery("span#sheet_generate_btn_sec").addClass("dnone");
      },
      change: function(event, ui) {
        if (ui.item == null) {
          jQuery(this).val('');
          jQuery(this).focus();
          jQuery('#filter_by_customer').val('');
          jQuery("span#sheet_generate_btn_sec").addClass("dnone");
        }
      }
    });
  }

  /*
  * Function for generate coupon code for add coupon page
  */

  jQuery(document).on('click',"#generate_coupon_code",function(e){
    var data = {
      'action': 'generate_membership_coupon_code'
    };          
    jQuery.post(ajaxurl, data, function(response) {
      var obj = jQuery.parseJSON(response);
      if(obj.error == 0){
        jQuery('#title').val(obj.generated_code).focus();
        jQuery('#coupon_category').val('coupon');
        jQuery("tr.link-affiliate").removeClass("dnone");
      }else{
        alert(obj.msg);
      }
    });
  });

  /*
  * Function for generate gift card code for add coupon page
  */

  jQuery(document).on('click',"#generate_gift_card_code",function(e){
    var data = {
      'action': 'generate_membership_gift_card_code'
    };          
    jQuery.post(ajaxurl, data, function(response) {
      var obj = jQuery.parseJSON(response);
      if(obj.error == 0){
        jQuery('#title').val(obj.generated_code).focus();
        jQuery('#coupon_category').val('gift_card');
        jQuery('#usage_limit_per_coupon').val(1);
        jQuery('#usage_limit_per_user').val(1);
        var nyDate = new Date();
        nyDate.setFullYear(nyDate.getFullYear() + 1);
        nyDate.setDate(nyDate.getDate() - 1);
        jQuery('#coupon_expiry_date').datepicker('setDate', nyDate);
        jQuery("tr.link-affiliate").addClass("dnone");
      }else{
        alert(obj.msg);
      }
    });
  });

  /*
  * Function for show/hide link-affiliate field on add/edit coupon code page
  */

  jQuery("select#coupon_category").change(function(){
    if(this.value && this.value == 'gift_card'){
      jQuery("tr.link-affiliate").addClass("dnone");
      jQuery("#generate_gift_card_code").removeClass("dnone");
      jQuery("#generate_coupon_code").addClass("dnone");
      jQuery("tr.fields-for-coupon").addClass("dnone");
      jQuery("tr.coupon-rule-2").addClass("dnone");
      jQuery("tr#location_link_list").addClass("dnone");
      //jQuery("#add_rule_2").parent().closest('tr.fields-for-coupon').removeClass("dnone");
    }else{
      jQuery("tr.link-affiliate").removeClass("dnone");
      jQuery("#generate_coupon_code").removeClass("dnone");
      jQuery("#generate_gift_card_code").addClass("dnone");
      jQuery("tr.fields-for-coupon").removeClass("dnone");
      if(jQuery("input[name='force_pickup_location']:checked").val() == 'no'){
        jQuery("tr#location_link_list").addClass("dnone");
      }
      if(jQuery("#is_add_rule_2").val() == 1){
        jQuery("tr.coupon-rule-2").removeClass("dnone");
        jQuery("#add_rule_2").parent().closest('tr.fields-for-coupon').addClass("dnone");
      }else{
        jQuery("#add_rule_2").parent().closest('tr.fields-for-coupon').removeClass("dnone");
      }
    }
  });
  
  /*
  * Method: function for show/hide coupon location link field
  */

  jQuery("input[name='force_pickup_location']").change(function(){
    if(this.value && this.value == 'yes'){
      jQuery("tr#location_link_list").removeClass("dnone");
    }else{
      jQuery("tr#location_link_list").addClass("dnone");
    }
  });

  jQuery(document).on('click',"#add_rule_2",function(e){
    jQuery(".coupon-rule-2").removeClass("dnone");
    jQuery(this).parent().closest('tr.fields-for-coupon').addClass("dnone");
    jQuery("#remove_rule_2").removeClass("dnone");
    jQuery("#is_add_rule_2").val(1);
  });

  jQuery(document).on('click',"#remove_rule_2",function(e){
    jQuery(".coupon-rule-2").addClass("dnone");
    jQuery(this).addClass("dnone");
    jQuery("#add_rule_2").parent().closest('tr.fields-for-coupon').removeClass("dnone");
    jQuery("#is_add_rule_2").val(0);
  });
  
  /*
  * Method: function for display autocomplete list of Registered Affiliate User on Membership Coupon add/edit page
  */

  if(jQuery('#location_link').length){
    jQuery('#location_link').autocomplete({
      source: function( request, response ) {
        $.ajax({
          type: 'POST',
          dataType: "json",
          url: ajaxurl,
          data: {
            action: "get_partners_pickup_locations",
            srch_val: jQuery('#location_link').val()
          },
          success: function (data) {
            if(data.ppllst){
              response($.map(data.ppllst, function (rslt) {
                return {
                  label: rslt.lel,
                  //value: rslt.vl,
                  pplid: rslt.ppl_id
                };
              }));
            }
          }
        });
      },
      delay: 500,
      minLength: 2,
      position: { offset: '0, -1' },
      select: function( event, ui ) {
        event.preventDefault();
        jQuery(this).val(ui.item.label);
        jQuery('#slcted_location_link').val(ui.item.pplid);
      },
      change: function(event, ui) {
        if (ui.item == null) {
          jQuery(this).val('');
          jQuery(this).focus();
          jQuery('#slcted_location_link').val('');
        }
      }
    });
  }

  //Pickup location details add more button click operation
  jQuery(document).on('click', "#ppp_add_more_button", function (e) {
      e.preventDefault();
      jQuery('#append_posted_days_hours tr:last').after('<tr><td><input type="text" name="ppp_posted_day[]" /></td><td><input type="text" name="ppp_posted_hours[]" /><a href="javascript:void(0);" class="ppp_remove_button"><img class="cursor-point" alt="Remove" src="' + tc_adm_data.adm_site_url + '/wp-content/plugins/toughcookies/images/cross.jpeg"></a></td></tr>');
  });

  //Pickup location details remove button at click operation
  jQuery(document).on('click', ".ppp_remove_button", function (e) {
      e.preventDefault();
      jQuery(this).closest("tr").remove();
  });

  jQuery(document).on('click tap', "input[name='live_menu_month']:checked", function (e) {
      //e.preventDefault();
      var $this = jQuery(this);
      if(this.value){
        jQuery.ajax({
          type: 'POST',
          url: ajaxurl,
          data:{
            action: "save_activated_live_menus",
            val: this.value
          },
          success: function( data ) {
            var obj = JSON.parse(data);
            if(obj.error == 0){
              $this.prop('disabled', true);
            }
            alert(obj.msg);
          },
          error: function (xhr, ajaxOptions, thrownError) {}
        });
      }
  }); 

  
  /*
  * Method: function for save week schedule off info
  */

  jQuery("#save_schedule_day_off").click(function(){
    jQuery("form#schedule_day_off_frm input").removeClass("error");
    jQuery("form#schedule_day_off_frm textarea").removeClass("error");
    jQuery("form#schedule_day_off_frm p.err-txt").remove();
    var data = {
      'action': 'save_schedule_day_off',
      'fdata': jQuery("#schedule_day_off_frm").serialize()
    };          
    jQuery.post(ajaxurl, data, function(response) {
      var obj = jQuery.parseJSON(response);
      if(obj.error == 0 && obj.msg != ""){
        alert(obj.msg);
        if(obj.redirecturl){
          window.location.href = obj.redirecturl;
        }
      }else{
        if(!Array.isArray(obj.msg)){
          jQuery.each(obj.msg, function( key, value ) {
            jQuery("#"+key).addClass("error");
            jQuery("textarea#" + key).after("<p class='err-txt'>" + value + "<p>");
            jQuery("input#"+key).after("<p class='err-txt'>"+value+"<p>");
          });
        }else{
          alert(obj.msg);
        }
      }
    });
  });


});

/*
* Method: Function for remove zipcode
*/

function delete_zipcode(zc){
  if (confirm("Are you sure ?")) {
    var data = {
      'action': 'remove_zipcode',
      'zc' : zc
    };          
    jQuery.post(ajaxurl, data, function(response) {
      var obj = jQuery.parseJSON(response);
      if(obj.error == 0 && obj.msg != ""){
        if(obj.redirecturl){
          window.location.href = obj.redirecturl;
        }
      }else{
        alert(obj.msg);
      }
    });
  }
}