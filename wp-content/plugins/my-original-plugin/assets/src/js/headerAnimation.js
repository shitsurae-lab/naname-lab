const headerAnimation = () => {
  let offset = 0;
  let lastPosition = 0;
  //let ticking = false;
  const header = document.getElementById('header');
  const height = header.offsetHeight;

  //「height」===固定値(ヘッダーの高さ)
  //「lastposition」===スクロールの値

  const onScroll = () => {
    const currentScroll = window.scrollY;
    if (currentScroll > height) {
      if (currentScroll > offset) {
        //スクロールダウン
        header.classList.add('js-head-animation');
        header.classList.remove('js-show-bg');
      } else {
        // スクロールアップ
        header.classList.remove('js-head-animation');
        header.classList.add('js-show-bg');
        //header.classList.remove('js-fixed');
      }
      header.classList.add('js-fixed');
    } else {
      //最上部近くでは固定解除
      header.classList.remove('js-head-animation');
      header.classList.remove('js-fixed');
      header.classList.remove('js-show-bg');
    }
    offset = currentScroll;
  };

  document.addEventListener('scroll', () => {
    window.requestAnimationFrame(onScroll);
  });
  // console.log('headerAnimation.jsが読み込まれています');
  return;
};

export default headerAnimation;
