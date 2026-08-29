/* ==========================================================================
   FINZO FINANCIAL SERVICES — EMI Calculator
   Vanilla JS. Standard reducing-balance EMI formula:
   EMI = P x R x (1+R)^N / ((1+R)^N - 1)
   where P = principal, R = monthly interest rate, N = tenure in months
   ========================================================================== */

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('emiCalculatorForm');
    if (!form) return;

    var amountInput = document.getElementById('emiAmount');
    var amountRange = document.getElementById('emiAmountRange');
    var rateInput = document.getElementById('emiRate');
    var rateRange = document.getElementById('emiRateRange');
    var tenureInput = document.getElementById('emiTenure');
    var tenureRange = document.getElementById('emiTenureRange');

    var emiOut = document.getElementById('emiMonthlyResult');
    var interestOut = document.getElementById('emiInterestResult');
    var totalOut = document.getElementById('emiTotalResult');
    var donut = document.getElementById('emiDonut');

    var amountLabel = document.getElementById('emiAmountLabel');
    var rateLabel = document.getElementById('emiRateLabel');
    var tenureLabel = document.getElementById('emiTenureLabel');

    function formatCurrency(value) {
      if (!isFinite(value)) value = 0;
      return '₹' + Math.round(value).toLocaleString('en-IN');
    }

    function clamp(value, min, max) {
      return Math.min(Math.max(value, min), max);
    }

    function syncPair(numberEl, rangeEl) {
      if (!numberEl || !rangeEl) return;
      numberEl.addEventListener('input', function () {
        rangeEl.value = numberEl.value;
        calculate();
      });
      rangeEl.addEventListener('input', function () {
        numberEl.value = rangeEl.value;
        calculate();
      });
    }

    function calculate() {
      var principal = clamp(parseFloat(amountInput.value) || 0, 0, 100000000);
      var annualRate = clamp(parseFloat(rateInput.value) || 0, 0.1, 36);
      var tenureYears = clamp(parseFloat(tenureInput.value) || 0, 0.5, 30);

      if (amountLabel) amountLabel.textContent = formatCurrency(principal);
      if (rateLabel) rateLabel.textContent = annualRate.toFixed(2) + '%';
      if (tenureLabel) tenureLabel.textContent = tenureYears + ' yrs';

      var months = Math.round(tenureYears * 12);
      var monthlyRate = annualRate / 12 / 100;

      var emi;
      if (monthlyRate === 0) {
        emi = principal / months;
      } else {
        var factor = Math.pow(1 + monthlyRate, months);
        emi = (principal * monthlyRate * factor) / (factor - 1);
      }

      if (!isFinite(emi) || months <= 0) emi = 0;

      var totalPayable = emi * months;
      var totalInterest = totalPayable - principal;
      if (totalInterest < 0) totalInterest = 0;

      updateResult(emiOut, formatCurrency(emi));
      updateResult(interestOut, formatCurrency(totalInterest));
      updateResult(totalOut, formatCurrency(totalPayable));

      if (donut) {
        var pct = totalPayable > 0 ? (totalInterest / totalPayable) * 100 : 0;
        donut.style.setProperty('--emi-percent', pct.toFixed(1) + '%');
      }
    }

    function updateResult(el, text) {
      if (!el) return;
      el.textContent = text;
      el.classList.remove('result-pulse');
      // Force reflow so the animation can retrigger on every update.
      void el.offsetWidth;
      el.classList.add('result-pulse');
    }

    syncPair(amountInput, amountRange);
    syncPair(rateInput, rateRange);
    syncPair(tenureInput, tenureRange);

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      calculate();
    });

    calculate();
  });
})();
