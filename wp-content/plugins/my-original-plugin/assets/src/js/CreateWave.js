const CreateWave = () => {
  const [...elements] = document.querySelectorAll(
    '.p-front .ark-block-section__color, .p-page .ark-block-section__color'
  );
  // const wave = document.createElement('div');
  // wave.setAttribute('class', 'p-wave');
  elements.forEach((elem) => {
    elem.insertAdjacentHTML(
      'afterend',
      '<div class="p-wave"><div class="p-wave__container"></div></div>'
    );
  });

  const footerContainer = document.querySelector(
    '.l-footer__foot > .l-container'
  );
  const div = document.createElement('div');
  div.setAttribute('class', 'p-wave');
  const container = document.createElement('div');
  container.setAttribute('class', 'p-wave__container');
  div.appendChild(container);
  footerContainer.before(div);
  //console.log(footerContainer);
  //console.log('CreateWave.jsが読み込まれています');
};
export default CreateWave;
