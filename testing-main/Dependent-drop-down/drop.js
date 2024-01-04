jQuery(document).ready(function () {
  jQuery("#country").on("change", function () {
    var countryId = jQuery(this).val();
    //passing the counrty ID 
    jQuery.ajax({
      method: "POST",
      url: ajax_object.ajax_url,
      data: { action: "get_states", ID: countryId },
      dataType: "html",
      success: function (data) {
        // funtion for state data 
        jQuery("#state").html(data);
      },
    });
  });

  jQuery("#state").on("change", function () {
    var stateId = jQuery(this).val();
    jQuery.ajax({
      method: "POST",
      url: ajax_object.ajax_url,
      data: { action: "get_cities", id: stateId },
      dataType: "html",
      success: function (data) {
        jQuery("#city").html(data);
      },
      error: function (error) {
        console.log("AJAX Error:", error);
      },
    });
  });
});



