jQuery(document).ready(function () {
    slcted_all_ml_with_qty = [];
    slcted_main_ml_with_qty = [];
    slcted_addon_ml_with_qty = [];
    pricing_pg_slcted_allergies = [];
    wkdys = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
    var menu_date_current = 0;
    var menuArray = [];
    var menu_date_max_index = jQuery("#mi_dates").val();
    if (menu_date_max_index) {
        menuArray = menu_date_max_index.split(',');
        menu_date_max_index = menuArray.length - 1;
    }
    //date filter on menu page
    jQuery('ul#mi_date li').click(function (e) {
        if (this.id) {
            jQuery("div.preloader").addClass('dblock');
            jQuery('div.menu-date-box').addClass('dnone');
            jQuery('div#' + this.id).removeClass('dnone');
            jQuery('button span#mi-show-date').html(jQuery(this).text());
            var slcted_date = this.getAttribute('data-midate');
            var menu_date_index = menuArray.indexOf(slcted_date);
            menu_date_current = menu_date_index;
            if (menu_date_index == 0) {
                //Previous disable
                jQuery('a#date-prev').addClass('disable');
            } else {
                // Previous enable
                jQuery('a#date-prev').removeClass('disable');
            }
            if (menu_date_index == menu_date_max_index) {
                // next disable
                jQuery('a#date-next').addClass('disable');
            } else {
                // next enable
                jQuery('a#date-next').removeClass('disable');
            }
            var planid = jQuery("ul#mi_plan li.active").attr('id');
            if (planid) {
                jQuery('div.menu-plan-box').addClass('dnone');
                jQuery('div#' + planid).removeClass('dnone');
            }
            jQuery("div.preloader").removeClass('dblock');
        }
    });
    jQuery('div.hndleMenu a#date-prev').click(function (e) {
        jQuery("div.preloader").addClass('dblock');
        menu_date_current--;
        var prevdate = menuArray[menu_date_current].replace(/-/g, '');
        //prevdate = prevdate.replace(/-/g,'');
        if (menu_date_current == 0) {
            // Previous disable
            jQuery('a#date-prev').addClass('disable');
        } else {
            // Previous enable
            jQuery('a#date-prev').removeClass('disable');
        }
        jQuery('a#date-next').removeClass('disable');
        jQuery('div.menu-date-box').addClass('dnone');
        jQuery('div#date' + prevdate).removeClass('dnone');
        jQuery('button span#mi-show-date').html(jQuery(jQuery('ul#mi_date li#date' + prevdate)).text());
        var planid = jQuery("ul#mi_plan li.active").attr('id');
        if (planid) {
            jQuery('div.menu-plan-box').addClass('dnone');
            jQuery('div#' + planid).removeClass('dnone');
        }
        jQuery("div.preloader").removeClass('dblock');
    });

    jQuery('div.hndleMenu a#date-next').click(function (e) {
        jQuery("div.preloader").addClass('dblock');
        menu_date_current++;
        var nextdate = menuArray[menu_date_current].replace(/-/g, '');
        if (menu_date_current == menu_date_max_index) {
            // next disable
            jQuery('a#date-next').addClass('disable');
        } else {
            // next enable
            jQuery('a#date-next').removeClass('disable');
        }
        jQuery('a#date-prev').removeClass('disable');
        jQuery('div.menu-date-box').addClass('dnone');
        jQuery('div#date' + nextdate).removeClass('dnone');
        jQuery('button span#mi-show-date').html(jQuery(jQuery('ul#mi_date li#date' + nextdate)).text());
        var planid = jQuery("ul#mi_plan li.active").attr('id');
        if (planid) {
            jQuery('div.menu-plan-box').addClass('dnone');
            jQuery('div#' + planid).removeClass('dnone');
        }
        jQuery("div.preloader").removeClass('dblock');
    });

    //plan filter on menu page
    jQuery('ul#mi_plan li').click(function (e) {
        if (this.id) {
            jQuery("div.preloader").addClass('dblock');
            jQuery('div.menu-plan-box').addClass('dnone');
            jQuery('div#' + this.id).removeClass('dnone');
            jQuery('li.plan-nav').removeClass('active');
            jQuery('li#' + this.id).addClass('active');
            jQuery("div.preloader").removeClass('dblock');
            jQuery('.otm-pln-desc').addClass('dnone');
            jQuery('#otm_desc_' + this.id).removeClass('dnone');
        }
    });

    jQuery(document).on('click', "ul.menulist li", function (e) {
        if (this.id) {
            jQuery("div.preloader").addClass('dblock');
            if (this.id == 'all') {
                jQuery('.menu-plan-box .prdctList div.meal-cat-box').removeClass('dnone');
                jQuery('div.no-data').addClass('dnone');
            } else {
                jQuery('.menu-plan-box .prdctList div.meal-cat-box').addClass('dnone');
                jQuery('.menu-plan-box .prdctList div.menu-cat-box').addClass('dnone');
                jQuery('.menu-plan-box .prdctList div#' + this.id).removeClass('dnone');
            }
            jQuery('li.cat-nav').removeClass('current');
            jQuery('li#' + this.id).addClass('current');
            jQuery("div.preloader").removeClass('dblock');
        }
    });

    jQuery("#signup-continue").click(function () {
        jQuery("div.preloader").addClass('dblock');
        jQuery('#signup-continue').addClass('proc-loader');
        jQuery("form#signup_frm input").removeClass("error");
        jQuery("form#signup_frm p.err-txt").remove();
        jQuery("#signup-alert-box p.zc-err-txt").remove();
        jQuery("#signup-alert-box").addClass("dnone");
        var data = {
            "action": "signup_user",
            "fdata": jQuery("#signup_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0 && obj.msg != "") {
                if (obj.redirecturl) {
                    window.location.href = obj.redirecturl;
                }
            } else {
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#" + key).addClass("error");
                    jQuery("input#" + key).after("<p class='err-txt'>" + value + "<p>");
                    if (key == 'zip_code_alert') {
                        jQuery("#zip_code").addClass("error");
                        jQuery("#signup-alert-box").removeClass("dnone");
                        jQuery("#signup-alert-box .container").append("<p class='zc-err-txt'>" + value + "<p>");
                    }
                });
            }
            jQuery('#signup-continue').removeClass('proc-loader');
            jQuery("div.preloader").removeClass('dblock');
        });
    });

    /*
     * Method: function for save customer's account info
     */
    jQuery("#save_acc_info").click(function () {
        jQuery(this).prop('disabled', true);
        jQuery(this).addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery("form#cust_acc_info_frm input").removeClass("error");
        jQuery("form#cust_acc_info_frm p.err-txt").remove();
        var data = {
            'action': 'save_account_info',
            'fdata': jQuery("#cust_acc_info_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                jQuery(".popAlrt").addClass("msg-box");
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    if (obj.redirecturl) {
                        window.location.href = obj.redirecturl;
                    }
                }, 3000);
            } else {
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#" + key).addClass("error");
                    jQuery("input#" + key).after("<p class='err-txt'>" + value + "<p>");
                });
            }
            jQuery("#save_acc_info").prop('disabled', false);
            jQuery("#save_acc_info").removeClass('proc-loader');
        });
        jQuery("div.preloader").removeClass('dblock');
    });

    /*
     * Method: function for save customer's delivery info
     */
    jQuery("#save_delivery_info").click(function () {
        jQuery(this).prop('disabled', true);
        jQuery(this).addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery("form#cust_delivery_info_frm input").removeClass("error");
        jQuery("form#cust_delivery_info_frm p.err-txt").remove();
        var data = {
            'action': 'save_delivery_info',
            'fdata': jQuery("#cust_delivery_info_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0 && obj.msg != "") {
                jQuery(".popAlrt").addClass("msg-box");
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    if (obj.redirecturl) {
                        window.location.href = obj.redirecturl;
                    }
                }, 3000);
            } else {
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#" + key).addClass("error");
                    jQuery("input#" + key).after("<p class='err-txt'>" + value + "<p>");
                });
            }
            jQuery("#save_delivery_info").prop('disabled', false);
            jQuery("#save_delivery_info").removeClass('proc-loader');
        });
        jQuery("div.preloader").removeClass('dblock');
    });

    /*
     * Method: function for save customer's delivery info
     */
    jQuery("#save_nutrition_info").click(function () {
        jQuery(this).prop('disabled', true);
        jQuery(this).addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        var data = {
            'action': 'save_nutrition_info',
            'fdata': jQuery("#cust_nutrition_info_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".popAlrt").addClass("msg-box");
            if (obj.error == 0) {
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    if (obj.redirecturl) {
                        window.location.href = obj.redirecturl;
                    }
                }, 3000);
            } else {
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
            }
            jQuery('#save_nutrition_info').prop('disabled', false);
            jQuery('#save_nutrition_info').removeClass('proc-loader');
        });
        jQuery("div.preloader").removeClass('dblock');
    });

    /*
     * Show/Hide Diet preferences images on Account => Edit Nutrition page
     */

    jQuery("div.chckRdoCstm ul li span").click(function () {
        if (jQuery(this).find('img').hasClass("blueImg")) {
            jQuery(this).removeClass('dblock');
            jQuery('div.chckRdoCstm ul li span img.blueImg').removeClass('dblock');
            jQuery('div.chckRdoCstm ul li span img.grayImg').removeClass('dnone');
        }
    });

    /*
     * Show/hide email field on sign up checkout page
     */
    jQuery("div#chng_email_box h5 span").click(function () {
        if (jQuery("div.chngMail").is(':visible')) {
            jQuery("div#chng_email_box div.chngMail").css('display', 'none');
            jQuery("div#chng_email_box > p").css('display', 'block');
        } else {
            jQuery("div#chng_email_box div.chngMail").css('display', 'block');
            jQuery("div#chng_email_box > p").css('display', 'none');
        }
    });

    /*
     * Hide email field on sign up checkout page on click on Save button
     */

    jQuery("button#change_order_email").click(function () {
        jQuery("div#chng_email_box div.chngMail").css('display', 'none');
        var email = jQuery("#bemail").val();
        jQuery("div#chng_email_box > p > a").empty().text(email);
        jQuery("div#chng_email_box > p").css('display', 'block');
    });

    /*
     * Show/Hide meal plans on click on plan group on account plan settings page
     */


    //jQuery("select#plan_group").click(function(){
    jQuery('select#plan_group').on('change', function () {
        if (this.value) {
            jQuery("div.preloader").addClass('dblock');
            jQuery('span.pg-box').addClass('dnone');
            jQuery('span#pg_box_' + this.value).removeClass('dnone');

            jQuery('span.pg-desc-box').addClass('dnone');
            jQuery('span#pg_desc_box_' + this.value).removeClass('dnone');

            jQuery("#slct_group").val(this.value);
            var curr_pln_group = jQuery("#curr_pln_group").val();
            if (this.value != curr_pln_group) {
                jQuery("#mealplan_" + this.value).attr('checked', 'checked');
            } else {
                jQuery("input.curr-plan").attr('checked', 'checked');
            }
            var slct_pln = jQuery("span#pln_box span#pg_box_" + this.value + " li.editOpt ul#mpd_list li input#mealplan_" + this.value + ":checked").val();
            if (slct_pln && slct_pln > 0) {
                jQuery("#slct_plan_id").val(slct_pln);
            }
            var mpd = jQuery("span#pln_box span#pg_box_" + this.value + " li.editOpt ul#mpd_list li input#mealplan_" + this.value + ":checked").data('mpdays');
            if (mpd && mpd > 0) {
                jQuery("#slct_meal_pr_day").val(mpd);
            }
            jQuery("div.preloader").removeClass('dblock');
        }
    });

    /*
     * Show/Hide meal plan on account plan settings page
     */

    jQuery("input[name='mealplan[]']").click(function () {
        if (this.value) {
            var slct_group = jQuery("#slct_group").val();
            jQuery('span.plan-box-' + slct_group).addClass('dnone');
            jQuery('span#pln_' + slct_group + '_' + this.value).removeClass('dnone');
        }
    });

    //change meal filter on upcoming page
    jQuery('ul#chn_meal_days li').click(function (e) {
        if (this.id) {
            jQuery('div.day-meals-box').addClass('dnone');
            jQuery('div#' + this.id).removeClass('dnone');
        }
    });

    jQuery('ul#meal_cat_list li').click(function (e) {
        if (this.id) {
            if (this.id == 'all') {
                jQuery('div.meal-cat-box').removeClass('dnone');
                jQuery('div.no-data').addClass('dnone');
            } else {
                jQuery('div.meal-cat-box').addClass('dnone');
                jQuery('div#' + this.id).removeClass('dnone');
            }
            jQuery('li.cat-nav').removeClass('current');
            jQuery('li#' + this.id).addClass('current');
        }
    });

    /*jQuery(".week-day-meal").click(function(){
     var meal_per_day = jQuery(this).data('wkplanmpd');//jQuery('#meal_per_day').val();
     if(meal_per_day){
     var meal_date = jQuery(this).data('daydate');
     if(meal_date){
     var wsdate = jQuery(this).data('wsdate');
     var wsdate_ky = wsdate.replace(new RegExp('-', 'g'),"");
     var md = meal_date.replace(new RegExp('-', 'g'),"");
     var meals = jQuery("input[name='meals"+md+"[]']:checked").map(function () {
     var ml_price = jQuery(this).data('price');
     if(ml_price == ''){
     return this.value;
     }
     }).get();
     var all_meals = jQuery("input[name='meals"+md+"[]']:checked").map(function () {
     return this.value;
     }).get();
     var addon_meals = jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_'+md+' div.prdctList .snglMenuList .li_adon_'+md+' .is_addon_ml:checked').map(function () {
     return this.value;
     }).get();
     if(meals.length > meal_per_day){
     jQuery(".popAlrt").addClass("msg-box");
     jQuery("#err_msg").removeClass("dnone");
     jQuery("#err_msg span").text('You can select any '+meal_per_day+' meals per day.');
     setTimeout(function(){ jQuery(".popAlrt").removeClass("msg-box"); }, 3000);
     return false;
     }else{
     var calories = jQuery(this).data('calories');
     var protien = jQuery(this).data('protien');
     var carbs = jQuery(this).data('carbs');
     var fat = jQuery(this).data('fat');
     var tot_cal = jQuery('span#tot_calories'+md).text();
     var tot_pro = jQuery('span#tot_protein'+md).text();
     var tot_car = jQuery('span#tot_carbs'+md).text();
     var tot_fat = jQuery('span#tot_fat'+md).text();
     var tot_calories, tot_protien, tot_carbs, tot_fats = 0;
     if(jQuery(this).is(":checked")){
     tot_calories = parseInt(tot_cal) + parseInt(calories);
     tot_protien = parseInt(tot_pro) + parseInt(protien);
     tot_carbs = parseInt(tot_car) + parseInt(carbs);
     tot_fats = parseInt(tot_fat) + parseInt(fat);
     jQuery(this).parent().closest('div').addClass('meal_slcted');
     }else{
     tot_calories = parseInt(tot_cal) - parseInt(calories);
     tot_protien = parseInt(tot_pro) - parseInt(protien);
     tot_carbs = parseInt(tot_car) - parseInt(carbs);
     tot_fats = parseInt(tot_fat) - parseInt(fat);
     jQuery(this).parent().closest('div').removeClass('meal_slcted');
     }
     jQuery('span#tot_calories'+md).text(tot_calories);
     jQuery('span#tot_protein'+md).text(tot_protien);
     jQuery('span#tot_carbs'+md).text(tot_carbs);
     jQuery('span#tot_fat'+md).text(tot_fats);
     
     var daydate = jQuery(this).data('daydate');
     if(daydate){
     var wmd = daydate.replace(new RegExp('-', 'g'),"");
     jQuery('span#tot_added_meal'+wmd).text(all_meals.length+'/'+meal_per_day);
     }
     if(all_meals.length >= meal_per_day){
     jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmheader div#cmhdrBtm'+wsdate_ky).addClass('cmhdrgrn');
     }else{
     jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmheader div#cmhdrBtm'+wsdate_ky).removeClass('cmhdrgrn');
     }
     
     if(meals.length == meal_per_day){
     jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_'+wmd+' div.prdctList .li_adon_'+wmd).removeClass('is-not-active');
     jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_'+wmd+' div.prdctList .li_adon_'+wmd+' .is_addon_ml').removeAttr("disabled");
     jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_'+wmd+' div.prdctList').addClass('slcted_max_meals');
     }else{
     var chk = parseInt(all_meals.length) - parseInt(addon_meals.length);
     jQuery('span#tot_added_meal'+wmd).text(chk+'/'+meal_per_day);
     jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_'+wmd+' div.prdctList .snglMenuList .li_adon_'+wmd).addClass('is-not-active');
     jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_'+wmd+' div.prdctList .snglMenuList .li_adon_'+wmd+' .is_addon_ml').attr("disabled","true");
     jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_'+wmd+' div.prdctList .snglMenuList .li_adon_'+wmd+' .is_addon_ml').prop('checked', false);
     jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_'+wmd+' div.prdctList').removeClass('slcted_max_meals');
     }
     }
     }
     }
     });*/

    jQuery('.accrdnWrap ul#week_box li div.acrdnHead').click(function (n) {
        var wsdate = jQuery(this).data('wsdate');
        if (wsdate) {
            var md = wsdate.replace(new RegExp('-', 'g'), "");
            jQuery('#curr_date').val(wsdate);
            jQuery('div.meal-cat-box').removeClass('dnone');
            jQuery('div.no-data').addClass('dnone');
            jQuery('li.cat-nav').removeClass('current');
            jQuery('li#all').addClass('current');
        }
    });

    jQuery('.amdInr ul li').click(function (n) {
        if (this.id) {
            var meal_date = this.id;
            var md = meal_date.replace(new RegExp('-', 'g'), "");
            var week_start_date = jQuery(this).data('week_start_date');
            var wkstartdate_ky = week_start_date.replace(new RegExp('-', 'g'), "");
            jQuery('div.wkcmmeals' + wkstartdate_ky).addClass('dnone');
            jQuery('div#dm_' + md).removeClass('dnone');
            jQuery('ul#cmd_tab li.wkdaytabs' + wkstartdate_ky).removeClass('active');
            jQuery('ul#cmd_tab li#dt_' + md).addClass('active');
            jQuery('button#previous' + wkstartdate_ky).data('currdate' + wkstartdate_ky, meal_date);
            jQuery('button#next' + wkstartdate_ky).data('currdate' + wkstartdate_ky, meal_date);
            curr_date = meal_date.split('-');
            var month = parseInt(curr_date[1]) - 1;
            var d = new Date(curr_date[0], month, curr_date[2]);
            jQuery('button#previous' + wkstartdate_ky).removeAttr("disabled", true);
            jQuery('button#next' + wkstartdate_ky).removeAttr("disabled", true);
            var wkdaysrange = jQuery(this).data('wkdayrange');
            var start_day = 0;
            var end_day = 6;
            if (wkdaysrange) {
                wdr_arr = wkdaysrange.split('-');
                if (wdr_arr[0]) {
                    start_day = wdr_arr[0];
                }
                if (wdr_arr[1]) {
                    end_day = wdr_arr[1];
                }
            }
            var prev_dy = '';
            var nxt_dy = '';
            jQuery('button#previous' + wkstartdate_ky + ' i.fa').removeClass('dnone');
            jQuery('button#next' + wkstartdate_ky + ' i.fa').removeClass('dnone');
            if (d.getDay() == start_day) {
                //disable previouse button
                jQuery('button#previous' + wkstartdate_ky).attr("disabled", true);
                jQuery('button#previous' + wkstartdate_ky + ' i.fa').addClass('dnone');
            } else {
                prev_dy = wkdys[d.getDay() - 1];
            }
            jQuery('button#previous' + wkstartdate_ky + ' span').text(prev_dy);
            if (d.getDay() == end_day) {
                //disable next button
                jQuery('button#next' + wkstartdate_ky).attr("disabled", true);
                jQuery('button#next' + wkstartdate_ky + ' i.fa').addClass('dnone');
            } else {
                nxt_dy = wkdys[d.getDay() + 1];
            }
            jQuery('button#next' + wkstartdate_ky + ' span').text(nxt_dy);
            //total added meals on the selected date
            var tot_added_mls = jQuery(this).data('tot_added_mls');
            var meal_per_day = jQuery(this).data('wkmpd');
            if (wkstartdate_ky) {
                jQuery('span.wktmcnter' + wkstartdate_ky).addClass('dnone');
                jQuery('span#tot_added_meal' + md).removeClass('dnone');
                if (tot_added_mls == meal_per_day) {
                    jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmheader div#cmhdrBtm' + wkstartdate_ky).addClass('cmhdrgrn');
                    if (d.getDay() == end_day) {
                        jQuery('a#sav_meal' + wkstartdate_ky).addClass("glow-blue-btn");
                        jQuery('button#next' + wkstartdate_ky).removeClass("chng-ml-nxt-btn-glow");
                    } else {
                        jQuery('a#sav_meal' + wkstartdate_ky).removeClass("glow-blue-btn");
                        jQuery('button#next' + wkstartdate_ky).addClass("chng-ml-nxt-btn-glow");
                    }
                } else {
                    jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmheader div#cmhdrBtm' + wkstartdate_ky).removeClass('cmhdrgrn');
                    jQuery('a#sav_meal' + wkstartdate_ky).removeClass("glow-blue-btn");
                }
                jQuery('span.slctwktxt' + wkstartdate_ky).addClass('dnone');
                jQuery('span#slctwktxt' + md).removeClass('dnone');
            }
        }
    });


    jQuery('.cmhdrBtm p button.prev-next-btn').click(function (n) {
        if (this.value) {
            var new_date, prev_date, next_date;
            var wkstartdate = jQuery(this).data('wkstartdate');
            var wkstartdate_ky = wkstartdate.replace(new RegExp('-', 'g'), "");
            var curr_date = jQuery(this).data('currdate' + wkstartdate_ky);
            var wkdaysrange = jQuery(this).data('daysrange');
            var start_day = 0;
            var end_day = 6;
            if (wkdaysrange) {
                wdr_arr = wkdaysrange.split('-');
                if (wdr_arr[0]) {
                    start_day = wdr_arr[0];
                }
                if (wdr_arr[1]) {
                    end_day = wdr_arr[1];
                }
            }
            var meal_per_day = jQuery(this).data('planmpd');
            var tot_added_mls = 0;
            if (curr_date) {
                var prev_dy, nxt_dy;
                curr_date = curr_date.split('-');
                var month = parseInt(curr_date[1]) - 1;
                var d = new Date(curr_date[0], month, curr_date[2]);
                if (this.value == 'previous') {
                    jQuery('button#next' + wkstartdate_ky).removeAttr("disabled", true);
                    //get previous date
                    d.setDate(d.getDate() - 1);
                    var month = d.getMonth() + 1;
                    month = month.toString().padStart(2, 0);
                    var day = d.getDate();
                    day = day.toString().padStart(2, 0);
                    prev_date = d.getFullYear() + '-' + month + '-' + day;
                    if (prev_date) {
                        if (d.getDay() >= start_day) {
                            var md = prev_date.replace(new RegExp('-', 'g'), "");
                            jQuery('div.wkcmmeals' + wkstartdate_ky).addClass('dnone');
                            jQuery('div#dm_' + md).removeClass('dnone');
                            jQuery('ul#cmd_tab li.wkdaytabs' + wkstartdate_ky).removeClass('active');
                            jQuery('ul#cmd_tab li#dt_' + md).addClass('active');
                            jQuery(this).data('currdate' + wkstartdate_ky, prev_date);
                            jQuery('button#next' + wkstartdate_ky).data('currdate' + wkstartdate_ky, prev_date);
                            prev_dy = wkdys[d.getDay() - 1];
                            nxt_dy = wkdys[d.getDay() + 1];
                            jQuery('button#next' + wkstartdate_ky + ' span').text(nxt_dy);
                            jQuery('button#next' + wkstartdate_ky + ' i.fa').removeClass('dnone');
                            jQuery('button#' + this.id + ' i.fa').removeClass('dnone');
                            if (d.getDay() == start_day) {
                                //disable previouse button
                                jQuery('button#' + this.id).attr("disabled", true);
                                jQuery('button#' + this.id + ' i.fa').addClass('dnone');
                                prev_dy = '';
                            }
                            jQuery('button#' + this.id + ' span').text(prev_dy);
                            if (md) {
                                jQuery('span.wktmcnter' + wkstartdate_ky).addClass('dnone');
                                jQuery('span#tot_added_meal' + md).removeClass('dnone');//.text(tot_added_mls+'/'+meal_per_day);
                                jQuery('span.slctwktxt' + wkstartdate_ky).addClass('dnone');
                                jQuery('span#slctwktxt' + md).removeClass('dnone');
                            }
                        }
                    }
                    jQuery('a#sav_meal' + wkstartdate_ky).removeClass("glow-blue-btn");
                }
                if (this.value == 'next') {
                    jQuery('button#previous' + wkstartdate_ky).removeAttr("disabled", true);
                    //get next date
                    d.setDate(d.getDate() + 1);
                    var month = d.getMonth() + 1;
                    month = month.toString().padStart(2, 0);
                    var day = d.getDate();
                    day = day.toString().padStart(2, 0);
                    next_date = d.getFullYear() + '-' + month + '-' + day;
                    if (d.getDay() <= end_day) {
                        var md = next_date.replace(new RegExp('-', 'g'), "");
                        jQuery('div.wkcmmeals' + wkstartdate_ky).addClass('dnone');
                        jQuery('div#dm_' + md).removeClass('dnone');
                        jQuery('ul#cmd_tab li.wkdaytabs' + wkstartdate_ky).removeClass('active');
                        jQuery('ul#cmd_tab li#dt_' + md).addClass('active');
                        jQuery(this).data('currdate' + wkstartdate_ky, next_date);
                        jQuery('button#previous' + wkstartdate_ky).data('currdate' + wkstartdate_ky, next_date);
                        prev_dy = wkdys[d.getDay() - 1];
                        nxt_dy = wkdys[d.getDay() + 1];
                        jQuery('button#previous' + wkstartdate_ky + ' span').text(prev_dy);
                        jQuery('button#previous' + wkstartdate_ky + ' i.fa').removeClass('dnone');
                        jQuery('button#' + this.id + ' i.fa').removeClass('dnone');
                        if (d.getDay() == end_day) {
                            //disable next button
                            jQuery('button#' + this.id).attr("disabled", true);
                            jQuery('button#' + this.id + ' i.fa').addClass('dnone');
                            nxt_dy = '';
                        }
                        jQuery('button#' + this.id + ' span').text(nxt_dy);
                        if (md) {
                            jQuery('span.wktmcnter' + wkstartdate_ky).addClass('dnone');
                            jQuery('span#tot_added_meal' + md).removeClass('dnone');//.text(tot_added_mls+'/'+meal_per_day);
                            jQuery('span.slctwktxt' + wkstartdate_ky).addClass('dnone');
                            jQuery('span#slctwktxt' + md).removeClass('dnone');
                        }
                    }
                }
            }
            slcted_all_ml_with_qty.length = 0;
            slcted_main_ml_with_qty.length = 0;
            slcted_addon_ml_with_qty.length = 0;
            if (md) {
                var slcted_all_mls = jQuery('input#slcted_allmls_' + md).val();
                if (slcted_all_mls) {
                    slcted_all_ml_with_qty = jQuery.parseJSON(slcted_all_mls);
                }
                var slcted_main_mls = jQuery('input#slcted_mainmls_' + md).val();
                if (slcted_main_mls) {
                    slcted_main_ml_with_qty = jQuery.parseJSON(slcted_main_mls);
                }
                var slcted_addon_mls = jQuery('input#slcted_addonmls_' + md).val();
                if (slcted_addon_mls) {
                    slcted_addon_ml_with_qty = jQuery.parseJSON(slcted_addon_mls);
                }
                tot_added_mls = slcted_main_ml_with_qty.length;
                if (tot_added_mls == meal_per_day) {
                    jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmheader div#cmhdrBtm' + wkstartdate_ky).addClass('cmhdrgrn');
                    if (d.getDay() == end_day) {
                        jQuery('a#sav_meal' + wkstartdate_ky).addClass("glow-blue-btn");
                        jQuery('button#next' + wkstartdate_ky).removeClass("chng-ml-nxt-btn-glow");
                    } else {
                        jQuery('button#next' + wkstartdate_ky).addClass("chng-ml-nxt-btn-glow");
                    }
                } else {
                    jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmheader div#cmhdrBtm' + wkstartdate_ky).removeClass('cmhdrgrn');
                }
            }
        }
    });

    jQuery("#finish_without_quiz").click(function () {
        window.location.href = 'upcoming';
    });

    jQuery("#onboarding_wlcm_nxt").click(function () {
        jQuery('#onboarding_welcome').addClass('onboarding-hide-box');
        jQuery('#onboarding_welcome_parent').removeClass('mob-body');
        jQuery('div#onboarding_diet_preferences').addClass('onboarding-show-box');
        jQuery('li.onbrding-tabs').removeClass('active');
        jQuery('li#onbrding_diet_preferences').addClass('active');
    });

    jQuery("#back_to_wlcom").click(function () {
        jQuery('#onboarding_welcome_parent').addClass('mob-body');
        jQuery('#onboarding_welcome').removeClass('onboarding-hide-box');
        jQuery('div#onboarding_diet_preferences').removeClass('onboarding-show-box');
        jQuery('li.onbrding-tabs').removeClass('active');
        jQuery('li#onbrding_welcome').addClass('active');
    });

    jQuery("#back_to_diet_preferences").click(function () {
        jQuery('div#onboarding_optimize_results').removeClass('onboarding-show-box');
        jQuery('div#onboarding_diet_preferences').removeClass('onboarding-hide-box').addClass('onboarding-show-box');
        jQuery('li.onbrding-tabs').removeClass('active');
        jQuery('li#onbrding_diet_preferences').addClass('active');
        jQuery('div.optimize-result-box').removeClass('opt-result-hide-box').removeClass('opt-result-show-box');
        jQuery('div.optimize-result-sec').removeClass('dnone');
        jQuery('button#finish_without_quiz').removeClass('dnone');
    });

    jQuery("#manually_enter_macros_btn").click(function () {
        jQuery('div.optimize-result-sec').addClass('opt-result-hide-box');
        jQuery('div.manual-enter-macro-sec').addClass('opt-result-show-box');
        jQuery('.ppwMain').addClass('set-macro-wrapper');
        jQuery('button#finish_without_quiz').addClass('dnone');
        if (jQuery('#is_manual_macros_set').val() == 1) {
            jQuery("button#onboarding_finish_quiz").removeClass('dnone');
        }
    });

    jQuery("#get_started_btn").click(function () {
        jQuery('div.optimize-result-sec').addClass('opt-result-hide-box');
        jQuery('div.diet-macro-calc-sec').addClass('opt-result-show-box');
        jQuery('button#finish_without_quiz').addClass('dnone');
        jQuery('button#back_to_diet_preferences').addClass('dnone');
        jQuery('a.learn-about-macros-btn').removeClass('dnone');
    });

    /*
     * Method: function for save customer's delivery info
     */
    jQuery("#save_diet_preferences").click(function () {
        jQuery("#save_diet_preferences").addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        var data = {
            'action': 'save_diet_preferences',
            'fdata': jQuery("#diet_preferences_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".popAlrt").addClass("msg-box");
            if (obj.error == 0) {
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    window.location.href = 'upcoming';
                }, 3000);
            } else {
                jQuery("#save_diet_preferences").removeClass('proc-loader');
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
            }
        });
        jQuery("div.preloader").removeClass('dblock');
    });

    /*
     * Method: function for save customer's manually macros info
     */

    jQuery("#save_manually_macros").click(function () {
        jQuery("#save_manually_macros").addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        var data = {
            'action': 'save_manually_macros',
            'fdata': jQuery("#manually_macros_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".popAlrt").addClass("msg-box");
            if (obj.error == 0) {
                if (jQuery('.ppwMain').hasClass("set-macro-wrapper")) {
                    jQuery('.ppwMain').removeClass('set-macro-wrapper');
                }
                jQuery("html").removeClass("open-popup");
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                jQuery("button#save_manually_macros").addClass('macros-set');
                jQuery("#onboarding_finish_quiz").removeClass("dnone");
                jQuery("#save_manually_macros").removeClass('proc-loader');
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
            } else {
                jQuery("#save_manually_macros").removeClass('proc-loader');
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").empty();
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#err_msg span").append("<p>" + value + "<p>");
                });
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 2500);
            }
        });
        jQuery("div.preloader").removeClass('dblock');
    });

    /*
     * Method: function for show/hide body fat boxes
     */

    jQuery("input[name='known_bf']").click(function () {
        if (this.value == 'yes') {
            jQuery('div.body-fat-no').addClass('dnone');
            jQuery('div.body-fat-yes').removeClass('dnone');
        } else {
            jQuery('div.body-fat-yes').addClass('dnone');
            jQuery('div.body-fat-no').removeClass('dnone');
        }
    });

    /*
     * Method: function for calculate & save customer's diet info
     */

    jQuery("#calc_diet_calorie_macro").click(function () {
        jQuery("#calc_diet_calorie_macro").addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        jQuery(".result").addClass("dnone");
        var goal = jQuery("input[name='goal']:checked").val();
        var goal_name = jQuery("input[name='goal']:checked").data('goalname');
        var known_bf = jQuery("input[name='known_bf']:checked").val();
        var excrcsinweek = parseFloat(jQuery("input[name='excrcsinweek']:checked").val());
        var meal_count = parseInt(jQuery("input[id='meal_count']").val());
        var result = macros = err_msg = '';
        var error = 0;
        if (known_bf == 'yes') {
            var wt = parseInt(jQuery("input[id='weight_yes']").val());
            var kg = Math.round(wt / 2.2);
            var bf = parseInt(jQuery("input[id='body_fat']").val());
            if (wt && kg && bf) {
                result = calc_cals(known_bf, kg, excrcsinweek, bf);
                macros = calc_macros(result, goal, known_bf, excrcsinweek, bf, kg);
            } else {
                err_msg = 'Please enter Body Fat % and Weight in lbs.';
                error = 1;
            }
        } else {
            var wt = parseInt(jQuery("input[id='weight_no']").val());
            var kg = Math.round(wt / 2.2);
            var age = parseInt(jQuery("input[id='age']").val());
            var gender = jQuery("input[name='gender']:checked").val();
            var feet = parseFloat(jQuery("input[id='feet']").val());
            var inches = parseFloat(jQuery("input[id='inches']").val());
            var ht = Math.round(((feet * 12) + inches) * 2.54);
            if (wt && kg && age && feet) {
                result = calc_cals(known_bf, kg, excrcsinweek, bf = undefined, age, ht, gender);
                macros = calc_macros(result, goal, known_bf, excrcsinweek, bf = undefined, kg);
            } else {
                err_msg = 'Please enter Weight in lbs, Height in feet, Height in inches and Year.';
                error = 1;
            }
        }
        if (error == 0) {
            var pro = macros[0];
            var pro_per = Math.round(pro / meal_count);
            var carbs = macros[1];
            var carbs_per = Math.round(carbs / meal_count);
            var fats = macros[2];
            var fats_per = Math.round(fats / meal_count);
            var calories = 0;
            if (goal == 'weight-loss' || goal == 'lose-weight') {
                calories = result - 500;
                jQuery(".cals").html(calories + " calories");
                jQuery(".protein").html(pro + " grams of protein" + " or " + pro_per + " grams per meal");
                jQuery(".carbs").html(carbs + " grams of carbs" + " or " + carbs_per + " grams per meal");
                jQuery(".fats").html(fats + " grams of fats" + " or " + fats_per + " grams per meal");
            } else if (goal == 'balanced') {
                calories = result + 250;
                jQuery(".cals").html(calories + " calories");
                jQuery(".protein").html(pro + " grams of protein" + " or " + pro_per + " grams per meal");
                jQuery(".carbs").html(carbs + " grams of carbs" + " or " + carbs_per + " grams per meal");
                jQuery(".fats").html(fats + " grams of fats" + " or " + fats_per + " grams per meal");
            } else {
                calories = result;
                jQuery(".cals").html(calories + " calories");
                jQuery(".protein").html(pro + " grams of protein" + " or " + pro_per + " grams per meal");
                jQuery(".carbs").html(carbs + " grams of carbs" + " or " + carbs_per + " grams per meal");
                jQuery(".fats").html(fats + " grams of fats" + " or " + fats_per + " grams per meal");
            }
            jQuery("span#rbm_calories").html(calories);
            jQuery("span#rbm_protien").html(pro + "g");
            jQuery("span#rbm_carbs").html(carbs + "g");
            jQuery("span#rbm_fat").html(fats + "g");
            var data = {
                'action': 'save_diet_calorie_macro',
                'goal': goal,
                'calories': calories,
                'protein': pro,
                'carbs': carbs,
                'fats': fats
            };
            jQuery.post(ajaxurl, data, function (response) {
                var obj = jQuery.parseJSON(response);
                jQuery(".popAlrt").addClass("msg-box");
                if (obj.error == 0) {
                    if (goal == 'weight-loss' || goal == 'lose-weight') {
                        jQuery(".goal-str").html('lose weight');
                    } else if (goal == 'balanced') {
                        jQuery(".goal-str").html('maintain your weight');
                    } else {
                        jQuery(".goal-str").html('gain muscle');
                    }
                    jQuery(".result").removeClass("dnone");
                    jQuery("#onboarding_finish_quiz").removeClass("dnone");
                    jQuery('button#back_to_opt_rslt').addClass('dnone');
                    jQuery("#succ_msg").removeClass("dnone");
                    jQuery("#succ_msg span").text(obj.msg);
                    jQuery("html").removeClass("open-popup");
                    jQuery('#calc_diet_calorie_macro').removeClass('proc-loader');
                } else {
                    jQuery("#err_msg").removeClass("dnone");
                    jQuery("#err_msg span").text(obj.msg);
                }
            });
        } else {
            jQuery('#calc_diet_calorie_macro').removeClass('proc-loader');
            jQuery(".popAlrt").addClass("msg-box");
            jQuery("#err_msg").removeClass("dnone");
            jQuery("#err_msg span").text(err_msg);
        }
        setTimeout(function () {
            jQuery(".popAlrt").removeClass("msg-box");
        }, 3000);
        jQuery("div.preloader").removeClass('dblock');
    });


    jQuery('a.change_diet_goal').click(function (e) {
        jQuery('div.nutrition-goal-box').removeClass('opt-result-hide-box').addClass('opt-result-show-box');
        jQuery('div.nutrition-manual-enter-macro-sec').removeClass('opt-result-show-box').addClass('opt-result-hide-box');
        jQuery('div.diet-macro-calc-sec').removeClass('opt-result-show-box').addClass('opt-result-hide-box');
        jQuery('section#diet_calc_box').removeClass('dnone');
        jQuery("html").addClass("open-popup");
    });

    jQuery('#cancel_calc_frm').click(function (e) {
        jQuery('section#diet_calc_box').addClass('dnone');
        jQuery("html").removeClass("open-popup");
        jQuery('section#diet_calc_box').removeClass('phs2-nut-manual-enter-macro');
    });

    /*jQuery('div#meal_rating_box i').click(function (e) {
        var umid = jQuery(this).data('umid');
        var rating = jQuery(this).data('rating');
        if (umid && rating) {
            jQuery("div.preloader").addClass('dblock');
            var data = {
                'action': 'user_rate_meal',
                'umid': umid,
                'rating': rating
            };
            jQuery.post(ajaxurl, data, function (response) {
                var obj = jQuery.parseJSON(response);
                jQuery(".popAlrt").addClass("msg-box");
                if (obj.error == 0) {
                    jQuery("#succ_msg").removeClass("dnone");
                    jQuery("#succ_msg span").text(obj.msg);
                    setTimeout(function () {
                        jQuery(".popAlrt").removeClass("msg-box");
                        location.reload();
                    }, 3000);
                } else {
                    jQuery("#err_msg").removeClass("dnone");
                    jQuery("#err_msg span").text(obj.msg);
                    setTimeout(function () {
                        jQuery(".popAlrt").removeClass("msg-box");
                    }, 3000);
                }
            });
            jQuery("div.preloader").removeClass('dblock');
        } else {
            jQuery(".popAlrt").addClass("msg-box");
            jQuery("#err_msg").removeClass("dnone");
            jQuery("#err_msg span").text("Something goes wrong, please try again!");
            setTimeout(function () {
                jQuery(".popAlrt").removeClass("msg-box");
                location.reload();
            }, 3000);
        }
    });*/

    //Days Slider
    if (jQuery("#slider-range").length) {
        var i;
        jQuery("#slider-range").slider({
            step: 0,
            range: true,
            min: 0,
            max: 6,
            values: [1, 5],
            slide: function (event, ui) {
                jQuery("div.preloader").addClass('dblock');
                jQuery(".popAlrt").removeClass("msg-box");
                jQuery("#err_msg").addClass("dnone");
                var tot_days = parseInt(ui.values[1] - ui.values[0]) + 1;
                if (tot_days >= 2) {
                    for (i = 0; i <= 6; i++) {
                        jQuery('#day_' + i).removeClass('highlight-slide');
                    }
                    for (i = ui.values[0]; i <= ui.values[1]; i++) {
                        jQuery('#day_' + i).addClass('highlight-slide');
                    }
                    var start_day = getDayFromIndex(ui.values[0]);
                    var end_day = getDayFromIndex(ui.values[1]);
                    var day_format = start_day + '-' + end_day + ' - ' + tot_days;
                    jQuery("#slctd_days").text(day_format);
                    jQuery("#slct_days").val(tot_days);
                    jQuery("#slct_days_range").val(ui.values[0] + '-' + ui.values[1]);
                    var ppsa = [];
                    if (pricing_pg_slcted_allergies.length > 0) {
                        jQuery.each(pricing_pg_slcted_allergies, function (key, mlgid) {
                            ppsa.push({'mlgid': mlgid, 'allergy': jQuery('#allergies_' + mlgid).val()});
                        });
                    }
                    var pppgids = jQuery("input[name='slct_meal_pr_day[]']").map(function () {
                        var pppgid = jQuery(this).data('pppgid');
                        var plnid = this.value;
                        if (pppgid != '') {
                            return {pgid: pppgid, plnid: plnid};
                        }
                    }).get();
                    var data = {
                        'action': 'get_slcted_days_plans',
                        'tot_days': tot_days,
                        //'is_allergies': jQuery('input[name="is_allergies_'+slct_pln_grp+'"]:checked').val(),
                        'allergies': ppsa, //jQuery('#allergies_'+slct_pln_grp).val()
                        'pppgids': pppgids
                    };
                    jQuery.post(ajaxurl, data, function (response) {
                        var obj = jQuery.parseJSON(response);
                        if (obj.error == 0 && obj.msg != "") {
                            jQuery("#plan_block").html(obj.msg);
                            jQuery("div.pln-slider-nav .slick-active").removeClass('is-active');
                            jQuery("div.pln-slider-nav .slick-current").addClass('is-active');
                        } else {
                            jQuery("#err_msg").removeClass("dnone");
                            jQuery(".popAlrt").addClass("msg-box");
                            jQuery("#err_msg span").text(obj.msg);
                            setTimeout(function () {
                                jQuery(".popAlrt").removeClass("msg-box");
                            }, 3000);
                        }
                    });
                } else {
                    jQuery("#err_msg").removeClass("dnone");
                    jQuery(".popAlrt").addClass("msg-box");
                    jQuery("#err_msg span").text("You must select at least 2 days.");
                    setTimeout(function () {
                        jQuery(".popAlrt").removeClass("msg-box");
                    }, 3000);
                    jQuery("div.preloader").removeClass('dblock');
                    return false;
                }
                jQuery("div.preloader").removeClass('dblock');
            }
        });
    }

    jQuery('div#plan_block').on('click', 'div.pln-grp-box ul#meal_per_day_list li', function (e) {
        if (this.id) {
            var ul_cls = '';
            var mpd = jQuery(this).data('mpd');
            if (mpd) {
                switch (mpd) {
                    case 1:
                        ul_cls = 'onemlprdy';
                        break;
                    case 2:
                        ul_cls = 'twomlprdy';
                        break;
                    case 3:
                        ul_cls = 'threemlprdy';
                        break;
                    default:
                        ul_cls = 'onemlprdy';
                        break;
                }
            }
            jQuery(this).closest('ul').removeClass('onemlprdy').removeClass('twomlprdy').removeClass('threemlprdy').addClass(ul_cls);
            var slct_pln_grp_id = jQuery(this).data('plngid');
            jQuery("#slct_meal_pr_day_" + slct_pln_grp_id).val(this.id);
            if (slct_pln_grp_id) {
                var pln_initial_payment = jQuery("#pln_wk_initial_payment_" + slct_pln_grp_id + "_" + this.id).val();
                if (pln_initial_payment) {
                    jQuery("#weekly_initial_payment_" + slct_pln_grp_id + "_" + this.id).val(pln_initial_payment);
                    var pip = parseFloat(pln_initial_payment);
                    pip = pip.toFixed(2);
                    jQuery("span#wk_total_box_" + slct_pln_grp_id + "_" + this.id).text("$" + pip);
                }
                var pln_billing_payment = jQuery("#pln_wk_billing_payment_" + slct_pln_grp_id + "_" + this.id).val();
                if (pln_billing_payment) {
                    jQuery("#weekly_billing_payment_" + slct_pln_grp_id + "_" + this.id).val(pln_billing_payment);
                }
                jQuery("#allergies_" + slct_pln_grp_id).val("");
            }
        }
    });

    jQuery("#onboarding_finish_quiz").click(function () {
        window.location.href = 'upcoming';
    });

    jQuery(".tpImg").click(function () {
        jQuery(".Prdct-dtl-pop").addClass('dnone');
        jQuery(this).parent('.tpSngl').next('.Prdct-dtl-pop').removeClass('dnone');
    });

    jQuery(".tpCntnt h3").click(function () {
        jQuery(".Prdct-dtl-pop").addClass('dnone');
        jQuery(this).parent('.tpCntnt').parent('.tpSngl').next('.Prdct-dtl-pop').removeClass('dnone');
    });

    jQuery(".see-detail").click(function () {
        jQuery(".Prdct-dtl-pop").addClass('dnone');
        jQuery(this).parent('.tpCntnt').parent('.tpSngl').next('.Prdct-dtl-pop').removeClass('dnone');
    });

    jQuery('div.week-content-box').on('click', 'div.prdctList ul#wdml_ul .tpImg', function (e) {
        jQuery(this).parent('.tpSngl').next('.Prdct-dtl-pop').removeClass('dnone').addClass('open-meal-detail');
    });

    jQuery('div.week-content-box').on('click', 'div.prdctList ul#wdml_ul .tpCntnt h3', function (e) {
        jQuery(this).parent('.tpCntnt').parent('.tpSngl').next('.Prdct-dtl-pop').removeClass('dnone').addClass('open-meal-detail');
    });
    /* for upcoming page meal detail popup */
    jQuery('div.week-content-box').on('click', 'div.prdctList ul#wdml_ul .tpCntnt', function (e) {
        jQuery(this).parent('.tpSngl').next('.Prdct-dtl-pop').removeClass('dnone').addClass('open-meal-detail');
    });
    jQuery('div.week-content-box').on('click', 'div.prdctList ul#wdml_ul .Prdct-dtl-pop .cancelBtn a', function (e) {
        jQuery('div.week-content-box div.prdctList ul#wdml_ul .Prdct-dtl-pop').removeClass('open-meal-detail');
    });
    /* for current page meal detail popup */
    jQuery('div.prdctList').on('click', 'ul.meal-design .tpCntnt', function (e) {
        jQuery(this).parent('.tpSngl').next('.Prdct-dtl-pop').removeClass('dnone').addClass('open-meal-detail');
    });
    jQuery('div.prdctList').on('click', 'ul.meal-design .Prdct-dtl-pop .cancelBtn a', function (e) {
        jQuery('div.prdctList ul.meal-design .Prdct-dtl-pop').removeClass('open-meal-detail');
    });
    /* for delivery history page meal detail popup */
    jQuery('section.sec-dlvry-hitry').on('click', 'div.prdctList ul li .tpCntnt', function (e) {
        jQuery(this).parent('.tpSngl').next('.Prdct-dtl-pop').removeClass('dnone').addClass('open-meal-detail');
    });
    jQuery('section.sec-dlvry-hitry').on('click', 'div.prdctList ul .Prdct-dtl-pop .cancelBtn a', function (e) {
        jQuery('section.sec-dlvry-hitry div.prdctList ul .Prdct-dtl-pop').removeClass('open-meal-detail');
    });

    jQuery('span#pln_box').on('click', 'span.pg-box ul#mpd_list li', function (e) {
        if (this.id) {
            jQuery("#slct_plan_id").val(this.id);
            var meal_pr_day = jQuery(this).data('mpd');
            if (meal_pr_day && meal_pr_day > 0) {
                jQuery("#slct_meal_pr_day").val(meal_pr_day);
            }
            var slct_pln_grp = jQuery("#slct_group").val();
            if (slct_pln_grp && slct_pln_grp > 0) {
                jQuery("span.plan-box-" + slct_pln_grp).addClass("dnone");
                jQuery("span#pln_" + slct_pln_grp + "_" + this.id).removeClass("dnone");
            }
        }
    });

    /*
     * Method: function for save customer's plan settong's info
     */
    jQuery("#save_plan_setting").click(function () {
        jQuery(this).prop('disabled', true);
        jQuery(this).addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        var data = {
            'action': 'save_plan_setting',
            'fdata': jQuery("#cust_pln_setting_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".popAlrt").addClass("msg-box");
            if (obj.error == 0) {
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
            } else {
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
            }
            jQuery('#save_plan_setting').removeClass('proc-loader');
            jQuery('#save_plan_setting').prop('disabled', false);
        });
        jQuery("div.preloader").removeClass('dblock');
    });

    jQuery('form#upcming_mng_wk_frm').on('click', 'ul#upcming_mwk_mpd_list li', function (e) {
        if (this.id) {
            var wk_meal_pr_day = jQuery(this).data('wkmpd');
            var wk_date = jQuery(this).data('wkdate');
            jQuery('ul#week_box li#li_' + wk_date + ' div.acrdnPanel div.manageWeek div.topDateRange input#week_plan_id').val(this.id);
            if (wk_meal_pr_day && wk_meal_pr_day > 0 && wk_date) {
                jQuery('ul#week_box li#li_' + wk_date + ' div.acrdnPanel div.manageWeek div.topDateRange input#week_meal_pr_day').val(wk_meal_pr_day);
                jQuery('ul#upcming_mwk_mpd_list').siblings('span.plnlst' + wk_date).addClass('dnone');
                jQuery('ul#upcming_mwk_mpd_list').siblings('span#pln_' + wk_date + '_' + this.id).removeClass('dnone');
                jQuery('ul#upcming_mwk_mpd_list').find('li[data-wkdate="' + wk_date + '"] >label>p').addClass('mngweek-uncheck-pln');
                jQuery(this).find('label>input#mealplan_' + wk_date + '_' + this.id).siblings('p.mngweek-uncheck-pln').removeClass('mngweek-uncheck-pln');
                jQuery('div#confrm_popup' + wk_date + ' .mwInner div#mngwk_settings' + wk_date + ' span.cfirm-pln-sumry').addClass('dnone');
                jQuery('div#confrm_popup' + wk_date + ' .mwInner div#mngwk_settings' + wk_date + ' span#cfirm_pln_sumry' + this.id).removeClass('dnone');
            }
        }
    });

    /*
     * function for show/hide allergies box
     */

    jQuery(document).on('click tap', "input:radio.alyn", function (e) {
        e.preventDefault();
        var slct_pln_grp = jQuery(this).data('pricingpgid');
        if (slct_pln_grp) {
            jQuery(".alrgy_yn_" + slct_pln_grp).removeClass("pricing-ysno");
            if (this.value && this.value == 'yes') {
                //jQuery(".al_box").addClass("dnone");
                jQuery("#al_box_" + slct_pln_grp).removeClass("dnone");
                jQuery("#alrgy_y_" + slct_pln_grp).addClass("pricing-ysno");
                if (pricing_pg_slcted_allergies.length > 0) {
                    if (jQuery.inArray(slct_pln_grp, pricing_pg_slcted_allergies) == -1) {
                        pricing_pg_slcted_allergies.push(slct_pln_grp);
                    }
                } else {
                    pricing_pg_slcted_allergies.push(slct_pln_grp);
                }
                jQuery("#pg" + slct_pln_grp).addClass("pricing-pln-box");
                jQuery(".alrgy_yn_" + slct_pln_grp).closest('div.rdoBtns').removeClass("alry_no").addClass("alry_yes");
            } else {
                jQuery("#allergies_" + slct_pln_grp).val("");
                //var slct_pln_grp_id = jQuery("#slct_pln_grp").val();
                //if(slct_pln_grp){
                var slct_pln_id = jQuery("ul.mpdl" + slct_pln_grp + " li.active").attr('id');
                //}
                if (slct_pln_grp && slct_pln_id) {
                    var pln_initial_payment = jQuery("#pln_wk_initial_payment_" + slct_pln_grp + "_" + slct_pln_id).val();
                    if (pln_initial_payment) {
                        jQuery("#weekly_initial_payment_" + slct_pln_grp + "_" + slct_pln_id).val(pln_initial_payment);
                        var pip = parseFloat(pln_initial_payment);
                        pip = pip.toFixed(2);
                        jQuery("span#wk_total_box_" + slct_pln_grp + "_" + slct_pln_id).text("$" + pip);
                    }
                    var pln_billing_payment = jQuery("#pln_wk_billing_payment_" + slct_pln_grp + "_" + slct_pln_id).val();
                    if (pln_billing_payment) {
                        jQuery("#weekly_billing_payment_" + slct_pln_grp + "_" + slct_pln_id).val(pln_billing_payment);
                    }
                }
                jQuery("#al_box_" + slct_pln_grp).addClass("dnone");
                //jQuery(".al_box").addClass("dnone");
                jQuery("#alrgy_n_" + slct_pln_grp).addClass("pricing-ysno");
                if (pricing_pg_slcted_allergies.length > 0 && jQuery.inArray(slct_pln_grp, pricing_pg_slcted_allergies) != -1) {
                    pricing_pg_slcted_allergies.splice(jQuery.inArray(slct_pln_grp, pricing_pg_slcted_allergies), 1);
                }
                jQuery("#pg" + slct_pln_grp).removeClass("pricing-pln-box");
                jQuery(".alrgy_yn_" + slct_pln_grp).closest('div.rdoBtns').removeClass("alry_yes").addClass("alry_no");
            }
        }
    });

    /*
     * function for update plan price
     */

    jQuery("div#plan_block").on('change', 'div.wstbInr div.wsSngl div.wssInr div.wsTab div.slctBx select.allergies_list', function () {
        var slct_pln_grp_id = jQuery(this).data('pripgid');
        if (slct_pln_grp_id) {
            var slct_pln_id = jQuery("div#pg" + slct_pln_grp_id + " ul.mpdl" + slct_pln_grp_id + " li.active").attr("id");
        }
        if (slct_pln_grp_id && slct_pln_id) {
            var allergy_cost = 0;
            if (this.value == "gluten_dairy_free") {
                allergy_cost = 15;
            } else {
                allergy_cost = 10;
            }
            var pln_initial_payment = jQuery("#pln_wk_initial_payment_" + slct_pln_grp_id + "_" + slct_pln_id).val();
            var pln_billing_payment = jQuery("#pln_wk_billing_payment_" + slct_pln_grp_id + "_" + slct_pln_id).val();
            if (pln_initial_payment) {
                if (this.value) {
                    var tpip = parseFloat(pln_initial_payment) + parseFloat(allergy_cost);
                    tpip = tpip.toFixed(2);
                    jQuery("#weekly_initial_payment_" + slct_pln_grp_id + "_" + slct_pln_id).val(tpip);
                    jQuery("span#wk_total_box_" + slct_pln_grp_id + "_" + slct_pln_id).text("$" + tpip);
                } else {
                    jQuery("#weekly_initial_payment_" + slct_pln_grp_id + "_" + slct_pln_id).val(pln_initial_payment);
                    jQuery("span#wk_total_box_" + slct_pln_grp_id + "_" + slct_pln_id).text("$" + pln_initial_payment);
                }
            }
            if (pln_billing_payment) {
                if (this.value) {
                    var tpbp = parseFloat(pln_billing_payment) + parseFloat(allergy_cost);
                    tpbp = tpbp.toFixed(2);
                    jQuery("#weekly_billing_payment_" + slct_pln_grp_id + "_" + slct_pln_id).val(tpbp);
                } else {
                    jQuery("#weekly_billing_payment_" + slct_pln_grp_id + "_" + slct_pln_id).val(pln_billing_payment);
                }
            }
        }
    });

    /*jQuery("div#plan_block").on('change', 'div.wstbInr div.wsSngl div.wssInr div.wsTab div.wstbInr div.sbWrap div.slctBx select.allergies_list',function(){
     var slct_pln_grp_id = jQuery("#slct_pln_grp").val();
     if(slct_pln_grp_id){
     var slct_pln_id = jQuery("ul.mpdl"+slct_pln_grp_id+" li.active").attr("id");
     }
     if(slct_pln_grp_id && slct_pln_id){
     var allergy_cost = 0;
     if(this.value == "gluten_dairy_free"){
     allergy_cost = 15;
     }else{
     allergy_cost = 10;
     }
     var pln_initial_payment = jQuery("#pln_wk_initial_payment_"+slct_pln_grp_id+"_"+slct_pln_id).val();
     var pln_billing_payment = jQuery("#pln_wk_billing_payment_"+slct_pln_grp_id+"_"+slct_pln_id).val();
     if(pln_initial_payment){
     if(this.value){
     var tpip = parseFloat(pln_initial_payment) + parseFloat(allergy_cost);
     tpip = tpip.toFixed(2);
     jQuery("#weekly_initial_payment_"+slct_pln_grp_id+"_"+slct_pln_id).val(tpip);
     jQuery("span#wk_total_box_"+slct_pln_grp_id+"_"+slct_pln_id).text("$"+tpip);
     }else{
     jQuery("#weekly_initial_payment_"+slct_pln_grp_id+"_"+slct_pln_id).val(pln_initial_payment);
     jQuery("span#wk_total_box_"+slct_pln_grp_id+"_"+slct_pln_id).text("$"+pln_initial_payment);
     }
     }
     if(pln_billing_payment){
     if(this.value){
     var tpbp = parseFloat(pln_billing_payment) + parseFloat(allergy_cost);
     tpbp = tpbp.toFixed(2);
     jQuery("#weekly_billing_payment_"+slct_pln_grp_id+"_"+slct_pln_id).val(tpbp);
     }else{
     jQuery("#weekly_billing_payment_"+slct_pln_grp_id+"_"+slct_pln_id).val(pln_billing_payment);
     }
     }
     }
     });*/

    jQuery('.unSkpWeek').click(function (n) {
        if (confirm("Are you sure you want to unskip this week ?")) {
            if (this.id) {
                jQuery("div.preloader").addClass('dblock');
                jQuery(".popAlrt").removeClass("msg-box");
                jQuery("#err_msg").addClass("dnone");
                jQuery("#succ_msg").addClass("dnone");
                var data = {
                    'action': 'unskip_week',
                    'wsdate': this.id
                };
                jQuery.post(ajaxurl, data, function (response) {
                    var obj = jQuery.parseJSON(response);
                    setTimeout(function () {
                        jQuery("div.preloader").removeClass("dblock");
                        jQuery(".popAlrt").addClass("msg-box");
                    }, 1000);
                    if (obj.error == 0) {
                        jQuery("#succ_msg").removeClass("dnone");
                        jQuery("#succ_msg span").text(obj.msg);
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                        setTimeout(function () {
                            jQuery(".popAlrt").removeClass("msg-box");
                        }, 3000);
                    } else {
                        jQuery("#err_msg").removeClass("dnone");
                        jQuery("#err_msg span").text(obj.msg);
                        setTimeout(function () {
                            jQuery(".popAlrt").removeClass("msg-box");
                        }, 3000);
                    }
                });
            } else {
                jQuery(".popAlrt").addClass("msg-box");
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text("Something goes wrong, please try again!");
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    location.reload();
                }, 3000);
            }
        }
    });

    /*
     * Method: function for skip all weeks by user
     */
    /*jQuery("button#cancel_acc_skip_weeks").click(function(){
     if (confirm("Are you sure you want to skip all weeks ?")) {
     jQuery("#cancel_acc_skip_weeks").addClass('proc-loader');
     jQuery("div.preloader").addClass('dblock');
     jQuery(".popAlrt").removeClass("msg-box");
     jQuery("#err_msg").addClass("dnone");
     jQuery("#succ_msg").addClass("dnone");
     jQuery("#msg_box").addClass("dnone");
     var data = {
     'action': 'user_skip_weeks'
     };
     jQuery.post(ajaxurl, data, function(response) {
     var obj = jQuery.parseJSON(response);
     jQuery(".popAlrt").addClass("msg-box");
     if(obj.error == 0){
     jQuery("#succ_msg").removeClass("dnone");
     jQuery("#succ_msg span").text(obj.msg);
     setTimeout(function(){ 
     jQuery(".popAlrt").removeClass("msg-box");location.reload();
     jQuery("#cancel_acc_skip_weeks").removeClass('proc-loader');
     }, 3000);
     }else{
     jQuery("#err_msg").removeClass("dnone");
     jQuery("#err_msg span").text(obj.msg);
     setTimeout(function(){ 
     jQuery(".popAlrt").removeClass("msg-box");
     jQuery("#cancel_acc_skip_weeks").removeClass('proc-loader');
     }, 3000);
     }
     });
     jQuery("div.preloader").removeClass('dblock');
     }
     });*/

    /*
     * Method: function for cancel account by user
     */

    jQuery(".mngwk_goback").click(function () {
        if (this.value) {
            jQuery("#confrm_popup" + this.value).addClass("dnone");
            jQuery("#mang_wk_popup" + this.value).removeClass("dnone");
        }
    });

    jQuery('a.page-scroll').bind('click', function (event) {
        var $anchor = jQuery(this);
        var a = jQuery($anchor.attr('href')).offset().top - 10;
        jQuery('html, body').stop().animate({
            scrollTop: a
        }, 1000, 'easeInOutExpo');
        event.preventDefault();
    });

    /*
     * Method: function for add juice product into cart and redirect user to checkout page
     */
    jQuery("a[name='jp_buy_now']").click(function () {
        var jpid = jQuery(this).data('jpid');
        var jpvid = jQuery(this).data('jpvid');
        if (jpid && jpvid) {
            var data = {
                'action': 'add_juice_product_to_cart',
                'jp_id': jpid,
                'jpv_id': jpvid
            };
            jQuery.post(ajaxurl, data, function (response) {
                var obj = jQuery.parseJSON(response);
                if (obj.error == 0) {
                    window.location.href = obj.rurl;
                } else {
                    alert(obj.msg);
                }
            });
        } else {
            alert('Something goes wrong, please try again!');
        }
    });

    /*
     * Method: function for add insulated bag into cart and redirect user to checkout page
     */
    jQuery("button[name='id_buy_now']").click(function () {
        var ibid = jQuery(this).data('ibid');
        if (ibid) {
            jQuery("button[name='id_buy_now']").addClass('proc-loader');
            var data = {
                'action': 'add_ins_bag_product_to_cart',
                'ib_id': ibid,
            };
            jQuery.post(ajaxurl, data, function (response) {
                var obj = jQuery.parseJSON(response);
                if (obj.error == 0) {
                    window.location.href = obj.rurl;
                } else {
                    jQuery("button[name='id_buy_now']").removeClass('proc-loader');
                    alert(obj.msg);
                }
            });
        } else {
            alert('Something goes wrong, please try again!');
        }
    });


    /*
     * Method: function for signup by user from juice page
     */

    jQuery("#jp-signup-continue").click(function () {
        jQuery("div.preloader").addClass('dblock');
        jQuery("form#jp_signup_frm input").removeClass("error");
        jQuery("form#jp_signup_frm p.err-txt").remove();
        var data = {
            "action": "signup_user",
            "fdata": jQuery("#jp_signup_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                location.reload();
            } else {
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#" + key).addClass("error");
                    jQuery("input#" + key).after("<p class='err-txt'>" + value + "<p>");
                    if (key == 'zip_code_alert') {
                        jQuery("#zip_code").addClass("error");
                        jQuery("input#zip_code").after("<p class='err-txt'>" + value + "<p>");
                    }
                });
            }
            jQuery("div.preloader").removeClass('dblock');
        });
    });

    /*
     * Method: function for show signin form and hide signup form 
     */

    jQuery("#jp_signin_lnk").click(function () {
        jQuery("#jp_signin_box").removeClass("dnone");
        jQuery("#jp_signup_box").addClass("dnone");
    });

    /*
     * Method: function for show signup form and hide signin form
     */

    jQuery("#jp_signup_lnk").click(function () {
        jQuery("#jp_signup_box").removeClass("dnone");
        jQuery("#jp_signin_box").addClass("dnone");
    });

    /*
     * Method: function for signin by user from juice page
     */

    jQuery("#jp-signin").click(function () {
        jQuery("div.preloader").addClass('dblock');
        jQuery("form#jp_signin_frm input").removeClass("error");
        jQuery("form#jp_signin_frm p.err-txt").remove();
        var data = {
            "action": "sigin_jp_user",
            "fdata": jQuery("#jp_signin_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                location.reload();
            } else {
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#" + key).addClass("error");
                    jQuery("input#" + key).after("<p class='err-txt'>" + value + "<p>");
                    if (key == 'si_password') {
                        jQuery("form#jp_signin_frm div#rmbrforgt").addClass("mrgntop28px");
                    }
                });
            }
            jQuery("div.preloader").removeClass('dblock');
        });
    });

    /*
     * Method: function for add new email field on free meal page
     */

    jQuery("a#add_new_email_fld").click(function () {
        jQuery("#add_more_email_fld").append('<div class="snglInpt"><input type="email" class="wdth90prer" placeholder="Enter email address" name="ref_emails[]" value=""><a href="javascript:void(0);" class="remove_email_fld"><img src="' + tc_site_url.site_url + '/wp-content/plugins/toughcookies/images/cross.jpeg" alt="Remove" title="Remove" class="cursor-point"></a></div>');
    });

    /*
     * Method: function for remove email field on free meal page
     */

    jQuery('#add_more_email_fld').on("click", ".remove_email_fld", function (e) {
        jQuery(this).parent('div').remove();
    })

    /*
     * Method: function for send invitaion to friends from Free Meals page
     */

    jQuery("#send_invitation_to_frnds").click(function () {
        jQuery("div.preloader").addClass('dblock');
        jQuery("#send_invitation_msg").empty();
        var data = {
            "action": "send_invitation_to_frnds",
            "fdata": jQuery("#send_invitation_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.msg != '') {
                var msg_cls;
                if (obj.error == 0) {
                    var msg_cls = 'txt-color-green';
                    jQuery("input[name*='ref_emails']").val('');
                } else {
                    var msg_cls = 'txt-color-red';
                }
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#send_invitation_msg").html("<p class='" + msg_cls + "'>" + value + "<p>");
                });
            }
            jQuery("div.preloader").removeClass('dblock');
            if (obj.error == 0) {
                setTimeout(function () {
                    location.reload();
                }, 500);
            }
        });
    });

    jQuery("#nutrition_manually_enter_macros_btn").click(function () {
        jQuery('div.nutrition-goal-box').removeClass('opt-result-show-box').addClass('opt-result-hide-box');
        jQuery('div.nutrition-manual-enter-macro-sec').removeClass('opt-result-hide-box').addClass('opt-result-show-box');
        jQuery('section#diet_calc_box').addClass('phs2-nut-manual-enter-macro');

    });
    jQuery("#nutrition_get_started_btn").click(function () {
        jQuery('div.nutrition-goal-box').removeClass('opt-result-show-box').addClass('opt-result-hide-box');
        jQuery('div.diet-macro-calc-sec').removeClass('opt-result-hide-box').addClass('opt-result-show-box');
        jQuery('section#diet_calc_box').removeClass('phs2-nut-manual-enter-macro');
    });

    //jQuery('#discount_coupon_button').click(function() {
    jQuery(document).on('click tap', "#discount_coupon_button", function (e) {
        e.preventDefault();
        jQuery(this).prop('disabled', true);
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        //jQuery('#pmpro_message').hide()
        var coupon_code = jQuery('#discount_coupon_code').val();
        var level_id = jQuery('#level').val();
        if (coupon_code && level_id) {
            jQuery("div.preloader").addClass('dblock');
            //jQuery('#discount_coupon_button').attr('disabled', 'disabled');
            var data = {
                "action": "apply_discount_coupon",
                "coupon_code": coupon_code,
                "plan_id": level_id,
            };
            jQuery.post(ajaxurl, data, function (response) {
                var obj = jQuery.parseJSON(response);
                /*jQuery.each(obj.msg, function( key, value ) {
                 jQuery("#pmpro_message").html("<p>"+value+"<p>");
                 });*/
                jQuery(".popAlrt").addClass("msg-box");
                if (obj.error == 0) {
                    jQuery('#has_discount_code_p').addClass('dnone');
                    jQuery('#dis_code_sec').addClass('dnone');
                    jQuery('#chng_discount_code_p').removeClass('dnone');
                    jQuery('#signup_step3_future_price_banner').removeClass('dnone');
                    jQuery('#frstweek-alert-wrap').addClass('dnone');
                    //jQuery('#dis_code').html('(' + coupon_code + ')');
                    //jQuery('#discount_amt').html('-' + obj.coupon_amt);
                    //jQuery('#disc_li').removeClass('dnone');
                    if(obj.show_cpn_discnt == 1){
                        //jQuery('#dis_code').html('('+coupon_code+')');
                        //jQuery('#discount_amt').html('-'+obj.coupon_amt);
                        jQuery('#dis_code').html('('+obj.coupon_dis_code+')');
                        jQuery('#discount_amt').html('-'+obj.coupon_dis_amt);
                        jQuery('#disc_li').removeClass('dnone');
                    }
                    if(obj.show_gc_bal == 1){
                        jQuery('#gc_discount_amt').html('-'+obj.giftcards_balance);
                        jQuery('#gc_disc_li').removeClass('dnone');
                    }
                    if (obj.pl_detail.id > 0) {
                        jQuery('#pickup_li').trigger('click');
                        show_pickup_location_detail(obj.pl_detail.id);
                        //select_pickup_location(obj.pl_detail.id);
                        jQuery('input#force_pickup_location_selected').val(1);
                        jQuery('input#is_pickup_location_selected').val(1);
                        //jQuery('input#pickup_location').val(obj.pl_detail.id);
                        //jQuery('p#location_near_you').html('<span class="pl-name">'+obj.pl_detail.name+'</span> <span  class="pl-address">'+obj.pl_detail.address+'</span>');
                        jQuery('div.location-near').addClass('disabled');
                        //jQuery('div#selected_pickup_location_detail').addClass('dnone');
                        jQuery('li#delivery_li').addClass('disabled');
                    } else if (jQuery('input#force_pickup_location_selected').val() == 1) {
                        jQuery('input#force_pickup_location_selected').val(0);
                        jQuery('input#is_pickup_location_selected').val(0);
                        jQuery('input#pickup_location').val(0);
                        var loc_under_five_miles_distances = jQuery('input#total_count_under_five_miles_distances').val();
                        var zipcode = jQuery('input#bzipcode').val();
                        jQuery('p.location_near_you').html(loc_under_five_miles_distances + ' location near ' + zipcode + '...');
                        jQuery('div.location-near').removeClass('disabled');
                        jQuery('div#selected_pickup_location_detail').addClass('dnone');
                        jQuery('li#delivery_li').removeClass('disabled');
                    }

                    jQuery('#sales_tax').html(obj.sales_tax_amt);
                    jQuery('#plan_weekly_total').html(obj.newTotalPrice);
                    jQuery('span#checkout_top_banner_first_wk_price').html(obj.newTotalPrice);
                    jQuery('span#checkout_top_banner_future_wk_price').html(obj.newTotalBillingPrice);
                    
                    
                    jQuery("#succ_msg").removeClass("dnone");
                    jQuery("#succ_msg span").html(obj.msg);
                } else {
                    //jQuery('#pmpro_message').show();
                    //jQuery('#pmpro_message').removeClass('pmpro_success').addClass('pmpro_error');
                    jQuery('#frstweek-alert-wrap').removeClass('dnone');
                    jQuery('#signup_step3_future_price_banner').addClass('dnone');
                    ///jQuery('#disc_li').addClass('dnone');
                    if(obj.show_cpn_discnt == 1){
                        //jQuery('#dis_code').html('('+coupon_code+')');
                        //jQuery('#discount_amt').html('-'+obj.coupon_amt);
                        jQuery('#dis_code').html('('+obj.coupon_dis_code+')');
                        jQuery('#discount_amt').html('-'+obj.coupon_dis_amt);
                        jQuery('#disc_li').removeClass('dnone');
                    }
                    /*else{
                        jQuery('#disc_li').addClass('dnone');
                    }*/
                    if(obj.show_gc_bal == 1){
                        jQuery('#gc_discount_amt').html('-'+obj.giftcards_balance);
                        jQuery('#gc_disc_li').removeClass('dnone');
                    }
                    /*else{
                        jQuery('#gc_disc_li').addClass('dnone');
                    }*/
                    //jQuery('html, body').animate({scrollTop: 0}, 800);
                    if (jQuery('input#is_pickup_location_selected').val() == 0) {
                        jQuery('input#pickup_location').val(0);
                        var loc_under_five_miles_distances = jQuery('input#total_count_under_five_miles_distances').val();
                        var zipcode = jQuery('input#bzipcode').val();
                        jQuery('p#location_near_you').html(loc_under_five_miles_distances + ' location near ' + zipcode + '...');
                        jQuery('div.location-near').removeClass('disabled');
                        jQuery('div#selected_pickup_location_detail').addClass('dnone');
                    }
                    jQuery("#err_msg").removeClass("dnone");
                    jQuery.each(obj.msg, function (key, value) {
                        jQuery("#err_msg span").html("<p>" + value + "<p>");
                    });
                }
                /*jQuery('#sales_tax').html(obj.sales_tax_amt);
                jQuery('#plan_weekly_total').html(obj.newTotalPrice);
                jQuery('span#checkout_top_banner_first_wk_price').html(obj.newTotalPrice);
                jQuery('span#checkout_top_banner_future_wk_price').html(obj.newTotalBillingPrice);*/
                jQuery('#discount_coupon_button').removeAttr('disabled');
                //jQuery("div.preloader").removeClass('dblock');
                //jQuery('#discount_coupon_button').removeAttr('disabled');
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
                jQuery("div.preloader").removeClass('dblock');
            });
        } else {
            jQuery(".popAlrt").addClass("msg-box");
            jQuery("#err_msg").removeClass("dnone");
            jQuery("#err_msg span").text('Please enter discount code.');
            jQuery('#discount_coupon_button').removeAttr('disabled');
            setTimeout(function () {
                jQuery(".popAlrt").removeClass("msg-box");
            }, 3000);
            jQuery("div.preloader").removeClass('dblock');
            //jQuery('#pmpro_message').show().removeClass('pmpro_success').addClass('pmpro_error').html('Please enter discount code.');
        }
        //jQuery('#discount_coupon_button').removeAttr('disabled');
        //setTimeout(function(){ jQuery(".popAlrt").removeClass("msg-box"); }, 3000);
    });

    /*jQuery('#discount_coupon_button').click(function() {
     jQuery("div.preloader").addClass('dblock');
     jQuery('#pmpro_message').hide()
     var coupon_code = jQuery('#discount_coupon_code').val();
     var level_id = jQuery('#level').val();
     if(coupon_code && level_id){
     jQuery('#discount_coupon_button').attr('disabled', 'disabled');
     var data = {
     "action": "apply_discount_coupon",
     "coupon_code" : coupon_code,
     "plan_id" : level_id,
     };          
     jQuery.post(ajaxurl, data, function(response) {
     var obj = jQuery.parseJSON(response);
     jQuery.each(obj.msg, function( key, value ) {
     jQuery("#pmpro_message").html("<p>"+value+"<p>");
     });
     if(obj.error == 0){
     jQuery('#has_discount_code_p').addClass('dnone');
     jQuery('#dis_code_sec').addClass('dnone');
     jQuery('#chng_discount_code_p').removeClass('dnone');
     jQuery('#signup_step3_future_price_banner').removeClass('dnone');
     jQuery('#frstweek-alert-wrap').addClass('dnone');
     jQuery('#disc_li').removeClass('dnone');
     jQuery('#discount_amt').html('-'+obj.coupon_amt);
     }else{
     jQuery('#pmpro_message').show();
     jQuery('#pmpro_message').removeClass('pmpro_success').addClass('pmpro_error');
     jQuery('#frstweek-alert-wrap').removeClass('dnone');
     jQuery('#signup_step3_future_price_banner').addClass('dnone');
     jQuery('#disc_li').addClass('dnone');
     jQuery('html, body').animate({scrollTop: 0}, 800);
     }
     jQuery('#plan_weekly_total').html(obj.newTotalPrice);
     jQuery('span#checkout_top_banner_first_wk_price').html(obj.newTotalPrice);
     jQuery('span#checkout_top_banner_future_wk_price').html(obj.newTotalBillingPrice);
     jQuery('#discount_coupon_button').removeAttr('disabled');
     });
     }else{
     jQuery('#pmpro_message').show().removeClass('pmpro_success').addClass('pmpro_error').html('Please enter discount code.');
     alert('Please enter discount code.');
     }
     jQuery("div.preloader").removeClass('dblock');
     })*/

    /*
     * Method: function for reactivate account by user
     */

    jQuery(document).on('click tap', "button#reactivate_account", function (e) {
        e.preventDefault();
        jQuery(this).prop('disabled', true);
        jQuery("div.preloader").addClass('dblock');
        jQuery(this).addClass('proc-loader');
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        jQuery("#msg_box").addClass("dnone");
        var data = {'action': 'user_reactivate_account'};
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".popAlrt").addClass("msg-box");
            if (obj.error == 0) {
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
                if (obj.redirecturl) {
                    window.location.href = obj.redirecturl;
                }
            } else {
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    jQuery('#reactivate_account').removeClass('proc-loader');
                    jQuery("div.preloader").removeClass('dblock');
                    jQuery("#reactivate_account").prop('disabled', false);
                }, 3000);
            }
        });
    });

    jQuery('#has_discount_code_a').click(function () {
        jQuery('div#dis_code_sec').removeClass('dnone');
        jQuery('#has_discount_code_p').addClass('dnone');
        jQuery('#discount_coupon_code').focus();
    });

    jQuery('#chng_discount_code_a').click(function () {
        jQuery('div#dis_code_sec').removeClass('dnone');
        jQuery('#chng_discount_code_p').addClass('dnone');
        jQuery('#discount_coupon_code').focus();
    });

    /*
     * Method: function for register partner user
     */

    jQuery("#partner_signup").click(function () {
        jQuery("div.preloader").addClass('dblock');
        jQuery("form#partner_sign_up_frm input").removeClass("error");
        jQuery("form#partner_sign_up_frm select").removeClass("error");
        jQuery("form#partner_sign_up_frm p.err-txt").remove();
        var data = {
            "action": "partner_user_signup",
            "fdata": jQuery("#partner_sign_up_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0 && obj.msg != "") {
                if (obj.redirecturl) {
                    window.location.href = obj.redirecturl;
                }
            } else {
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#" + key).addClass("error");
                    jQuery("input#" + key).after("<p class='err-txt'>" + value + "<p>");
                    if (key == 'pi_state' || key == 'gym_state' || key == 'gym_members') {
                        jQuery("select#" + key).after("<p class='err-txt'>" + value + "<p>");
                    }
                });
            }
            jQuery("div.preloader").removeClass('dblock');
        });
    });

    /*
     * Method: function for close addon meal confirmation popup
     */

    jQuery(".addon-meals .crossIcn>span").click(function () {
        var wksd = jQuery(this).data('wksd');
        if (wksd) {
            jQuery("div#chml_addon_ml_confrm_popup" + wksd).addClass("dnone");
            jQuery("div#chng_meals_box" + wksd).removeClass("dnone");
        }
    });

    /*
     * Method: function for select un-select add-on meals from addon meal confirmation popup
     */

    /*jQuery('input.addon-wk-day-meal').live('click', function(){
     var mldaydate = jQuery(this).data('daydate');
     var mlwsdate = jQuery(this).data('wsdate');
     var mlprice = jQuery(this).data('price');
     var tot_admls_itms = jQuery('#chml_addon_ml_confrm_popup'+mlwsdate+' .addon-meals .am-footer .amf-single input#tot_admls_itms').val();
     var tot_amls_cost = jQuery('#chml_addon_ml_confrm_popup'+mlwsdate+' .addon-meals .am-footer .amf-single input#tot_amls_cost').val();
     if(jQuery(this).is(":checked")){
     jQuery('#dm_'+mldaydate+' .addon_meal input#'+this.value).prop("checked",true);
     tot_admls_itms = parseInt(tot_admls_itms) + 1;
     tot_amls_cost = parseFloat(tot_amls_cost) + parseFloat(mlprice);
     }else{
     jQuery('#dm_'+mldaydate+' .addon_meal input#'+this.value).prop("checked",false);
     tot_admls_itms = parseInt(tot_admls_itms) - 1;
     tot_amls_cost = parseFloat(tot_amls_cost) - parseFloat(mlprice);
     }
     jQuery("div#chml_addon_ml_confrm_popup"+mlwsdate+" div.am-footer div.amf-single span.admls_tot_itms").empty().html(tot_admls_itms);
     jQuery("div#chml_addon_ml_confrm_popup"+mlwsdate+" div.am-footer div.amf-single input#tot_admls_itms").val(tot_admls_itms);
     jQuery("div#chml_addon_ml_confrm_popup"+mlwsdate+" div.am-footer div.amf-single span.amls_tot_cost").empty().html('+$'+tot_amls_cost.toFixed(2));
     jQuery("div#chml_addon_ml_confrm_popup"+mlwsdate+" div.am-footer div.amf-single input#tot_amls_cost").val(tot_amls_cost.toFixed(2));
     });*/

    jQuery("#zip_submit").click(function () {
        var data = {
            "action": "check_zipcode_availability",
            "fdata": jQuery("#get_zipcode").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                jQuery("#zip_code").removeClass("error");
                jQuery("p.err-txt").remove();
                jQuery('#zip_submit').attr('data-dismiss', 'modal');
                jQuery("#zip_submit").click();
                window.location.reload();
            } else {
                if (obj.redirecturl) {
                    window.location.href = obj.redirecturl;
                }
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#" + key).addClass("error");
                    jQuery('.err-txt').text(value);
                    if (key == 'zip_code') {
                        jQuery("#zip_code").addClass("error");
                    }
                });
            }
            jQuery("div.preloader").removeClass('dblock');
        });
    });

    jQuery(".frstweek-alert").click(function () {
        jQuery(".frstweek-alert-wrap").remove();
    });

    jQuery('a#uc_optimize_result_box').click(function (e) {
        jQuery('div.optimize-result-box').removeClass('opt-result-hide-box').addClass('opt-result-show-box');
        jQuery('div.manual-enter-macro-sec').removeClass('opt-result-show-box').addClass('opt-result-hide-box');
        jQuery('div.diet-macro-calc-sec').removeClass('opt-result-show-box').addClass('opt-result-hide-box');
        jQuery('section#uc_optimize_result_box').removeClass('dnone');
        jQuery('button#back_to_opt_rslt').addClass('dnone');
        jQuery('a.learn-about-macros-btn').addClass('dnone');
        jQuery("button#onboarding_finish_quiz").addClass('dnone');
        jQuery("html").addClass("open-popup");
    });

    jQuery('a#uc_cls_opti_rslt_box').click(function (e) {
        jQuery('section#uc_optimize_result_box').addClass('dnone');
        jQuery("html").removeClass("open-popup");
        if (jQuery(".onboarding-box-inner").hasClass("get-opt-results")) {
            jQuery('.onboarding-box-inner').removeClass('get-opt-results');
        }
        if (jQuery(".ppwMain").hasClass("opt-results")) {
            jQuery('.ppwMain').removeClass('opt-results');
        }
    });

    jQuery("#manually_enter_macros_btn").click(function () {
        jQuery('div.optimize-result-sec').addClass('opt-result-hide-box');
        jQuery('div.manual-enter-macro-sec').addClass('opt-result-show-box');
        jQuery('button#back_to_opt_rslt').removeClass('dnone');
        if (jQuery('#is_manual_macros_set').val() == 1) {
            jQuery("button#onboarding_finish_quiz").removeClass('dnone');
        }
    });

    jQuery("#uc_get_started_btn").click(function () {
        jQuery('div.optimize-result-sec').addClass('opt-result-hide-box');
        jQuery('div.diet-macro-calc-sec').addClass('opt-result-show-box');
        jQuery('.onboarding-box-inner').addClass('get-opt-results');
        jQuery('.ppwMain').addClass('opt-results');
        jQuery('button#back_to_opt_rslt').removeClass('dnone');
        jQuery('a.learn-about-macros-btn').removeClass('dnone');
    });

    jQuery("#back_to_opt_rslt").click(function () {
        jQuery('div.optimize-result-box').removeClass('opt-result-hide-box').addClass('opt-result-show-box');
        jQuery('div.manual-enter-macro-sec').removeClass('opt-result-show-box').addClass('opt-result-hide-box');
        jQuery('.onboarding-box-inner').removeClass('get-opt-results');
        jQuery('.ppwMain').removeClass('opt-results');
        jQuery('div.diet-macro-calc-sec').removeClass('opt-result-show-box').addClass('opt-result-hide-box');
        jQuery('button#back_to_opt_rslt').addClass('dnone');
        jQuery('a.learn-about-macros-btn').addClass('dnone');
        jQuery("button#onboarding_finish_quiz").addClass('dnone');
        if (jQuery('.ppwMain').hasClass("set-macro-wrapper")) {
            jQuery('.ppwMain').removeClass('set-macro-wrapper');
        }
    });

    /*
     * Method: Function for apply coupon code by user from acc > payment page
     */

    //jQuery('#acc_apply_promocode').click(function() {
    jQuery(document).on('click tap', "#acc_apply_promocode", function (e) {
        e.preventDefault();
        jQuery(this).prop('disabled', true);
        jQuery(this).addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        var data = {
            "action": "acc_apply_promocode",
            "promo_code": jQuery('#acc_promo_code').val()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".popAlrt").addClass("msg-box");
            if (obj.error == 0) {
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").html(obj.msg);
                if (obj.tot_credit_points > 0) {
                    jQuery("span#usr_tot_credit").text(obj.tot_credit_points.toFixed(2));
                }
                setTimeout(function () {
                    jQuery('#acc_promo_code').val('');
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
            } else {
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").html(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
            }
            jQuery('#acc_apply_promocode').removeAttr('disabled');
            jQuery("div.preloader").removeClass('dblock');
        });
        jQuery('#acc_apply_promocode').removeClass('proc-loader');
    });

    /*
     * Method: function for trigger li or meal card event on upcoming > change meal page
     */

    var is_li_trigr_wth_inc_qty = 0;
    //jQuery(".chng-sngl-ml-card").mouseup(function(){
    //jQuery(".chng-sngl-ml-card").click(function(){
    //jQuery(document).on('mouseup click',".chng-sngl-ml-card",function(){
    jQuery(document).on('click tap', ".chng-sngl-ml-card", function (e) {
        e.preventDefault();
        jQuery(this).addClass('meal_card_slcted');
        var ml_id = jQuery(this).data('menu');
        var weekdate = jQuery(this).data('weekdate');
        var li_triggered = jQuery(this).data('li-triggered');
        is_li_trigr_wth_inc_qty = 0;
        if (li_triggered) {
            jQuery('#increase_qty_' + weekdate + '_' + ml_id).click();
            is_li_trigr_wth_inc_qty = 1;
            return false;
        }
    });

    /*
     * Method: function for increase quantity of any meal from change meals page
     */

    //jQuery(".increase-qty").click(function(){
    jQuery(document).on('click tap', ".increase-qty", function (e) {
        e.preventDefault();
        var ml_id = parseInt(this.value);
        var meal_per_day = jQuery(this).data('wkplanmpd');
        if (meal_per_day && is_li_trigr_wth_inc_qty == 0 && jQuery(this).is(':disabled') == false) {
            var meal_date = jQuery(this).data('daydate');
            if (meal_date) {
                var wsdate = jQuery(this).data('wsdate');
                var wsdate_ky = wsdate.replace(new RegExp('-', 'g'), "");
                var md = meal_date.replace(new RegExp('-', 'g'), "");
                var mlqty = parseInt(jQuery('input#meal_' + md + '_' + ml_id).data('dmlqty'));//jQuery('input#mlqty_'+md+'_'+ml_id).val());
                var tot_ml_qty = mlqty + 1;
                jQuery('input#meal_' + md + '_' + ml_id).attr('checked', true);
                //jQuery(this).addClass('hglt-qty-btn');
                //jQuery(this).closest('li').addClass('hglt-qty-li');
                //jQuery('#decrese_qty_'+md+'_'+ml_id).addClass('hglt-qty-btn');
                var slcted_al_mls = jQuery('input#slcted_allmls_' + md).val();
                if (slcted_al_mls) {
                    slcted_all_ml_with_qty = jQuery.parseJSON(slcted_al_mls);
                }
                var slcted_mn_mls = jQuery('input#slcted_mainmls_' + md).val();
                if (slcted_mn_mls) {
                    slcted_main_ml_with_qty = jQuery.parseJSON(slcted_mn_mls);
                }
                var slcted_adon_mls = jQuery('input#slcted_addonmls_' + md).val();
                if (slcted_adon_mls) {
                    slcted_addon_ml_with_qty = jQuery.parseJSON(slcted_adon_mls);
                }
                jQuery.each(jQuery("input[name='meals" + md + "[]']:checked"), function (key, ml_obj) {
                    if (ml_id == parseInt(ml_obj.value)) {
                        var ml_price = jQuery(this).data('price');
                        if (ml_price > 0) {//add-on meals
                            slcted_addon_ml_with_qty.push(ml_id);
                        } else {//main meals
                            slcted_main_ml_with_qty.push(ml_id);
                        }
                        slcted_all_ml_with_qty.push(ml_id);
                    }
                });
                var tot_all_mls = slcted_all_ml_with_qty.length;
                var tot_main_mls = slcted_main_ml_with_qty.length;
                var tot_addon_mls = slcted_addon_ml_with_qty.length;
                //if(jQuery(this).parent().closest('li').hasClass('li_adon_'+md) && tot_main_mls < meal_per_day){
                //return false;
                //}
                if (tot_main_mls > meal_per_day) {
                    //var numOccurences = jQuery.grep(slcted_main_ml_with_qty, function (elem) {
                    //return elem === ml_id;
                    //}).length;
                    slcted_all_ml_with_qty.pop(ml_id);
                    slcted_main_ml_with_qty.pop(ml_id);
                    //if(numOccurences == 1){
                    //if(jQuery('.li_main_'+md).hasClass('hglt-qty-li')){
                    //jQuery('.li_main_'+md).removeClass('hglt-qty-li');
                    //}
                    //jQuery('#decrese_qty_'+md+'_'+ml_id).removeClass('hglt-qty-btn');
                    //jQuery('#increase_qty_'+md+'_'+ml_id).removeClass('hglt-qty-btn');
                    //}
                    jQuery('input#slcted_allmls_' + md).val(JSON.stringify(slcted_all_ml_with_qty));
                    jQuery('input#slcted_mainmls_' + md).val(JSON.stringify(slcted_main_ml_with_qty));
                    jQuery(".popAlrt").addClass("msg-box");
                    jQuery("#succ_msg").addClass("dnone");
                    jQuery("#err_msg").removeClass("dnone");
                    jQuery("#err_msg span").text('You can select any ' + meal_per_day + ' meals per day.');
                    setTimeout(function () {
                        jQuery(".popAlrt").removeClass("msg-box");
                    }, 3000);
                    return false;
                } else {
                    jQuery(this).addClass('hglt-qty-btn');
                    jQuery('#decrese_qty_' + md + '_' + ml_id).addClass('hglt-qty-btn');
                    jQuery(this).closest('li').addClass('hglt-qty-li');
                    jQuery(this).closest('li').data('li-triggered', 0);
                    jQuery('input#slcted_allmls_' + md).val(JSON.stringify(slcted_all_ml_with_qty));
                    jQuery('input#slcted_mainmls_' + md).val(JSON.stringify(slcted_main_ml_with_qty));
                    jQuery('input#slcted_addonmls_' + md).val(JSON.stringify(slcted_addon_ml_with_qty));
                    jQuery('input#meal_' + md + '_' + ml_id).data('dmlqty', tot_ml_qty);
                    jQuery('input#mlqty_' + md + '_' + ml_id).val(tot_ml_qty);
                    var calories = jQuery(this).data('calories');
                    var protien = jQuery(this).data('protien');
                    var carbs = jQuery(this).data('carbs');
                    var fat = jQuery(this).data('fat');
                    var tot_cal = jQuery('span#tot_calories' + md).text();
                    var tot_pro = jQuery('span#tot_protein' + md).text();
                    var tot_car = jQuery('span#tot_carbs' + md).text();
                    var tot_fat = jQuery('span#tot_fat' + md).text();
                    var tot_calories, tot_protien, tot_carbs, tot_fats = 0;
                    tot_calories = parseInt(tot_cal) + parseInt(calories);
                    tot_protien = parseInt(tot_pro) + parseInt(protien);
                    tot_carbs = parseInt(tot_car) + parseInt(carbs);
                    tot_fats = parseInt(tot_fat) + parseInt(fat);
                    jQuery(this).parent().closest('div').addClass('meal_slcted');
                    jQuery('span#tot_calories' + md).text(tot_calories);
                    jQuery('span#tot_protein' + md).text(tot_protien);
                    jQuery('span#tot_carbs' + md).text(tot_carbs);
                    jQuery('span#tot_fat' + md).text(tot_fats);
                    var daydate = jQuery(this).data('daydate');
                    if (daydate) {
                        var wmd = daydate.replace(new RegExp('-', 'g'), "");
                        jQuery('span#tot_added_meal' + wmd).text(tot_all_mls + '/' + meal_per_day);
                    }
                    if (tot_all_mls >= meal_per_day) {
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmheader div#cmhdrBtm' + wsdate_ky).addClass('cmhdrgrn');
                        if (jQuery('button#next' + wsdate_ky).is(':disabled') == true) {
                            jQuery('a#sav_meal' + wsdate_ky).addClass("glow-blue-btn");
                        } else {
                            jQuery('button#next' + wsdate_ky).addClass("chng-ml-nxt-btn-glow");
                        }
                    } else {
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmheader div#cmhdrBtm' + wsdate_ky).removeClass('cmhdrgrn');
                        jQuery('button#next' + wsdate_ky).removeClass("chng-ml-nxt-btn-glow");
                        jQuery('a#sav_meal' + wsdate_ky).removeClass("glow-blue-btn");
                    }
                    if (tot_main_mls == meal_per_day) {
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .li_adon_' + wmd).removeClass('is-not-active');
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .li_adon_' + wmd + ' .is_addon_ml').removeAttr("disabled");
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList').addClass('slcted_max_meals');
                    } else {
                        var chk = parseInt(tot_all_mls) - parseInt(tot_addon_mls);
                        jQuery('span#tot_added_meal' + wmd).text(chk + '/' + meal_per_day);
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd).addClass('is-not-active');
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' .is_addon_ml').attr("disabled", "true");
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' .is_addon_ml').prop('checked', false);
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList').removeClass('slcted_max_meals');
                    }
                    if ((typeof (tc_site_url.cmanpsds_arr[wsdate_ky]) != "undefined" && tc_site_url.cmanpsds_arr[wsdate_ky] !== null && tc_site_url.cmanpsds_arr[wsdate_ky] == 1) || (typeof (tc_site_url.cmanpsds_arr[wsdate_ky]) == "undefined")) {
                        if (tot_main_mls == meal_per_day && tot_addon_mls == 0) {
                            jQuery('#bd-mc-' + md).removeClass('bd-mc-hidden');
                        } else {
                            jQuery('#bd-mc-' + md).addClass('bd-mc-hidden');
                        }
                    }
                }
            }
        }
    });

    /*
     * Method: function for decrese quantity of any meal from change meals page
     */

    jQuery(".decrese-qty").click(function () {
        var ml_id = parseInt(this.value);
        var meal_per_day = jQuery(this).data('wkplanmpd');
        if (meal_per_day) {
            var meal_date = jQuery(this).data('daydate');
            if (meal_date) {
                var wsdate = jQuery(this).data('wsdate');
                var wsdate_ky = wsdate.replace(new RegExp('-', 'g'), "");
                var md = meal_date.replace(new RegExp('-', 'g'), "");
                var tot_ml_qty = parseInt(jQuery('input#mlqty_' + md + '_' + ml_id).val());
                if (tot_ml_qty > 0) {
                    var tot_ml_qty = tot_ml_qty - 1;
                    if (tot_ml_qty == 0) {
                        jQuery(this).closest('li').removeClass('hglt-qty-li');
                        jQuery(this).closest('.tpSngl').removeClass('meal_slcted');
                        jQuery(this).closest('.qty').removeClass('meal_slcted');
                        jQuery('input#meal_' + md + '_' + ml_id).attr('checked', false);
                        jQuery(this).removeClass('hglt-qty-btn');
                        jQuery('#increase_qty_' + md + '_' + ml_id).removeClass('hglt-qty-btn');
                        jQuery(this).closest('li').data('li-triggered', 1);
                    }
                    var slcted_al_mls = jQuery('input#slcted_allmls_' + md).val();
                    if (slcted_al_mls) {
                        slcted_all_ml_with_qty = jQuery.parseJSON(slcted_al_mls);
                    }
                    if (slcted_all_ml_with_qty.length > 0 && jQuery.inArray(ml_id, slcted_all_ml_with_qty) != -1) {
                        slcted_all_ml_with_qty.splice(jQuery.inArray(ml_id, slcted_all_ml_with_qty), 1);
                    }
                    var slcted_main_mls = jQuery('input#slcted_mainmls_' + md).val();
                    if (slcted_main_mls) {
                        slcted_main_ml_with_qty = jQuery.parseJSON(slcted_main_mls);
                    }
                    if (slcted_main_ml_with_qty.length > 0 && jQuery.inArray(ml_id, slcted_main_ml_with_qty) != -1) {
                        slcted_main_ml_with_qty.splice(jQuery.inArray(ml_id, slcted_main_ml_with_qty), 1);
                    }
                    var slcted_adon_mls = jQuery('input#slcted_addonmls_' + md).val();
                    if (slcted_adon_mls) {
                        slcted_addon_ml_with_qty = jQuery.parseJSON(slcted_adon_mls);
                    }
                    if (slcted_addon_ml_with_qty.length > 0 && jQuery.inArray(ml_id, slcted_addon_ml_with_qty) != -1) {
                        slcted_addon_ml_with_qty.splice(jQuery.inArray(ml_id, slcted_addon_ml_with_qty), 1);
                    }
                    var tot_main_mls = slcted_main_ml_with_qty.length;
                    if (tot_main_mls < meal_per_day) {
                        slcted_addon_ml_with_qty.length = 0;
                        slcted_all_ml_with_qty = slcted_main_ml_with_qty;
                    }
                    jQuery('input#slcted_allmls_' + md).val(JSON.stringify(slcted_all_ml_with_qty));
                    jQuery('input#slcted_mainmls_' + md).val(JSON.stringify(slcted_main_ml_with_qty));
                    jQuery('input#slcted_addonmls_' + md).val(JSON.stringify(slcted_addon_ml_with_qty));
                    var tot_all_mls = slcted_all_ml_with_qty.length;
                    var tot_addon_mls = slcted_addon_ml_with_qty.length;
                    jQuery('input#meal_' + md + '_' + ml_id).data('dmlqty', tot_ml_qty);
                    jQuery('input#mlqty_' + md + '_' + ml_id).val(tot_ml_qty);
                    var tot_calories = tot_protien = tot_carbs = tot_fats = 0;
                    if (slcted_all_ml_with_qty.length > 0) {
                        jQuery.each(slcted_all_ml_with_qty, function (key, mlid) {
                            var ml_cal = jQuery('input#meal_' + md + '_' + mlid).data('calories');
                            var ml_pro = jQuery('input#meal_' + md + '_' + mlid).data('protien');
                            var ml_carb = jQuery('input#meal_' + md + '_' + mlid).data('carbs');
                            var ml_fat = jQuery('input#meal_' + md + '_' + mlid).data('fat');
                            tot_calories = parseInt(tot_calories) + parseInt(ml_cal);
                            tot_protien = parseInt(tot_protien) + parseInt(ml_pro);
                            tot_carbs = parseInt(tot_carbs) + parseInt(ml_carb);
                            tot_fats = parseInt(tot_fats) + parseInt(ml_fat);
                        });
                    }
                    jQuery('span#tot_calories' + md).text(tot_calories);
                    jQuery('span#tot_protein' + md).text(tot_protien);
                    jQuery('span#tot_carbs' + md).text(tot_carbs);
                    jQuery('span#tot_fat' + md).text(tot_fats);
                    //jQuery(this).parent().closest('div').removeClass('meal_slcted');
                    var daydate = jQuery(this).data('daydate');
                    if (daydate) {
                        var wmd = daydate.replace(new RegExp('-', 'g'), "");
                        jQuery('span#tot_added_meal' + wmd).text(tot_all_mls + '/' + meal_per_day);
                    }
                    if (tot_all_mls >= meal_per_day) {
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmheader div#cmhdrBtm' + wsdate_ky).addClass('cmhdrgrn');
                    } else {
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmheader div#cmhdrBtm' + wsdate_ky).removeClass('cmhdrgrn');
                    }
                    if (tot_main_mls == meal_per_day) {
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .li_adon_' + wmd).removeClass('is-not-active');
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .li_adon_' + wmd + ' .is_addon_ml').removeAttr("disabled");
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList').addClass('slcted_max_meals');
                    } else {
                        var chk = parseInt(tot_all_mls) - parseInt(tot_addon_mls);
                        jQuery('span#tot_added_meal' + wmd).text(chk + '/' + meal_per_day);
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd).addClass('is-not-active').removeClass('hglt-qty-li').removeClass('meal_card_slcted');
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd).data('li-triggered', 1);
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' .is_addon_ml').attr("disabled", "true");
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' .is_addon_ml').prop('checked', false);
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' .is_addon_ml').data('dmlqty', 0);
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' input.qty_is_addon_ml').val(0);
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' button.increase-qty').removeClass('hglt-qty-btn');
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' button.decrese-qty').removeClass('hglt-qty-btn');
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' div.addon_meal').removeClass('meal_slcted');
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' div.min-max-box div.qty').removeClass('meal_slcted');
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList .snglMenuList .li_adon_' + wmd + ' input#meal_' + wmd + '_' + ml_id).attr('checked', false);
                        jQuery('div.chnageMealWrap .cmInner .container .cmmInr .cmBody div#dm_' + wmd + ' div.prdctList').removeClass('slcted_max_meals');
                    }
                    if ((typeof (tc_site_url.cmanpsds_arr[wsdate_ky]) != "undefined" && tc_site_url.cmanpsds_arr[wsdate_ky] !== null && tc_site_url.cmanpsds_arr[wsdate_ky] == 1) || (typeof (tc_site_url.cmanpsds_arr[wsdate_ky]) == "undefined")) {
                        if (tot_main_mls == meal_per_day && tot_addon_mls == 0) {
                            jQuery('#bd-mc-' + md).removeClass('bd-mc-hidden');
                        } else {
                            jQuery('#bd-mc-' + md).addClass('bd-mc-hidden');
                        }
                    }
                }
                jQuery('a#sav_meal' + wsdate_ky).removeClass("glow-blue-btn");
            }
        }
        return false;
    });

    /*
     * Method: function trigger when click on continue button on upcoming summary page
     */

    jQuery("#continue_upcming_pg_popup_msg").click(function () {
        var data = {
            "action": "set_upcming_pg_popup_msg_display_status"
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                jQuery('div.get-start-plan-up-del').removeClass('get-show');
                jQuery('body').removeClass('show-popup-msg');
                window.location.reload();
            }
        });
    });

    /*
     * Method: function trigger when click on continue button on upcoming > change meals page
     */

    jQuery("#continue_slct_meals_pg_popup_msg").click(function () {
        var data = {
            "action": "set_select_meals_pg_popup_msg_display_status"
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                jQuery('#slct_meal_pg_shw_status_val').val(0);
                jQuery('div#slct_meal_pg_block').removeClass('get-show');
            }
        });
    });

    jQuery('.bd-mc-close').click(function () {
        var parentId = jQuery(this).closest('.date-meal-box').attr('id');
        jQuery('#' + parentId + ' .bd-mc').addClass('bd-mc-hidden');
        var wsdteky = jQuery('#' + parentId + ' .bd-mc').data('wsdteky');
        var data = {
            "action": "set_change_meals_addon_notice_popup_show_date",
            "wsdteky": wsdteky
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                if (typeof (tc_site_url.cmanpsds_arr) != "undefined" && tc_site_url.cmanpsds_arr !== null) {
                    tc_site_url.cmanpsds_arr[wsdteky] = 0;
                }
            }
        });
    });

    jQuery(window).resize(function () {
        var screenHeight = jQuery(window).height();
        if (screenHeight <= 740) {
            jQuery('body').addClass('mng-wk-screen');
        } else {
            if (jQuery('body').hasClass('mng-wk-screen')) {
                jQuery('body').removeClass('mng-wk-screen');
            }
        }
    });

    var screenHeight = jQuery(window).height();
    if (screenHeight <= 740) {
        jQuery('body').addClass('mng-wk-screen');
    } else {
        if (jQuery('body').hasClass('mng-wk-screen')) {
            jQuery('body').removeClass('mng-wk-screen');
        }
    }

    /*
     * Method: function for set flag from on the menu page open popup
     */

    jQuery(document).on('click tap', ".tpImg, .tpCntnt h3, .tpCntnt > .see-detail", function (e) {
        e.preventDefault();
        var data = {
            "action": "set_flag_from_on_the_menu_page_open_popup",
            "mid": jQuery(this).closest('li').data('mid'),
            "flg": "add"
        };
        jQuery.post(ajaxurl, data, function (response) {
            //nothing to do
        });
    });

    /*
     * Method: function for remove flag from on the menu page open popup
     */

    jQuery(document).on('click tap', ".Prdct-dtl-pop .cancelBtn a", function (e) {
        var data = {
            "action": "set_flag_from_on_the_menu_page_open_popup",
            "mid": jQuery(this).closest('li').data('mid'),
            "flg": "remove"
        };
        jQuery.post(ajaxurl, data, function (response) {
            //nothing to do
        });
    });

    /*
     * Method: Function for manage week settings
     */

    jQuery(document).on('click tap', "button#cancel_account", function (e) {
        jQuery('body').addClass('prdctDtlPop');
        jQuery("#skip_next_wk_confirm").addClass("dnone");
        jQuery("#cancel_confirm").removeClass("dnone");
    });

    jQuery(document).on('click tap', "button#cancel_confirm_goback", function (e) {
        jQuery('body').removeClass('prdctDtlPop');
        jQuery("#cancel_confirm").addClass("dnone");
        jQuery("#skip_next_wk_confirm").addClass("dnone");
    });

    /*
     * Method: function for cancel account by user
     */

    jQuery(document).on('click tap', "button#confirm_cancel_account", function (e) {
        e.preventDefault();
        jQuery(this).prop('disabled', true);
        jQuery(this).addClass('proc-loader');
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        jQuery("#msg_box").addClass("dnone");
        var data = {'action': 'user_cancel_account'};
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".popAlrt").addClass("msg-box");
            if (obj.error == 0) {
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
                if (obj.redirecturl) {
                    window.location.href = obj.redirecturl;
                }
            } else {
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    jQuery('#confirm_cancel_account').removeClass('proc-loader');
                    jQuery("#confirm_cancel_account").prop('disabled', false);

                }, 3000);
            }
        });
    });

    jQuery(document).on('click tap', "button#cancel_acc_skip_weeks", function (e) {
        jQuery('body').addClass('prdctDtlPop');
        jQuery("#cancel_confirm").addClass("dnone");
        jQuery("#skip_next_wk_confirm").removeClass("dnone");
    });

    jQuery(document).on('click tap', "button#skip_confirm_goback", function (e) {
        jQuery('body').removeClass('prdctDtlPop');
        jQuery("#cancel_confirm").addClass("dnone");
        jQuery("#skip_next_wk_confirm").addClass("dnone");
    });

    /*
     * Method: function for skip all weeks by user
     */

    jQuery(document).on('click tap', "button#confirm_next_wk_skip", function (e) {
        e.preventDefault();
        jQuery(this).prop('disabled', true);
        jQuery(this).addClass('proc-loader');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        jQuery("#msg_box").addClass("dnone");
        var data = {
            'action': 'user_skip_weeks'
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".popAlrt").addClass("msg-box");
            if (obj.error == 0) {
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
                location.reload();
            } else {
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    jQuery('#confirm_next_wk_skip').removeClass('proc-loader');
                    jQuery("#confirm_next_wk_skip").prop('disabled', false);

                }, 3000);
            }
        });
    });

    /*
     * Method: function for save & send cancel account feeback to website admin user
     */

    jQuery(document).on('click tap', "button#cancel_account_feeback", function (e) {
        e.preventDefault();
        jQuery(this).prop('disabled', true);
        jQuery("div.preloader").addClass('dblock');
        jQuery(this).addClass('proc-loader');
        jQuery("form#cancel_acc_feedback_frm input").removeClass("error");
        jQuery("form#cancel_acc_feedback_frm p.err-txt").remove();
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#succ_msg").addClass("dnone");
        var data = {
            "action": "send_cancel_account_feeback",
            "fdata": jQuery("#cancel_acc_feedback_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".popAlrt").addClass("msg-box");
            if (obj.error == 0 && obj.msg != "") {
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
                if (obj.redirecturl) {
                    window.location.href = obj.redirecturl;
                }
            } else {
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#" + key).addClass("error");
                    jQuery("textarea#" + key).after("<p class='err-txt'>" + value + "<p>");
                    if (key == 'rating') {
                        jQuery("div#fd-frm-rat-box").after("<p class='err-txt'>" + value + "<p>");
                    }
                });
                jQuery(".popAlrt").removeClass("msg-box");
                jQuery('#cancel_account_feeback').removeClass('proc-loader');
                jQuery("div.preloader").removeClass('dblock');
                jQuery("#cancel_account_feeback").prop('disabled', false);
            }
        });
    });


    //When add to cart button click for giftcard product.
    jQuery(document).on('click tap', "button#gc_add_to_card", function (e) {
        e.preventDefault();
        var gc_prod_id = jQuery('#gc_add_to_card').data('gcid');
        if (gc_prod_id) {
            jQuery("form#gc_frm input").removeClass("error");
            jQuery("form#gc_frm textarea").removeClass("error");
            jQuery("form#gc_frm p.err-txt").remove();
            var product_data = jQuery('#gc_frm').serialize();
            jQuery(this).addClass('proc-loader');
            var data = {
                'action': 'add_giftcard_product_to_cart',
                'gc_id': gc_prod_id,
                'gift_data': product_data
            };
            jQuery.post(ajaxurl, data, function (response) {
                var obj = jQuery.parseJSON(response);
                if (obj.error == 0) {
                    jQuery('body').addClass('sidebar-open');
                    jQuery('#cart-body').html(obj.popup_html);
                    jQuery('#cart-total').html(obj.cart_tot);
                    jQuery('#cart_chout_btn').removeClass('dnone');
                } else {
                    jQuery.each(obj.msg, function (key, value) {
                        jQuery("#" + key).addClass("error");
                        if (key == 'gc_quantity') {
                            alert(value);
                        } else {
                            jQuery("#" + key).after("<p class='err-txt'>" + value + "<p>");
                        }
                    });
                }
                jQuery('#gc_add_to_card').removeClass('proc-loader');
            });
        } else {
            alert('Something goes wrong, please try again!');
        }
    });
    // prop uncheck when select manual amount option.
    jQuery("#giftcard_custom_amount").click(function (e) {
        e.preventDefault();
        jQuery('input[name="gc_variation_price"]').prop('checked', false);
        jQuery(".gc-amt").html(0);
    });
    jQuery("input#giftcard_custom_amount").keyup(function () {
        if (this.value) {
            jQuery(".gc-amt").html(this.value);
        } else {
            jQuery(".gc-amt").html(0);
        }
    });
    //click on email to recipt btn.
    jQuery('#email_recipient').prop('checked', false);
    jQuery('#email_recipient').click(function (e) {
        jQuery('#email-recipient-sec').removeClass('dnone');
        jQuery('#printathome').prop('checked', false);
    });
    jQuery('#printathome').click(function (e) {
        jQuery('#email-recipient-sec').addClass('dnone');
        jQuery('#email_recipient').prop('checked', false);
    });
    //cart itek popup close
    jQuery('.add-to-card-head img').click(function () {
        jQuery('body').removeClass('sidebar-open');
    });

    jQuery('.gift-cart').click(function () {
        jQuery('body').addClass('sidebar-open');
    });

    jQuery("input[name='gc_variation_price']").click(function () {
        if (this.value) {
            jQuery(".gc-amt").html(this.value);
            jQuery("#giftcard_custom_amount").val('');
        }
    });

    /*
     * Method: function for save customer's shipping info
     */

    jQuery("#save_shipping_info").click(function () {
        jQuery(this).prop('disabled', true);
        jQuery(this).addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery("form#cust_shipping_info_frm input").removeClass("error");
        jQuery("form#cust_shipping_info_frm p.err-txt").remove();
        var data = {
            'action': 'save_shopper_shipping_info',
            'fdata': jQuery("#cust_shipping_info_frm").serialize()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0 && obj.msg != "") {
                jQuery(".popAlrt").addClass("msg-box");
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    if (obj.redirecturl) {
                        window.location.href = obj.redirecturl;
                    }
                }, 3000);
            } else {
                jQuery.each(obj.msg, function (key, value) {
                    jQuery("#" + key).addClass("error");
                    jQuery("input#" + key).after("<p class='err-txt'>" + value + "<p>");
                });
            }
            jQuery("#save_shipping_info").prop('disabled', false);
            jQuery("#save_shipping_info").removeClass('proc-loader');
        });
        jQuery("div.preloader").removeClass('dblock');
    });

    /*
     * Method: function for plan pricing page slider
     */

    jQuery('.pln-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: false,
        centerPadding: '245px',
        prevArrow: false,
        nextArrow: false,
        fade: false,
        centerMode: true,
        accessibility: true,
        focusOnSelect: true,
        responsive: [
            {
                breakpoint: 1500,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '70px',
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 1240,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '70px',
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 991,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '100px',
                    slidesToShow: 1,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '44px',
                    slidesToShow: 1,
                }
            }
        ]
    });

    /*
     * Method: function for plan pricing page nav slider
     */

    jQuery('.pln-slider-nav').on('init', function (event, slick) {
        jQuery('.pln-slider-nav .slick-slide.slick-current').addClass('is-active');
    }).slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        dots: false,
        fade: false,
        focusOnSelect: false,
        infinite: false,
    });

    /*
     * Method: function for plan pricing page slider event
     */

    jQuery('.pln-slider').on('afterChange', function (event, slick, currentSlide) {
        jQuery('.pln-slider-nav').slick('slickGoTo', currentSlide);
        var currrentNavSlideElem = '.pln-slider-nav .slick-slide[data-slick-index="' + currentSlide + '"]';
        jQuery('.pln-slider-nav .slick-slide.is-active').removeClass('is-active');
        jQuery(currrentNavSlideElem).addClass('is-active');
        var pgid = '';
        switch (currentSlide) {
            case 0:
                pgid = 33;
                break;
            case 1:
                pgid = 34;
                break;
            case 2:
                pgid = 35;
                break;
            case 3:
                pgid = 36;
                break;
            case 4:
                pgid = 37;
                break;
        }
        if (pgid != '') {
            jQuery('.ml-pln-slider-main').addClass('dnone');
            jQuery('#ml_pln_slider_' + pgid).removeClass('dnone');
        }
    });

    /*
     * Method: function for plan pricing page nav slider event
     */

    jQuery('.pln-slider-nav').on('click', '.slick-slide', function (event) {
        event.preventDefault();
        var goToSingleSlide = jQuery(this).data('slick-index');
        jQuery('.pln-slider').slick('slickGoTo', goToSingleSlide);
        var pgid = jQuery(this).find("a.slidr-pln-nv").data('pgid');
        if (pgid) {
            jQuery('.ml-pln-slider-main').addClass('dnone');
            jQuery('#ml_pln_slider_' + pgid).removeClass('dnone');
        }
    });
    /*jQuery('.preview-slider .owl-carousel').owlCarousel({
     items : 3,
     itemsDesktop : [1199,3],
     itemsDesktopSmall : [980,3],
     itemsTablet: [768,2],
     itemsMobile : [479,2],
     navigation : true,
     pagination : false,
     });*/

    jQuery('.preview-slider .owl-carousel').owlCarousel({
        items: 3,
        loop: true,
        margin: 5,
        nav: true,
        autoplay: true,
        responsiveClass: true,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });

    jQuery('.preview-slider .ml-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        arrows: true,
        autoplaySpeed: 5000,
        dots: false,
        lazyLoad: 'ondemand',
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    arrows: false,
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 980,
                settings: {
                    arrows: false,
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    arrows: false,
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 479,
                settings: {
                    arrows: false,
                    slidesToShow: 2
                }
            }
        ]
    });

    jQuery('.testimonial-slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        arrows: false,
        centerMode: true,
        centerPadding: '122px',
        autoplaySpeed: 5000,
        dots: false,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    arrows: false,
                    centerMode: true,
                    centerPadding: '0px',
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 767,
                settings: {
                    arrows: false,
                    centerMode: false,
                    centerPadding: '0px',
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
    });

    /*
     * Method: function for reload account page after click on camcel button
     */

    jQuery(document).on('click tap', "button#acc_cancel_btn", function (e) {
        location.reload();
    });

    /*
     * Method: function for load footer drop-down pages
     */

    jQuery(document).on('change tap', "select#ftr-menu-itms", function (e) {
        if (this.value) {
            window.location.href = this.value;
        }
    });

    jQuery('.home-page-slider').slick({
        centerMode: true,
        //variableWidth: true,
        slidesToScroll: 1,
        infinite: false,
        centerPadding: '100px',
        slidesToShow: 4,
        autoplay: true,
        autoplaySpeed: 1000,
        responsive: [
            {
                breakpoint: 1600,
                settings: {
                    centerPadding: '40px',
                    infinite: false,
                    variableWidth: false,
                    //slidesToScroll: 1,
                    //slidesToShow: 4
                }
            },
            {
                breakpoint: 767,
                settings: {
                    centerPadding: '60px',
                    slidesToShow: 1,
                    //variableWidth: true,
                }
            }
        ]
    });
    /*jQuery('.home-page-slider').slick({
         centerMode: true,
        //variableWidth: true,
        slidesToScroll: 1,
        infinite: false,
        centerPadding: '100px',
        slidesToShow: 4,
        autoplay: true,
        autoplaySpeed: 1000,
        responsive: [
            {
                breakpoint: 1500,
                settings: {
                    centerPadding: '40px',
                    slidesToScroll: 1,
                    slidesToShow: 3
                }
            },

            {
                breakpoint: 1200,
                settings: {
                    centerPadding: '40px',
                    slidesToScroll: 1,
                    slidesToShow: 2
                }
            },

            {
                breakpoint: 767,
                settings: {
                    centerPadding: '60px',
                    slidesToShow: 1
                }
            }
        ]
    });*/

    jQuery('form#signin #username, form#signin #password').keyup(function () {
        if ((jQuery(this).val()).length) {
            jQuery('form#signin #woo-login-btn').addClass('active-btn');
        } else {
            var unmvl = jQuery('form#signin #username').val();
            var pwdvl = jQuery('form#signin #password').val();
            if (unmvl.length == 0 && pwdvl.length == 0) {
                jQuery('form#signin #woo-login-btn').removeClass('active-btn');
            }
        }
    });

    /*jQuery('form#signin #password').keyup(function () {
     if ((jQuery(this).val()).length) {
     jQuery('form#signin #woo-login-btn').addClass('active-btn');
     } else {
     jQuery('form#signin #woo-login-btn').removeClass('active-btn');
     }
     });*/

    /*
     * Method: function for skip all weeks by user
    */

    jQuery(document).on('click tap', "button#remove_user_pickup_location", function (e) {
        e.preventDefault();
        jQuery(this).prop('disabled', true);
        jQuery(this).addClass('proc-loader');
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        var data = {
            'action': 'remove_user_pickup_location'
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                setTimeout(function () {
                    jQuery("body").removeClass("prdctDtlPop");
                }, 3000);
                location.reload();
            } else {
                jQuery(".popAlrt").addClass("msg-box");
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
            }
            jQuery("#remove_user_pickup_location").prop('disabled', false);
            jQuery("#remove_user_pickup_location").removeClass('proc-loader');
        });
    });

    jQuery(document).on('click', "#pickup_li", function (e) {
        jQuery(this).addClass('active');
        jQuery('li#delivery_li').removeClass('active');
        jQuery('.pick-up-loction').removeClass('dnone');
        jQuery('#desired_pickup_location').removeClass('dnone');
        jQuery('input#special_delivery_instructions').addClass('dnone');
        jQuery('input#delivery_or_pickup').val(2);
        jQuery('h3.dlvry_pickup_hd').text('Personal information');
        if (jQuery('#is_pickup_location_selected').val() == 1) {
            jQuery('#selected_pickup_location_detail').removeClass('dnone');
        }
    });

    jQuery(document).on('click', "#delivery_li", function (e) {
        jQuery(this).addClass('active');
        jQuery('li#pickup_li').removeClass('active');
        jQuery('.pick-up-loction').addClass('dnone');
        jQuery('#desired_pickup_location').addClass('dnone');
        jQuery('input#delivery_or_pickup').val(1);
        jQuery('input#special_delivery_instructions').removeClass('dnone');
        jQuery('h3.dlvry_pickup_hd').text('Delivery information');
        if (jQuery('#is_pickup_location_selected').val() == 1) {
            jQuery('#selected_pickup_location_detail').addClass('dnone');
        }
    });

    jQuery(document).click(function (e) {
        var pickupLoctionContainer = jQuery(".pick-up-loction");
        if (!pickupLoctionContainer.is(e.target) && pickupLoctionContainer.has(e.target).length === 0) {
            jQuery('body').removeClass('show-map');
        }
    });

     jQuery('div.phs-2-manageWeek').on('click', 'div.up-manage-wk-tabs ul li', function (e) {
        var wktbid = jQuery(this).data('wktbid');
        var wkdt = jQuery(this).data('wkdt');
        if (wktbid && wkdt) {
            jQuery('li.wk-tab-' + wkdt).removeClass('active');
            jQuery(this).addClass('active');
            jQuery('div.manage-wk-options-pickup-' + wkdt).addClass('dnone');
            jQuery('div#' + wktbid).removeClass('dnone');
            if (jQuery('is_pickup_location_selected_' + wkdt).val() == 1) {
                jQuery('selected_pickup_location_detail_' + wkdt).addClass('dnone');
            }
        }
    });

    jQuery('div.phs-2-manageWeek').on('click', 'div.upcming-manage-wk-pickup-location div.nav-tabs-main ul li', function (e) {
        var dptbid = jQuery(this).data('dptbid');
        var dpdt = jQuery(this).data('dpdt');
        if (dptbid && dpdt) {
            jQuery('li.delry-pickup-tab-' + dpdt).removeClass('active');
            jQuery(this).addClass('active');
            jQuery('div.wk-delry-pickup-' + dpdt).addClass('dnone');
            jQuery('div#' + dptbid).removeClass('dnone');
            var tbid = jQuery(this).data('tbid');
            jQuery('#delivery_or_pickup_' + dpdt).val(tbid);            
            if (jQuery('#is_pickup_location_selected_' + dpdt).val() == 1) {
                jQuery('#selected_pickup_location_detail_' + dpdt).removeClass('dnone');
            }
        }
    });

    jQuery('div.phs-2-manageWeek').on('click', 'p.location_near_you', function (e) {
        var map_main_area_id = jQuery(this).next('.map-area-main').attr('id');
        if (typeof (map_main_area_id) != 'undefined') {
            jQuery('.map-area-main').addClass('dnone');
            jQuery('#' + map_main_area_id).removeClass('dnone');
        }
    });

    jQuery(document).on('click', ".delivery-review-star", function (e) {
        jQuery('#user_delivery_feedback_rating').val(jQuery(this).val());
    });

    jQuery(document).on('click', ".submit-delivery-rating", function (e) {
        var ml_id = jQuery(this).data('ml-id');
        jQuery('#ml_id').val(ml_id);
        var order_week = jQuery(this).data('order-week');
        jQuery('#ml_order_week').val(order_week);
        var order_week_day = jQuery(this).data('order-week-day');
        jQuery('#ml_order_week_day').val(order_week_day);
        jQuery('body').addClass('prdctDtlPop');
        jQuery('.ml-hstry-popup').removeClass('dnone');
        jQuery('.ml-dtil-popup').addClass('dnone');
    });
    
    /*
     * Method: function for save customer's account info
    */

    jQuery("#ml_feedback_without_comment, #ml_feedback_with_comment").click(function () {
        jQuery(this).prop('disabled', true);
        jQuery(this).addClass('proc-loader');
        jQuery("form#user_delivery_feeback_form input").removeClass("error");
        jQuery("form#user_delivery_feeback_form textarea").removeClass("error");
        jQuery("form#user_delivery_feeback_form p.err-txt").remove();
        var feedback_val = jQuery(this).data('feedback-val');
        var data = {
            'action': 'save_user_delivery_feedback',
            'fdata': jQuery("#user_delivery_feeback_form").serialize()+'&feedback_val='+feedback_val
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                jQuery(".popAlrt").addClass("msg-box");
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    if (obj.redirecturl) {
                        window.location.href = obj.redirecturl;
                    }
                }, 3000);
            } else {
                jQuery.each(obj.msg, function (key, value) {
                    if (key == 'ml_order_week' || key == 'ml_id') {
                        jQuery("div#frm-err-box").html("<p class='err-txt'>" + value + "<p>");
                    }else{
                        jQuery("#" + key).addClass("error");
                        jQuery("input#" + key).after("<p class='err-txt'>" + value + "<p><br>");
                        jQuery("textarea#" + key).after("<p class='err-txt'>" + value + "<p><br>");    
                    }
                });
            }
            jQuery("#ml_feedback_without_comment, #ml_feedback_with_comment").prop('disabled', false);
            jQuery("#ml_feedback_without_comment, #ml_feedback_with_comment").removeClass('proc-loader');
        });
    });

    /*
     * Method: function for close upcoming > manage week map popup
     */

    jQuery(".pick-up-loction .map-area-inr-hd .mapCrossIcn>span").click(function () {
        jQuery('body').removeClass('show-map');
    });

    jQuery(".phs-2-btmMacros .bmLeft").click(function(){
        jQuery(".phs-2-btmMacros ul").fadeToggle(70);
    });
    jQuery(document).on('click tap', "footer.footer-main .footer-bottom .mobile-item .chat-box", function (e) {
        jQuery('body').addClass('open-intercom-chat');
    });
