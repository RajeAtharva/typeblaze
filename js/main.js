$(function () {
  const savedTheme = document.cookie.replace(/(?:(?:^|.*;\s*)tb_theme\s*=\s*([^;]*).*$)|^.*$/, '$1');
  if (savedTheme === 'light') $('body').addClass('light');
  updateThemeBtn();

  $('#theme-toggle').on('click', function () {
    $('body').toggleClass('light');
    const theme = $('body').hasClass('light') ? 'light' : 'dark';
    document.cookie = `tb_theme=${theme};path=/;max-age=31536000`;
    updateThemeBtn();
  });

  function updateThemeBtn() {
    $('#theme-toggle').text($('body').hasClass('light') ? 'Light' : 'Dark');
  }

  window.showToast = function (msg, type = 'success') {
    const $toast = $('#toast');
    $toast.html(`<div class="toast-dot"></div><span>${msg}</span>`);
    $toast.removeClass('error').addClass('show');
    if (type === 'error') $toast.addClass('error');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => $toast.removeClass('show'), 3200);
  };

  const path = window.location.pathname;
  $('.main-nav a').each(function () {
    if ($(this).attr('href') && path.endsWith($(this).attr('href').split('/').pop())) {
      $(this).addClass('active');
    }
  });
});
