!function(o){let e=dokan_helper.i18n_time_format,n=dokanMultipleTime.fullDayString,i=dokan_helper.timepicker_locale.am,t=dokan_helper.timepicker_locale.pm,r={init:function(){o(".dokan-store-times").on("click",".open-close-actions a",this.stopDefaultBehaviour),o(".dokan-store-times").on("focus",".dokan-form-control",this.changeClockTimeOnFocus),o(".dokan-store-times").each(this.onLoadContainer),o(".dokan-store-times").on("click",".added-store-opening-time",this.addedStoreMultipleTime),o(".dokan-store-times").on("click",".remove-store-closing-time",this.removeStoreMultipleTime),o('input[name="dokan_update_store_settings"]').on("click",this.updateStoreSettings),o('input[name="dokan_update_delivery_time_settings"]').on("click",this.updateStoreSettings),o(".dokan-store-times").on("change",".dokan-form-group",this.changeDokanOpenCloseTime),o(".dokan-time-slots .dokan-form-group .switch").on("toggle",this.toggleSwitcher)},stopDefaultBehaviour:function(o){o.stopPropagation(),o.preventDefault()},changeClockTimeOnFocus:function(r){r.stopPropagation();const a=o(this),s=a.closest(".dokan-form-group");s.find(".time .clock-picker .dokan-form-control:eq(0)").timepicker({step:30,lang:dokan_helper.timepicker_locale,minTime:"12:00 "+i,maxTime:"11:30 "+t,timeFormat:e,noneOption:{label:n,value:"fullDay",className:"fullDayClockOne"},scrollDefault:"now"}).on("changeTime",(function(e){const r=e.target.timepickerObj.selectedValue,s=moment(r,"H\\h:mm a").format("hh:mm a");if(n===r||"fullDay"===r){let e=a.closest(".dokan-form-group").find(".and-time"),r=a.closest(".dokan-form-group").find(".time:eq(0)"),s=a.closest(".dokan-form-group").find(".time:eq(1)");void 0!==e[0]?(a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .time-to").hide(),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .time").remove(),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .time-to").before(r),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .time-to").after(s.hide())):(a.closest(".dokan-form-group").find(".time-to").hide(),a.closest(".dokan-form-group").find(".time:eq(1)").hide()),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .added-store-opening-time").hide(),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0)").nextAll(".dokan-form-group").remove(),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .clockTwo").val("11:59 "+t),o(this).next("input").val("12:00 "+i),o(".ui-timepicker-wrapper").hide(),o(this).val(n)}else a.closest(".dokan-form-group").find(".time-to").show(),a.closest(".dokan-form-group").find(".time:eq(1)").show(),a.closest(".dokan-form-group").find(".added-store-opening-time").show(),o(this).next("input").val(s)})),s.find(".time .clock-picker .dokan-form-control:eq(1)").timepicker({step:30,lang:dokan_helper.timepicker_locale,minTime:"12:00 "+i,maxTime:"11:30 "+t,timeFormat:e,noneOption:{label:n,value:"fullDay",className:"fullDayClockTwo"},scrollDefault:"now"}).on("changeTime",(function(e){const r=e.target.timepickerObj.selectedValue,s=moment(r,"H\\h:mm a").format("hh:mm a");if(n===r||"fullDay"===r){let e=a.closest(".dokan-form-group").find(".and-time"),r=a.closest(".dokan-form-group").find(".time:eq(0)"),s=a.closest(".dokan-form-group").find(".time:eq(1)");void 0!==e[0]?(a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .time").remove(),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .time-to").before(r.hide()).hide(),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .time-to").after(s)):(a.closest(".dokan-form-group").find(".time-to").hide(),a.closest(".dokan-form-group").find(".time:eq(0)").hide()),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .added-store-opening-time").hide(),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0)").nextAll(".dokan-form-group").remove(),a.closest(".dokan-store-times").find(".dokan-form-group:eq(0) .clockOne").val("12:00 "+i),o(this).next("input").val("11:59 "+t),o(".ui-timepicker-wrapper").hide(),o(this).val(n)}else a.closest(".dokan-form-group").find(".time-to").show(),a.closest(".dokan-form-group").find(".time:eq(0)").show(),a.closest(".dokan-form-group").find(".added-store-opening-time").show(),o(this).next("input").val(s)}))},onLoadContainer:function(r){const a=o(this),s=a.find(".toogle-checkbox").val();a.find(".dokan-form-group .time .clock-picker .dokan-form-control").timepicker({timeFormat:e}),a.find(".switch").minitoggle({on:1==s}),"1"===s&&(a.find(".toggle-handle").attr("style","transform: translate3d( 22px, 0px, 0px )"),a.find(".close-status").hide(),a.find(".open-status").show()),a.find(".dokan-form-group").each((function(){const e=o(this),r=a.find(".dokan-form-group:eq(0) .clock-picker .clockOne").val(),s=a.find(".dokan-form-group:eq(0) .clock-picker .clockTwo").val(),d=e.find(".clock-picker .clockTwo").val(),c=e.closest(".dokan-form-group").next(".dokan-form-group");r==="12:00 "+i&&s==="11:59 "+t&&(e.find(".time .clock-picker .dokan-form-control:eq(1)").val(""),e.find(".time-to").hide(),e.find(".time:eq(1)").hide(),e.find(".time .clock-picker .dokan-form-control:eq(0)").val(n)),"11:30 "+t!==d&&"11:59 "+t!==d||e.find(".added-store-opening-time").addClass("hide-element"),void 0!==c[0]&&e.find(".added-store-opening-time").addClass("hide-element")}))},addedStoreMultipleTime:function(e){const n=o(this),i=n.closest(".dokan-store-times"),t=i.find(".dokan-form-group .dokan-form-control").get(0).id,r=i.find(".dokan-form-group .dokan-form-control").get(1).id,a=i.find(".dokan-form-group .clockOne").get(0).name,s=i.find(".dokan-form-group .clockTwo").get(0).name,d=`<div class='dokan-form-group'>\n                                    <label class='day and-time'></label>\n                                    <label for='opening-time' class='time'>\n                                        <div class='clock-picker'>\n                                            <span class='far fa-clock'></span>\n                                            <input type='text' class='dokan-form-control opening-time' id='${t}'\n                                                placeholder='${dokanMultipleTime.place_start}' autocomplete='off' value=''>\n                                            <input type='hidden' value='' class='clockOne' name='${a}' />\n                                            <span class='fa fa-exclamation-triangle'></span>\n                                        </div>\n                                    </label>\n                                    <span class='time-to fas fa-minus'></span>\n                                    <label for='closing-time' class='time'>\n                                        <div class='clock-picker'>\n                                            <span class='far fa-clock'></span>\n                                            <input type='text' class='dokan-form-control closing-time' autocomplete='off'\n                                                id='${r}' placeholder='${dokanMultipleTime.place_end}' value='' />\n                                            <input type='hidden' value='' class='clockTwo' name='${s}' />\n                                            <span class='fa fa-exclamation-triangle'></span>\n                                        </div>\n                                    </label>\n                                    <label for='open-close-actions' class='open-close-actions' style='display: flex; align-items: center;'>\n                                        <a href='' class='remove-store-closing-time'>\n                                            <span class="fas fa-trash" style="margin-top: 2px;"></span>\n                                        </a>\n                                        <a href='' class='added-store-opening-time'>\n                                            ${dokanMultipleTime.add_action}\n                                        </a>\n                                    </label>\n                                </div>`;n.addClass("hide-element"),n.closest(".dokan-form-group").after(d)},removeStoreMultipleTime:function(e){const n=o(this),i=n.closest(".dokan-form-group"),t=i.find(".and-time"),r=i.next(".dokan-form-group");if(void 0!==r[0]){const o=r.find(".opening-time").val(),e=i.prev(".dokan-form-group").find(".closing-time").val(),n=moment(o,"H\\h:mm a").format("HH:mm");moment(e,"H\\h:mm a").format("HH:mm")<n&&(i.next(".dokan-form-group").find(".clock-picker:eq(0)").css({"border-color":"#bbb"}),i.next(".dokan-form-group").find(".clock-picker:eq(0) .fa-clock").css({color:"#666"}),i.next(".dokan-form-group").find(".opening-time").css({color:"#4e4e4e"}))}if(void 0!==t[0])return void 0===r[0]&&i.prev(".dokan-form-group").find(".added-store-opening-time").removeClass("hide-element"),n.closest(".dokan-form-group").remove(),!1;if(void 0===t[0]&&void 0===r[0])return i.find(".time").css({visibility:"hidden"}),i.find(".time-to").css({visibility:"hidden"}),i.find(".open-close-actions").css({visibility:"hidden"}),i.find(".toogle-checkbox").val(0),i.find(".minitoggle").removeClass("active"),i.find(".toggle-handle").css({transform:"translate3d(0px, 0px, 0px)"}),i.find(".close-status").show(),i.find(".open-status").hide(),!1;if(void 0===t[0]&&void 0!==r[0]){let o=r.find(".time:eq(0)"),e=r.find(".time:eq(1)"),n=r.find(".open-close-actions");return i.find(".time").remove(),i.find(".dokan-status").after(o),i.find(".open-close-actions").before(e),i.find(".open-close-actions").before(n),i.find(".open-close-actions:eq(1)").remove(),r.remove(),!1}},changeDokanOpenCloseTime:function(e){e.stopPropagation();const i=o(this),r=i.find(".clock-picker .clockTwo").val(),a=i.closest(".dokan-form-group").next(".dokan-form-group");o(".ui-timepicker-wrapper").hide(),void 0===a[0]&&i.closest(".dokan-form-group").find(".added-store-opening-time").removeClass("hide-element"),"11:30 "+t===r&&i.find(".added-store-opening-time").addClass("hide-element"),o(".dokan-store-times").each((function(){let e=o(this).find(".dokan-form-group").length,i=0;for(;i<e;i++){const e=o(this),t=e.find(".dokan-form-group:eq("+i+") .clock-picker .opening-time").val(),r=e.find(".dokan-form-group:eq("+i+") .clock-picker .closing-time").val(),a=e.find(".dokan-form-group:eq("+i+")").prev(".dokan-form-group"),s=e.find(".dokan-form-group:eq("+(i-1)+") .closing-time").val(),d=moment(t,"H\\h:mm a").format("HH:mm"),c=moment(r,"H\\h:mm a").format("HH:mm"),l=moment(s,"H\\h:mm a").format("HH:mm");t&&(r&&n!==r&&d>=c?(e.find(".dokan-form-group:eq("+i+") .clock-picker").css({"border-color":"#F87171"}),e.find(".dokan-form-group:eq("+i+") .fa-clock").css({color:"#F87171"}),e.find(".dokan-form-group:eq("+i+") .dokan-form-control").css({color:"#F87171"})):(e.find(".dokan-form-group:eq("+i+") .dokan-form-control").css({color:"#4e4e4e"}),e.find(".dokan-form-group:eq("+i+") .clock-picker").css({"border-color":"#bbb"}),e.find(".dokan-form-group:eq("+i+") .fa-clock").css({color:"#666"}))),t&&r?e.find(".dokan-form-group:eq("+i+") .fa-exclamation-triangle").css({display:"none"}):(e.find(".dokan-form-group:eq("+i+") .clock-picker").css({"border-color":"#F5C33B"}),e.find(".dokan-form-group:eq("+i+") .dokan-form-control").css({color:"#4e4e4e"}),e.find(".dokan-form-group:eq("+i+") .fa-exclamation-triangle").css({display:"block"})),t&&s&&void 0!==a[0]&&d<l&&(e.find(".dokan-form-group:eq("+i+") .clock-picker:eq(0)").css({"border-color":"#F87171"}),e.find(".dokan-form-group:eq("+i+") .clock-picker:eq(0) .fa-clock").css({color:"#F87171"}),e.find(".dokan-form-group:eq("+i+") .clock-picker:eq(0) .dokan-form-control").css({color:"#F87171"})),n!==t&&n!==r||(e.find(".dokan-form-group:eq("+i+") .dokan-form-control").css({color:"#4e4e4e"}),e.find(".dokan-form-group:eq("+i+") .clock-picker").css({"border-color":"#bbb"}),e.find(".dokan-form-group:eq("+i+") .fa-clock").css({color:"#666"}),e.find(".dokan-form-group:eq("+i+") .fa-exclamation-triangle").css({display:"none"}))}}))},updateStoreSettings:function(e){o(".dokan-store-times").each((function(){let i=0,t=o(this).find(".dokan-form-group").length;for(;i<t;i++){const t=o(this),a=t.find(".dokan-form-group:eq("+i+") .clock-picker .opening-time").val(),s=t.find(".dokan-form-group:eq("+i+") .clock-picker .closing-time").val(),d=t.find(".dokan-form-group:eq("+i+")").prev(".dokan-form-group"),c=t.find(".toogle-checkbox").val(),l=t.find(".dokan-form-group:eq("+(i-1)+") .closing-time").val(),m=moment(a,"H\\h:mm a").format("HH:mm"),f=moment(s,"H\\h:mm a").format("HH:mm"),p=moment(l,"H\\h:mm a").format("HH:mm");if("0"!==c&&n!==a&&n!==s){if(!a)return r.updateSettingsView(t,i,150,0),e.preventDefault(),!1;if(!s)return r.updateSettingsView(t,i,150,1),e.preventDefault(),!1;if(m>=f)return r.updateSettingsView(t,i,300),e.preventDefault(),!1;if(l&&void 0!==d[0]&&m<p)return r.updateSettingsView(t,i,300,0),e.preventDefault(),!1}}return!0}))},updateSettingsView:function(e,n,i,t=!1){t=!1!==t?`:eq(${t})`:"",e.find(".dokan-form-group:eq("+n+") .clock-picker"+t).css({"border-color":"#F87171"}),e.find(".dokan-form-group:eq("+n+") .clock-picker"+t+" .fa-clock").css({color:"#F87171"}),e.find(".dokan-form-group:eq("+n+") .dokan-form-control"+t).css({color:"#F87171"}),o("html, body").animate({scrollTop:o(".dokan-time-slots").offset().top-120},i)},toggleSwitcher:function(e){const n=o(this).closest(".dokan-form-group");e.isActive?(n.find(".toogle-checkbox").val(1),n.find(".time").css({visibility:"visible"}),n.find(".time-to").css({visibility:"visible"}),n.find(".open-close-actions").css({visibility:"visible"}),n.nextAll(".dokan-form-group").show(),n.find(".close-status").hide(),n.find(".open-status").show()):(n.find(".toogle-checkbox").val(0),n.find(".time").css({visibility:"hidden"}),n.find(".time-to").css({visibility:"hidden"}),n.find(".open-close-actions").css({visibility:"hidden"}),n.nextAll(".dokan-form-group").hide(),n.find(".close-status").show(),n.find(".open-status").hide())}};o(document).ready((function(){r.init()}))}(jQuery);