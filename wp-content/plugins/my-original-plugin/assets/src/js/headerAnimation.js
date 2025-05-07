const headerAnimation = () => {
  let offset = 0;
  let lastPosition = 0;
  //let ticking = false;
  const header = document.getElementById('header');
  const height = header.offsetHeight;

  //「height」===固定値(ヘッダーの高さ)
  //「lastposition」===スクロールの値

  const onScroll = () => {
    if (lastPosition > height) {
      if (lastPosition > offset) {
        header.classList.add('js-head-animation');
        header.classList.add('js-fixed');
      } else {
        header.classList.remove('js-head-animation');
        // header.classList.add('js-fixed');
        header.classList.remove('js-fixed');
      }
      offset = lastPosition;
    } else {
      header.classList.remove('js-fixed');
    }
  };

  document.addEventListener('scroll', () => {
    lastPosition = window.scrollY;

    window.requestAnimationFrame(() => {
      onScroll();
    });
  });
  // console.log('headerAnimation.jsが読み込まれています');
  return;
};

export default headerAnimation;