//end document.ready function    
});

function getDayFromIndex(index) {
    var day;
    switch (index) {
        case 0 :
            day = 'Sun';
            break;
        case 1 :
            day = 'Mon';
            break;
        case 2 :
            day = 'Tue';
            break;
        case 3 :
            day = 'Wed';
            break;
        case 4 :
            day = 'Thu';
            break;
        case 5 :
            day = 'Fri';
            break;
        case 6 :
            day = 'Sat';
            break;
    }
    return day;
}

function calc_cals(known_bf, kg, pa, bf, age, ht, gender) {
    if (known_bf == 'yes') {
        return Math.round((370 + (21.6 * (kg - (kg * (bf / 100))))) * pa);
    } else {
        if (gender == "male") {
            return Math.round(((kg * 10) + (ht * 6.25) - (5 * age) + 5) * pa);
        } else {
            return Math.round(((kg * 10) + (ht * 6.25) - (5 * age) - 161) * pa);
        }
    }
}

function calc_macros(cals, goal, known_bf, pa, bf, kg) {
    var protein = 0;
    var carbs = 0;
    var fats = 0;
    if (known_bf == 'yes') {
        // If user knowns his BF
        var ffm = (kg - (kg * (bf / 100)));
        if (goal == 1) {
            // Lose bf. 
            cals = cals - 500;
            protein = ffm * 2.3;
            fats = ffm * .9;
            carbs = (cals - (protein * 4) - (fats * 9)) / 4;
        } else if (goal == 2) {
            // Muscle gain
            cals = cals + 250;
            protein = ffm * 1.8;
            fats = (cals * .25) / 9;
            carbs = (cals - (protein * 4) - (fats * 9)) / 4;
        } else {
            // Maintenance goal
            protein = ffm * 2.6;
            fats = ffm * 1.1;
            carbs = (cals - (protein * 4) - (fats * 9)) / 4;
        }
    } else {
        // User doesn't know his BF
        lbs = kg * 2.2;
        if (goal == 1) {
            // Lose bf. 
            cals = cals - 500;
            protein = (lbs * .8);
            fats = (lbs * .35);
            carbs = (cals - (protein * 4) - (fats * 9)) / 4;
        } else if (goal == 2) {
            // Muscle gain
            cals = cals + 250;
            protein = (lbs * .7);
            fats = (cals * .25) / 9;
            carbs = (cals - (protein * 4) - (fats * 9)) / 4;
        } else {
            // mainteance 
            protein = (lbs * .9);
            fats = (lbs * .40);
            carbs = (cals - (protein * 4) - (fats * 9)) / 4;
        }
    }
    return [Math.round(protein), Math.round(carbs), Math.round(fats)]
}

