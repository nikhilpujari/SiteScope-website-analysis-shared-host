(function () {
  fetch('/SiteScope/track.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      url: window.location.pathname,
      referrer: document.referrer,
      ua: navigator.userAgent,
      screen: `${window.innerWidth}x${window.innerHeight}`
    })
  }).catch(() => {});
})();