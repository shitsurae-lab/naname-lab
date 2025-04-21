const setObserver = () => {
  //①コールバック関数
  const callback = (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('inView');
        console.log('監視範囲に入りました');
      } else {
        entry.target.classList.remove('inView');
        console.log('監視範囲を出ました');
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
  const monitorObjects = [...document.querySelectorAll('.js-target')];

  //START: 確認
  // const monitorObjectMember = monitorObjects.map((elm) => {
  //   return elm;
  // });
  // console.table(monitorObjectMember);
  //END: 確認

  //⑤すべての監視対象要素をobserve()メソッドに指定
  monitorObjects.forEach((elem) => {
    //observe()に監視対象の要素を指定
    observer.observe(elem);
  });
  console.log('Intersection Observer APIを読み込んでます');
};
export default setObserver;
