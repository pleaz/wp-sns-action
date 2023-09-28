(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

const submitLeadForm = (getForm, api, stat, type, source) => {
    const formData = new FormData(getForm);

    if (formData.has('phone')) {
        formData.set('phone', formData.get('phone').replaceAll(' ', ''));
    }

    let domain = document.location.hostname;
    let country;

    if (domain.includes('easyalquiler')) {
        country = 'easyalquiler';
    } else if (domain.includes('easynoleggio')) {
        country = 'easynoleggio';
    } else if (domain.includes('easyaluguer')) {
        country = 'easyaluguer';
    } else {
        country = 'easytoolhire';
    }

    const UTMData = sessionStorage.getItem('UTM_' + country);
    const UTMDataParsed = JSON.parse(UTMData);

    const subscriptionValue = formData.has('subscription');

    formData.append('page_url', window.location.href);
    /*formData.append('email_to', "info@easytoolhire.com");*/
    formData.append('source', source);
    formData.set('subscription', subscriptionValue);

    if (UTMDataParsed) {
        formData.append('utm', UTMData);
    }

    const countrySelect = document.getElementById('countrySelect');
    let selectedCountryCode = null;

    if (type === 'enquiry') {
    	selectedCountryCode = Array.from(countrySelect.options).find(el => el.selected).value;
    }

    getForm.reset();
    stat && statistics(stat);

    // success messages / close form, show lightbox
    if (type === 'region') {
        document.getElementById('content-form').style.display = 'none';
        document.getElementById('success-form').style.display = 'block';
    }
    if (type === 'enquiry') {
        document.getElementsByClassName('enquiry_success')[0].style.display = 'block';
        setDefaultCountryCode(selectedCountryCode);
    }
    if (type === 'voucher') {
        document.getElementsByClassName('voucher-success')[0].classList.add("open");
    }

    if (api !== '') {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', api);
        xhr.send(formData);
    }
}
