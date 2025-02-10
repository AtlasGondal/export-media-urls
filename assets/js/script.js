jQuery(document).ready(function ($) {
  $(".select2").select2();
});

function showRangeFields() {
    document.getElementById('dateRange').style.display = 'block';
}

function hideRangeFields() {
    document.getElementById('dateRange').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('input[name="date-range"]:checked').value === 'range') {
        showRangeFields();
    } else {
        hideRangeFields();
    }
});