/*
 * Function for open add-on meals confirmation popup
 */

function confirmation_addon_meals(wsdate) {
    if (wsdate) {
        var wsdate_ky = wsdate.replace(new RegExp('-', 'g'), "");
        jQuery('#sav_meal' + wsdate_ky).addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        var slcted_meals = jQuery("input[name^='meals']:checked").map(function () {
            var mlprice = jQuery(this).data('price');
            if (mlprice != '') {
                var meal_date = jQuery(this).data('daydate');
                var wsdate = jQuery(this).data('wsdate');
                var mlqty = jQuery(this).data('dmlqty');
                var meal_id = this.value;
                return {meal_date: meal_date, meal_id: meal_id, wsdate: wsdate, mlprice: mlprice, mlqty: mlqty};
            }
        }).get();
        var data = {
            'action': 'filter_addon_meals',
            'wsdate': wsdate,
            'slcted_meals': slcted_meals
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                if (obj.wkadonml_html) {
                    jQuery("div#chml_addon_ml_confrm_popup" + wsdate_ky + " div.am-list").empty().html(obj.wkadonml_html);
                }
                if (obj.amls_tot_items) {
                    jQuery("div#chml_addon_ml_confrm_popup" + wsdate_ky + " div.am-footer div.amf-single span.admls_tot_itms").empty().html(obj.amls_tot_items);
                    jQuery("div#chml_addon_ml_confrm_popup" + wsdate_ky + " div.am-footer div.amf-single input#tot_admls_itms").val(obj.amls_tot_items);
                }
                if (obj.amls_tot_cost) {
                    jQuery("div#chml_addon_ml_confrm_popup" + wsdate_ky + " div.am-footer div.amf-single span.amls_tot_cost").empty().html('+$' + obj.amls_tot_cost);
                    jQuery("div#chml_addon_ml_confrm_popup" + wsdate_ky + " div.am-footer div.amf-single input#tot_amls_cost").val(obj.amls_tot_cost);
                }
                //setTimeout(function(){ 
                jQuery("div#chng_meals_box" + wsdate_ky).addClass('dnone');
                jQuery("div#chml_addon_ml_confrm_popup" + wsdate_ky).removeClass('dnone');
                //}, 300);
            } else if (obj.error == 1) {
                jQuery(".popAlrt").addClass("msg-box");
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                }, 3000);
            } else if (obj.error == 2) {
                save_week_day_meal(wsdate);
            }
            if (obj.error != 2) {
                jQuery('#sav_meal' + wsdate_ky).removeClass('proc-loader');
            }
        });
        jQuery("div.preloader").removeClass('dblock');
    }
}

