!function(a){"use strict";var b={cache:{$document:a(document),$window:a(window)},init:function(){this.bindEvents()},bindEvents:function(){var a=this;this.cache.$document.on("ready",function(){a.initApply(),a.initIndeed(),a.avoidSubmission(),a.initContact(),a.fileUploadButton(),a.initSelectedPackage()})},initApply:function(){var b=a(".application_details, .resume_contact_details"),c=a(".application_button, .resume_contact_button");b.length&&(c.unbind("click"),b.addClass("modal").attr("id","apply-overlay"),c.addClass("popup-trigger").attr("href","#apply-overlay"))},initIndeed:function(){a(".job_listings").on("update_results",function(){a(".indeed_job_listing").addClass("type-job_listing")})},initContact:function(){a(".resume_contact_button").click(function(b){return b.preventDefault(),Jobify.App.popup({items:{src:a(".resume_contact_details")}}),!1})},avoidSubmission:function(){a(".job_filters, .resume_filters").submit(function(a){return!1})},fileUploadButton:function(){var b=a(".listify-file-upload");b.each(function(b){var c=a(this),d=c.next(),e=d.text();c.on("change",function(a){console.log("wat"),console.log(this.files);var b="";b=this.files&&this.files.length>1?(c.data("multiple-caption")||"").replace("%d",this.files.length):a.target.value.split("\\").pop(),b?d.text(b):d.text(e)})})},initSelectedPackage:function(){var b=a("#jobify_selected_package");if(0!=b.length){var c=b.val(),d=a(".job_listing_packages, .resume_listing_packages"),e=a("#job_package_selection, #resume_package_selection");d.find("#package-"+c).attr("checked","checked"),e.submit()}}};b.init()}(jQuery),function(a){"use strict";var b={cache:{$document:a(document),$window:a(window)},init:function(){this.bindEvents()},bindEvents:function(){var a=this;this.cache.$document.on("ready",function(){a.initApplyWith(),a.initApplications()})},initApplyWith:function(){a(".wp-job-manager-application-details").addClass("modal").on("wp-job-manager-application-details-show",function(b){Jobify.App.popup({items:{src:a(b.delegateTarget)}})})},initApplications:function(){if(a("#apply-overlay.application_details").is(":visible")){var b=a(".job-manager-applications-error").detach();a(".job-manager-application-form fieldset:first-of-type").before(b),Jobify.App.popup({items:{src:a("#apply-overlay.application_details")}})}}};b.init()}(jQuery);