/* ==========================================================================
   FINZO FINANCIAL SERVICES — Form Validation
   Vanilla JS validation for the Enquiry / Apply Now and Contact forms.
   A form with a `data-endpoint` attribute (e.g. contact.php's form, wired to
   mail/send-contact.php) is actually POSTed via fetch(); any other
   `[data-validate]` form (currently apply-now.php) stays frontend-only and
   just shows a simulated confirmation, until it's given its own endpoint.
   ========================================================================== */

(function () {
  'use strict';

  var MOBILE_REGEX = /^[6-9]\d{9}$/;
  var EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-validate]').forEach(setupForm);
  });

  function setupForm(form) {
    form.setAttribute('novalidate', 'novalidate');

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      e.stopPropagation();

      var isValid = validateForm(form);
      form.classList.add('was-validated');

      if (!isValid) {
        var firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.focus();
        return;
      }

      submitFormData(form);
    });

    // Live re-validation as the user fixes a field.
    form.querySelectorAll('.form-control, .form-select').forEach(function (field) {
      field.addEventListener('input', function () { validateField(field); });
      field.addEventListener('change', function () { validateField(field); });
    });
  }

  function validateForm(form) {
    var fields = form.querySelectorAll('.form-control, .form-select');
    var allValid = true;
    fields.forEach(function (field) {
      if (!validateField(field)) allValid = false;
    });
    return allValid;
  }

  function validateField(field) {
    var value = field.value.trim();
    var valid = true;

    if (field.hasAttribute('required') && value === '') {
      valid = false;
    }

    if (valid && field.type === 'email' && value !== '' && !EMAIL_REGEX.test(value)) {
      valid = false;
    }

    if (valid && field.dataset.validate === 'mobile' && value !== '' && !MOBILE_REGEX.test(value)) {
      valid = false;
    }

    if (valid && field.dataset.validate === 'amount' && value !== '') {
      var amount = parseFloat(value.replace(/,/g, ''));
      if (isNaN(amount) || amount <= 0) valid = false;
    }

    if (valid && field.tagName === 'SELECT' && field.hasAttribute('required') && value === '') {
      valid = false;
    }

    field.classList.toggle('is-invalid', !valid);
    field.classList.toggle('is-valid', valid && value !== '');

    return valid;
  }

  function submitFormData(form) {
    var submitBtn = form.querySelector('button[type="submit"]');
    var originalText = submitBtn ? submitBtn.innerHTML : '';

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...';
    }

    function resetButton() {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      }
    }

    function resetFormState() {
      form.reset();
      form.classList.remove('was-validated');
      form.querySelectorAll('.is-valid').forEach(function (f) { f.classList.remove('is-valid'); });
    }

    var endpoint = form.dataset.endpoint;

    if (endpoint) {
      // Real submission — POST to the PHP endpoint and reflect its JSON response.
      fetch(endpoint, { method: 'POST', body: new FormData(form) })
        .then(function (res) {
          return res.json().catch(function () {
            throw new Error('Unexpected server response.');
          });
        })
        .then(function (data) {
          resetButton();
          if (data && data.success) {
            showAlert(form, 'success', data.message || 'Thank you! Your enquiry has been received.');
            resetFormState();
          } else {
            showAlert(form, 'danger', (data && data.message) || 'Something went wrong. Please try again or contact us directly.');
          }
        })
        .catch(function () {
          resetButton();
          showAlert(form, 'danger', 'We could not reach the server. Please check your connection and try again, or call us directly.');
        });
      return;
    }

    // No endpoint configured yet on this form — simulate success so the UI
    // stays testable end-to-end until this form is also wired up.
    window.setTimeout(function () {
      resetButton();
      showAlert(form, 'success', 'Thank you! Your enquiry has been received. Our team will contact you shortly.');
      resetFormState();
    }, 900);
  }

  function showAlert(form, type, message) {
    var existing = form.parentElement.querySelector('.finzo-form-alert');
    if (existing) existing.remove();

    var icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';

    var alertEl = document.createElement('div');
    alertEl.className = 'alert alert-' + type + ' finzo-form-alert mt-4 d-flex align-items-center gap-2';
    alertEl.setAttribute('role', 'alert');
    alertEl.innerHTML = '<i class="bi ' + icon + '"></i><span>' + message + '</span>';

    form.insertAdjacentElement('afterend', alertEl);

    window.setTimeout(function () {
      alertEl.remove();
    }, 7000);

    alertEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
})();