/*
 * Function for save week change meals data
 */

function save_week_day_meal(wsdate) {
    if (wsdate) {
        var wksd_ky = wsdate.replace(new RegExp('-', 'g'), "");
        jQuery('#save_week_day_meal' + wksd_ky).addClass('proc-loader');
        jQuery("div.preloader").addClass('dblock');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        var meal_per_day = jQuery('#meal_per_day').val();
        var wedate = jQuery('div#chng_meals_box' + wksd_ky + ' div.cmheader #last_wk_day_date').val();
        var meals = jQuery("input[name^='meals']:checked").map(function () {
            var meal_date = jQuery(this).data('daydate');
            var wsdate = jQuery(this).data('wsdate');
            var wkplanmpd = jQuery(this).data('wkplanmpd');
            var mlprice = jQuery(this).data('price');
            var mlqty = jQuery(this).data('dmlqty');
            var meal_id = this.value;
            return {meal_date: meal_date, meal_id: meal_id, wsdate: wsdate, wkplanmpd: wkplanmpd, mlprice: mlprice, mlqty: mlqty};
        }).get();
        var data = {
            'action': 'save_week_day_meals',
            'wsdate': wsdate,
            'wedate': wedate,
            'meals': meals
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            jQuery(".popAlrt").addClass("msg-box");
            if (obj.error == 0) {
                if (obj.wkdm_html) {
                    jQuery.each(obj.wkdm_html, function (key, value) {
                        jQuery("div#" + key + " div.prdctList ul#wdml_ul").empty().html(value);
                    });
                }
                if (obj.wkdmtm_html) {
                    jQuery.each(obj.wkdmtm_html, function (wkdmtm_key, wkdmtm_value) {
                        jQuery("div#" + wkdmtm_key + " div.prdctList ul#wk_tot_macros").empty().html(wkdmtm_value);
                    });
                }
                if (obj.wk_day_addon_mls_amt) {
                    jQuery.each(obj.wk_day_addon_mls_amt, function (wkdama_key, wkdama_value) {
                        if (wkdama_key) {
                            var wkd_ky = wkdama_key.replace(new RegExp('-', 'g'), "");
                            var wd_sub_tot = jQuery('#wkd_sub_tot_' + wkd_ky).val();
                            if (wd_sub_tot > 0) {
                                var fwd_sub_tot = parseFloat(wd_sub_tot);// + parseFloat(wkdama_value);
                                jQuery("span#spn_sub_tot_" + wkd_ky).empty().html('$' + fwd_sub_tot.toFixed(2));
                            }
                            var wd_tot = jQuery('#wkd_tot_' + wkd_ky).val();
                            if (wd_tot > 0) {
                                var fwd_tot = parseFloat(wd_tot) + parseFloat(wkdama_value);
                                jQuery("span#spn_tot_" + wkd_ky).empty().html('$' + fwd_tot.toFixed(2));
                            }
                            if (wkdama_value > 0) {
                                jQuery('li#li_adonmls_' + wkd_ky).removeClass('dnone');
                                var tot_adn_mls = wkdama_value / 5;
                                jQuery('li#li_adonmls_' + wkd_ky + ' span.sch-left').empty().html('Add-ons (' + tot_adn_mls + ')');
                                jQuery('li#li_adonmls_' + wkd_ky + ' span.sch-right').empty().html('$' + wkdama_value.toFixed(2));
                            } else {
                                jQuery('li#li_adonmls_' + wkd_ky).addClass('dnone');
                            }
                        }
                    });
                }
                if (obj.tot_day_mls) {
                    jQuery.each(obj.tot_day_mls, function (key, value) {
                        var tot_meal_cls = '';
                        switch (value) {
                            case 1:
                                tot_meal_cls = 'tot_one';
                                break;
                            case 2:
                                tot_meal_cls = 'tot_two';
                                break;
                            case 3:
                                tot_meal_cls = 'tot_three';
                                break;
                            default :
                                tot_meal_cls = '';
                                break;
                        }
                        jQuery("div#" + key + " div.prdctList ul#wdml_ul").removeClass('tot_one').removeClass('tot_two').removeClass('tot_three').addClass(tot_meal_cls);
                    });
                }
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                jQuery('.cancelBtn>a').trigger("click");
            } else {
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
            }
            jQuery("div#chml_addon_ml_confrm_popup" + wksd_ky).addClass("dnone");
            jQuery("div#chng_meals_box" + wksd_ky).removeClass("dnone");
            setTimeout(function () {
                jQuery(".popAlrt").removeClass("msg-box");
            }, 3000);
        });
        jQuery("div.preloader").removeClass('dblock');
        setTimeout(function () {
            jQuery('#save_week_day_meal' + wksd_ky).removeClass('proc-loader');
            jQuery('#sav_meal' + wksd_ky).removeClass('proc-loader');
        }, 3000);
    } else {
        jQuery(".popAlrt").addClass("msg-box");
        jQuery("#err_msg").removeClass("dnone");
        jQuery("#err_msg span").text('Something goes wrong, please try again!');
        setTimeout(function () {
            jQuery(".popAlrt").removeClass("msg-box");
            location.reload();
        }, 3000);
    }
}

