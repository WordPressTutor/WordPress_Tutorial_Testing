jQuery(document).ready(function($) {
    // Populate countries on page load
    $.ajax({
        url: ajax_object.ajax_url,
        type: 'post',
        data: { action: 'country_state_city' },
        success: function(response) {
            if (response.countries) {
                // Populate country dropdown
                var countryDropdown = $('#country');
                $.each(response.countries, function(index, country) {
                    countryDropdown.append('<option value="' + country.id + '">' + country.name + '</option>');
                });

                // Populate state dropdown
                var stateDropdown = $('#state');
                var selectedCountryId = countryDropdown.val();
                $.each(response.states, function(index, state) {
                    if (state.country_id == selectedCountryId) {
                        stateDropdown.append('<option value="' + state.id + '">' + state.name + '</option>');
                    }
                });

                // Populate city dropdown
                var cityDropdown = $('#city');
                var selectedStateId = stateDropdown.val();
                $.each(response.cities, function(index, city) {
                    if (city.state_id == selectedStateId) {
                        cityDropdown.append('<option value="' + city.id + '">' + city.name + '</option>');
                    }
                });
            }
        }
    });

    // Update state dropdown based on selected country
    $('#country').on('change', function() {
        var selectedCountryId = $(this).val();

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'post',
            data: { action: 'country_state_city', country_id: selectedCountryId },
            success: function(response) {
                if (response.states) {
                    var stateDropdown = $('#state');
                    stateDropdown.empty();
                    $.each(response.states, function(index, state) {
                        stateDropdown.append('<option value="' + state.id + '">' + state.name + '</option>');
                    });

                    // Trigger change event to update city dropdown
                    stateDropdown.trigger('change');
                }
            }
        });
    });

    // Update city dropdown based on selected state
    $('#state').on('change', function() {
        var selectedStateId = $(this).val();

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'post',
            data: { action: 'country_state_city', state_id: selectedStateId },
            success: function(response) {
                if (response.cities) {
                    var cityDropdown = $('#city');
                    cityDropdown.empty();
                    $.each(response.cities, function(index, city) {
                        cityDropdown.append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                }
            }
        });
    });
});
