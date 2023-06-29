function addSelector() {
  const targets = document.querySelectorAll(
    '.p-front__content .ark-block-section__bodyInner'
  );

  targets.forEach((target) => target.classList.add('js-target'));
}

export default addSelector;