/*
 * Method: Function for skip week
 */
function skip_week(wsdate) {
    if (wsdate) {
        var wsdate_ky = wsdate.replace(new RegExp('-', 'g'), "");
        jQuery("#wk_skip" + wsdate_ky).removeClass("dnone");
        jQuery("#mngwk_settings" + wsdate_ky).addClass("dnone");
        jQuery("#confrm_popup" + wsdate_ky).removeClass("dnone");
        jQuery("#mang_wk_popup" + wsdate_ky).addClass("dnone");
    }
}

function confirm_skip_week(wsdate) {
    if (wsdate) {
        jQuery("div.preloader").addClass('dblock');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        var wsd_ky = wsdate.replace(new RegExp('-', 'g'), "");
        jQuery('#confirm_skip_week' + wsd_ky).addClass('proc-loader');
        var data = {
            'action': 'skip_week',
            'wsdate': wsdate
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            setTimeout(function () {
                jQuery("div.preloader").removeClass("dblock");
                jQuery(".popAlrt").addClass("msg-box");
            }, 1000);
            if (obj.error == 0) {
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    location.reload();
                }, 1500);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    location.reload();
                    jQuery('#confirm_skip_week' + wsd_ky).removeClass('proc-loader');
                }, 3000);
            } else {
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery(".popAlrt").removeClass("msg-box");
                    jQuery('#confirm_skip_week' + wsd_ky).removeClass('proc-loader');
                }, 3000);
            }
        });
    } else {
        jQuery(".popAlrt").addClass("msg-box");
        jQuery("#err_msg").removeClass("dnone");
        jQuery("#err_msg span").text("Something goes wrong, please try again!");
        setTimeout(function () {
            jQuery(".popAlrt").removeClass("msg-box");
            location.reload();
        }, 3000);
    }
}

