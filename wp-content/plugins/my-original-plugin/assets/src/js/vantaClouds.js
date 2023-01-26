const setVanta = () => {
  if (window.VANTA)
    window.VANTA.CLOUDS({
      el: '.s-page-1 .s-section-1 .s-section',
      mouseControls: true,
      touchControls: true,
      gyroControls: false,
      minHeight: 200.0,
      minWidth: 200.0,
      skyColor: 0x3a8aac,
    });
};
_strk.push(function () {
  setVanta();
  window.edit_page.Event.subscribe('Page.beforeNewOneFadeIn', setVanta);
});

export default setVanta;
