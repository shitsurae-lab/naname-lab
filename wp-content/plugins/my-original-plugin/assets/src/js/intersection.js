const setObserver = () => {
  //①コールバック関数
  const callback = (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('inView');
      } else {
        entry.target.classList.remove('inView');
      }
    });
  };
  //②オプション
  const options = {
    threshold: [0, 1],
  };

  //③引数にコールバック関数とオプションを指定してオブザーバーを生成
  const observer = new IntersectionObserver(callback, options);

  //④監視対象の要素をすべて取得
  const imageTargets = [...document.querySelectorAll('.js-target__image')];
  console.log(imageTargets);

  //⑤すべての監視対象要素をobserve()メソッドに指定
  imageTargets.forEach((elem) => {
    //observe()に監視対象の要素を指定
    observer.observe(elem);
  });
  console.log('Intersection Observer APIを読み込んでます');
};
export default setObserver;