function process_to_checkout_packagename(lvl_grp_id, lvl_id, plname){
     if (lvl_grp_id && lvl_id) {
        jQuery("div.preloader").addClass('dblock');
        jQuery("#process-to-checkout").addClass('proc-loader');
        var data = {
            'action': 'process_to_checkout',
            'group_id': lvl_grp_id,
            'plan_id': lvl_id,
            'week_initial_total': jQuery('#weekly_initial_payment_' + lvl_grp_id + '_' + lvl_id).val(),
            'week_billing_total': jQuery('#weekly_billing_payment_' + lvl_grp_id + '_' + lvl_id).val(),
            'delivery_fee': jQuery('#delivery_fee_' + lvl_grp_id + '_' + lvl_id).val(),
            'slct_days': jQuery('#slct_days').val(),
            'slct_days_range': jQuery('#slct_days_range').val(),
            'is_allergies': jQuery("input[name='is_allergies_" + lvl_grp_id + "']:checked").val(),
            'allergies': jQuery('#allergies_' + lvl_grp_id).val(),
            'pg_name': plname
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0 && obj.rurl != "") {
                window.location.href = obj.rurl;
            } else {
                if (obj.loginurl && obj.loginurl != "") {
                    window.location.href = obj.loginurl;
                } else {
                    jQuery("#process-to-checkout").removeClass('proc-loader');
                    alert(obj.msg);
                }
            }
        });
        jQuery("div.preloader").removeClass('dblock');
    }
}

