<script type="text/javascript">
    function initPartnerPickupLocationApi() {
        var input = document.getElementById('ppp_address');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addDomListener(input, 'keydown', function (e) { /*Only for prevent to reload page*/
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        });
        autocomplete.addListener('place_changed', function (e) {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            jQuery('#ppp_latitude').val(place.geometry.location.lat());
            jQuery('#ppp_longitude').val(place.geometry.location.lng());
        });

    }

    jQuery(document).on('blur, keyup', "#ppp_address", function (e) {
        if (jQuery(this).val() == '') {
            jQuery('#ppp_latitude').val('');
            jQuery('#ppp_longitude').val('');
        }

    });

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo TOUGHCOOKIES_GOOGLE_API_KEY . '&libraries=places&callback=initPartnerPickupLocationApi'; ?>" async defer></script>