/*
 * Method: Function for skip week
 */
function process_to_checkout(lvl_grp_id, lvl_id) {
    if (lvl_grp_id && lvl_id) {
        jQuery("div.preloader").addClass('dblock');
        jQuery("#process-to-checkout").addClass('proc-loader');
        var data = {
            'action': 'process_to_checkout',
            'group_id': lvl_grp_id,
            'plan_id': lvl_id,
            'week_initial_total': jQuery('#weekly_initial_payment_' + lvl_grp_id + '_' + lvl_id).val(),
            'week_billing_total': jQuery('#weekly_billing_payment_' + lvl_grp_id + '_' + lvl_id).val(),
            'delivery_fee': jQuery('#delivery_fee_' + lvl_grp_id + '_' + lvl_id).val(),
            'slct_days': jQuery('#slct_days').val(),
            'slct_days_range': jQuery('#slct_days_range').val(),
            'is_allergies': jQuery("input[name='is_allergies_" + lvl_grp_id + "']:checked").val(),
            'allergies': jQuery('#allergies_' + lvl_grp_id).val()
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0 && obj.rurl != "") {
                window.location.href = obj.rurl;
            } else {
                if (obj.loginurl && obj.loginurl != "") {
                    window.location.href = obj.loginurl;
                } else {
                    jQuery("#process-to-checkout").removeClass('proc-loader');
                    alert(obj.msg);
                }
            }
        });
        jQuery("div.preloader").removeClass('dblock');
    }
}

/*
 * Method: Function for manage week settings
 */

function manage_wk_settings(wsdate) {
    if (wsdate) {
        var wsdate_ky = wsdate.replace(new RegExp('-', 'g'), "");
        jQuery("#mngwk_settings" + wsdate_ky).removeClass("dnone");
        jQuery("#wk_skip" + wsdate_ky).addClass("dnone");
        jQuery("#confrm_popup" + wsdate_ky).removeClass("dnone");
        jQuery("#mang_wk_popup" + wsdate_ky).addClass("dnone");
        var delvryorpickup = jQuery('#delivery_or_pickup_' + wsdate_ky).val();
        if(delvryorpickup == 2){
            var selected_pickup_location_detail_html = jQuery("div#selected_pickup_location_detail_"+wsdate_ky).html();
            if(selected_pickup_location_detail_html != ''){
                jQuery('.up-mngwk-cnfm-pickup-dtil-main-' + wsdate_ky).removeClass('dnone');
                jQuery('.up-mngwk-cnfm-pickup-dtil-' + wsdate_ky).removeClass('dnone').html(selected_pickup_location_detail_html);
            }else{
                jQuery('.up-mngwk-cnfm-pickup-dtil-' + wsdate_ky).addClass('dnone');
                jQuery('.up-mngwk-cnfm-pickup-dtil-main-' + wsdate_ky).addClass('dnone');   
            }
            jQuery('.cnfrm-dlvry-or-pkup-bx-' + wsdate_ky).addClass('dnone');
        }else{
            jQuery('.up-mngwk-cnfm-pickup-dtil-' + wsdate_ky).addClass('dnone');
            jQuery('.up-mngwk-cnfm-pickup-dtil-main-' + wsdate_ky).addClass('dnone'); 
            jQuery('.cnfrm-dlvry-or-pkup-bx-' + wsdate_ky).removeClass('dnone');
        }
    }
}


function confirm_manage_wk_settings(wsdate) {
    if (wsdate) {
        jQuery("div.preloader").addClass('dblock');
        jQuery(".popAlrt").removeClass("msg-box");
        jQuery("#err_msg").addClass("dnone");
        jQuery("#succ_msg").addClass("dnone");
        var wsd_ky = wsdate.replace(new RegExp('-', 'g'), "");
        jQuery('#confirm_manage_wk_settings' + wsd_ky).addClass('proc-loader');
        var data = {
            'action': 'manage_week_settings',
            "fdata": jQuery("ul#week_box li#li_" + wsd_ky + " div.acrdnPanel div.manageWeek form#upcming_mng_wk_frm").serialize(),
            'wsdate': wsdate
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            setTimeout(function () {
                jQuery("div.preloader").removeClass('dblock');
                jQuery(".popAlrt").addClass("msg-box");
            }, 1000);
            if (obj.error == 0) {
                jQuery("#succ_msg").removeClass("dnone");
                jQuery("#succ_msg span").text(obj.msg);
                setTimeout(function () {
                    location.reload();
                }, 1500);
                setTimeout(function () {
                    jQuery("#confrm_popup" + wsd_ky).addClass("dnone");
                    jQuery("#mang_wk_popup" + wsd_ky).addClass("dnone");
                    jQuery(".popAlrt").removeClass("msg-box");
                    jQuery('#confirm_manage_wk_settings' + wsd_ky).removeClass('proc-loader');
                }, 3000);
            } else {
                jQuery("#err_msg").removeClass("dnone");
                jQuery("#err_msg span").text(obj.msg);
                setTimeout(function () {
                    jQuery("#confrm_popup" + wsd_ky).addClass("dnone");
                    jQuery("#mang_wk_popup" + wsd_ky).removeClass("dnone");
                    jQuery(".popAlrt").removeClass("msg-box");
                    jQuery('#confirm_manage_wk_settings' + wsd_ky).removeClass('proc-loader');
                }, 3000);
            }
        });
    } else {
        jQuery(".popAlrt").addClass("msg-box");
        jQuery("#err_msg").removeClass("dnone");
        jQuery("#err_msg span").text("Something goes wrong, please try again!");
        setTimeout(function () {
            jQuery(".popAlrt").removeClass("msg-box");
            location.reload();
        }, 3000);
    }
}

function share_fb(url) {
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, 'facebook-share-dialog', "width=626, height=436")
}

function share_twitter(url) {
    window.open('http://twitter.com/share?url=' + url, 'twitter-share-dialog', "width=626, height=436")
}

/*
 * Function for get date formate
 */

function formatDate(dateStr) {
    var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var monthOfYear = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var myDate = new Date(dateStr);
    var ds = day_suffix(myDate);
    return daysOfWeek[myDate.getDay()] + ', ' + monthOfYear[myDate.getMonth()] + ' ' + ds;
    //return daysOfWeek[myDate.getDay()] + ' ' + myDate.getDate() + 'th ' + monthOfYear[myDate.getMonth()] + ' ' + myDate.getFullYear() + ' ' + hours + ':' + minutes + ':' + seconds + ' ' + mid;
}

/*
 * Function for get day suffix
 */

function day_suffix(dt) {
    return dt.getDate() + (dt.getDate() % 10 == 1 && dt.getDate() != 11 ? 'st' : (dt.getDate() % 10 == 2 && dt.getDate() != 12 ? 'nd' : (dt.getDate() % 10 == 3 && dt.getDate() != 13 ? 'rd' : 'th')));
}

/*
 * Function for copy coupon code
 */
function copyToClipboard() {
    var el = document.getElementById('checkout_copy_promo_code');
    if (!el || el.length == 0) {
        el = document.getElementById('copy-coupon-code');
    }
    var range = document.createRange();
    range.selectNodeContents(el);
    var sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);
    document.execCommand('copy');
}

/*
 * Method: function for buying product again from order history page
 */
function buyProductAgain(el) {
    var pid = jQuery(el).data('pid');
    if (pid) {
        var data = {
            'action': 'buy_product_again',
            'p_id': pid,
        };
        jQuery.post(ajaxurl, data, function (response) {
            var obj = jQuery.parseJSON(response);
            if (obj.error == 0) {
                window.location.href = obj.rurl;
            } else {
                alert(obj.msg);
            }
        });
    } else {
        alert('Something goes wrong, please try again!');
    }
}

/*
 * Method: function for partner pickup location to locate markers in the google map 
 */

var customMapStyles = [
    {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 0
            },
            {
                "color": "#27983d"
            },
            {
                "lightness": 0
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "on"
            },
            {
                "color": "#27983d"
            },
            {
                "lightness": 100
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#b7eace"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#90d3ac"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#eff9f4"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#eff9f4"
            },
            {
                "lightness": 21
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#eff9f4"
            },
            {
                "lightness": 17
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#90d3ac"
            },
            {
                "lightness": 29
            },
            {
                "weight": 0.2
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#90d3ac"
            },
            {
                "lightness": 18
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#90d3ac"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#90d3ac"
            },
            {
                "lightness": 19
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#b7eace"
            },
            {
                "lightness": 17
            }
        ]
    }
];

function partner_pickup_locator_api(latitudeZipCode, longitudeZipCode, pickupLocationsInfo, zoomLevel, seeMore, isPickupDetailShow) {
    var marker, mapOptions;
    latitudeZipCode = typeof (latitudeZipCode) !== 'undefined' ? latitudeZipCode : false;
    longitudeZipCode = typeof (longitudeZipCode) !== 'undefined' ? longitudeZipCode : false;
    pickupLocationsInfo = typeof (pickupLocationsInfo) !== 'undefined' ? pickupLocationsInfo : false;
    zoomLevel = typeof (zoomLevel) !== 'undefined' ? zoomLevel : false;
    seeMore = typeof (seeMore) !== 'undefined' ? seeMore : false;
    isPickupDetailShow = typeof (isPickupDetailShow) !== 'undefined' ? isPickupDetailShow : false;
    if (latitudeZipCode && longitudeZipCode && pickupLocationsInfo) {
        //jQuery('#pickup_location').val(0);
        var pickupMapCenter = {lat: latitudeZipCode, lng: longitudeZipCode};
        mapOptions = {center: pickupMapCenter, styles: customMapStyles};
        if (zoomLevel) {
            mapOptions = {center: pickupMapCenter, zoom: zoomLevel, styles: customMapStyles};
        }
        // Create a map object and specify the DOM element for display.
        var map = new google.maps.Map(document.getElementById('pickupLocationMap'), mapOptions);
        bounds = new google.maps.LatLngBounds();
        jQuery('#location_listing').html('');
        if (pickupLocationsInfo.length > 0) {
            pickupLocationsInfo.forEach(function (pickupLocationInfo) {
                var _title = typeof (pickupLocationInfo._title) != 'undefined' ? pickupLocationInfo._title : '';
                var distance = typeof (pickupLocationInfo.distance) != 'undefined' ? pickupLocationInfo.distance : '';
                var _address = typeof (pickupLocationInfo._address) != 'undefined' ? pickupLocationInfo._address : '';
                var _post_id = typeof (pickupLocationInfo._post_id) != 'undefined' ? pickupLocationInfo._post_id : 0;
                var location = typeof (pickupLocationInfo.location) != 'undefined' ? pickupLocationInfo.location : '';
                var _post_thumbnail_url = tc_site_url.site_url+ '/wp-content/themes/toughcookies/images/map-pin.png';//typeof (pickupLocationInfo._post_thumbnail_url) != 'undefined' ? pickupLocationInfo._post_thumbnail_url : '';
                // Create pickup location li
                var pickupLocationLi = '<li id="tc-ppl-' + _post_id + '"><div class="location-inner"><i class="fa"><img src="' + tc_site_url.site_url + '/wp-content/themes/toughcookies/images/google-map-pin.svg" alt=""></i><h3>' + _title + ' <span class="miles">' + distance + ' miles</span></h3><p class="loc-address">' + _address + '</p><a href="javascript:void(0);" onclick="select_pickup_location(' + _post_id + ')" class="btn-select">select</a></div></li>';
                jQuery('#location_listing').append(pickupLocationLi);
                // Attach Pickup Location attibute to manage select pickup location
                var JsonStringifyPickupLocation = JSON.stringify(pickupLocationInfo);
                jQuery('#tc-ppl-' + _post_id).attr('selected-pickup-location', JsonStringifyPickupLocation);
                // Create a marker and set its position.
                marker = new google.maps.Marker({
                    map: map,
                    position: location,
                    title: _title,
                    label: {
                        text: _title.length > 12 ? _title.substr(0, 10) + '...' : _title,
                        color: '#545454',
                        fontSize: '14px',
                        fontWeight: '600',
                        fontFamily: "acumin-pro"
                    },
                    icon: {
                        url: _post_thumbnail_url,
                        size: new google.maps.Size(45, 45),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(0, 0),
                        scaledSize: new google.maps.Size(45, 40),
                        labelOrigin: new google.maps.Point(22, 45)
                    }
                });
                // show pickup location info when marker is clicked
                marker.addListener('click', function () {
                    jQuery('ul.location-listing li.active').removeClass('active');
                    jQuery('#tc-ppl-' + _post_id).addClass('active');
                    var $selectedPickupLocation = jQuery('li[id=' + "tc-ppl-" + _post_id + ']');
                    var $locationListContainer = jQuery('div.location-list-inr');
                    $locationListContainer.animate({
                        scrollTop: $selectedPickupLocation.offset().top - $locationListContainer.offset().top + $locationListContainer.scrollTop()
                    });
                });
                loc = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
                bounds.extend(loc);
                if (jQuery('#account_selected_pickup_location').val() == _post_id) {
                    if (!seeMore && !isPickupDetailShow) {
                        select_pickup_location(_post_id, true);
                    }else if (isPickupDetailShow) {
                        select_pickup_location(_post_id, false);
                    }
                    jQuery('#tc-ppl-' + _post_id).addClass('active');
                }
            });
        }
        if (!zoomLevel) {
            map.fitBounds(bounds);
            map.panToBounds(bounds);
            if (seeMore) {
                var count_pickup_location = jQuery('#total_count_all_pickup_location').val();
                jQuery('#pickup_location_count').html(count_pickup_location);
                if (jQuery('.pickup_see_less').hasClass('dnone')) {
                    jQuery('.pickup_see_less').removeClass('dnone');
                    jQuery('.pickup_see_more').addClass('dnone');
                }
            } else {
                var count_pickup_location = jQuery('#total_count_under_five_miles_distances').val();
                jQuery('#pickup_location_count').html(count_pickup_location);
                if (jQuery('.pickup_see_more').hasClass('dnone')) {
                    jQuery('.pickup_see_more').removeClass('dnone');
                    jQuery('.pickup_see_less').addClass('dnone');
                }
            }
            if (parseInt(jQuery('#is_pickup_location_selected').val()) === 0) {
                var bzipcode = jQuery('#bzipcode').val();
                if (count_pickup_location > 0) {
                    var location_near_you_msg = count_pickup_location + ' location near ' + bzipcode + '...';
                } else {
                    var location_near_you_msg = 'There are no pickup locations for you.';
                }
                jQuery('.location_near_you').html(location_near_you_msg);
            }
        }
    }
}

/*
 * Method: function for select pickup location
 */

function select_pickup_location(postId, isAutomaticSelect) {
    var call_area_html = '', posted_hours_html = '', selected_pickup_location_detail_html = '', posted_hours_day_li = '', website_area_html = '', call_area_li = '';
    var selectedPickupLocationData = jQuery.parseJSON(jQuery('#tc-ppl-' + postId).attr('selected-pickup-location'));
    var isAutomaticSelect = typeof (isAutomaticSelect) != 'undefined' && isAutomaticSelect ? true : false;
    var _title = typeof (selectedPickupLocationData._title) != 'undefined' ? selectedPickupLocationData._title : false;
    var _address = typeof (selectedPickupLocationData._address) != 'undefined' ? selectedPickupLocationData._address : false;
    var _phone_number = typeof (selectedPickupLocationData._phone_number) != 'undefined' ? selectedPickupLocationData._phone_number : false;
    var _website = typeof (selectedPickupLocationData._website) != 'undefined' ? selectedPickupLocationData._website : false;
    var _posted_hours = typeof (selectedPickupLocationData._posted_hours) != 'undefined' ? selectedPickupLocationData._posted_hours : false;
    var _posted_day = typeof (selectedPickupLocationData._posted_day) != 'undefined' ? selectedPickupLocationData._posted_day : false;
    var _post_thumbnail_url = typeof (selectedPickupLocationData._post_thumbnail_url) != 'undefined' ? selectedPickupLocationData._post_thumbnail_url : false;
    var location_near_you = '<span class="pl-name">'+_title + '</span> <span class="pl-address">' + _address+'</span>';
    var user_home_address = (typeof (selectedPickupLocationData.user_home_address) != 'undefined' && selectedPickupLocationData.user_home_address != '') ? selectedPickupLocationData.user_home_address : false;
    jQuery('.location_near_you').html(location_near_you);
    var current_page_slug = typeof (jQuery('#current_page_slug').val()) != 'undefined' ? jQuery('#current_page_slug').val() : false;
    if (!isAutomaticSelect) {
        jQuery('body').removeClass('show-map');
    }
    if (_posted_hours && _posted_day && _posted_hours.length > 0 && _posted_day.length > 0) {
        posted_hours_html = '<div class="see-hours-detail"><strong> Hours: </strong><span>see hours</span><ul class="sub-list">';
        _posted_hours.forEach(function (_posted_hour, index) {
            if (_posted_hour != '') {
                posted_hours_day_li += '<li><span class="pld-day">' + _posted_day[index] + ':</span> <span class="pld-time">' + _posted_hour + '</span></li>';
            }
        });
        if (posted_hours_day_li == '') {
            posted_hours_day_li = 'Not Available';
        }
        posted_hours_html += posted_hours_day_li + '</ul></div>';
    }
    if (_phone_number) {
        call_area_li += '<li><span class="tel-mobile"><a href="tel:' + _phone_number + '">CALL</a></span><span class="tel-desktop"><a href="javascript:void(0);">CALL: ' + _phone_number + '</a></span></li>';
    }
    if (current_page_slug && current_page_slug == 'delivery-info' && user_home_address && _address) {
        var lnk = encodeURI('https://maps.google.com/maps?saddr=' + user_home_address + '&daddr=' + _address);
        call_area_li += '<li><span><a target="_blank" href="' + lnk + '">Directions</a></span></li>';
    }
    if (current_page_slug == 'delivery-info' && _website) {
        call_area_li += '<li><span><a target="_blank" href="' + _website + '">Websites</a></span></li>';
    }
    if (call_area_li != '') {
        call_area_html = '<div class="call-area"><ul class="list-inline"> ' + call_area_li + ' </ul></div>';
    }
    jQuery('#is_pickup_location_selected').val(1);
    selected_pickup_location_detail_html = '<div class="pic-loc-detail-inr"><div class="pic-loc-detail-block"><div class="pic-loc-detail-img"><img src="' + _post_thumbnail_url + '"></div><div class="pic-loc-detail-cont"><h3>' + _title + '</h3><p><strong> Address: </strong> ' + _address + '</p> ' + posted_hours_html + ' </div></div> ' + call_area_html + website_area_html + ' </div>';
    jQuery('#selected_pickup_location_detail').removeClass('dnone').html(selected_pickup_location_detail_html);
    if (isAutomaticSelect) {
        jQuery('#selected_pickup_location_detail').addClass('dnone');
    }
    jQuery('#pickup_location').val(postId);
}


/*
 * Method: function for partner pickup location to locate markers in the google map 
 */

function partner_pickup_locator_multiple_api(latitudeZipCode, longitudeZipCode, pickupLocationsInfo, zoomLevel, seeMore, wk_tab_key, isPickupDetailShow) {
    var marker, mapOptions;
    latitudeZipCode = typeof (latitudeZipCode) !== 'undefined' ? latitudeZipCode : false;
    longitudeZipCode = typeof (longitudeZipCode) !== 'undefined' ? longitudeZipCode : false;
    pickupLocationsInfo = typeof (pickupLocationsInfo) !== 'undefined' ? pickupLocationsInfo : false;
    zoomLevel = typeof (zoomLevel) !== 'undefined' ? zoomLevel : false;
    seeMore = typeof (seeMore) !== 'undefined' ? seeMore : false;
    wk_tab_key = typeof (wk_tab_key) !== 'undefined' ? wk_tab_key : false;
    isPickupDetailShow = typeof (isPickupDetailShow) !== 'undefined' ? isPickupDetailShow : false;
    if (latitudeZipCode && longitudeZipCode && pickupLocationsInfo && wk_tab_key) {
        //jQuery('#pickup_location').val(0);
        var pickupMapCenter = {lat: latitudeZipCode, lng: longitudeZipCode};
        mapOptions = {center: pickupMapCenter, styles: customMapStyles};
        if (zoomLevel) {
            mapOptions = {center: pickupMapCenter, zoom: zoomLevel, styles: customMapStyles};
        }
        // Create a map object and specify the DOM element for display.
        var map = new google.maps.Map(document.getElementById('pickupLocationMap_' + wk_tab_key), mapOptions);
        bounds = new google.maps.LatLngBounds();
        jQuery('#location_listing_' + wk_tab_key).html('');
        if (pickupLocationsInfo.length > 0) {
            pickupLocationsInfo.forEach(function (pickupLocationInfo) {
                var _title = typeof (pickupLocationInfo._title) != 'undefined' ? pickupLocationInfo._title : '';
                var distance = typeof (pickupLocationInfo.distance) != 'undefined' ? pickupLocationInfo.distance : '';
                var _address = typeof (pickupLocationInfo._address) != 'undefined' ? pickupLocationInfo._address : '';
                var _post_id = typeof (pickupLocationInfo._post_id) != 'undefined' ? pickupLocationInfo._post_id : 0;
                var location = typeof (pickupLocationInfo.location) != 'undefined' ? pickupLocationInfo.location : '';
                var _post_thumbnail_url = tc_site_url.site_url+ '/wp-content/themes/toughcookies/images/map-pin.png';//typeof (pickupLocationInfo._post_thumbnail_url) != 'undefined' ? pickupLocationInfo._post_thumbnail_url : '';
                // Create pickup location li
                var pickupLocationLi = '<li id="tc-ppl-' + _post_id + '-' + wk_tab_key + '"><div class="location-inner"><i class="fa"><img src="' + tc_site_url.site_url + '/wp-content/themes/toughcookies/images/google-map-pin.svg" alt=""></i><h3>' + _title + ' <span class="miles">' + distance + ' miles</span></h3><p class="loc-address">' + _address + '</p><a href="javascript:void(0);" onclick="select_pickup_location_multiple(' + _post_id + ', false, ' + wk_tab_key + ')" class="btn-select">select</a></div></li>';
                jQuery('#location_listing_' + wk_tab_key).append(pickupLocationLi);
                // Attach Pickup Location attibute to manage select pickup location
                var JsonStringifyPickupLocation = JSON.stringify(pickupLocationInfo);
                jQuery('#tc-ppl-' + _post_id + '-' + wk_tab_key).attr('selected-pickup-location', JsonStringifyPickupLocation);
                // Create a marker and set its position.
                marker = new google.maps.Marker({
                    map: map,
                    position: location,
                    title: _title,
                    label: {
                        text: _title.length > 12 ? _title.substr(0, 10) + '...' : _title,
                        color: '#2B2B2B',
                        fontSize: '14px',
                        fontWeight: '600',
                        fontFamily: "acumin-pro"
                    },
                    icon: {
                        url: _post_thumbnail_url,
                        size: new google.maps.Size(45, 45),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(0, 0),
                        scaledSize: new google.maps.Size(45, 40),
                        labelOrigin: new google.maps.Point(22, 45)
                    }
                });
                // show pickup location info when marker is clicked
                marker.addListener('click', function () {
                    jQuery('ul.location-listing li.active').removeClass('active');
                    jQuery('#tc-ppl-' + _post_id + '-' + wk_tab_key).addClass('active');
                    var $selectedPickupLocation = jQuery('li[id=' + "tc-ppl-" + _post_id + '-' + wk_tab_key + ']');
                    var $locationListContainer = jQuery('div.location-list-inr-' + wk_tab_key);
                    $locationListContainer.animate({
                        scrollTop: $selectedPickupLocation.offset().top - $locationListContainer.offset().top + $locationListContainer.scrollTop()
                    });
                });
                loc = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
                bounds.extend(loc);
                if (jQuery('#account_selected_pickup_location_' + wk_tab_key).val() == _post_id) {
                    if (!seeMore && !isPickupDetailShow) {
                        select_pickup_location_multiple(_post_id, true, wk_tab_key);
                    }else if (isPickupDetailShow) {
                        select_pickup_location_multiple(_post_id, false, wk_tab_key);
                    }
                    jQuery('#tc-ppl-' + _post_id + '-' + wk_tab_key).addClass('active');

                }
            });
        }
        if (!zoomLevel) {
            map.fitBounds(bounds);
            map.panToBounds(bounds);
            if (seeMore) {
                var count_pickup_location = jQuery('#total_count_all_pickup_location_' + wk_tab_key).val();
                jQuery('#pickup_location_count_' + wk_tab_key).html(count_pickup_location);
                if (jQuery('.pickup_see_less_' + wk_tab_key).hasClass('dnone')) {
                    jQuery('.pickup_see_less_' + wk_tab_key).removeClass('dnone');
                    jQuery('.pickup_see_more_' + wk_tab_key).addClass('dnone');
                }
            } else {
                var count_pickup_location = jQuery('#total_count_under_five_miles_distances_' + wk_tab_key).val();
                jQuery('#pickup_location_count_' + wk_tab_key).html(count_pickup_location);
                if (jQuery('.pickup_see_more_' + wk_tab_key).hasClass('dnone')) {
                    jQuery('.pickup_see_more_' + wk_tab_key).removeClass('dnone');
                    jQuery('.pickup_see_less_' + wk_tab_key).addClass('dnone');
                }
            }
            if (parseInt(jQuery('#is_pickup_location_selected_' + wk_tab_key).val()) === 0) {
                var bzipcode = jQuery('#bzipcode_' + wk_tab_key).val();
                if (count_pickup_location > 0) {
                    var location_near_you_msg = count_pickup_location + ' location near ' + bzipcode + '...';
                } else {
                    var location_near_you_msg = 'There are no pickup locations for you.';
                }
                jQuery('.location_near_you_' + wk_tab_key).html(location_near_you_msg);
            }
        }
    }
}
/*
 * Method: function for select pickup location
 */

function select_pickup_location_multiple(postId, isAutomaticSelect, wk_tab_key) {
    var call_area_html = '', posted_hours_html = '', selected_pickup_location_detail_html = '', posted_hours_day_li = '', website_area_html = '', call_area_li = '';
    var selectedPickupLocationData = jQuery.parseJSON(jQuery('#tc-ppl-' + postId + '-' + wk_tab_key).attr('selected-pickup-location'));
    var isAutomaticSelect = typeof (isAutomaticSelect) != 'undefined' && isAutomaticSelect ? true : false;
    var _title = typeof (selectedPickupLocationData._title) != 'undefined' ? selectedPickupLocationData._title : false;
    var _address = typeof (selectedPickupLocationData._address) != 'undefined' ? selectedPickupLocationData._address : false;
    var _phone_number = typeof (selectedPickupLocationData._phone_number) != 'undefined' ? selectedPickupLocationData._phone_number : false;
    var _website = typeof (selectedPickupLocationData._website) != 'undefined' ? selectedPickupLocationData._website : false;
    var _posted_hours = typeof (selectedPickupLocationData._posted_hours) != 'undefined' ? selectedPickupLocationData._posted_hours : false;
    var _posted_day = typeof (selectedPickupLocationData._posted_day) != 'undefined' ? selectedPickupLocationData._posted_day : false;
    var _post_thumbnail_url = typeof (selectedPickupLocationData._post_thumbnail_url) != 'undefined' ? selectedPickupLocationData._post_thumbnail_url : false;
    var location_near_you = '<span class="pl-name">'+_title + '</span> <span class="pl-address">' + _address+'</span>';
    var user_home_address = (typeof (selectedPickupLocationData.user_home_address) != 'undefined' && selectedPickupLocationData.user_home_address != '') ? selectedPickupLocationData.user_home_address : false;
    var wk_tab_key = typeof (wk_tab_key) !== 'undefined' ? wk_tab_key : false;
    jQuery('.location_near_you_' + wk_tab_key).html(location_near_you);
    var current_page_slug = typeof (jQuery('#current_page_slug').val()) != 'undefined' ? jQuery('#current_page_slug').val() : false;
    if (!isAutomaticSelect) {
        jQuery('body').removeClass('show-map');
    }
    if (_posted_hours && _posted_day && _posted_hours.length > 0 && _posted_day.length > 0) {
        posted_hours_html = '<div class="see-hours-detail"><strong> Hours: </strong><span>see hours</span><ul class="sub-list">';
        _posted_hours.forEach(function (_posted_hour, index) {
            if (_posted_hour != '') {
                posted_hours_day_li += '<li><span class="pld-day">' + _posted_day[index] + ':</span> <span class="pld-time">' + _posted_hour + '</span></li>';
            }
        });
        if (posted_hours_day_li == '') {
            posted_hours_day_li = 'Not Available';
        }
        posted_hours_html += posted_hours_day_li + '</ul></div>';
    }
    if (_phone_number) {
        call_area_li += '<li><span class="tel-mobile"><a href="tel:' + _phone_number + '">CALL</a></span><span class="tel-desktop"><a href="javascript:void(0);">CALL: ' + _phone_number + '</a></span></li>';
    }
    if (user_home_address && _address) {
        var lnk = encodeURI('https://maps.google.com/maps?saddr=' + user_home_address + '&daddr=' + _address);
        call_area_li += '<li><span><a target="_blank" href="' + lnk + '">Directions</a></span></li>';
    }
    if (_website) {
        call_area_li += '<li><span><a target="_blank" href="' + _website + '">Websites</a></span></li>';
    }
    if (call_area_li != '') {
        call_area_html = '<div class="call-area"><ul class="list-inline"> ' + call_area_li + ' </ul></div>';
    }
    jQuery('#is_pickup_location_selected_' + wk_tab_key).val(1);
    selected_pickup_location_detail_html = '<div class="pic-loc-detail-inr"><div class="pic-loc-detail-block"><div class="pic-loc-detail-img"><img src="' + _post_thumbnail_url + '"></div><div class="pic-loc-detail-cont"><h3>' + _title + '</h3><p><strong> Address: </strong> ' + _address + '</p> ' + posted_hours_html + ' </div></div> ' + call_area_html + website_area_html + ' </div>';
    jQuery('#selected_pickup_location_detail_' + wk_tab_key).removeClass('dnone').html(selected_pickup_location_detail_html);
    //jQuery('.up-mngwk-cnfm-pickup-dtil-' + wk_tab_key).removeClass('dnone').html(selected_pickup_location_detail_html);
    if (isAutomaticSelect) {
        jQuery('#selected_pickup_location_detail_' + wk_tab_key).addClass('dnone');
    }
    jQuery('#pickup_location_' + wk_tab_key).val(postId);
}

/*
 * Method: function for show pickup location detail if coupon linked with any pickup location
 */

function show_pickup_location_detail(postId) {
    var call_area_html = '', posted_hours_html = '', selected_pickup_location_detail_html = '', posted_hours_day_li = '', website_area_html = '', call_area_li = '';
    var data = {
        "action": "get_pickup_location_info",
        "pplid": postId
    };
    jQuery.post(ajaxurl, data, function (response) {
        var obj = jQuery.parseJSON(response);
        if (obj.error == 0) {
            var selectedPickupLocationData = obj.msg;
            var _title = typeof (selectedPickupLocationData.name) != 'undefined' ? selectedPickupLocationData.name : false;
            var _address = typeof (selectedPickupLocationData.address) != 'undefined' ? selectedPickupLocationData.address : false;
            var _phone_number = typeof (selectedPickupLocationData.phone) != 'undefined' ? selectedPickupLocationData.phone : false;
            var _posted_hours = typeof (selectedPickupLocationData.posted_hours) != 'undefined' ? selectedPickupLocationData.posted_hours : false;
            var _posted_day = typeof (selectedPickupLocationData.posted_day) != 'undefined' ? selectedPickupLocationData.posted_day : false;
            var _post_thumbnail_url = typeof (selectedPickupLocationData.ppl_logo) != 'undefined' ? selectedPickupLocationData.ppl_logo : false;
            //var location_near_you = _title + ' ' + _address;
            var location_near_you = '<span class="pl-name">'+_title + '</span> <span class="pl-address">' + _address+'</span>';
            jQuery('.location_near_you').html(location_near_you);
            var current_page_slug = typeof (jQuery('#current_page_slug').val()) != 'undefined' ? jQuery('#current_page_slug').val() : false;
            if (_posted_hours && _posted_day && _posted_hours.length > 0 && _posted_day.length > 0) {
                posted_hours_html = '<div class="see-hours-detail"><strong> Hours: </strong><span>see hours</span><ul class="sub-list">';
                _posted_hours.forEach(function (_posted_hour, index) {
                    if (_posted_hour != '') {
                        posted_hours_day_li += '<li><span class="pld-day">' + _posted_day[index] + ':</span> <span class="pld-time">' + _posted_hour + '</span></li>';
                    }
                });
                if (posted_hours_day_li == '') {
                    posted_hours_day_li = 'Not Available';
                }
                posted_hours_html += posted_hours_day_li + '</ul></div>';
            }
            if (_phone_number) {
                call_area_li += '<li><span class="tel-mobile"><a href="tel:' + _phone_number + '">CALL</a></span><span class="tel-desktop"><a href="javascript:void(0);">CALL: ' + _phone_number + '</a></span></li>';
            }
            if (call_area_li != '') {
                call_area_html = '<div class="call-area"><ul class="list-inline"> ' + call_area_li + ' </ul></div>';
            }
            jQuery('#is_pickup_location_selected').val(1);
            selected_pickup_location_detail_html = '<div class="pic-loc-detail-inr"><div class="pic-loc-detail-block"><div class="pic-loc-detail-img"><img src="' + _post_thumbnail_url + '"></div><div class="pic-loc-detail-cont"><h3>' + _title + '</h3><p><strong> Address: </strong> ' + _address + '</p> ' + posted_hours_html + ' </div></div> ' + call_area_html + website_area_html + ' </div>';
            jQuery('#selected_pickup_location_detail').removeClass('dnone').html(selected_pickup_location_detail_html);
            jQuery('#pickup_location').val(postId); 
        } else {
            alert(obj.msg);
        }
    });
}